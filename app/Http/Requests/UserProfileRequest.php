<?php

namespace App\Http\Requests;

use App\Rules\Email;
use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
            'nombre'                => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'max:255', new Email, 'email', 'unique:users,email,' . auth()->user()->id . ',id'],
            'tipo_documento'        => ['required', 'max:2'],
            'tipo_vinculacion'      => ['required', 'max:191'],
            'numero_documento'      => ['required', 'min:55555', 'max:9999999999999', 'integer', 'unique:users,numero_documento,' . auth()->user()->id . ',id'],
            'numero_celular'        => ['required', 'min:0', 'max:9999999999', 'integer'],
            'role_id*'              => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:roles,id']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->tipo_documento)) {
            $this->merge([
                'tipo_documento' => $this->tipo_documento['value'],
            ]);
        }

        if (is_array($this->tipo_vinculacion)) {
            $this->merge([
                'tipo_vinculacion' => $this->tipo_vinculacion['value'],
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
