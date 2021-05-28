<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineaInvestigacionRequest extends FormRequest
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
            'grupo_investigacion_id'    => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:grupos_investigacion,id'],
            'nombre'                    => ['required', 'max:191', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->grupo_investigacion_id)) {
            $this->merge([
                'grupo_investigacion_id' => $this->grupo_investigacion_id['value'],
            ]);
        }
    }
}
