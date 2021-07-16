<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReglaRolCulturaRequest extends FormRequest
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
            'centro_formacion_id'  => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
            'auxiliar_editorial'   => ['required', 'min:0', 'max:32767', 'integer'],
            'gestor_editorial'     => ['required', 'min:0', 'max:32767', 'integer'],
            'experto_tematico'     => ['required', 'min:0', 'max:32767', 'integer'],
        ];
    }
}
