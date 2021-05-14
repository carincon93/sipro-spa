<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiskAnalysisRequest extends FormRequest
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
            'level'                 => ['required', 'max:191'],
            'type'                  => ['required', 'max:191'],
            'description'           => ['required'],
            'impact'                => ['required', 'max:191'],
            'probability'           => ['required', 'max:191'],
            'effects'               => ['required'],
            'mitigation_measures'   => ['required'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->level) ) {
            $this->merge([
                'level' => $this->level['value'],
            ]);
        }

        if( is_array($this->type) ) {
            $this->merge([
                'type' => $this->type['value'],
            ]);
        }

        if( is_array($this->impact) ) {
            $this->merge([
                'impact' => $this->impact['value'],
            ]);
        }

        if( is_array($this->probability) ) {
            $this->merge([
                'probability' => $this->probability['value'],
            ]);
        }
    }
}
