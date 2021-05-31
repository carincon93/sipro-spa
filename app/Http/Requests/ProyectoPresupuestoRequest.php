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
            'valor'                         => ['nullable', 'min:0', 'numeric'],
            'numero_items'                  => ['nullable', 'min:0', 'numeric'],
            'servicio_edicion_info'         => ['exclude_if:numero_items,null', 'exclude_if:codigo_uso_presupuestal,2010100600203101', 'required_if:codigo_uso_presupuestal,2020200800901', 'integer'],
            'tipo_licencia'                 => ['exclude_if:numero_items,null', 'exclude_if:codigo_uso_presupuestal,2020200800901', 'required_if:codigo_uso_presupuestal,2010100600203101', 'integer'],
            'tipo_software'                 => ['exclude_if:numero_items,null', 'exclude_if:codigo_uso_presupuestal,2020200800901', 'required_if:codigo_uso_presupuestal,2010100600203101', 'integer'],
            'fecha_inicio'                  => ['exclude_if:numero_items,null', 'exclude_if:codigo_uso_presupuestal,2020200800901', 'required_if:codigo_uso_presupuestal,2010100600203101', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion'],
            'fecha_finalizacion'            => ['exclude_if:numero_items,null', 'exclude_if:codigo_uso_presupuestal,2020200800901', 'required_if:codigo_uso_presupuestal,2010100600203101', 'date', 'date_format:Y-m-d', 'after:fecha_inicio'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->servicio_edicion_info)) {
            $this->merge([
                'servicio_edicion_info' => $this->servicio_edicion_info['value'],
            ]);
        }
    }
}
