<?php namespace App\Http\Controllers;

use App\Http\Requests\ChainRequest;
use App\Models\Chain;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;
use Sentinel;

class ChainsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission_admin');
    }

    public function index()
    {
        return view('admin.chains.index');
    }

    public function data()
    {
        $user = Sentinel::getUser();

        $query = DB::table("chains")
            ->whereNull('deleted_at');

        //User limited to one chain id
        if ($user->chain_id != null && $user->chain_id != 0) {
            $query = $query->where('id', $user->chain_id);
        }

        $records = $query->get();

        return Datatables::of($records)
            ->add_column('actions', function ($chain) {
                return
                    $this->getGridEditButton(route('admin.chains.edit', $chain->id)) .
                    $this->getGridDeleteButton(route('admin.chains.confirm-delete', $chain->id));
            })->make(true);
    }

    public function create()
    {
        return view('admin.chains.create');
    }

    public function store(ChainRequest $request)
    {
        $chain = new Chain($request->except('_token'));
        $chain->fill($request->except('_method', '_token'));

        //Save the record in the database
        $chain->save();

        // Prepare the success message
        $success = Lang::get('message.success.create');

        // Redirect to the list page
        return Redirect::route('admin.chains.index')
            ->with('success', $success);
    }

    public function edit(Chain $chain = null)
    {
        return view('admin.chains.edit', compact('chain'));
    }

    public function update(Chain $chain, ChainRequest $request)
    {
        $chain->fill($request->except('_method', '_token'));

        //Save the record in the database
        $chain->save();

        // Prepare the success message
        $success = Lang::get('message.success.update');

        // Redirect to the list page
        return Redirect::route('admin.chains.index')->with('success', $success);
    }

    public function delete($id = null)
    {
        $result = false;
        $item = Chain::find($id);

        if ($item != null) {
            $result = $item->delete();
        }

        if ($result) {
            return Redirect::route('admin.chains.index')
                ->with('success', Lang::get('message.success.delete'));
        } else {
            return Redirect::route('admin.chains.index')
                ->with('error', Lang::get('message.error.delete'));
        }
    }

    public function getModalDelete($id = null)
    {
        $error = '';
        $model = '';
        $confirm_route = route('admin.chains.delete', ['id' => $id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }


}
