<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectTypeRequest extends FormRequest
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
            'programmatic_line_id'  => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:programmatic_lines,id'],
            'name'                  => ['required', 'max:191'],
            'maximum_value'         => ['required', 'min:0', 'max:9999999999', 'integer'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->programmatic_line_id) ) {
            $this->merge([
                'programmatic_line_id' => $this->programmatic_line_id['value'],
            ]);
        }
    }
}
