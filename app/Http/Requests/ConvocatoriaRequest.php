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
            'fecha_inicio_convocatoria_idi'             => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion_convocatoria_idi'],
            'fecha_finalizacion_convocatoria_idi'       => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio_convocatoria_idi'],
            'fecha_inicio_convocatoria_cultura'         => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion_convocatoria_cultura'],
            'fecha_finalizacion_convocatoria_cultura'   => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio_convocatoria_cultura'],
            'fecha_inicio_convocatoria_st'              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion_convocatoria_st'],
            'fecha_finalizacion_convocatoria_st'        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio_convocatoria_st'],
            'fecha_inicio_convocatoria_ta'              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion_convocatoria_ta'],
            'fecha_inicio_convocatoria_tp'              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion_convocatoria_tp'],
            'fecha_finalizacion_convocatoria_ta'        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio_convocatoria_ta'],
            'fecha_finalizacion_convocatoria_tp'        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio_convocatoria_tp'],
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
}
