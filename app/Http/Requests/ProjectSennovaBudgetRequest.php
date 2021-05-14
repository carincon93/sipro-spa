<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectSennovaBudgetRequest extends FormRequest
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
            'call_budget_id'    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:call_budgets,id'],
            'description'       => ['required'],
            'justification'     => ['required'],
            'value'             => ['nullable', 'min:0', 'numeric'],
            'qty_items'         => ['nullable', 'min:0', 'numeric'],
        ];
    }
}
