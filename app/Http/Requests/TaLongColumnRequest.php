<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxWords;
use App\Rules\FechaInicioProyecto;
use App\Rules\FechaFinalizacionProyecto;

class TaLongColumnRequest extends FormRequest
{

    private $columnsRules = [
        'resumen'                                   => ['required', 'max:40000', 'string'],
        'resumen_regional'                          => ['required', 'max:40000', 'string'],
        'antecedentes'                              => ['required', 'max:40000', 'string'],
        'antecedentes_tecnoacademia'                => ['required', 'max:40000', 'string'],
        'justificacion_problema'                    => ['required', 'max:40000', 'string'],
        'marco_conceptual'                          => ['required', 'string'],
        'bibliografia'                              => ['required', 'string'],
        'impacto_municipios'                        => ['required', 'string'],
        'articulacion_centro_formacion'             => ['required', 'string'],
        'pertinencia_territorio'                    => ['required', 'max:40000', 'string'],
        'retos_oportunidades'                       => ['required', 'max:40000', 'string'],
        'proyectos_macro'                           => ['nullable', 'string'],
        'lineas_medulares_centro'                   => ['nullable', 'string'],
        'lineas_tecnologicas_centro'                => ['nullable', 'string'],
    ];
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
        if ($this->columnsRules[$this->route('column')]) {
            return [
                $this->route('column') => $this->columnsRules[$this->route('column')]
            ];
        }else{
            return [
                'column' => 'required'
            ];
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
    }
}
