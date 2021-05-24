<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FechaInicioProyecto;
use App\Rules\FechaFinalizacionProyecto;

class ProductoRequest extends FormRequest
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
            'resultado_id'                  => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:resultados,id'],
            'nombre'                        => ['required', 'max:191'],
            'fecha_inicio'                  => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
            'fecha_finalizacion'            => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
            'indicador'                     => ['required', 'string'],
            'idi'                           => ['required', 'boolean'],
            'trl'                           => ['required_if:idi,true', 'exclude_if:idi,false', 'digits_between:1,9'],
            'subtipologia_minciencias_id'   => ['required_if:idi,true', 'exclude_if:idi,false', 'min:0', 'max:2147483647', 'integer', 'exists:subtipologias_minciencias,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->resultado_id)) {
            $this->merge([
                'resultado_id' => $this->resultado_id['value'],
            ]);
        }

        if (is_array($this->tipo)) {
            $this->merge([
                'tipo' => $this->tipo['value'],
            ]);
        }
    }
}
