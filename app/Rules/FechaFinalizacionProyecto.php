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

        if ($this->proyecto && $this->proyecto->idi()->exists() || $this->tipoProyecto == 'idi') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_idi;
        } elseif ($this->proyecto && $this->proyecto->ta()->exists() || $this->tipoProyecto == 'ta') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_ta;
        } elseif ($this->proyecto && $this->proyecto->tp()->exists() || $this->tipoProyecto == 'tp') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_tp;
        } elseif ($this->proyecto && $this->proyecto->servicioTecnologico()->exists() || $this->tipoProyecto == 'st') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_st;
        } elseif ($this->proyecto && $this->proyecto->culturaInnovacion()->exists() || $this->tipoProyecto == 'cultura') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_cultura;
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
        if ($this->proyecto && $this->proyecto->idi()->exists() || $this->tipoProyecto == 'idi') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_idi;
        } elseif ($this->proyecto && $this->proyecto->ta()->exists() || $this->tipoProyecto == 'ta') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_ta;
        } elseif ($this->proyecto && $this->proyecto->tp()->exists() || $this->tipoProyecto == 'tp') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_ta;
        } elseif ($this->proyecto && $this->proyecto->servicioTecnologico()->exists() || $this->tipoProyecto == 'st') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_st;
        } elseif ($this->proyecto && $this->proyecto->culturaInnovacion()->exists() || $this->tipoProyecto == 'cultura') {
            $maxFechaFinalizacionProyectos = $this->convocatoria->max_fecha_finalizacion_proyectos_cultura;
        }

        $maxFechaFinalizacionProyectos = date('d-m-Y', strtotime($maxFechaFinalizacionProyectos));

        return "La fecha de cierre no debe sobrepasar {$maxFechaFinalizacionProyectos}.";
    }
}
