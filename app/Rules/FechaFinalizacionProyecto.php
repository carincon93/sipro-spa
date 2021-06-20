<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FechaFinalizacionProyecto implements Rule
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
                $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_st;
                break;
            case 'tatp':
                $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_tatp;
                break;
            case 'idi':
                $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_idi;
                break;
            case 'cultura':
                $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_cultura;
                break;
            default:
                break;
        }

        return ($value <= $maxFechaFinalizacionProyectos);
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
                $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_st;
                break;
            case 'tatp':
                $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_tatp;
                break;
            case 'idi':
                $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_idi;
                break;
            case 'cultura':
                $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_cultura;
                break;
            default:
                break;
        }

        $maxFechaFinalizacionProyectos = date('d-m-Y', strtotime($maxFechaFinalizacionProyectos));

        return "La fecha de cierre no debe sobrepasar {$maxFechaFinalizacionProyectos}.";
    }
}
