<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectAnnexeRequest extends FormRequest
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
            'annexe_id'  => ['required', 'min:0', 'max:9999999999', 'integer', 'exists:annexes,id'],
            'file'       => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf'],
        ];
    }
}
