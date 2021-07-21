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
            'lineas_investigacion*'         => ['required', 'integer', 'exists:lineas_investigacion,id'],
            'grupos_investigacion*'         => ['required', 'integer', 'exists:grupos_investigacion,id'],
            'semilleros_investigacion*'     => ['required', 'integer', 'exists:semilleros_investigacion,id'],
            'articulacion_semillero'        => ['required', 'min:0', 'max:2', 'integer'],
            'semilleros_en_formalizacion'   => ['nullable', 'json'],
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

        if (is_array($this->articulacion_semillero)) {
            $this->merge([
                'articulacion_semillero' => $this->articulacion_semillero['value'],
            ]);
        }
    }
}
