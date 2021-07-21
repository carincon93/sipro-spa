<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EdtRequest extends FormRequest
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
            'proyecto_presupuesto_id'           => ['required', 'integer', 'exists:proyecto_presupuesto,id'],
            'tipo_evento'                       => ['required', 'max:2'],
            'descripcion_evento'                => ['required', 'max:40000', 'string'],
            'descripcion_participacion_entidad' => ['required', 'max:40000', 'string'],
            'publico_objetivo'                  => ['required', 'string', 'max:255'],
            'numero_asistentes'                 => ['required', 'integer', 'max:2147483647'],
            'estrategia_comunicacion'           => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->tipo_evento)) {
            $this->merge([
                'tipo_evento' => $this->tipo_evento['value'],
            ]);
        }

        if (is_array($this->proyecto_presupuesto_id)) {
            $this->merge([
                'proyecto_presupuesto_id' => $this->proyecto_presupuesto_id['value'],
            ]);
        }
    }
}
