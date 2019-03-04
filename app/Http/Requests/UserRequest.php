<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Lang;

class UserRequest extends Request
{

    function getModel()
    {
        return new User();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'first_name' => 'required|min:3',
                    'last_name' => 'required|min:3',
                    'username' => 'required|min:3|unique:users,username',
                    'email' => 'required|email', //|unique:users,email
                    'password' => 'required|between:3,32',
                    'password_confirm' => 'required|same:password',
                    'group_id' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'first_name' => 'required|min:3',
                    'last_name' => 'required|min:3',
                    'username' => 'required|min:3|unique:users,username,' . $this->user->id,
                    'email' => 'required', //|unique:users,email,' . $this->user->id,
                    'password' => 'between:3,32',
                    'password_confirm' => 'between:3,32|same:password',
                    'group_id' => 'required'
                ];
            }
            default:
                break;
        }

        return [

        ];
    }

    public function attributes()
    {
        $model = $this->getModel();
        $fs = $model->getFillable();
        $table = $model->getTable();
        $attrs = array();
        foreach ($fs as $f) {
            $attrs[$f] = Lang::get("$table/title.$f");
        }
        //Add additional fields
        $attrs["password_confirm"] = Lang::get("users/title.confirm_password");
        $attrs["group_id"] = Lang::get("users/title.role");
        return $attrs;
    }

}

