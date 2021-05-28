<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrupoInvestigacionRequest extends FormRequest
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
                'nombre'                => ['required', 'max:191', 'string'],
                'acronimo'              => ['required', 'max:191'],
                'email'                 => ['required', 'max:191', 'email', 'unique:grupos_investigacion,email,' . $this->route('grupo_investigacion')->id . ',id'],
                'enlace_gruplac'        => ['required', 'url', 'max:191'],
                'codigo_minciencias'    => ['required', 'max:10', 'string', 'unique:grupos_investigacion,codigo_minciencias,' . $this->route('grupo_investigacion')->id . ',id'],
                'categoria_minciencias' => ['required', 'max:16'],
            ];
        } else {
            return [
                'centro_formacion_id'   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
                'nombre'                => ['required', 'max:191', 'string'],
                'acronimo'              => ['required', 'max:191'],
                'email'                 => ['required', 'max:191', 'email', 'unique:grupos_investigacion,email'],
                'enlace_gruplac'        => ['required', 'url', 'max:191'],
                'codigo_minciencias'    => ['required', 'max:10', 'string', 'unique:grupos_investigacion,codigo_minciencias'],
                'categoria_minciencias' => ['required', 'max:16'],
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
        if (is_array($this->centro_formacion_id)) {
            $this->merge([
                'centro_formacion_id' => $this->centro_formacion_id['value'],
            ]);
        }

        if (is_array($this->categoria_minciencias)) {
            $this->merge([
                'categoria_minciencias' => $this->categoria_minciencias['value'],
            ]);
        }

        $this->merge([
            'nombre' => mb_strtolower($this->nombre),
        ]);

        $this->merge([
            'email' => mb_strtolower($this->email),
        ]);
    }
}
