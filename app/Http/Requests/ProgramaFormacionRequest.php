<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramaFormacionRequest extends FormRequest
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
                'centro_formacion_id'   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'nombre'                => ['required', 'max:191'],
                'codigo'                => ['required', 'min:0', 'max:2147483647', 'integer', 'unique:programas_formacion,codigo,' . $this->route('programa_formacion')->id . ',id'],
                'modalidad'             => ['required', 'max:191'],
            ];
        } else {
            return [
                'centro_formacion_id'   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'nombre'                => ['required', 'max:191'],
                'codigo'                => ['required', 'min:0', 'max:2147483647', 'integer', 'unique:programas_formacion,codigo'],
                'modalidad'             => ['required', 'max:191'],
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
        if (is_array($this->modalidad)) {
            $this->merge([
                'modalidad' => $this->modalidad['value'],
            ]);
        }

        if (is_array($this->centro_formacion_id)) {
            $this->merge([
                'centro_formacion_id' => $this->centro_formacion_id['value'],
            ]);
        }

        $this->merge([
            'nombre' => mb_strtolower($this->nombre),
        ]);
    }
}
