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
                'centro_formacion_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'tecnoacademia_linea_tecnologica_id'        => ['required', 'min:0', 'max:2147483647', 'exists:tecnoacademia_linea_tecnologica,id'],
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
                'impacto_municipios'                        => ['required', 'string'],
                'articulacion_centro_formacion'             => ['required', 'string'],
                'nombre_instituciones'                      => ['required', 'json'],
                'nombre_instituciones_programas'            => ['required', 'json'],
                'diseno_curricular'                         => ['required']
            ];
        } else {
            return [
                'centro_formacion_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'tecnoacademia_linea_tecnologica_id'        => ['required', 'min:0', 'max:2147483647', 'exists:tecnoacademia_linea_tecnologica,id'],
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


        if (is_array($this->tecnoacademia_linea_tecnologica_id) && count($this->tecnoacademia_linea_tecnologica_id) > 0) {
            $this->merge([
                'tecnoacademia_linea_tecnologica_id' => $this->tecnoacademia_linea_tecnologica_id['value'],
            ]);
        }

        if (is_array($this->linea_programatica)) {
            $this->merge([
                'linea_programatica' => $this->linea_programatica['codigo'],
            ]);
        }

        $this->merge([
            'diseno_curricular' => 0
        ]);
    }
}
