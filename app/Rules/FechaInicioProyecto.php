<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FechaInicioProyecto implements Rule
{
    public $convocatoria;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($convocatoria)
    {
        $this->convocatoria = $convocatoria;
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
        return ($value >= $this->convocatoria->min_fecha_inicio_proyectos);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $minFechaIncioProyectos = date('d-m-Y', strtotime($this->convocatoria->min_fecha_inicio_proyectos));
        return "La fecha de inicio no debe ser menor a {$minFechaIncioProyectos}.";
    }
}
