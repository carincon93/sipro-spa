<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxWords;
use App\Rules\FechaInicioProyecto;
use App\Rules\FechaFinalizacionProyecto;

class TaTpRequest extends FormRequest
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
                'centro_formacion_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'tipo_proyecto_id'                          => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tipos_proyecto,id'],
                'tecnoacademia_linea_tecnologica_id'        => ['required', 'min:0', 'max:2147483647', 'exists:tecnoacademia_linea_tecnologica,id'],
                'titulo'                                    => ['required', new MaxWords],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
                'resumen'                                   => ['required', 'max:2400', 'string'],
                'antecedentes'                              => ['required', 'max:40000', 'string'],
                'marco_conceptual'                          => ['required', 'string'],
                'metodologia'                               => ['required', 'string'],
                'propuesta_sostenibilidad'                  => ['required', 'string'],
                'bibliografia'                              => ['required', 'string'],
                'municipios*'                               => ['required', 'integer', 'exists:municipios,id'],
                'impacto_municipios'                        => ['required', 'string'],
                'impacto_centro_formacion'                  => ['required', 'string'],
                'nombre_instituciones'                      => ['required', 'json'],
                'diseno_curricular'                         => ['required']
            ];
        } else {
            return [
                'centro_formacion_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'tipo_proyecto_id'                          => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tipos_proyecto,id'],
                'tecnoacademia_linea_tecnologica_id'        => ['required', 'min:0', 'max:2147483647', 'exists:tecnoacademia_linea_tecnologica,id'],
                'titulo'                                    => ['required', new MaxWords],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
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
        if (is_array($this->municipios)) {
            if (isset($this->municipios['value']) && is_numeric($this->municipios['value'])) {
                $this->merge([
                    'municipios' => $this->municipios['value'],
                ]);
            } else {
                $municipios = [];
                foreach ($this->municipios as $municipio) {
                    if (is_array($municipio)) {
                        array_push($municipios, $municipio['value']);
                    }
                }
                $this->merge(['municipios' => $municipios]);
            }
        }

        if (is_string($this->titulo)) {
            $this->merge([
                'titulo' => ucfirst(mb_strtolower($this->titulo)),
            ]);
        }

        $this->merge([
            'diseno_curricular' => 0
        ]);
    }
}
