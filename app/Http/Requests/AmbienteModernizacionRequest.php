<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmbienteModernizacionRequest extends FormRequest
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
            'red_conocimiento_id'    => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:redes_conocimiento,id'],
            'linea_investigacion_id'    => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:lineas_investigacion,id'],
            'centro_formacion_id'    => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:centros_formacion,id'],
            'disciplina_subarea_conocimiento_id'    => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:disciplinas_subarea_conocimiento,id'],
            'tematica_estrategica_id'    => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:tematicas_estrategicas,id'],
            'mesa_sectorial_id*'    => ['nullable', 'min:0', 'max:2147483647999', 'integer', 'exists:mesas_sectoriales,id'],
            // 'codigos_proyectos_id'    => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:redes_conocimiento,id'],
            // 'cod_proyectos_beneficiados_id'    => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:redes_conocimiento,id'],

            'nombre_ambiente' =>  ['required', 'max:191', 'string'],
            'alineado_mesas_sectoriales' => ['required'],
            'financiado_anteriormente' => ['required'],
            'estado_general_maquinaria' => ['required'],
            'razon_estado_general' => ['required_if:estado_general_maquinaria,2,3'],
            'ambiente_activo' => ['required'],
            'programas_formacion_calificados' => ['required_if:ambiente_activo,1'],
            'programas_formacion' => ['required_if:ambiente_activo,1'],
            'justificacion_ambiente_inactivo' => ['required_if:ambiente_activo,0'],
            'ambiente_activo_procesos_idi' => ['required'],
            'numero_proyectos_beneficiados' => ['required_if:ambiente_activo_procesos_idi,1'],
            'ambiente_formacion_complementaria' => ['required'],
            'numero_total_cursos_comp' => ['required_if:ambiente_formacion_complementaria,1'],
            'numero_cursos_empresas' => ['required'],
            'datos_empresa' => ['required_if:ambiente_formacion_complementaria,1'],
            'cursos_complementarios' => ['required_if:ambiente_formacion_complementaria,1'],
            'coordenada_latitud_ambiente' => ['required'],
            'coordenada_longitud_ambiente' => ['required'],
            'soporte_fotos_ambiente' => ['required'],
            'impacto_procesos_formacion' => ['required'],
            'pertinencia_sector_productivo' => ['required'],
            'palabras_clave_ambiente' => ['required'],
            'observaciones_generales_ambiente' => ['nullable'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->centro_formacion_id)) {
            $this->merge([
                'centro_formacion_id' => $this->centro_formacion_id['value'],
            ]);
        }

        if (is_array($this->codigo_proyecto_sgps_id)) {
            $this->merge([
                'codigo_proyecto_sgps_id' => $this->codigo_proyecto_sgps_id['value'],
            ]);
        }

        if (is_array($this->tipologia_ambiente_id)) {
            $this->merge([
                'tipologia_ambiente_id' => $this->tipologia_ambiente_id['value'],
            ]);
        }

        if (is_array($this->ambiente_formacion_complementaria)) {
            $this->merge([
                'ambiente_formacion_complementaria' => $this->ambiente_formacion_complementaria['value'] == '1' ? 1 : 0,
            ]);
        }

        if (is_array($this->ambiente_activo_procesos_idi)) {
            $this->merge([
                'ambiente_activo_procesos_idi' => $this->ambiente_activo_procesos_idi['value'] == '1' ? 1 : 0,
            ]);
        }

        if (is_array($this->ambiente_activo)) {
            $this->merge([
                'ambiente_activo' => $this->ambiente_activo['value'] == '1' ? 1 : 0,
            ]);
        }

        if (is_array($this->financiado_anteriormente)) {
            $this->merge([
                'financiado_anteriormente' => $this->financiado_anteriormente['value'] == '1' ? 1 : 0,
            ]);
        }

        if (is_array($this->alineado_mesas_sectoriales)) {
            $this->merge([
                'alineado_mesas_sectoriales' => $this->alineado_mesas_sectoriales['value'] == '1' ? 1 : 0,
            ]);
        }

        if (is_array($this->estado_general_maquinaria)) {
            $this->merge([
                'estado_general_maquinaria' => $this->estado_general_maquinaria['value'],
            ]);
        }

        if (is_array($this->semilleros_investigacion_id)) {
            if (isset($this->semilleros_investigacion_id['value']) && is_numeric($this->semilleros_investigacion_id['value'])) {
                $this->merge([
                    'semilleros_investigacion_id' => $this->semilleros_investigacion_id['value'],
                ]);
            } else {
                $semillerosInvestigacion = [];
                foreach ($this->semilleros_investigacion_id as $semilleroInvestigacion) {
                    if (is_array($semilleroInvestigacion)) {
                        array_push($semillerosInvestigacion, $semilleroInvestigacion['value']);
                    }
                }
                $this->merge(['semilleros_investigacion_id' => $semillerosInvestigacion]);
            }
        }

        if (is_array($this->codigos_proyectos_id)) {
            if (isset($this->codigos_proyectos_id['value']) && is_numeric($this->codigos_proyectos_id['value'])) {
                $this->merge([
                    'codigos_proyectos_id' => $this->codigos_proyectos_id['value'],
                ]);
            } else {
                $codigos_proyectos_id = [];
                foreach ($this->codigos_proyectos_id as $codigo) {
                    if (is_array($codigo)) {
                        array_push($codigos_proyectos_id, $codigo['value']);
                    }
                }
                $this->merge(['codigos_proyectos_id' => $codigos_proyectos_id]);
            }
        }

        if (is_array($this->cod_proyectos_beneficiados_id)) {
            if (isset($this->cod_proyectos_beneficiados_id['value']) && is_numeric($this->cod_proyectos_beneficiados_id['value'])) {
                $this->merge([
                    'cod_proyectos_beneficiados_id' => $this->cod_proyectos_beneficiados_id['value'],
                ]);
            } else {
                $cod_proyectos_beneficiados_id = [];
                foreach ($this->cod_proyectos_beneficiados_id as $codigo) {
                    if (is_array($codigo)) {
                        array_push($cod_proyectos_beneficiados_id, $codigo['value']);
                    }
                }
                $this->merge(['cod_proyectos_beneficiados_id' => $cod_proyectos_beneficiados_id]);
            }
        }

        if (is_array($this->programas_formacion_calificados)) {
            if (isset($this->programas_formacion_calificados['value']) && is_numeric($this->programas_formacion_calificados['value'])) {
                $this->merge([
                    'programas_formacion_calificados' => $this->programas_formacion_calificados['value'],
                ]);
            } else {
                $programas_formacion_calificados = [];
                foreach ($this->programas_formacion_calificados as $programaFormacionCalificado) {
                    if (is_array($programaFormacionCalificado)) {
                        array_push($programas_formacion_calificados, $programaFormacionCalificado['value']);
                    }
                }
                $this->merge(['programas_formacion_calificados' => $programas_formacion_calificados]);
            }
        }

        if (is_array($this->programas_formacion)) {
            if (isset($this->programas_formacion['value']) && is_numeric($this->programas_formacion['value'])) {
                $this->merge([
                    'programas_formacion' => $this->programas_formacion['value'],
                ]);
            } else {
                $programas_formacion = [];
                foreach ($this->programas_formacion as $programaFormacionNoCalificado) {
                    if (is_array($programaFormacionNoCalificado)) {
                        array_push($programas_formacion, $programaFormacionNoCalificado['value']);
                    }
                }
                $this->merge(['programas_formacion' => $programas_formacion]);
            }
        }
    }
}
