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
    public function __construct($convocatoria, $tipoProyecto, $proyecto)
    {
        $this->convocatoria = $convocatoria;
        $this->tipoProyecto = $tipoProyecto;
        $this->proyecto     = $proyecto;
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
        if ($this->proyecto) {
            if ($this->proyecto->idi()->exists() || $this->tipoProyecto == 'st') {
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_idi;
            } elseif ($this->proyecto->taTp()->exists() || $this->tipoProyecto == 'tatp') {
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_tatp;
            } elseif ($this->proyecto->servicioTecnologico()->exists() || $this->tipoProyecto == 'idi') {
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_st;
            } elseif ($this->proyecto->culturaInnovacion()->exists() || $this->tipoProyecto == 'cultura') {
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_cultura;
            }
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
        if ($this->proyecto) {
            if ($this->proyecto->idi()->exists() || $this->tipoProyecto == 'st') {
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_idi;
            } elseif ($this->proyecto->taTp()->exists() || $this->tipoProyecto == 'tatp') {
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_tatp;
            } elseif ($this->proyecto->servicioTecnologico()->exists() || $this->tipoProyecto == 'idi') {
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_st;
            } elseif ($this->proyecto->culturaInnovacion()->exists() || $this->tipoProyecto == 'cultura') {
                $minFechaFinalizacionProyectos = $this->convocatoria->min_fecha_finalizacion_proyectos_cultura;
            }
        }

        $minFechaFinalizacionProyectos = date('d-m-Y', strtotime($minFechaFinalizacionProyectos));
        return "La fecha de inicio no debe ser menor a {$minFechaFinalizacionProyectos}.";
    }
}
