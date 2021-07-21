<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyectoPresupuestoRequest extends FormRequest
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
            'convocatoria_presupuesto_id'   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:convocatoria_presupuesto,id'],
            'descripcion'                   => ['required', 'string'],
            'justificacion'                 => ['required'],
            'valor_total'                   => ['nullable', 'min:0', 'numeric'],
            'formato_estudio_mercado'       => ['nullable', 'max:10000000', 'file'],
        ];
    }
}
