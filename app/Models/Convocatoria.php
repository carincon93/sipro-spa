<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Convocatoria extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'convocatorias';

    /**
     * appends
     *
     * @var array
     */
    public $appends = ['year', 'fechas_idi', 'fechas_st', 'fechas_ta', 'fechas_tp', 'fechas_cultura'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion',
        'esta_activa',
        'min_fecha_inicio_proyectos_idi',
        'max_fecha_finalizacion_proyectos_idi',
        'min_fecha_inicio_proyectos_cultura',
        'max_fecha_finalizacion_proyectos_cultura',
        'min_fecha_inicio_proyectos_st',
        'max_fecha_finalizacion_proyectos_st',
        'min_fecha_inicio_proyectos_ta',
        'min_fecha_inicio_proyectos_tp',
        'max_fecha_finalizacion_proyectos_ta',
        'max_fecha_finalizacion_proyectos_tp',
        'fecha_inicio_convocatoria_idi',
        'fecha_finalizacion_convocatoria_idi',
        'fecha_inicio_convocatoria_cultura',
        'fecha_finalizacion_convocatoria_cultura',
        'fecha_inicio_convocatoria_st',
        'fecha_finalizacion_convocatoria_st',
        'fecha_inicio_convocatoria_ta',
        'fecha_inicio_convocatoria_tp',
        'fecha_finalizacion_convocatoria_ta',
        'fecha_finalizacion_convocatoria_tp',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    /**
     * Relationship with Proyecto
     *
     * @return object
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

    /**
     * Relationship with ConvocatoriaBudget
     *
     * @return object
     */
    public function rubrosPresupuestalesConvocatoria()
    {
        return $this->hasMany(ConvocatoriaBudget::class);
    }

    /**
     * Relationship with ConvocatoriaRolSennova
     *
     * @return object
     */
    public function convocatoriaRolesSennova()
    {
        return $this->hasMany(ConvocatoriaRolSennova::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterConvocatoria($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->whereRaw("unaccent(descripcion) ilike unaccent('%" . $search . "%')");
        });
    }

    /**
     * getYearAttribute
     *
     * @return void
     */
    public function getYearAttribute()
    {
        return date('Y', strtotime($this->max_fecha_finalizacion_proyectos_idi));
    }

    /**
     * getFechaInicioFormateadoAttribute
     *
     * @return void
     */
    public function getFechasIdiAttribute()
    {
        return "La convocatoria de proyectos I+D+i empezó el " . Carbon::parse($this->fecha_inicio_convocatoria_idi, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . " y finaliza el " . Carbon::parse($this->fecha_finalizacion_convocatoria_idi, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . ".";
    }

    /**
     * getFechasStAttribute
     *
     * @return void
     */
    public function getFechasStAttribute()
    {
        return "La convocatoria de proyectos de servicios tecnológicos empezó el " . Carbon::parse($this->fecha_inicio_convocatoria_st, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . " y finaliza el " . Carbon::parse($this->fecha_finalizacion_convocatoria_st, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . ".";
    }

    /**
     * getFechasStAttribute
     *
     * @return void
     */
    public function getFechasTaAttribute()
    {
        return "La convocatoria de proyectos de Tecnoacademia empezó el " . Carbon::parse($this->fecha_inicio_convocatoria_ta, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . " y finaliza el " . Carbon::parse($this->fecha_finalizacion_convocatoria_ta, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . ".";
    }

    /**
     * getFechasStAttribute
     *
     * @return void
     */
    public function getFechasTpAttribute()
    {
        return "La convocatoria de proyectos de Tecnoparque empezó el " . Carbon::parse($this->fecha_inicio_convocatoria_tp, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . " y finaliza el " . Carbon::parse($this->fecha_finalizacion_convocatoria_tp, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . ".";
    }

    /**
     * getFechasStAttribute
     *
     * @return void
     */
    public function getFechasCulturaAttribute()
    {
        return "La convocatoria de proyectos de la cultura de la innovación empezó el " . Carbon::parse($this->fecha_inicio_convocatoria_cultura, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . " y finaliza el " . Carbon::parse($this->fecha_finalizacion_convocatoria_cultura, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') . ".";
    }
}
