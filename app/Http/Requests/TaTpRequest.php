<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
                'linea_programatica_id'                     => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:lineas_programaticas,id'],
                'tecnoacademia_linea_tecnologica_id'        => ['required_if:codigo_linea_programatica,70', 'exclude_if:codigo_linea_programatica,69', 'min:0', 'max:2147483647', 'exists:tecnoacademia_linea_tecnologica,id'],
                'nodo_tecnoparque_id'                       => ['required_if:codigo_linea_programatica,69', 'exclude_if:codigo_linea_programatica,70', 'min:0', 'max:2147483647', 'exists:nodos_tecnoparque,id'],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
                'max_meses_ejecucion'                       => ['required', 'numeric', 'min:1', 'max:12'],
                'resumen'                                   => ['required', 'max:40000', 'string'],
                'antecedentes'                              => ['required', 'max:40000', 'string'],
                'marco_conceptual'                          => ['required', 'string'],
                'bibliografia'                              => ['required', 'string'],
                'municipios*'                               => ['required', 'integer', 'exists:municipios,id'],
                'impacto_municipios'                        => ['required', 'string'],
                'impacto_centro_formacion'                  => ['required', 'string'],
                'nombre_instituciones'                      => ['required_if:codigo_linea_programatica,70', 'exclude_if:codigo_linea_programatica,69', 'json'],
                'diseno_curricular'                         => ['required_if:codigo_linea_programatica,70', 'exclude_if:codigo_linea_programatica,69']
            ];
        } else {
            return [
                'centro_formacion_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'linea_programatica_id'                     => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:lineas_programaticas,id'],
                'tecnoacademia_linea_tecnologica_id'        => ['required_if:codigo_linea_programatica,70', 'exclude_if:codigo_linea_programatica,69', 'min:0', 'max:2147483647', 'exists:tecnoacademia_linea_tecnologica,id'],
                'nodo_tecnoparque_id'                       => ['required_if:codigo_linea_programatica,69', 'exclude_if:codigo_linea_programatica,70', 'min:0', 'max:2147483647', 'exists:nodos_tecnoparque,id'],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
                'max_meses_ejecucion'                       => ['required', 'numeric', 'min:1', 'max:12'],
                'rol_sennova_id'                            => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:roles_sennova,id'],
                'cantidad_horas'                            => ['required', 'numeric', 'min:1', 'max:168'],
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

        if (is_array($this->tecnoacademia_linea_tecnologica_id)) {
            $this->merge([
                'tecnoacademia_linea_tecnologica_id' => $this->tecnoacademia_linea_tecnologica_id['value'],
            ]);
        }

        if (is_array($this->nodo_tecnoparque_id)) {
            $this->merge([
                'nodo_tecnoparque_id' => $this->nodo_tecnoparque_id['value'],
            ]);
        }

        if (is_array($this->rol_sennova_id)) {
            $this->merge([
                'rol_sennova_id' => $this->rol_sennova_id['value'],
            ]);
        }

        $this->merge([
            'diseno_curricular' => 0
        ]);
    }
}
