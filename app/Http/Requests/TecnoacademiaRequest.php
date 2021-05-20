<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TecnoacademiaRequest extends FormRequest
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
            'nombre'                => ['required', 'max:255'],
            'linea_tecnologica_id*' => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:lineas_tecnologicas,id'],
        ];
    }
}
