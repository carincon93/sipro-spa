<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventarioEquipoRequest extends FormRequest
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
            'nombre'                    => ['required', 'string', 'max:255'],
            'marca'                     => ['required', 'string', 'max:255'],
            'serial'                    => ['required', 'string', 'max:255'],
            'codigo_interno'            => ['required', 'string', 'max:255'],
            'fecha_adquisicion'         => ['required', 'date', 'date_format:Y-m-d'],
            'estado'                    => ['required', 'integer'],
            'uso_st'                    => ['required', 'integer'],
            'uso_otra_dependencia'      => ['required', 'integer'],
            'dependencia'               => ['nullable', 'string', 'max:255'],
            'descripcion'               => ['required', 'string', 'max:10000'],
            'mantenimiento_prox_year'   => ['required', 'integer'],
            'calibracion_prox_year'     => ['required', 'integer'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->estado)) {
            $this->merge([
                'estado' => $this->estado['value'],
            ]);
        }

        if (is_array($this->uso_st)) {
            $this->merge([
                'uso_st' => $this->uso_st['value'],
            ]);
        }

        if (is_array($this->uso_otra_dependencia)) {
            $this->merge([
                'uso_otra_dependencia' => $this->uso_otra_dependencia['value'],
            ]);
        }

        if (is_array($this->mantenimiento_prox_year)) {
            $this->merge([
                'mantenimiento_prox_year' => $this->mantenimiento_prox_year['value'],
            ]);
        }

        if (is_array($this->calibracion_prox_year)) {
            $this->merge([
                'calibracion_prox_year' => $this->calibracion_prox_year['value'],
            ]);
        }
    }
}
