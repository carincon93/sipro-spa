<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallRequest extends FormRequest
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
            'description'           => ['required'],
            'fecha_incio'            => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion' ],
            'fecha_finalizacion'              => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_incio'],
            'active'                => ['required', 'boolean'],
            'project_fecha_incio'    => ['required', 'date', 'date_format:Y-m-d', 'before:project_fecha_finalizacion' ],
            'project_fecha_finalizacion'      => ['required', 'date', 'date_format:Y-m-d', 'after:project_fecha_incio'],
        ];
    }
}
