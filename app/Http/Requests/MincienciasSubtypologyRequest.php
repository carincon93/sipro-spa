<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MincienciasSubtypologyRequest extends FormRequest
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
            'minciencias_typology_id'   => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:minciencias_typologies,id'],
            'name'                      => ['required', 'max:191'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->minciencias_typology_id) ) {
            $this->merge([
                'minciencias_typology_id' => $this->minciencias_typology_id['value'],
            ]);
        }
    }
}
