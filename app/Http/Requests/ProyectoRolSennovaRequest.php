<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyectoRolSennovaRequest extends FormRequest
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
            'convocatoria_rol_sennova_id'   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:convocatoria_rol_sennova,id'],
            'descripcion'                   => ['required'],
            'numero_meses'                  => ['required', 'min:0', 'max:12'],
            'numero_roles'                  => ['required', 'min:0', 'max:999', 'integer']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->convocatoria_rol_sennova_id) ) {
            $this->merge([
                'convocatoria_rol_sennova_id' => $this->convocatoria_rol_sennova_id['value'],
            ]);
        }
    }
}
