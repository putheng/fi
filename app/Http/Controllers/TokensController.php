<?php namespace App\Http\Controllers;

use App\Http\Requests\TokenRequest;
use App\Models\Token;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

class TokensController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission_admin');
    }

    public function index()
    {
        return view('admin.tokens.index');
    }

    public function data()
    {
        $records = DB::table("tokens")
            ->whereNull('deleted_at')
            ->get();

        return Datatables::of($records)
            ->edit_column('token_num', function ($token) {
                if ($token->is_default == 1) {

                    return $token->token_num . ' <span class="label label-sm label-info label-mini">' . Lang::get('tokens/title.is_default') . '</span>';
                }
                return $token->token_num;
            })
            ->add_column('actions', function ($token) {
                $actions = $this->getGridEditButton(route('admin.tokens.edit', $token->id));
                if ($token->is_default == 0) {
                    $actions .= $this->getGridDeleteButton(route('admin.tokens.confirm-delete', $token->id));
                }
                return $actions;
            })->make(true);
    }

    public function create()
    {
        $defaultImageUrl = $this->getDefaultImageUrl();

        return view('admin.tokens.create')
            ->with('default_image_url', $defaultImageUrl);
    }


    public function store(TokenRequest $request)
    {
        $token = new Token($request->except('_token'));
        $token->fill($request->except('_method', '_token'));

        //Convert empty to null
        if($token->fe_welcome_text_lang2 == "") $token->fe_welcome_text_lang2 = null;
        if($token->fe_snippet_text_lang2 == "") $token->fe_snippet_text_lang2 = null;

        $this->storeImage($request, $token);

        //Use default image
        if(!$token->fe_image_url) $token->fe_image_url = $this->getDefaultImageUrl();

        //Save the record in the database
        $token->save();

        // Prepare the success message
        $success = Lang::get('message.success.create');

        // Redirect to the list page
        return Redirect::route('admin.tokens.index')->with('success', $success);
    }

    public function edit(Token $token = null)
    {
        return view('admin.tokens.edit', compact('token'));
    }

    public function update(Token $token, TokenRequest $request)
    {
        $token->fill($request->except('_method', '_token'));

        //Special handling for checkboxes. When unchecked they are not passed to the request
        $token->is_incentive = isset($request['is_incentive']) ? $request['is_incentive'] : 0;
        $token->skip_risk_assessment = isset($request['skip_risk_assessment']) ? $request['skip_risk_assessment'] : 0;

        //Convert empty to null
        if($token->fe_welcome_text_lang2 == "") $token->fe_welcome_text_lang2 = null;
        if($token->fe_snippet_text_lang2 == "") $token->fe_snippet_text_lang2 = null;

        $this->storeImage($request, $token);

        //Save the record in the database
        $token->save();

        // Prepare the success message
        $success = Lang::get('message.success.update');

        // Redirect to the list page
        return Redirect::route('admin.tokens.index')->with('success', $success);
    }

    function storeImage(TokenRequest $request, Token &$token)
    {
        $imageUrl = "";
        //Check if a new image has been uploaded
        if ($file = $request->file('fe_image')) {
            $imageNameOrig = $request->file('fe_image')->getClientOriginalName();
            $imageName = $token->token_num . '.' . $request->file('fe_image')->getClientOriginalExtension();

            $defaultPath = base_path() . '/public';
            $basePath = env('UPLOAD_FOLDER_PATH', $defaultPath);
            $path = "/images/tokens/";
            $origPath = "origin/";

            $request->file('fe_image')->move($basePath . $path, $imageName);
            $imageUrl = url("/") . $path . $imageName;

            //Keep also a copy of the original file
            File::copy($basePath . $path . $imageName, $basePath . $path . $origPath . $imageNameOrig);

            //IMAGE RESIZE - Width 700
            $img = Image::make($basePath . $path . $imageName);
            if ($img->getWidth() > 750) {
                $img->widen(750);
                $img->save();
            }

            //die("PATH:" . $basePath . $path . "   URL" . $imageUrl);
        }
        //Save the record in the database
        if ($imageUrl != "") {
            $token->fe_image_url = $imageUrl;
        }
    }

    public function delete($id = null)
    {
        $result = false;
        $item = Token::find($id);
        if ($item != null) {
            $result = $item->delete();
        }
        if ($result) {
            return Redirect::route('admin.tokens.index')
                ->with('success', Lang::get('message.success.delete'));
        } else {
            return Redirect::route('admin.tokens.index')
                ->with('error', Lang::get('message.error.delete'));
        }
    }

    public function getModalDelete($id = null)
    {
        $error = '';
        $model = '';
        $confirm_route = route('admin.tokens.delete', ['id' => $id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }


    function getDefaultImageUrl(){
        $defaultToken = DB::table('tokens')
            ->where('is_default', '=', 1)
            ->whereNull('deleted_at')
            ->first();

        $defaultImageUrl = null;
        if($defaultToken != null) {
            $defaultImageUrl = $defaultToken->fe_image_url;
        }

        return $defaultImageUrl;
    }
}
