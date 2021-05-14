<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallSennovaRoleRequest extends FormRequest
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
            'sennova_role_id'       => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:sennova_roles,id'],
            'salary'                => ['required', 'min:0', 'max:2147483647'],
            'qty_months'            => ['nullable', 'min:0', 'max:12', 'integer'],
            'qty_roles'             => ['nullable', 'min:0', 'max:999', 'integer'],
            'academic_degree'       => ['required', 'integer']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->sennova_role_id) ) {
            $this->merge([
                'sennova_role_id' => $this->sennova_role_id['value'],
            ]);
        }

        if( is_array($this->academic_degree) ) {
            $this->merge([
                'academic_degree' => $this->academic_degree['value'],
            ]);
        }
    }

}
