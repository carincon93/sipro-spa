<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectResultRequest extends FormRequest
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
            'description'           => ['required', 'string'],
            'type'                  => ['required', 'digits_between:1,4'],
            'trl'                   => ['required', 'digits_between:1,9'],
            'indicator'             => ['required', 'string'],
            'means_of_verification' => ['required', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->type) ) {
            $this->merge([
                'type' => $this->type['value'],
            ]);
        }
    }
}
