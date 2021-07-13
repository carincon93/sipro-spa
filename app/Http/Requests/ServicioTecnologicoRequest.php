<?php

namespace App\Http\Requests;

use App\Rules\FechaFinalizacionProyecto;
use App\Rules\FechaInicioProyecto;
use App\Rules\MaxWords;
use Illuminate\Foundation\Http\FormRequest;

class ServicioTecnologicoRequest extends FormRequest
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
        if ($this->isMethod('PUT')) {
            return [
                'titulo'                                    => ['required', 'string', new MaxWords(40)],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'), 'st', null)],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'), 'st', null)],
                'max_meses_ejecucion'                       => ['required', 'numeric', 'min:1', 'max:12'],
                'resumen'                                   => ['required', 'max:1000', 'string'],
                'antecedentes'                              => ['required', 'max:10000', 'string'],
                'identificacion_problema'                   => ['required', 'max:5000', 'string'],
                'pregunta_formulacion_problema'             => ['required', 'string', new MaxWords(50)],
                'justificacion_problema'                    => ['required', 'max:5000', 'string'],
                'bibliografia'                              => ['required', 'string'],
            ];
        } else {
            return [
                'tipo_proyecto_st_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tipos_proyecto_st,id'],
                'titulo'                                    => ['required', 'string', new MaxWords(40)],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'), 'st', null)],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'), 'st', null)],
                'max_meses_ejecucion'                       => ['required', 'numeric', 'min:1', 'max:12'],
                'rol_sennova'                               => ['required', 'min:0', 'max:2147483647', 'integer'],
            ];
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_string($this->titulo)) {
            $this->merge([
                'titulo' => ucfirst(mb_strtolower($this->titulo)),
            ]);
        }

        if (is_array($this->tipo_proyecto_st_id)) {
            $this->merge([
                'tipo_proyecto_st_id' => $this->tipo_proyecto_st_id['value'],
            ]);
        }

        if (is_array($this->rol_sennova)) {
            $this->merge([
                'rol_sennova' => $this->rol_sennova['value'],
            ]);
        }
    }
}
