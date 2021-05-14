<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProjectStartDate;
use App\Rules\ProjectEndDate;

class ActivityRequest extends FormRequest
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
                'specific_objective_id'         => ['nullable', 'min:0', 'max:2147483647', 'integer', 'exists:specific_objectives,id'],
                'output_id*'                    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:outputs,id'],
                'project_sennova_budget_id*'    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:project_sennova_budgets,id'],
                'description'                   => ['required', 'string'],
                'fecha_incio'                    => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new ProjectStartDate($this->route('call'))],
                'fecha_finalizacion'                      => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_incio', new ProjectEndDate($this->route('call'))],
            ];
        } else {
            return [
                'specific_objective_id'         => ['nullable', 'min:0', 'max:2147483647', 'integer', 'exists:specific_objectives,id'],
                'output_id*'                    => ['nullable', 'min:0', 'max:2147483647', 'integer', 'exists:outputs,id'],
                'project_sennova_budget_id*'    => ['required', 'min:0', 'max:2147483647', 'integer', 'exists:project_sennova_budgets,id'],
                'description'                   => ['required', 'string'],
                'fecha_incio'                    => ['required', 'date', 'date_format:Y-m-d', 'before:fecha_finalizacion', new ProjectStartDate($this->route('call'))],
                'fecha_finalizacion'                      => ['required', 'date', 'date_format:Y-m-d', 'after:fecha_incio', new ProjectEndDate($this->route('call'))],
            ];
        }
    }
}
