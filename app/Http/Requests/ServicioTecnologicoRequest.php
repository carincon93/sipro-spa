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
                'mesa_tecnica_sector_productivo_id'         => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:mesa_tecnica_sector_productivo,id'],
                'titulo'                                    => ['required', new MaxWords],
                'fecha_inicio'                              => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new FechaInicioProyecto($this->route('convocatoria'))],
                'fecha_finalizacion'                        => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new FechaFinalizacionProyecto($this->route('convocatoria'))],
                'max_meses_ejecucion'                       => ['required', 'numeric', 'min:1', 'max:12'],
                'resumen'                                   => ['required', 'max:1000', 'string'],
                'antecedentes'                              => ['required', 'max:10000', 'string'],
                'bibliografia'                              => ['required', 'string'],
                'especificaciones_area'                     => ['required', 'string'],
            ];
        } else {
            return [
                'centro_formacion_id'                       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'tipo_proyecto_id'                          => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:tipos_proyecto,id'],
                'mesa_tecnica_sector_productivo_id'         => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:mesa_tecnica_sector_productivo,id'],
                'titulo'                                    => ['required', new MaxWords],
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
