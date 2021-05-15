<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultadoRequest extends FormRequest
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
            'descripcion'           => ['required', 'string'],
            'tipo'                  => ['required', 'digits_between:1,4'],
            'trl'                   => ['required', 'digits_between:1,9'],
            'indicador'             => ['required', 'string'],
            'medios_verificacion'   => ['required', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->tipo) ) {
            $this->merge([
                'tipo' => $this->tipo['value'],
            ]);
        }
    }
}
