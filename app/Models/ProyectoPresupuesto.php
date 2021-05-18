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
    protected $appends = ['promedio'];

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
        'valor',
        'numero_items'
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
     * Relationship with ProyectoLoteEstudioMercado
     *
     * @return object
     */
    public function proyectoLoteEstudioMercado()
    {
        return $this->hasMany(ProyectoLoteEstudioMercado::class);
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
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoPresupuesto($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query
                ->select('proyecto_presupuesto.*')
                ->join('convocatoria_presupuesto', 'proyecto_presupuesto.convocatoria_presupuesto_id', 'convocatoria_presupuesto.id')
                ->join('presupuesto_sennova', 'convocatoria_presupuesto.presupuesto_sennova', 'presupuesto_sennova.id')
                ->join('segundo_grupo_presupuestal', 'presupuesto_sennova.segundo_grupo_presupuestal_id', 'segundo_grupo_presupuestal.id')
                ->where('segundo_grupo_presupuestal.nombre', 'ilike', '%' . $search . '%');
        });
    }

    /**
     * getDescripcionAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getDescripcionAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * getPromedioAttribute
     *
     * @return void
     */
    public function getPromedioAttribute()
    {
        $total = 0;

        if ($this->proyectoLoteEstudioMercado()->exists()) {
            if ($this->proyectoLoteEstudioMercado->count() > 0) {
                foreach ($this->proyectoLoteEstudioMercado as $estudioMercado) {
                    $total += $estudioMercado->getPromedioAttribute();
                }
            } else {
                $total += $this->proyectoLoteEstudioMercado->getPromedioAttribute();
            }
        } elseif (!$this->proyectoLoteEstudioMercado()->exists() && !$this->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado) {
            $this->valor > 0 ? $total = ($this->numero_items * $this->valor) : $total = 0;
        }

        return $total;
    }
}
