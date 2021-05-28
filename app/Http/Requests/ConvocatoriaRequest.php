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
            'descripcion'                       => ['required'],
            'fecha_inicio'                       => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion'],
            'fecha_finalizacion'                => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio'],
            'esta_activa'                       => ['required', 'boolean'],
            'min_fecha_inicio_proyectos'        => ['required', 'date', 'date_format:Y-m-d', 'before:max_fecha_finalizacion_proyectos'],
            'max_fecha_finalizacion_proyectos'  => ['required', 'date', 'date_format:Y-m-d', 'after:min_fecha_inicio_proyectos'],
        ];
    }
}
