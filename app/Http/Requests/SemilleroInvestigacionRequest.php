<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SemilleroInvestigacionRequest extends FormRequest
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
            'linea_investigacion_id'  => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:lineas_investigacion,id'],
            'nombre'                  => ['required', 'max:191'],
            'fecha_creacion_semillero' => ['required', 'date', 'date_format:Y-m-d'],
            'nombre_lider_semillero'  => ['required', 'max:191'],
            'email_contacto'          => ['required', 'max:191', 'email'],
            'reconocimientos_semillero_investigacion'  => ['required'],
            'vision'                  => ['required'],
            'mision'                  => ['required'],
            'objetivos_especificos'   => ['required'],
            'objetivo_general'        => ['required'],
            'link_semillero'          => ['nullable', 'url'],
            'formato_gic_f020'        => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'formato_gic_f032'        => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'formato_aval_semillero'  => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document'],

        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->linea_investigacion_id)) {
            $this->merge([
                'linea_investigacion_id' => $this->linea_investigacion_id['value'],
            ]);
        }
    }
}
