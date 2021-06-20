<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FechaInicioProyecto;
use App\Rules\FechaFinalizacionProyecto;

class ActividadRequest extends FormRequest
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
            'objetivo_especifico_id'            => ['nullable', 'min:0', 'max:2147483647', 'integer', 'exists:objetivos_especificos,id'],
            'producto_id*'                      => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:productos,id'],
            'proyecto_presupuesto_id*'          => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:proyecto_rubro_presupuestal,id'],
            'descripcion'                       => ['required', 'string'],
            'fecha_inicio'                      => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'), null, $this->route('proyecto'))],
            'fecha_finalizacion'                => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'), null, $this->route('proyecto'))],
        ];
    }
}
