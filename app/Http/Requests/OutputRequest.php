<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProjectStartDate;
use App\Rules\ProjectEndDate;

class OutputRequest extends FormRequest
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
            'project_result_id' => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:project_results,id'],
            'name'              => ['required', 'max:191'],
            'fecha_inicio'        => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new ProjectStartDate($this->route('call'))],
            'fecha_finalizacion'          => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_inicio', new ProjectEndDate($this->route('call'))],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->project_result_id) ) {
            $this->merge([
                'project_result_id' => $this->project_result_id['value'],
            ]);
        }
    }
}
