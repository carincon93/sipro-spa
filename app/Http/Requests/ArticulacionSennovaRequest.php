<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticulacionSennovaRequest extends FormRequest
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
            'lineas_investigacion*'             => ['required', 'integer', 'exists:lineas_investigacion,id'],
            'grupos_investigacion*'             => ['required', 'integer', 'exists:grupos_investigacion,id'],
            'semilleros_investigacion*'         => ['required', 'integer', 'exists:semilleros_investigacion,id'],
            'disciplinas_subarea_conocimiento*' => ['required', 'integer', 'exists:disciplinas_subarea_conocimiento,id'],
            'redes_conocimiento*'               => ['required', 'integer', 'exists:redes_conocimiento,id'],
            'tematicas_estrategicas*'           => ['required', 'integer', 'exists:tematicas_estrategicas,id'],
            'actividades_economicas*'           => ['required', 'integer', 'exists:actividades_economicas,id'],
            'articulacion_semillero'            => ['required', 'min:0', 'max:2', 'integer'],
            'semilleros_en_formalizacion'       => ['nullable', 'json'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->lineas_investigacion)) {
            if (isset($this->lineas_investigacion['value']) && is_numeric($this->lineas_investigacion['value'])) {
                $this->merge([
                    'lineas_investigacion' => $this->lineas_investigacion['value'],
                ]);
            } else {
                $lineasInvestigacion = [];
                foreach ($this->lineas_investigacion as $lineaInvestigacion) {
                    if (is_array($lineaInvestigacion)) {
                        array_push($lineasInvestigacion, $lineaInvestigacion['value']);
                    }
                }
                $this->merge(['lineas_investigacion' => $lineasInvestigacion]);
            }
        }

        if (is_array($this->grupos_investigacion)) {
            if (isset($this->grupos_investigacion['value']) && is_numeric($this->grupos_investigacion['value'])) {
                $this->merge([
                    'grupos_investigacion' => $this->grupos_investigacion['value'],
                ]);
            } else {
                $gruposInvestigacion = [];
                foreach ($this->grupos_investigacion as $grupoInvestigacion) {
                    if (is_array($grupoInvestigacion)) {
                        array_push($gruposInvestigacion, $grupoInvestigacion['value']);
                    }
                }
                $this->merge(['grupos_investigacion' => $gruposInvestigacion]);
            }
        }

        if (is_array($this->semilleros_investigacion)) {
            if (isset($this->semilleros_investigacion['value']) && is_numeric($this->semilleros_investigacion['value'])) {
                $this->merge([
                    'semilleros_investigacion' => $this->semilleros_investigacion['value'],
                ]);
            } else {
                $semillerosInvestigacion = [];
                foreach ($this->semilleros_investigacion as $semilleroInvestigacion) {
                    if (is_array($semilleroInvestigacion)) {
                        array_push($semillerosInvestigacion, $semilleroInvestigacion['value']);
                    }
                }
                $this->merge(['semilleros_investigacion' => $semillerosInvestigacion]);
            }
        }

        if (is_array($this->disciplinas_subarea_conocimiento)) {
            if (isset($this->disciplinas_subarea_conocimiento['value']) && is_numeric($this->disciplinas_subarea_conocimiento['value'])) {
                $this->merge([
                    'disciplinas_subarea_conocimiento' => $this->disciplinas_subarea_conocimiento['value'],
                ]);
            } else {
                $disciplinasSubareaConocimiento = [];
                foreach ($this->disciplinas_subarea_conocimiento as $lineaInvestigacion) {
                    if (is_array($lineaInvestigacion)) {
                        array_push($disciplinasSubareaConocimiento, $lineaInvestigacion['value']);
                    }
                }
                $this->merge(['disciplinas_subarea_conocimiento' => $disciplinasSubareaConocimiento]);
            }
        }

        if (is_array($this->tematicas_estrategicas)) {
            if (isset($this->tematicas_estrategicas['value']) && is_numeric($this->tematicas_estrategicas['value'])) {
                $this->merge([
                    'tematicas_estrategicas' => $this->tematicas_estrategicas['value'],
                ]);
            } else {
                $tematicasEstrategicas = [];
                foreach ($this->tematicas_estrategicas as $lineaInvestigacion) {
                    if (is_array($lineaInvestigacion)) {
                        array_push($tematicasEstrategicas, $lineaInvestigacion['value']);
                    }
                }
                $this->merge(['tematicas_estrategicas' => $tematicasEstrategicas]);
            }
        }

        if (is_array($this->actividades_economicas)) {
            if (isset($this->actividades_economicas['value']) && is_numeric($this->actividades_economicas['value'])) {
                $this->merge([
                    'actividades_economicas' => $this->actividades_economicas['value'],
                ]);
            } else {
                $actividadesEconomicas = [];
                foreach ($this->actividades_economicas as $lineaInvestigacion) {
                    if (is_array($lineaInvestigacion)) {
                        array_push($actividadesEconomicas, $lineaInvestigacion['value']);
                    }
                }
                $this->merge(['actividades_economicas' => $actividadesEconomicas]);
            }
        }

        if (is_array($this->redes_conocimiento)) {
            if (isset($this->redes_conocimiento['value']) && is_numeric($this->redes_conocimiento['value'])) {
                $this->merge([
                    'redes_conocimiento' => $this->redes_conocimiento['value'],
                ]);
            } else {
                $redesConocimiento = [];
                foreach ($this->redes_conocimiento as $lineaInvestigacion) {
                    if (is_array($lineaInvestigacion)) {
                        array_push($redesConocimiento, $lineaInvestigacion['value']);
                    }
                }
                $this->merge(['redes_conocimiento' => $redesConocimiento]);
            }
        }

        if (is_array($this->articulacion_semillero)) {
            $this->merge([
                'articulacion_semillero' => $this->articulacion_semillero['value'],
            ]);
        }
    }
}
