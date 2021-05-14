<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CallSennovaRole;

class ProjectSennovaRoleRequest extends FormRequest
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
            'call_sennova_role_id'  => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:call_sennova_roles,id'],
            'description'           => ['required'],
            'qty_months'            => ['required', 'min:0', 'max:12'],
            'qty_roles'             => ['required', 'min:0', 'max:999', 'integer']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $callSennovaRole = CallSennovaRole::find($this->call_sennova_role_id);
        if ($callSennovaRole->qty_months > 0) {
            $this->merge([
                'qty_months' => $callSennovaRole->qty_months ?? $this->qty_months,
            ]);
        }

        if ($callSennovaRole->qty_roles > 0) {
            $this->merge([
                'qty_roles' => $callSennovaRole->qty_roles ?? $this->qty_roles,
            ]);
        }
    }
}
