<?php namespace App\Http\Controllers;

use App\Http\Common\ORAConsts;
use App\Http\Requests\UserRequest;
use App\Models\Clinic;
use App\Models\Organization;
use App\Models\OrganizationsGroups;
use App\Models\Site;
use App\Models\UsersClinic;
use App\OrganizationUser;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use File;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Lang;
use Mail;
use Redirect;
use Sentinel;
use URL;
use View;
use DataTables;
use Validator;

class UsersController extends BaseController
{

    public function __construct()
    {
        
    }

    public function index()
    {
        return view('admin.users.index');
    }

    public function data()
    {
        $suff = App::getLocale() == ORAConsts::LANGUAGE2 ? "_lang2" : "";

        $users = DB::table("users")
            ->leftJoin("clinics", "users.clinic_id", "clinics.id")
            ->leftJoin("sites", "users.site_id", "sites.id")
            ->leftJoin("role_users", "users.id", "role_users.user_id")
            ->leftJoin("roles", "role_users.role_id", "roles.id")
            ->whereNull("users.deleted_at")
            ->select(
                'users.id',
                'users.username',
                'users.first_name',
                'users.last_name',
                'users.email',
                'roles.slug AS role_code',
                'roles.name AS role_name',
                DB::raw('IFNULL(clinics.name' . $suff . ', clinics.name) AS clinic_name'),
                DB::raw('IFNULL(sites.name' . $suff . ', sites.name) AS site_name')
            )
            ->get();

        return DataTables::of($users)
            ->editColumn('role_name', function ($user) {
                if ($user->role_code == "admin") {
                    return '<span class="label label-sm label-danger label-mini">' . $user->role_name . '</span>';
                }
                return '<span class="label label-sm label-info label-mini">' . $user->role_name . '</span>';
            })
            ->addColumn('actions', function ($user) {
                $actions = $this->getGridEditButton(route('admin.users.edit', $user->id));
                if ((Sentinel::getUser()->id != $user->id) && ($user->id != 1)) {
                    $actions .= $this->getGridDeleteButton(route('admin.users.confirm-delete', $user->id));
                }
                return $actions;
            })
            ->rawColumns(['role_name', 'actions'])
            ->make(true);
    }

    public function create()
    {
        //Load additional data for select boxes
        $groups = Sentinel::getRoleRepository()->pluck('name', 'id')->all();
        $clinics = Clinic::getEnabled()->pluck('name', 'id')->all();
        $sites = Site::getEnabled()->pluck('name', 'id')->all();

        // Show the page
        return view('admin.users.create', compact('groups', 'clinics', 'sites'));
    }

    public function store(UserRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {

                // Register the user
                $user = Sentinel::register($request->except('_token', 'password_confirm', 'group_id', 'activate', 'pic_file', 'clinics_ids'), true);

                //Store clinics
                $this->storeUserClinics($user, $request, true);

                //Add user to 'Role' group
                $group_id = $request->get('group_id');
                $role = Sentinel::findRoleById($group_id);
                if (!empty($role)) {
                    $role->users()->attach($user);
                }

            });

            // Redirect to the home page with success menu
            return Redirect::route('admin.users.index')->with('success', Lang::get('users/message.success.create'));
        } catch (LoginRequiredException $e) {
            $error = Lang::get('admin/users/message.user_login_required');
        } catch (PasswordRequiredException $e) {
            $error = Lang::get('admin/users/message.user_password_required');
        } catch (UserExistsException $e) {
            $error = Lang::get('admin/users/message.user_exists');
        }

        // Redirect to the user creation page
        return Redirect::back()->withInput()->with('error', $error);
    }

    public function edit(User $user = null)
    {
        // Get this user group
        $group_id = $user->getRoles()->pluck('id')->first();

        // Load additional data
        $groups = Sentinel::getRoleRepository()->pluck('name', 'id')->all();
        $clinics = Clinic::getEnabled()->pluck('name', 'id')->all();
        $sites = Site::getEnabled()->pluck('name', 'id')->all();
        $user_clinics = $user->usersClinics()->pluck('clinic_id')->all();;

        // Show the page
        return view('admin.users.edit',
            compact('user', 'groups', 'clinics', 'group_id', 'user_clinics', 'sites'))
            ->with('isMyUser', false);
    }

    public function myUser()
    {
        $loggedUser = Sentinel::getUser();
        $userId = $loggedUser->id;
        $user = User::where('id', '=', $userId)
            ->whereNull('deleted_at')
            ->first();
//        $user_clinics = $user->usersClinics()->pluck('clinic_id')->all();;

        // Get this user group
        $group_id = $user->getRoles()->pluck('id')->first();

        return view('admin.users.edit',
            compact('user', 'group_id'))//, 'user_clinics'
        ->with('isMyUser', true);
    }

    public function update(User $user, UserRequest $request)
    {
        try {
            DB::transaction(function () use ($user, $request) {
                $user->first_name = $request->get('first_name');
                $user->last_name = $request->get('last_name');
                $user->username = $request->get('username');
                $user->email = $request->get('email');
                if ($password = $request->has('password') && $request->password != '') {
                    $user->password = Hash::make($request->password);
                }

                //In user profile, do not update clinic, it's not visible
                if ($request->get('is_my_user') != '1') {
                    $user->clinic_id = $request->get('clinic_id');
                    $user->site_id = $request->get('site_id');
                }

                //Save user
                $user->save();

                //In user profile, do not update roles and clinics, they're not visible
                if ($request->get('is_my_user') != '1') {
                    //Store clinics
                    $this->storeUserClinics($user, $request, false);

                    // Get the current user groups
                    $userRoles = $user->roles()->pluck('id')->all();
                    $selectedRoles = array($request->get('group_id'));

                    // Groups comparison between the groups the user currently have and the groups the user wish to have.
                    $rolesToAdd = array_diff($selectedRoles, $userRoles);
                    $rolesToRemove = array_diff($userRoles, $selectedRoles);

                    // Remove the user from groups
                    foreach ($rolesToRemove as $roleId) {
                        $role = Sentinel::findRoleById($roleId);
                        $role->users()->detach($user);
                    }

                    // Assign the user to roles groups
                    foreach ($rolesToAdd as $roleId) {
                        $role = Sentinel::findRoleById($roleId);
                        $role->users()->attach($user);
                    }
                }
            });

            $loginUser = Sentinel::getUser();
            if ($loginUser->inRole('admin')) {
                // Admin returns to the list page
                return Redirect::route('admin.users.index', $user)->with('success', Lang::get('users/message.success.update'));
            } else {
                // User stays there
                return Redirect::route('admin.users.myuser')->with('success', Lang::get('users/message.success.update'));
            }
        } catch (UserNotFoundException $e) {
            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('error', Lang::get('users/message.user_not_found', compact('user')));
        }

        // Redirect to the user page
        return Redirect::route('admin.users.edit', $user)->withInput()->with('error', $error);
    }

    function storeUserClinics($user, $request, $isNewUser = false)
    {
        if (!$isNewUser) {
            //Delete everything and re-insert
            DB::table('users_clinics')
                ->where('user_id', $user->id)
                ->delete();
        }

        $clinics_ids = $request->get('clinics_ids');
        if ($clinics_ids != '') {
            $arr_clinics_ids = explode(',', $clinics_ids);
            foreach ($arr_clinics_ids as $clinic_id) {
                $users_clinic = new UsersClinic();
                $users_clinic->user_id = $user->id;
                $users_clinic->clinic_id = $clinic_id;
                $users_clinic->save();
            }
        }
    }

    public function getModalDelete($id = null)
    {
        $model = '';
        $confirm_route = $error = null;
        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = Lang::get('users/message.error.delete');
                return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = Lang::get('users/message.user_not_found', compact('id'));
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
        $confirm_route = route('admin.users.delete', ['id' => $user->id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    public function destroy($id = null)
    {
        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = Lang::get('admin/users/message.error.delete');

                // Redirect to the user management page
                return Redirect::route('admin.users.index')->with('error', $error);
            }

            // Delete the user
            //to allow soft deleted, we are performing query on users model instead of Sentinel model
            //$user->delete();
            User::destroy($id);

            // Prepare the success message
            $success = Lang::get('users/message.success.delete');

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = Lang::get('admin/users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('error', $error);
        }
    }

    public function passwordreset($id, Request $request)
    {
        $user = Sentinel::findUserById($id);
        $password = $request->get('password');
        $user->password = Hash::make($password);
        $user->save();
    }
}
