<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImpactRequest extends FormRequest
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
            'description'   => ['required', 'string', 'max:1200'],
            'type'          => ['required', 'digits_between:1,4'],
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
