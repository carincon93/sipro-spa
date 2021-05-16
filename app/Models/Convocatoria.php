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
    public $appends = ['year', 'fecha_inicio_formateado', 'fecha_finalizacion_formateado'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descripcion',
        'fecha_inicio',
        'fecha_finalizacion',
        'esta_activa',
        'min_fecha_inicio_proyectos',
        'max_fecha_finalizacion_proyectos',
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
     * @return void
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

    /**
     * Relationship with ConvocatoriaBudget
     *
     * @return void
     */
    public function rubrosPresupuestalesConvocatoria()
    {
        return $this->hasMany(ConvocatoriaBudget::class);
    }

    /**
     * Relationship with ConvocatoriaRolSennova
     *
     * @return void
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
            $query->where('fecha_inicio', 'ilike', '%'.$search.'%');
        });
    }

    /**
     * getYearAttribute
     *
     * @return void
     */
    public function getYearAttribute()
    {
        return date('Y', strtotime($this->fecha_finalizacion));
    }

    /**
     * getFechaInicioFormateadoAttribute
     *
     * @return void
     */
    public function getFechaInicioFormateadoAttribute()
    {
        return Carbon::parse($this->fecha_inicio, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY');
    }

    /**
     * getFechaFinalizacionFormateadoAttribute
     *
     * @return void
     */
    public function getFechaFinalizacionFormateadoAttribute()
    {
        return Carbon::parse($this->fecha_finalizacion, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY');
    }
}
