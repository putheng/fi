<?php namespace App\Http\Controllers;

use App\Http\Requests\SiteRequest;
use App\Models\Site;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;
use Sentinel;

class SitesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission_admin');
    }

    public function index()
    {
        return view('admin.sites.index');
    }

    public function data()
    {
        $user = Sentinel::getUser();

        $query = DB::table("sites")
            ->whereNull('deleted_at');

        //User limited to one site id
        if ($user->site_id != null && $user->site_id != 0) {
            $query = $query->where('id', $user->site_id);
        }

        $records = $query->get();

        return Datatables::of($records)
            ->add_column('actions', function ($site) {
                return
                    $this->getGridEditButton(route('admin.sites.edit', $site->id)) .
                    $this->getGridDeleteButton(route('admin.sites.confirm-delete', $site->id));
            })->make(true);
    }

    public function create()
    {
        return view('admin.sites.create');
    }

    public function store(SiteRequest $request)
    {
        $site = new Site($request->except('_token'));
        $site->fill($request->except('_method', '_token'));

        //Save the record in the database
        $site->save();

        // Prepare the success message
        $success = Lang::get('message.success.create');

        // Redirect to the list page
        return Redirect::route('admin.sites.index')
            ->with('success', $success);
    }

    public function edit(Site $site = null)
    {
        return view('admin.sites.edit', compact('site'));
    }

    public function update(Site $site, SiteRequest $request)
    {
        $site->fill($request->except('_method', '_token'));

        //Save the record in the database
        $site->save();

        // Prepare the success message
        $success = Lang::get('message.success.update');

        // Redirect to the list page
        return Redirect::route('admin.sites.index')->with('success', $success);
    }

    public function delete($id = null)
    {
        $result = false;
        $item = Site::find($id);

        if ($item != null) {
            $result = $item->delete();
        }

        if ($result) {
            return Redirect::route('admin.sites.index')
                ->with('success', Lang::get('message.success.delete'));
        } else {
            return Redirect::route('admin.sites.index')
                ->with('error', Lang::get('message.error.delete'));
        }
    }

    public function getModalDelete($id = null)
    {
        $error = '';
        $model = '';
        $confirm_route = route('admin.sites.delete', ['id' => $id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }


}
