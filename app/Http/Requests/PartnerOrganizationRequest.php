<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerOrganizationRequest extends FormRequest
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
                'partner_organization_type'     => ['required'],
                'name'                          => ['required', 'max:255'],
                'legal_status'                  => ['required'],
                'company_type'                  => ['required'],
                'nit'                           => ['required', 'max:11'],
                'agreement_description'         => ['nullable'],
                'research_group'                => ['nullable', 'max:191'],
                'gruplac_code'                  => ['nullable', 'max:191'],
                'gruplac_link'                  => ['nullable', 'url', 'max:191'],
                'knowledge_transfer_activities' => ['required', 'max:10000'],
                'letter_of_intent'              => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],
                'intellectual_property'         => ['nullable', 'max:10000000', 'file', 'mimetypes:application/pdf'],
                'in_kind'                       => ['required', 'numeric'],
                'in_kind_description'           => ['required'],
                'funds'                         => ['required', 'numeric'],
                'funds_description'             => ['required'],
                'activity_id*'                  => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:activities,id'],

            ];
        } else {
            return [
                'partner_organization_type'     => ['required'],
                'name'                          => ['required', 'max:255'],
                'legal_status'                  => ['required'],
                'company_type'                  => ['required'],
                'nit'                           => ['required', 'max:11'],
                'agreement_description'         => ['nullable'],
                'research_group'                => ['nullable', 'max:191'],
                'gruplac_code'                  => ['nullable', 'max:191'],
                'gruplac_link'                  => ['nullable', 'url', 'max:191'],
                'knowledge_transfer_activities' => ['required', 'max:10000'],
                'letter_of_intent'              => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf'],
                'intellectual_property'         => ['required', 'max:10000000', 'file', 'mimetypes:application/pdf'],
                'in_kind'                       => ['required', 'numeric'],
                'in_kind_description'           => ['required'],
                'funds'                         => ['required', 'numeric'],
                'funds_description'             => ['required'],
                'activity_id*'                  => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:activities,id'],
            ];
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if( is_array($this->partner_organization_type) ) {
            $this->merge([
                'partner_organization_type' => $this->partner_organization_type['value'],
            ]);
        }

        if( is_array($this->legal_status) ) {
            $this->merge([
                'legal_status' => $this->legal_status['value'],
            ]);
        }

        if( is_array($this->company_type) ) {
            $this->merge([
                'company_type' => $this->company_type['value'],
            ]);
        }
    }
}
