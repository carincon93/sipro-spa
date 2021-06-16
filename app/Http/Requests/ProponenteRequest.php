<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProponenteRequest extends FormRequest
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
            'user_id'        => ['required', 'integer', 'exists:users,id'],
            'rol_sennova_id' => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:roles_sennova,id'],
            'cantidad_horas' => ['required', 'numeric', 'min:1', 'max:168'],
            'cantidad_meses' => ['required', 'numeric', 'min:1', 'max:11.5'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->rol_sennova_id)) {
            $this->merge([
                'rol_sennova_id' => $this->rol_sennova_id['value'],
            ]);
        }
    }
}
