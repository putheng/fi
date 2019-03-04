<?php

namespace App\Http\Requests;

use App\Models\Token;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class TokenRequest extends Request
{

    public function getModel(){
        return new Token();
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
            case 'POST':
            case 'PUT':
            case 'PATCH': {
                return [
                    'token_num' => [
                        'required',
                        'integer',
                        ($this->method() == "PATCH" ? "" :
                            Rule::unique('tokens')->where(function ($query) {
                                $query->whereNull('deleted_at');
                            })
                        )
                    ],
                    'title' => 'required|max:100',
                    'return_url' => 'url|max:255',
                    'fe_default_lang' => 'required|max:10',
                    'fe_image_url' => 'mimes:jpg,jpeg,png',
                    'fe_welcome_text_size' => 'required|integer|min:10|max:100',
                    'fe_color_1' => 'required|max:20',
                    'fe_color_2' => 'required|max:20',
                    'fe_color_3' => 'required|max:20',
                    'fe_color_4' => 'required|max:20',
                    'is_enabled' => 'boolean',
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
        $t = new Token();
        $fs = $t->getFillable();
        $attrs = array();
        foreach ($fs as $f) {
           $attrs[$f] = Lang::get("tokens/title.$f");
        }
        return $attrs;
    }


}

