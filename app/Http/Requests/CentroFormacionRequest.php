<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CentroFormacionRequest extends FormRequest
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
            'regional_id'       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:regionales,id'],
            'subdirector_id'    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:users,id'],
            'nombre'            => ['required', 'max:191'],
            'codigo'            => ['required', 'min:0', 'max:2147483647', 'integer']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->regional_id)) {
            $this->merge([
                'regional_id' => $this->regional_id['value'],
            ]);
        }

        if (is_array($this->subdirector_id)) {
            $this->merge([
                'subdirector_id' => $this->subdirector_id['value'],
            ]);
        }

        $this->merge([
            'nombre' => mb_strtolower($this->nombre),
        ]);
    }
}
