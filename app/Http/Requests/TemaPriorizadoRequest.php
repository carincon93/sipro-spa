<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemaPriorizadoRequest extends FormRequest
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
            'sector_productivo_id'  => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:sectores_productivos,id'],
            'mesa_tecnica_id'       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:mesas_tecnicas,id'],
            'nombre'                => ['required', 'max:191', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->sector_productivo_id)) {
            $this->merge([
                'sector_productivo_id' => $this->sector_productivo_id['value'],
            ]);
        }

        if (is_array($this->mesa_tecnica_id)) {
            $this->merge([
                'mesa_tecnica_id' => $this->mesa_tecnica_id['value'],
            ]);
        }

        $this->merge([
            'nombre' => mb_strtolower($this->nombre),
        ]);
    }
}
