<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineaProgramaticaRequest extends FormRequest
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
            'nombre'                => ['required', 'max:191'],
            'codigo'                => ['required', 'min:0', 'max:2147483647', 'integer'],
            'descripcion'           => ['required'],
            'categoria_proyecto'    => ['required', 'max:191'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->categoria_proyecto)) {
            $this->merge([
                'categoria_proyecto' => $this->categoria_proyecto['value'],
            ]);
        }

        $this->merge([
            'nombre' => mb_strtolower($this->nombre),
        ]);

        if (is_array($this->lideres)) {
            if (isset($this->lideres['value']) && is_numeric($this->lideres['value'])) {
                $this->merge([
                    'lideres' => $this->lideres['value'],
                ]);
            } else {
                $lideres = [];
                foreach ($this->lideres as $lider) {
                    if (is_array($lider)) {
                        array_push($lideres, $lider['value']);
                    }
                }
                $this->merge(['lideres' => $lideres]);
            }
        }
    }
}
