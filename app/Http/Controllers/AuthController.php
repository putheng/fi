<?php namespace App\Http\Controllers;

use App\Http\Common\ORAConsts;
use App\Models\OrganizationsGroups;
use App\Models\User;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Organization;
use App\OrganizationUser;
use Lang;
use Mail;
use Reminder;
use Sentinel;
use URL;
use Validator;
use View;

class AuthController extends BaseController
{

    /**
     * Account sign in.
     *
     * @return View
     */
    public function getSignin()
    {
        // Is the user logged in?
        if (Sentinel::check()) {
            return Redirect::route('admin.reservations');
        }

        // Show the page
        return view('admin.login');
    }

    /**
     * Account sign in form processing.
     * @param Request $request
     * @return Redirect
     */
    public function postSignin(Request $request)
    {

        try {
            // Try to log the user in
            if (Sentinel::authenticate($request->only(['username', 'password']), ($request->get('remember-me') == "true" ? true : false))) {

                $user = Sentinel::getUser();

                //Validate the user clinic
                if ($user->inRole(ORAConsts::ROLE_ADMIN_SLUG) ||
                    $user->inRole(ORAConsts::ROLE_LIMITED_ADMIN_SLUG) ||
                    $user->inRole(ORAConsts::ROLE_STRONG_ADMIN_SLUG)) {
                    // Redirect to the clinics page
                    return Redirect::route("admin.clinics.index")->with('success', Lang::get('auth/message.signin.success'));
                } else {
                    if ($user->inRole(ORAConsts::ROLE_USER_MULTI_SLUG)) {
                        $clinics = DB::table('clinics AS c')
                            ->join('users_clinics AS uc', 'c.id', '=', 'uc.clinic_id')
                            ->where('uc.user_id', $user->id)
                            ->where('c.is_enabled', 1)
                            ->whereNull('c.deleted_at')->get();
                    } else {
                        $clinics = DB::table('clinics')
                            ->where('id', $user->clinic_id)
                            ->where('is_enabled', 1)
                            ->whereNull('deleted_at')->get();
                    }

                    if (count($clinics) == 0) {
                        throw new \Exception('auth/message.user_no_clinics');
                    } else {
                        session()->put('clinic_logo_url', $clinics[0]->logo_url);
                    }

                    // Redirect to the reservations page
                    return Redirect::route("admin.reservations")->with('success', Lang::get('auth/message.signin.success'));
                }
            }

            $this->messageBag->add('username', Lang::get('auth/message.account_not_found'));

        } catch (NotActivatedException $e) {
            $this->messageBag->add('username', Lang::get('auth/message.account_not_activated'));
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            $this->messageBag->add('username', Lang::get('auth/message.account_suspended', compact('delay')));
        } catch (\Exception $e) {
            Sentinel::logout();
            $request->session()->flush();
            $this->messageBag->add('username', Lang::get($e->getMessage()));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    /**
     * Forgot password form processing page.
     * @param Request $request
     *
     * @return Redirect
     */
    public function postForgotPassword(Request $request)
    {
        // Declare the rules for the validator
        $rules = array(
            'email' => 'required|email',
        );

        // Create a new validator instance from our dynamic rules
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::to(URL::previous() . '#toforgot')->withInput()->withErrors($validator);
        }

        try {
            // Get the user password recovery code
            $user = User::where('email', '=', $request->get('email'))
                ->whereNull('deleted_at')
                ->first();

            if (!$user) {
                return Redirect::to(URL::previous() . '#toforgot')->with('error', Lang::get('auth/message.account_email_not_found'));
            }
            $activation = Activation::completed($user);
            if (!$activation) {
                return Redirect::to(URL::previous() . '#toforgot')->with('error', Lang::get('auth/message.account_not_activated'));
            }
            $reminder = Reminder::exists($user) ?: Reminder::create($user);
            // Data to be used on the email view
            $data = array(
                'user' => $user,
                //'forgotPasswordUrl' => URL::route('forgot-password-confirm', $user->getResetPasswordCode()),
                'forgotPasswordUrl' => URL::route('forgot-password-confirm', [$user->id, $reminder->code]),
            );

            // Send the activation code through email
            Mail::send('emails.forgot-password', $data, function ($m) use ($user) {
                $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                $m->subject('Account Password Recovery');
            });
        } catch (UserNotFoundException $e) {
            // Even though the email was not found, we will pretend
            // we have sent the password reset code through email,
            // this is a security measure against hackers.
        }

        //  Redirect to the forgot password
        return Redirect::to(URL::previous() . '#toforgot')->with('success', Lang::get('auth/message.forgot-password.success'));
    }

    /**
     * Forgot Password Confirmation page.
     *
     * @param number $userId
     * @param  string $passwordResetCode
     * @return View
     */
    public function getForgotPasswordConfirm($userId, $passwordResetCode = null)
    {
        // Find the user using the password reset code
        if (!$user = Sentinel::findById($userId)) {
            // Redirect to the forgot password page
            return Redirect::route('forgot-password')->with('error', Lang::get('auth/message.account_not_found'));
        }

        if ($reminder = Reminder::exists($user)) {
            if ($passwordResetCode == $reminder->code) {
                return view('admin.auth.forgot-password-confirm');
            } else {
                return 'code does not match';
            }
        } else {
            return 'does not exists';
        }

        // Show the page
        // return View('admin.auth.forgot-password-confirm');
    }

    /**
     * Forgot Password Confirmation form processing page.
     *
     * @param Request $request
     * @param number $userId
     * @param  string $passwordResetCode
     * @return Redirect
     */
    public function postForgotPasswordConfirm(Request $request, $userId, $passwordResetCode = null)
    {
        // Declare the rules for the form validation
        $rules = array(
            'password' => 'required|between:3,32',
            'password_confirm' => 'required|same:password'
        );

        // Create a new validator instance from our dynamic rules
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::route('forgot-password-confirm', $passwordResetCode)->withInput()->withErrors($validator);
        }

        // Find the user using the password reset code
        $user = Sentinel::findById($userId);
        if (!$reminder = Reminder::complete($user, $passwordResetCode, $request->get('password'))) {
            // Ooops.. something went wrong
            return Redirect::route('signin')->with('error', Lang::get('auth/message.forgot-password-confirm.error'));
        }

        // Password successfully reseted
        return Redirect::route('signin')->with('success', Lang::get('auth/message.forgot-password-confirm.success'));
    }

    /**
     * Logout page.
     *
     * @return Redirect
     */
    public function getLogout(Request $request)
    {
        // Log the user out
        Sentinel::logout();

        //Clear the session
        $request->session()->flush();

        // Redirect to the users page
        return Redirect::to('admin/signin')->with('success', 'You have successfully logged out!');
    }

}
