<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstudioMercadoRequest extends FormRequest
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
                'primer_valor'     => ['required', 'numeric', 'min:0'],
                'primer_empresa'   => ['required', 'max:255'],
                'primer_archivo'   => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'segundo_valor'    => ['required', 'numeric', 'min:0'],
                'segunda_empresa'  => ['required', 'max:255'],
                'segundo_archivo'  => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'tercer_valor'    => ['nullable', 'numeric', 'min:0'],
                'tercer_empresa'  => ['nullable', 'max:255'],
                'tercer_archivo'  => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],
            ];
        } else {
            return [
                'primer_valor'    => ['required', 'numeric', 'min:0'],
                'primer_empresa'  => ['required', 'max:255'],
                'primer_archivo'  => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'segundo_valor'   => ['required', 'numeric', 'min:0'],
                'segunda_empresa' => ['required', 'max:255'],
                'segundo_archivo' => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'tercer_valor'    => ['nullable', 'numeric', 'min:0'],
                'tercer_empresa'  => ['nullable', 'max:255'],
                'tercer_archivo'  => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],
            ];
        }
    }
}
