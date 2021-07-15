<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FechaInicioProyecto;
use App\Rules\FechaFinalizacionProyecto;

class TaRequest extends FormRequest
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
                'tecnoacademia_linea_tecnoacademia_id*'       => ['required', 'min:0', 'max:2147483647', 'exists:tecnoacademia_linea_tecnoacademia,id'],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'), 'ta', null)],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'), 'ta', null)],
                'max_meses_ejecucion'                       => ['required', 'numeric', 'min:1', 'max:12'],
                'resumen'                                   => ['required', 'max:40000', 'string'],
                'resumen_regional'                          => ['required', 'max:40000', 'string'],
                'antecedentes'                              => ['required', 'max:40000', 'string'],
                'antecedentes_tecnoacademia'                => ['required', 'max:40000', 'string'],
                'justificacion'                             => ['required', 'max:40000', 'string'],
                'marco_conceptual'                          => ['required', 'string'],
                'bibliografia'                              => ['required', 'string'],
                'municipios*'                               => ['required', 'integer', 'exists:municipios,id'],
                'municipios_impactar*'                      => ['required', 'integer', 'exists:municipios,id'],
                'programas_formacion_articulados*'          => ['required', 'integer', 'exists:programas_formacion,id'],
                'dis_curricular_id*'                        => ['required', 'integer', 'exists:dis_curriculares,id'],
                'impacto_municipios'                        => ['required', 'string'],
                'articulacion_centro_formacion'             => ['required', 'string'],
                'nombre_instituciones'                      => ['required', 'json'],
                'nombre_instituciones_programas'            => ['required', 'json'],
                'nuevas_instituciones'                      => ['required', 'json'],
                'pertinencia_territorio'                    => ['required', 'max:40000', 'string'],
                'retos_oportunidades'                       => ['required', 'max:40000', 'string'],
            ];
        } else {
            return [
                'tecnoacademia_linea_tecnoacademia_id*'       => ['required', 'min:0', 'max:2147483647', 'exists:tecnoacademia_linea_tecnoacademia,id'],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'), 'ta', null)],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'), 'ta', null)],
                'max_meses_ejecucion'                       => ['required', 'numeric', 'min:1', 'max:12'],
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

        if (is_array($this->municipios_impactar)) {
            if (isset($this->municipios_impactar['value']) && is_numeric($this->municipios_impactar['value'])) {
                $this->merge([
                    'municipios_impactar' => $this->municipios_impactar['value'],
                ]);
            } else {
                $municipiosImpactar = [];
                foreach ($this->municipios_impactar as $municipio) {
                    if (is_array($municipio)) {
                        array_push($municipiosImpactar, $municipio['value']);
                    }
                }
                $this->merge(['municipios_impactar' => $municipiosImpactar]);
            }
        }

        if (is_array($this->programas_formacion_articulados)) {
            if (isset($this->programas_formacion_articulados['value']) && is_numeric($this->programas_formacion_articulados['value'])) {
                $this->merge([
                    'programas_formacion_articulados' => $this->programas_formacion_articulados['value'],
                ]);
            } else {
                $programasFormacionArticulados = [];
                foreach ($this->programas_formacion_articulados as $programaFormacion) {
                    if (is_array($programaFormacion)) {
                        array_push($programasFormacionArticulados, $programaFormacion['value']);
                    }
                }
                $this->merge(['programas_formacion_articulados' => $programasFormacionArticulados]);
            }
        }

        if (is_array($this->dis_curricular_id)) {
            if (isset($this->dis_curricular_id['value']) && is_numeric($this->dis_curricular_id['value'])) {
                $this->merge([
                    'dis_curricular_id' => $this->dis_curricular_id['value'],
                ]);
            } else {
                $programasDisCurricular = [];
                foreach ($this->dis_curricular_id as $programaFormacion) {
                    if (is_array($programaFormacion)) {
                        array_push($programasDisCurricular, $programaFormacion['value']);
                    }
                }
                $this->merge(['dis_curricular_id' => $programasDisCurricular]);
            }
        }

        if (is_array($this->tecnoacademia_linea_tecnoacademia_id)) {
            if (isset($this->tecnoacademia_linea_tecnoacademia_id['value']) && is_numeric($this->tecnoacademia_linea_tecnoacademia_id['value'])) {
                $this->merge([
                    'tecnoacademia_linea_tecnoacademia_id' => $this->tecnoacademia_linea_tecnoacademia_id['value'],
                ]);
            } else {
                $lineasTecnoacademiaRelacionados = [];
                foreach ($this->tecnoacademia_linea_tecnoacademia_id as $programaFormacion) {
                    if (is_array($programaFormacion)) {
                        array_push($lineasTecnoacademiaRelacionados, $programaFormacion['value']);
                    }
                }
                $this->merge(['tecnoacademia_linea_tecnoacademia_id' => $lineasTecnoacademiaRelacionados]);
            }
        }

        if (is_array($this->linea_programatica)) {
            $this->merge([
                'linea_programatica' => $this->linea_programatica['codigo'],
            ]);
        }
    }
}
