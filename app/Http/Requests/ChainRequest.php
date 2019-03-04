<?php

namespace App\Http\Requests;

use App\Models\Chain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class ChainRequest extends Request
{

    public function getModel(){
        return new Chain();
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
            case 'DELETE': { return []; }
            case 'POST':
            case 'PUT':
            case 'PATCH': {
                return [
                    'code' => [
                        'required',
                        'max:20',
                        ($this->method() == "PATCH" ? "" :
                            Rule::unique('chains')->where(function ($query) {
                                $query->whereNull('deleted_at');
                            })
                        )
                    ],
                    'name' => 'required|max:255',
                    'name_lang2' => 'max:255',
                    'billing_code_info' => 'max:2000',
                    'billing_code_info_lang2' => 'max:2000',
                    'promo_text' => 'max:2000',
                    'promo_text_lang2' => 'max:2000',
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
        $t = new Chain();
        $fs = $t->getFillable();
        $attrs = array();
        foreach ($fs as $f) {
           $attrs[$f] = Lang::get("chains/title.$f");
        }
        return $attrs;
    }


}

