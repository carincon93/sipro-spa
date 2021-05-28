<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SemilleroInvestigacionRequest extends FormRequest
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
            'linea_investigacion_id'  => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:lineas_investigacion,id'],
            'nombre'                  => ['required', 'max:191'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->linea_investigacion_id)) {
            $this->merge([
                'linea_investigacion_id' => $this->linea_investigacion_id['value'],
            ]);
        }

        $this->merge([
            'nombre' => mb_strtolower($this->nombre),
        ]);
    }
}
