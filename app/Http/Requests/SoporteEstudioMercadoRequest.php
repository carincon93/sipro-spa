<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SoporteEstudioMercadoRequest extends FormRequest
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
        if ($this->isMethod('PUT')) {
            return [
                'empresa' => ['required', 'max:191', 'string'],
                'soporte' => ['nullable', 'string', 'url'],
            ];
        } else {
            return [
                'empresa' => ['required', 'max:191', 'string'],
                'soporte' => ['required', 'string', 'url'],
            ];
        }
    }
}
