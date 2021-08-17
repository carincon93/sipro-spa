<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnexoRequest extends FormRequest
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
            'linea_programatica_id*'    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:lineas_programaticas,id'],
            'nombre'                    => ['required', 'max:191', 'string'],
            'descripcion'               => ['required', 'string'],
            'archivo'                   => ['nullable', 'max:10000000', 'file'],
            'obligatorio'               => ['required', 'boolean'],
            'habilitado'                => ['required', 'boolean'],
        ];
    }
}
