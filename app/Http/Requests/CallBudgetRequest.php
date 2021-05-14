<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallBudgetRequest extends FormRequest
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
            'call_id'           => ['required', 'min:0', 'max:9999999999', 'integer', 'exists:calls,id'],
            'sennova_budget_id' => ['required', 'min:0', 'max:9999999999', 'integer', 'exists:sennova_budgets,id'],
        ];
    }
}
