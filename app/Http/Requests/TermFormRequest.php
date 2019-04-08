<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'heading' => 'required',
            'heading_en' => 'required',
            'title' => 'required',
            'title_en' => 'required',
            'subtitle' => 'required',
            'subtitle_en' => 'required',
            'bradcume' => 'required',
            'bradcume_en' => 'required',
            'term' => 'required',
            'term_en' => 'required',
            'note' => 'required',
            'note_en' => 'required',
        ];
    }
}
