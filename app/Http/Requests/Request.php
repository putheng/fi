<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

abstract class Request extends FormRequest
{

    abstract function getModel();

    public function authorize()
    {
        return true;
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
        return $attrs;
    }

}
