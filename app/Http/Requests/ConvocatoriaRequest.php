<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvocatoriaRequest extends FormRequest
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
            'descripcion'                               => ['required'],
            'esta_activa'                               => ['required', 'boolean'],
            'fecha_finalizacion_fase'                   => ['required', 'date', 'date_format:Y-m-d'],
            'min_fecha_inicio_proyectos_idi'            => ['required', 'date', 'date_format:Y-m-d', 'before:max_fecha_finalizacion_proyectos_idi'],
            'max_fecha_finalizacion_proyectos_idi'      => ['required', 'date', 'date_format:Y-m-d', 'after:min_fecha_inicio_proyectos_idi'],
            'min_fecha_inicio_proyectos_cultura'        => ['required', 'date', 'date_format:Y-m-d', 'before:max_fecha_finalizacion_proyectos_cultura'],
            'max_fecha_finalizacion_proyectos_cultura'  => ['required', 'date', 'date_format:Y-m-d', 'after:min_fecha_inicio_proyectos_cultura'],
            'min_fecha_inicio_proyectos_st'             => ['required', 'date', 'date_format:Y-m-d', 'before:max_fecha_finalizacion_proyectos_st'],
            'max_fecha_finalizacion_proyectos_st'       => ['required', 'date', 'date_format:Y-m-d', 'after:min_fecha_inicio_proyectos_st'],
            'min_fecha_inicio_proyectos_ta'             => ['required', 'date', 'date_format:Y-m-d', 'before:max_fecha_finalizacion_proyectos_ta'],
            'min_fecha_inicio_proyectos_tp'             => ['required', 'date', 'date_format:Y-m-d', 'before:max_fecha_finalizacion_proyectos_tp'],
            'max_fecha_finalizacion_proyectos_ta'       => ['required', 'date', 'date_format:Y-m-d', 'after:min_fecha_inicio_proyectos_ta'],
            'max_fecha_finalizacion_proyectos_tp'       => ['required', 'date', 'date_format:Y-m-d', 'after:min_fecha_inicio_proyectos_tp'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->fase)) {
            $this->merge([
                'fase' => $this->fase['value'],
            ]);
        }
    }
}
