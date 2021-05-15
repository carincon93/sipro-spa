<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxWords;
use App\Rules\FechaInicioProyecto;
use App\Rules\FechaFinalizacionProyecto;

class IDiRequest extends FormRequest
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
                'linea_investigacion_id'                    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:lineas_investigacion,id'],
                'disciplina_subarea_conocimiento_id'        => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:disciplinas_subarea_conocimiento,id'],
                'tematica_estrategica_id'                   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tematicas_estrategicas,id'],
                'red_conocimiento_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:redes_conocimiento,id'],
                'actividad_economica_id'                    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:actividades_economicas,id'],
                'titulo'                                    => ['required', new MaxWords],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
                'video'                                     => ['nullable', 'max:191', 'url'],
                'resumen'                                   => ['required', 'max:2400', 'string'],
                'antecedentes'                              => ['required', 'max:40000', 'string'],
                'marco_conceptual'                          => ['required', 'string'],
                'metodologia'                               => ['required', 'string'],
                'propuesta_sostenibilidad'                  => ['required', 'string'],
                'justificacion_industria_4'                 => ['nullable', 'string'],
                'justificacion_economia_naranja'            => ['nullable', 'string'],
                'justificacion_politica_discapacidad'       => ['nullable', 'string'],
                'muestreo'                                  => ['required', 'max:191'],
                'actividades_muestreo'                      => ['nullable', 'max:191'],
                'muestreo_objective'                        => ['nullable', 'max:191'],
                'bibliografia'                              => ['required', 'string'],
                'numero_aprendices'                         => ['required', 'min:0', 'max:9999', 'integer'],
                'municipios*'                               => ['required', 'integer','exists:municipios,id'],
                'impacto_municipios'                        => ['required', 'string'],
                'impacto_centro_formacion'                  => ['required', 'string'],
                'relacionado_plan_tecnologico'              => ['required', 'min:0', 'max:3', 'integer'],
                'relacionado_agendas_competitividad'        => ['required', 'min:0', 'max:3', 'integer'],
                'relacionado_mesas_sectoriales'             => ['required', 'min:0', 'max:3', 'integer'],
                'relacionado_tecnoacademia'                 => ['required', 'min:0', 'max:3', 'integer'],
                'mesa_sectorial_id*'                        => ['required_if:relacionado_mesas_sectoriales,1', 'min:0', 'max:2147483647', 'exists:mesas_sectoriales,id'],
                'linea_tecnologica_id*'                     => ['required_if:relacionado_tecnoacademia,1', 'min:0', 'max:2147483647', 'exists:lineas_tecnologicas,id']
            ];
        } else {
            return [
                'centro_formacion_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'tipo_proyecto_id'                          => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tipos_proyecto,id'],
                'linea_investigacion_id'                    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:lineas_investigacion,id'],
                'disciplina_subarea_conocimiento_id'        => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:disciplinas_subarea_conocimiento,id'],
                'tematica_estrategica_id'                   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tematicas_estrategicas,id'],
                'red_conocimiento_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:redes_conocimiento,id'],
                'titulo'                                    => ['required', new MaxWords],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
                'actividad_economica_id'                    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:actividades_economicas,id'],
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
        if( is_string($this->titulo) ) {
            $this->merge([
                'titulo' => ucfirst(mb_strtolower($this->titulo)),
            ]);
        }

        if( is_array($this->centro_formacion_id) ) {
            $this->merge([
                'centro_formacion_id' => $this->centro_formacion_id['value'],
            ]);
        }

        if( is_array($this->relacionado_plan_tecnologico) ) {
            $this->merge([
                'relacionado_plan_tecnologico' => $this->relacionado_plan_tecnologico['value'],
            ]);
        }

        if( is_array($this->relacionado_agendas_competitividad) ) {
            $this->merge([
                'relacionado_agendas_competitividad' => $this->relacionado_agendas_competitividad['value'],
            ]);
        }

        if( is_array($this->relacionado_mesas_sectoriales) ) {
            $this->merge([
                'relacionado_mesas_sectoriales' => $this->relacionado_mesas_sectoriales['value'],
            ]);
        }

        if( is_array($this->relacionado_tecnoacademia) ) {
            $this->merge([
                'relacionado_tecnoacademia' => $this->relacionado_tecnoacademia['value'],
            ]);
        }

        if( is_array($this->municipios) ) {
            if(isset($this->municipios['value']) && is_numeric($this->municipios['value']) ) {
                $this->merge([
                    'municipios' => $this->municipios['value'],
                ]);
            }else{
                $municipios = [];
                foreach ($this->municipios as $municipio) {
                    if( is_array($municipio) ) {
                        array_push($municipios, $municipio['value']);
                    }
                }
                $this->merge(['municipios' => $municipios]);
            }
        }
    }
}
