<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxWords;
use App\Rules\ProjectStartDate;
use App\Rules\ProjectEndDate;

class RDIRequest extends FormRequest
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
                'academic_centre_id'                        => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:academic_centres,id'],
                'project_type_id'                           => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:project_types,id'],
                'linea_investigacion_id'                          => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:research_lines,id'],
                'disciplina_subarea_conocimiento_id'           => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:knowledge_subarea_disciplines,id'],
                'tematica_estrategica_id'                     => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:strategic_thematics,id'],
                'red_conocimiento_id'                      => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:knowledge_networks,id'],
                'actividad_economica_id'                              => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:economic_activities,id'],
                'title'                                     => ['required', new MaxWords],
                'fecha_incio'                                => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new ProjectStartDate($this->route('call'))],
                'fecha_finalizacion'                                  => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_incio', new ProjectEndDate($this->route('call'))],
                'video'                                     => ['nullable', 'max:191', 'url'],
                'abstract'                                  => ['required', 'max:2400', 'string'],
                'antecedentes'                        => ['required', 'max:40000', 'string'],
                'marco_conceptual'                      => ['required', 'string'],
                'metodologia'                       => ['required', 'string'],
                'propuesta_sostenibilidad'                   => ['required', 'string'],
                'justificacion_industria_4'                  => ['nullable', 'string'],
                'justificacion_economica_naranja'              => ['nullable', 'string'],
                'justificacion_politica_discapacidad'         => ['nullable', 'string'],
                'muestreo'                                  => ['nullable', 'max:191'],
                'actividades_muestreo'                         => ['nullable', 'max:191'],
                'muestreo_objective'                        => ['nullable', 'max:191'],
                'bibliografia'                              => ['required', 'string'],
                'numero_aprendices'                                  => ['required', 'min:0', 'max:9999', 'integer'],
                'cities'                                    => ['required', 'array'],
                'cities.*'                                  => ['required', 'exists:cities,id', 'integer'],
                'impacto_ciudades'                             => ['required', 'string'],
                'impacto_centro_formacion'                           => ['required', 'string'],
                'relacionado_plan_tecnologico'           => ['required', 'min:0', 'max:3', 'integer'],
                'relacionado_agendas_competitividad'   => ['required', 'min:0', 'max:3', 'integer'],
                'relacionado_mesas_sectoriales'       => ['required', 'min:0', 'max:3', 'integer'],
                'relacionado_tecnoacademia'               => ['required', 'min:0', 'max:3', 'integer'],
                'sector_based_committee_id*'                => ['required_if:relacionado_mesas_sectoriales,1', 'min:0', 'max:2147483647', 'integer', 'exists:sector_based_committees,id'],
                'technological_line_id*'                    => ['required_if:relacionado_tecnoacademia,1', 'min:0', 'max:2147483647', 'integer', 'exists:technological_lines,id']
            ];
        } else {
            return [
                'academic_centre_id'                    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:academic_centres,id'],
                'project_type_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:project_types,id'],
                'linea_investigacion_id'                      => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:research_lines,id'],
                'disciplina_subarea_conocimiento_id'       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:knowledge_subarea_disciplines,id'],
                'tematica_estrategica_id'                 => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:strategic_thematics,id'],
                'red_conocimiento_id'                  => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:knowledge_networks,id'],
                'title'                                 => ['required', new MaxWords],
                'fecha_incio'                            => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new ProjectStartDate($this->route('call'))],
                'fecha_finalizacion'                              => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_incio', new ProjectEndDate($this->route('call'))],
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
        if( is_string($this->title) ) {
            $this->merge([
                'title' => ucfirst(mb_strtolower($this->title)),
            ]);
        }

        if( is_array($this->academic_centre_id) ) {
            $this->merge([
                'academic_centre_id' => $this->academic_centre_id['value'],
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

        if( is_array($this->cities) ) {
            if(isset($this->cities['value']) && is_numeric($this->cities['value']) ) {
                $this->merge([
                    'cities' => $this->cities['value'],
                ]);
            }else{
                $cities = [];
                foreach ($this->cities as $index => $state) {
                    if( is_array($state) ) {
                        array_push($cities, $state['value']);
                    }
                }
                $this->merge(['cities' => $cities]);
            }
        }
    }
}
