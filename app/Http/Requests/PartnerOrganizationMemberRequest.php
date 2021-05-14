<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerOrganizationMemberRequest extends FormRequest
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
            'name'              => ['required', 'max:255', 'string'],
            'email'             => ['required', 'max:255'],
            'document_type'     => ['required', 'max:2', 'string'],
            'document_number'   => ['required', 'min:0', 'max:9999999999999', 'integer'],
            'cellphone_number'  => ['required', 'min:0', 'max:9999999999', 'integer'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->document_type) ) {
            $this->merge([
                'document_type' => $this->document_type['value'],
            ]);
        }
    }
}
