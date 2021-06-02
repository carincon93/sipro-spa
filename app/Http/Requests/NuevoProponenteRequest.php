<?php

namespace App\Http\Requests;

use App\Rules\Email;
use Illuminate\Foundation\Http\FormRequest;

class NuevoProponenteRequest extends FormRequest
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
            'centro_formacion_id'           => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id'],
            'nombre'                        => ['required', 'max:255', 'string'],
            'email'                         => ['required', 'max:255', new Email, 'unique:users,email', 'email'],
            'tipo_documento'                => ['required', 'max:2'],
            'numero_documento'              => ['required', 'min:0', 'unique:users,numero_documento', 'max:9999999999999', 'integer'],
            'numero_celular'                => ['required', 'min:0', 'max:9999999999', 'integer'],
            'tipo_participacion'            => ['required', 'max:191'],
            'es_autor'                      => ['required', 'boolean'],
            'cantidad_horas'                => ['required', 'numeric', 'min:1'],
            'cantidad_meses'                => ['required', 'numeric', 'min:1', 'max:11.5'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->es_autor)) {
            $this->merge([
                'es_autor' => $this->es_autor['value'],
            ]);
        }

        if (is_array($this->tipo_documento)) {
            $this->merge([
                'tipo_documento' => $this->tipo_documento['value'],
            ]);
        }

        if (is_array($this->tipo_participacion)) {
            $this->merge([
                'tipo_participacion' => $this->tipo_participacion['value'],
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
