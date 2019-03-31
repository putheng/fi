<?php

namespace App\Http\Requests;

use App\Models\Clinic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class ClinicRequest extends Request
{

//    public function __construct() {
//        Validator::extend("emails", function($attribute, $value, $parameters) {
//            $rules = [
//                'email' => 'email',
//            ];
//            foreach ($value as $email) {
//                $data = [
//                    'email' => $email
//                ];
//                $validator = Validator::make($data, $rules);
//                if ($validator->fails()) {
//                    return false;
//                }
//            }
//            return true;
//        });
//    }

    public function getModel(){
        return new Clinic();
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
                    'code' => [
                        'required',
                        'max:20',
                        ($this->method() == "PATCH" ? "" :
                            Rule::unique('clinics')->where(function ($query) {
                                $query->whereNull('deleted_at');
                            })
                        )
                    ],
                    'name' => 'required|max:255',
                    'name_lang2' => 'max:255',
                    //'site_id' => 'required',
                    'location_desc' => 'max:500',
                    'location_desc_lang2' => 'max:500',
                    'address_desc' => 'max:500',
                    'address_desc_lang2' => 'max:500',
                    'email' => 'max:500',
                    'phone_num' => 'max:50',
                    // 'website_url' => 'url|max:255',
                    'video_url' => 'url|max:1000',
                    'logo_url' => 'image',
                    // 'forward_url' => 'url|max:1000',
                    'res_notes' => 'max:4000',
                    'res_notes_lang2' => 'max:4000',
                    'res_time_slot_length' => 'required|integer',
                    'sort_index' => 'required|integer',
                    'is_enabled' => 'boolean',
                    'res_options' => 'boolean',
                    'res_options_text' => 'required_if:res_options,1|max:255',
                    'res_options_text_lang2' => 'required_if:res_options,1|max:255',
                    'days_visible' => 'required|numeric|min:1|max:30',
                    'directions_desc' => 'max:2000',
                    'directions_desc_lang2' => 'max:2000',
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
        $t = new Clinic();
        $fs = $t->getFillable();
        $attrs = array();
        foreach ($fs as $f) {
           $attrs[$f] = Lang::get("clinics/title.$f");
        }
        return $attrs;
    }


}

