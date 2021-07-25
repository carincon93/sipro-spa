<?php

namespace App\Http\Requests\Evaluacion;

use Illuminate\Foundation\Http\FormRequest;

class IdiEvaluacionRequest extends FormRequest
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
            'titulo_puntaje'                => ['nullable', 'numeric', 'max:1'],
            'titulo_comentario'             => ['nullable', 'string'],
            'video_puntaje'                 => ['nullable', 'numeric', 'max:1'],
            'video_comentario'              => ['nullable', 'string'],
            'resumen_puntaje'               => ['nullable', 'numeric', 'max:2'],
            'resumen_comentario'            => ['nullable', 'string'],
            'problema_central_puntaje'      => ['nullable', 'integer', 'max:6'],
            'problema_central_comentario'   => ['nullable', 'string'],
            'ortografia_puntaje'            => ['nullable', 'numeric'],
            'ortografia_comentario'         => ['nullable', 'string'],
            'redaccion_puntaje'             => ['nullable', 'numeric'],
            'redaccion_comentario'          => ['nullable', 'string'],
            'normas_apa_puntaje'            => ['nullable', 'numeric'],
            'normas_apa_comentario'         => ['nullable', 'string'],

        ];
    }
}
