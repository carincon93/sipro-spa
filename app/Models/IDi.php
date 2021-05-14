<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IDi extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'idi';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['fechaEjecucion'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'linea_investigacion_id',
        'disciplina_subarea_conocimiento_id',
        'tematica_estrategica_id',
        'red_conocimiento_id',
        'actividad_economica_id',
        'title',
        'fecha_incio',
        'fecha_finalizacion',
        'video',
        'justificacion_industria_4',
        'justificacion_economica_naranja',
        'justificacion_politica_discapacidad',
        'abstract',
        'antecedentes',
        'marco_conceptual',
        'metodologia',
        'propuesta_sostenibilidad',
        'muestreo',
        'actividades_muestreo',
        'muestreo_objective',
        'bibliografia',
        'numero_aprendices',
        'impacto_ciudades',
        'impacto_centro_formacion',
        'objetivo_general',
        'planteamiento_problema',
        'justificacion_problema',
        'relacionado_plan_tecnologico',
        'relacionado_agendas_competitividad',
        'relacionado_mesas_sectoriales',
        'relacionado_tecnoacademia'
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
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id');
    }

    /**
     * Relationship with LineaInvestigacion
     *
     * @return void
     */
    public function lineaInvestigacion()
    {
        return $this->belongsTo(LineaInvestigacion::class);
    }

    /**
     * Relationship with DisciplinaSubareaConocimiento
     *
     * @return void
     */
    public function disciplinaSubareaConocimiento()
    {
        return $this->belongsTo(DisciplinaSubareaConocimiento::class);
    }

    /**
     * Relationship with TematicaEstrategica
     *
     * @return void
     */
    public function tematicaEstrategica()
    {
        return $this->belongsTo(TematicaEstrategica::class);
    }

    /**
     * Relationship with RedConocimiento
     *
     * @return void
     */
    public function redConocimiento()
    {
        return $this->belongsTo(RedConocimiento::class);
    }

    /**
     * Relationship with ActividadEconomica
     *
     * @return void
     */
    public function actividadEconomica()
    {
        return $this->belongsTo(ActividadEconomica::class);
    }

    /**
     * Relationship with LineaTecnologcia
     *
     * @return void
     */
    public function lineasTecnologicas()
    {
        return $this->belongsToMany(LineaTecnologcia::class, 'idi_linea_tecnologica', 'idi_id', 'linea_tecnologica_id');
    }

    /**
     * Relationship with MesaSectorial
     *
     * @return void
     */
    public function mesasSectoriales()
    {
        return $this->belongsToMany(MesaSectorial::class, 'idi_mesa_sectorial', 'idi_id', 'mesa_sectorial_id');
    }

    /**
     * Relationship with MesaTecnica
     *
     * @return void
     */
    public function mesaTecnica()
    {
        return $this->belongsTo(MesaTecnica::class);
    }

    /**
     * Relationship with EntidadAliada
     *
     * @return void
     */
    public function entidadesAliadas()
    {
        return $this->hasMany(EntidadAliada::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterIDi($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('titulo', 'ilike', '%'.$search.'%');
        });
    }

    /**
     * getFechaEjecucionAttribute
     *
     * @return void
     */
    public function getFechaEjecucionAttribute()
    {
        $fecha_incio = Carbon::parse($this->fecha_incio, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY');
        $fecha_finalizacion   = Carbon::parse($this->fecha_finalizacion, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY');
        return "$fecha_incio al $fecha_finalizacion";
    }
}
