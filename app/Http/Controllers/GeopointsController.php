<?php namespace App\Http\Controllers;

use App\Http\Requests\GeopointRequest;
use App\Models\Geopoint;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

class GeopointsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission_admin');
    }

    public function index()
    {
        return view('admin.geopoints.index');
    }

    public function data()
    {
        $records = DB::table("geopoints")
            ->whereNull('deleted_at')
            ->get();

        return Datatables::of($records)
            ->add_column('actions', function ($geopoint) {
                return
                    $this->getGridEditButton(route('admin.geopoints.edit', $geopoint->id)) .
                    $this->getGridDeleteButton(route('admin.geopoints.confirm-delete', $geopoint->id));
            })->make(true);
    }

    public function create()
    {
        return view('admin.geopoints.create');
    }

    public function store(GeopointRequest $request)
    {
        $geopoint = new Geopoint($request->except('_token'));
        $geopoint->fill($request->except('_method', '_token'));

        //Save the record in the database
        $geopoint->save();

        // Prepare the success message
        $success = Lang::get('message.success.create');

        // Redirect to the list page
        return Redirect::route('admin.geopoints.index')
            ->with('success', $success);
    }

    public function edit(Geopoint $geopoint = null)
    {
        return view('admin.geopoints.edit', compact('geopoint'));
    }

    public function update(Geopoint $geopoint, GeopointRequest $request)
    {
        $geopoint->fill($request->except('_method', '_token'));

        //Save the record in the database
        $geopoint->save();

        // Prepare the success message
        $success = Lang::get('message.success.update');

        // Redirect to the list page
        return Redirect::route('admin.geopoints.index')->with('success', $success);
    }

    public function delete($id = null)
    {
        $result = false;
        $item = Geopoint::find($id);

        if ($item != null) {
            $result = $item->delete();
        }

        if ($result) {
            return Redirect::route('admin.geopoints.index')
                ->with('success', Lang::get('message.success.delete'));
        } else {
            return Redirect::route('admin.geopoints.index')
                ->with('error', Lang::get('message.error.delete'));
        }
    }

    public function getModalDelete($id = null)
    {
        $error = '';
        $model = '';
        $confirm_route = route('admin.geopoints.delete', ['id' => $id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }


}
