<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Call;

class ProjectStartDate implements Rule
{
    public $call;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($call)
    {
        $this->call = $call;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($value >= $this->call->project_fecha_incio);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $startDate = date('d-m-Y', strtotime($this->call->project_fecha_incio));
        return "La fecha de inicio no debe ser menor a {$startDate}.";
    }
}
