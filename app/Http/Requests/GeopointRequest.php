<?php

namespace App\Http\Requests;

use App\Models\Geopoint;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class GeopointRequest extends Request
{

    public function getModel(){
        return new Geopoint();
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
                            Rule::unique('geopoints')->where(function ($query) {
                                $query->whereNull('deleted_at');
                            })
                        )
                    ],
                    'name' => 'required|max:255',
                    'name_lang2' => 'required|max:255',
                    'sort_index' => 'required',
                    'gps_lat' => 'required|max:20',
                    'gps_long' => 'required|max:20'
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
        $t = new Geopoint();
        $fs = $t->getFillable();
        $attrs = array();
        foreach ($fs as $f) {
           $attrs[$f] = Lang::get("geopoints/title.$f");
        }
        return $attrs;
    }


}

