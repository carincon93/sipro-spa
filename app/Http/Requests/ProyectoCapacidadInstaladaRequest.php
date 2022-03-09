<?php

namespace App\Http\Requests;

use App\Rules\MaxWords;
use Illuminate\Foundation\Http\FormRequest;

class ProyectoCapacidadInstaladaRequest extends FormRequest
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
            'semillero_investigacion_id'                => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:semilleros_investigacion,id'],
            'disciplina_subarea_conocimiento_id'        => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:disciplinas_subarea_conocimiento,id'],
            'red_conocimiento_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:redes_conocimiento,id'],
            'actividad_economica_id'                    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:actividades_economicas,id'],
            'subtipo_proyecto_capacidad_instalada_id'   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:subtipos_proyecto_capacidad_instalada,id'],
            'titulo'                                    => ['required', 'string', new MaxWords(20)],
            'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion'],
            'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio'],
            'programas_formacion*'                      => ['required', 'integer', 'exists:programas_formacion,id'],
            'programas_formacion_articulados*'          => ['required', 'integer', 'exists:programas_formacion_articulados,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->beneficia_a)) {
            $this->merge([
                'beneficia_a' => $this->beneficia_a['value'],
            ]);
        }

        if (is_array($this->programas_formacion)) {
            if (isset($this->programas_formacion['value']) && is_numeric($this->programas_formacion['value'])) {
                $this->merge([
                    'programas_formacion' => $this->programas_formacion['value'],
                ]);
            } else {
                $programasFormacion = [];
                foreach ($this->programas_formacion as $programaFormacion) {
                    if (is_array($programaFormacion)) {
                        array_push($programasFormacion, $programaFormacion['value']);
                    }
                }
                $this->merge(['programas_formacion' => $programasFormacion]);
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
    }
}
