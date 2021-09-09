<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoPresupuesto extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyecto_presupuesto';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['presupuesto_aprobado'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proyecto_id',
        'convocatoria_presupuesto_id',
        'descripcion',
        'justificacion',
        'valor_total',
        'software',
        'codigo_uso_presupuestal'
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
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Relationship with ConvocatoriaPresupuesto
     *
     * @return object
     */
    public function convocatoriaPresupuesto()
    {
        return $this->belongsTo(ConvocatoriaPresupuesto::class);
    }

    /**
     * Relationship with Actividad
     *
     * @return object
     */
    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'actividad_proyecto_presupuesto', 'proyecto_presupuesto_id', 'actividad_id');
    }

    /**
     * Relationship with SoftwareInfo
     *
     * @return object
     */
    public function softwareInfo()
    {
        return $this->hasOne(SoftwareInfo::class);
    }

    /**
     * Relationship with ServicioEdicionInfo
     *
     * @return object
     */
    public function servicioEdicionInfo()
    {
        return $this->hasOne(ServicioEdicionInfo::class);
    }

    /**
     * Relationship with SoporteEstudioMercado
     *
     * @return object
     */
    public function soportesEstudioMercado()
    {
        return $this->hasMany(SoporteEstudioMercado::class);
    }

    /**
     * Relationship with Edt
     *
     * @return object
     */
    public function edt()
    {
        return $this->hasMany(Edt::class);
    }

    /**
     * Relationship with ProyectoPresupuestoEvaluacion
     *
     * @return object
     */
    public function proyectoPresupuestosEvaluaciones()
    {
        return $this->hasMany(\App\Models\Evaluacion\ProyectoPresupuestoEvaluacion::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoPresupuesto($query, array $filters)
    {
        $query->join('convocatoria_presupuesto', 'proyecto_presupuesto.convocatoria_presupuesto_id', 'convocatoria_presupuesto.id');
        $query->join('presupuesto_sennova', 'convocatoria_presupuesto.presupuesto_sennova_id', 'presupuesto_sennova.id');
        $query->join('segundo_grupo_presupuestal', 'presupuesto_sennova.segundo_grupo_presupuestal_id', 'segundo_grupo_presupuestal.id');
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->join('tercer_grupo_presupuestal', 'presupuesto_sennova.tercer_grupo_presupuestal_id', 'tercer_grupo_presupuestal.id');
            $query->join('usos_presupuestales', 'presupuesto_sennova.uso_presupuestal_id', 'usos_presupuestales.id');
            $query->whereRaw("unaccent(segundo_grupo_presupuestal.nombre) ilike unaccent('%" . $search . "%')");
            $query->orWhereRaw("unaccent(tercer_grupo_presupuestal.nombre) ilike unaccent('%" . $search . "%')");
            $query->orWhereRaw("unaccent(usos_presupuestales.descripcion) ilike unaccent('%" . $search . "%')");
        })->when($filters['presupuestos'] ?? null, function ($query, $presupuesto) {
            $query->whereRaw("unaccent(segundo_grupo_presupuestal.nombre) ilike unaccent('%" . $presupuesto . "%')");
        });
    }

    /**
     * getTotalPresupuestoActividadesSennova
     *
     * @return int
     */
    public function getTotalPresupuestoActividadesSennova()
    {
        $total = 0;
        $countProductos = 0;
        foreach ($this->actividades()->get() as $actividad) {
            $countProductos += $actividad->productos()->count();
        }

        if ($countProductos > 0) {
            $total = $this->valor_total / $countProductos;
        }

        return $total;
    }

    public function getPresupuestoAprobadoAttribute()
    {
        return $this->proyectoPresupuestosEvaluaciones()->count() == $this->proyectoPresupuestosEvaluaciones()->where('correcto', true)->count();
    }
}
