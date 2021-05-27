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
            'user_id'           => ['required', 'integer', 'exists:users,id'],
            'es_autor'          => ['required', 'boolean'],
            'cantidad_horas'    => ['required', 'numeric', 'min:1'],
            'cantidad_meses'    => ['required', 'numeric', 'min:1', 'max:11.5'],
        ];
    }
}
