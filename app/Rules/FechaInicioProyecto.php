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
    public function __construct($convocatoria, $tipoProyecto)
    {
        $this->convocatoria = $convocatoria;
        $this->tipoProyecto = $tipoProyecto;
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
        switch ($this->tipoProyecto) {
            case 'st':
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_st;
                break;
            case 'tatp':
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_tatp;
                break;
            case 'idi':
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_idi;
                break;
            case 'cultura':
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_cultura;
                break;
            default:
                break;
        }

        return ($value >= $minFechaFinalizacionProyectos);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        switch ($this->tipoProyecto) {
            case 'st':
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_st;
                break;
            case 'tatp':
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_tatp;
                break;
            case 'idi':
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_idi;
                break;
            case 'cultura':
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_cultura;
                break;
            default:
                break;
        }

        $minFechaFinalizacionProyectos = date('d-m-Y', strtotime($minFechaFinalizacionProyectos));
        return "La fecha de inicio no debe ser menor a {$minFechaFinalizacionProyectos}.";
    }
}
