<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SennovaBudgetRequest extends FormRequest
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
            'first_budget_info_id'              => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:first_budget_info,id'],
            'second_budget_info_id'             => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:second_budget_info,id'],
            'third_budget_info_id'              => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:third_budget_info,id'],
            'budget_usage_id'                   => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:budget_usages,id'],
            'programmatic_line_id'              => ['required', 'min:0', 'max:2147483647999', 'integer', 'exists:programmatic_lines,id'],
            'requires_market_research_batch'    => ['required', 'boolean'],
            'requires_market_research'          => ['required', 'boolean'],
            'can_be_added'                      => ['required', 'boolean'],
            'message'                           => ['nullable', 'string'],
            'max_value'                         => ['nullable', 'numeric'],
        ];
    }
}
