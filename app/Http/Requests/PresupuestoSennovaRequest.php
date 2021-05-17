<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresupuestoSennovaRequest extends FormRequest
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
            'primer_grupo_presupuestal_id'     => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:primer_grupo_presupuestal,id'],
            'segundo_grupo_presupuestal_id'    => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:segundo_grupo_presupuestal,id'],
            'tercer_grupo_presupuestal_id'     => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:tercer_grupo_presupuestal,id'],
            'uso_presupuestal_id'              => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:usos_presupuestales,id'],
            'linea_programatica_id'            => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:lineas_programaticas,id'],
            'requiere_lote_estudio_mercado'    => ['required', 'boolean'],
            'requiere_estudio_mercado'         => ['required', 'boolean'],
            'sumar_al_presupuesto'             => ['required', 'boolean'],
            'mensaje'                          => ['nullable', 'string'],
            'valor_maximo'                     => ['nullable', 'numeric'],
        ];
    }
}
