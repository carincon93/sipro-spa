<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectBudgetBatchRequest extends FormRequest
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
        if ($this->isMethod('PUT')) {
            return [
                'qty_items'                 => ['required', 'numeric', 'min:1'],
                'fact_sheet'                => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'first_price_quote'         => ['required', 'numeric', 'min:0'],
                'first_company_name'        => ['required', 'max:255'],
                'first_price_quote_file'    => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'second_price_quote'        => ['required', 'numeric', 'min:0'],
                'second_company_name'       => ['required', 'max:255'],
                'second_price_quote_file'   => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'third_price_quote'         => ['required_if:requires_third_market_research,1', 'nullable', 'min:0', 'numeric'],
                'third_company_name'        => ['required_if:requires_third_market_research,1', 'max:255'],
                'third_price_quote_file'    => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],
            ];
        } else {
            return [
                'qty_items'                 => ['required', 'numeric', 'min:1'],
                'fact_sheet'                => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'first_price_quote'         => ['required', 'numeric', 'min:0'],
                'first_company_name'        => ['required', 'max:255'],
                'first_price_quote_file'    => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'second_price_quote'        => ['required', 'numeric', 'min:0'],
                'second_company_name'       => ['required', 'max:255'],
                'second_price_quote_file'   => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf'],

                'third_price_quote'         => ['required_if:requires_third_market_research,1', 'nullable', 'numeric', 'min:0'],
                'third_company_name'        => ['required_if:requires_third_market_research,1', 'nullable', 'max:255'],
                'third_price_quote_file'    => ['required_if:requires_third_market_research,1', 'nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],
            ];
        }
    }
}
