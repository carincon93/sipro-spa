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
                'centro_formacion_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'tipo_proyecto_id'                          => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tipos_proyecto,id'],
                'disciplina_subarea_conocimiento_id'        => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:disciplinas_subarea_conocimiento,id'],
                'tematica_estrategica_id'                   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tematicas_estrategicas,id'],
                'red_conocimiento_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:redes_conocimiento,id'],
                'actividad_economica_id'                    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:actividades_economicas,id'],
                'linea_programatica_id'                     => ['nullable', 'min:0', 'max:2147483647', 'integer', 'exists:lineas_programaticas,id'],
                'titulo'                                    => ['required', new MaxWords],
                'titulo_proyecto_articulado'                => ['nullable', 'string'],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
                'video'                                     => ['required', 'max:191', 'url'],
                'resumen'                                   => ['required', 'max:2400', 'string'],
                'antecedentes'                              => ['required', 'max:40000', 'string'],
                'metodologia'                               => ['required', 'string'],
                'propuesta_sostenibilidad'                  => ['required', 'string'],
                'justificacion_industria_4'                 => ['nullable', 'string'],
                'justificacion_economia_naranja'            => ['nullable', 'string'],
                'justificacion_politica_discapacidad'       => ['nullable', 'string'],
                'bibliografia'                              => ['required', 'string'],
                'numero_aprendices'                         => ['required', 'min:0', 'max:9999', 'integer'],
                'impacto_centro_formacion'                  => ['required', 'string'],
                'pregunta_formulacion_problema'             => ['required', 'string'],
                'infraestructura_adecuada'                  => ['required', 'boolean'],
                'especificaciones_area'                     => ['required', 'string'],
            ];
        } else {
            return [
                'centro_formacion_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'tipo_proyecto_id'                          => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tipos_proyecto,id'],
                'disciplina_subarea_conocimiento_id'        => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:disciplinas_subarea_conocimiento,id'],
                'tematica_estrategica_id'                   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tematicas_estrategicas,id'],
                'red_conocimiento_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:redes_conocimiento,id'],
                'actividad_economica_id'                    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:actividades_economicas,id'],
                'linea_programatica_id'                     => ['nullable', 'min:0', 'max:2147483647', 'integer', 'exists:lineas_programaticas,id'],
                'tema_priorizado_id'                        => ['nullable', 'min:0', 'max:2147483647', 'integer', 'exists:temas_priorizados,id'],
                'titulo'                                    => ['required', new MaxWords],
                'titulo_proyecto_articulado'                => ['nullable', 'string'],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
                'rol_sennova_id'                            => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:roles_sennova,id'],
                'cantidad_horas'                            => ['required', 'numeric', 'min:1'],
                'cantidad_meses'                            => ['required', 'numeric', 'min:1', 'max:11.5'],
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

        if (is_array($this->centro_formacion_id)) {
            $this->merge([
                'centro_formacion_id' => $this->centro_formacion_id['value'],
            ]);
        }

        if (is_array($this->rol_sennova_id)) {
            $this->merge([
                'rol_sennova_id' => $this->rol_sennova_id['value'],
            ]);
        }
    }
}
