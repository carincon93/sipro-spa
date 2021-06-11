<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CulturaInnovacion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'cultura_innovacion';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['fecha_ejecucion'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'linea_investigacion_id',
        'area_conocimiento_id',
        'tematica_estrategica_id',
        'actividad_economica_id',
        'titulo',
        'fecha_inicio',
        'fecha_finalizacion',
        'video',
        'justificacion_industria_4',
        'justificacion_economia_naranja',
        'justificacion_politica_discapacidad',
        'resumen',
        'antecedentes',
        'marco_conceptual',
        'metodologia',
        'propuesta_sostenibilidad',
        'muestreo',
        'actividades_muestreo',
        'objetivo_muestreo',
        'recoleccion_especimenes',
        'impacto_municipios',
        'impacto_centro_formacion',
        'objetivo_general',
        'planteamiento_problema',
        'justificacion_problema',
        'relacionado_plan_tecnologico',
        'relacionado_agendas_competitividad',
        'relacionado_mesas_sectoriales',
        'relacionado_tecnoacademia',
        'bibliografia',
        'numero_aprendices',
        'max_meses_ejecucion'
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
        return $this->belongsTo(Proyecto::class, 'id');
    }

    /**
     * Relationship with LineaInvestigacion
     *
     * @return object
     */
    public function lineaInvestigacion()
    {
        return $this->belongsTo(LineaInvestigacion::class);
    }

    /**
     * Relationship with AreaConocimiento
     *
     * @return object
     */
    public function areaConocimiento()
    {
        return $this->belongsTo(AreaConocimiento::class);
    }

    /**
     * Relationship with TematicaEstrategica
     *
     * @return object
     */
    public function tematicaEstrategica()
    {
        return $this->belongsTo(TematicaEstrategica::class);
    }

    /**
     * Relationship with ActividadEconomica
     *
     * @return object
     */
    public function actividadEconomica()
    {
        return $this->belongsTo(ActividadEconomica::class);
    }

    /**
     * Relationship with TecnoacademiaLineaTecnologica
     *
     * @return object
     */
    public function tecnoacademiaLineasTecnologicas()
    {
        return $this->belongsToMany(TecnoacademiaLineaTecnologica::class, 'cultura_innovacion_linea_tecnologica', 'cultura_innovacion_id', 'tecnoacademia_linea_tecnologica_id');
    }

    /**
     * Relationship with MesaSectorial
     *
     * @return object
     */
    public function mesasSectoriales()
    {
        return $this->belongsToMany(MesaSectorial::class, 'cultura_innovacion_mesa_sectorial', 'cultura_innovacion_id', 'mesa_sectorial_id');
    }

    /**
     * Relationship with MesaTecnica
     *
     * @return object
     */
    public function mesaTecnica()
    {
        return $this->belongsTo(MesaTecnica::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterCulturaInnovacion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->whereRaw("unaccent(titulo) ilike unaccent('%" . $search . "%')");
        });
    }

    /**
     * getFechaEjecucionAttribute
     *
     * @return void
     */
    public function getFechaEjecucionAttribute()
    {
        $fecha_inicio       = Carbon::parse($this->fecha_inicio, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY');
        $fecha_finalizacion = Carbon::parse($this->fecha_finalizacion, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY');
        return "$fecha_inicio al $fecha_finalizacion";
    }

    /**
     * getProyectosPorRol
     *
     * @param  mixed $convocatoria
     * @return object
     */
    public static function getProyectosPorRol($convocatoria)
    {
        $user = Auth::user();
        if ($user->hasRole(1)) {
            $cultura_innovacion = CulturaInnovacion::select('cultura_innovacion.id', 'cultura_innovacion.titulo', 'cultura_innovacion.fecha_inicio', 'cultura_innovacion.fecha_finalizacion')
                ->join('proyectos', 'cultura_innovacion.id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)->orderBy('titulo', 'ASC')
                ->filterCulturaInnovacion(request()->only('search'))->paginate();
        } else {
            $cultura_innovacion = CulturaInnovacion::select('cultura_innovacion.id', 'cultura_innovacion.titulo', 'cultura_innovacion.fecha_inicio', 'cultura_innovacion.fecha_finalizacion')
                ->join('proyectos', 'cultura_innovacion.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->where('proyecto_participantes.user_id', Auth::user()->id)
                ->orderBy('titulo', 'ASC')
                ->filterCulturaInnovacion(request()->only('search'))->paginate();
        }

        $cultura_innovacion->load('proyecto');
        return $cultura_innovacion;
    }
}
