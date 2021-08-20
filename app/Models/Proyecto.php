<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Proyecto extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyectos';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['codigo', 'diff_meses', 'precio_proyecto', 'total_roles_sennova', 'fecha_inicio', 'fecha_finalizacion', 'max_material_formacion', 'max_bienestar_aprendiz', 'estado_evaluacion'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'convocatoria_id',
        'centro_formacion_id',
        'linea_programatica_id',
        'finalizado',
        'modificable',
        'a_evaluar',
        'en_subsanacion',
        'estructuracion_proyectos'
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
     * Relationship with Convocatoria
     *
     * @return object
     */
    public function convocatoria()
    {
        return $this->belongsTo(Convocatoria::class);
    }

    /**
     * Relationship with LineaProgramatica
     *
     * @return object
     */
    public function lineaProgramatica()
    {
        return $this->belongsTo(LineaProgramatica::class);
    }

    /**
     * Relationship with CentroFormacion
     *
     * @return object
     */
    public function centroFormacion()
    {
        return $this->belongsTo(CentroFormacion::class);
    }

    /**
     * Relationship with Idi
     *
     * @return object
     */
    public function idi()
    {
        return $this->hasOne(Idi::class, 'id');
    }

    /**
     * Relationship with CulturaInnovacion
     *
     * @return object
     */
    public function culturaInnovacion()
    {
        return $this->hasOne(CulturaInnovacion::class, 'id');
    }

    /**
     * Relationship with ServicioTecnologico
     *
     * @return object
     */
    public function servicioTecnologico()
    {
        return $this->hasOne(ServicioTecnologico::class, 'id');
    }

    /**
     * Relationship with Ta
     *
     * @return object
     */
    public function ta()
    {
        return $this->hasOne(Ta::class, 'id');
    }

    /**
     * Relationship with Tp
     *
     * @return object
     */
    public function tp()
    {
        return $this->hasOne(Tp::class, 'id');
    }

    /**
     * Relationship with EntidadAliada
     *
     * @return object
     */
    public function entidadesAliadas()
    {
        return $this->hasMany(EntidadAliada::class);
    }

    /**
     * Relationship with Municipio
     *
     * @return object
     */
    public function municipios()
    {
        return $this->belongsToMany(Municipio::class, 'proyecto_municipio', 'proyecto_id', 'municipio_id')->orderBy('municipios.nombre', 'ASC');
    }

    /**
     * Relationship with Municipio
     *
     * @return object
     */
    public function municipiosAImpactar()
    {
        return $this->belongsToMany(Municipio::class, 'proyecto_municipio_impactar', 'proyecto_id', 'municipio_id')->orderBy('municipios.nombre', 'ASC');
    }

    /**
     * Relationship with CausaDirecta
     *
     * @return object
     */
    public function causasDirectas()
    {
        return $this->hasMany(CausaDirecta::class)->orderBy('id', 'ASC');
    }

    /**
     * Relationship with EfectoDirecto
     *
     * @return object
     */
    public function efectosDirectos()
    {
        return $this->hasMany(EfectoDirecto::class)->orderBy('id', 'ASC');
    }

    /**
     * Relationship with ProyectoAnexo
     *
     * @return object
     */
    public function proyectoAnexo()
    {
        return $this->hasMany(ProyectoAnexo::class);
    }

    /**
     * Relationship with AnalisisRiesgo
     *
     * @return object
     */
    public function analisisRiesgos()
    {
        return $this->hasMany(AnalisisRiesgo::class);
    }

    /**
     * Relationship with ProyectoPresupuesto
     *
     * @return object
     */
    public function proyectoPresupuesto()
    {
        return $this->hasMany(ProyectoPresupuesto::class);
    }

    /**
     * Relationship with ProyectoRolSennova
     *
     * @return object
     */
    public function proyectoRolesSennova()
    {
        return $this->hasMany(ProyectoRolSennova::class);
    }

    /**
     * Relationship with InventarioEquipo
     *
     * @return object
     */
    public function inventarioEquipos()
    {
        return $this->hasMany(InventarioEquipo::class);
    }

    /**
     * Relationship with Evaluacion
     *
     * @return object
     */
    public function evaluaciones()
    {
        return $this->hasMany(\App\Models\Evaluacion\Evaluacion::class);
    }

    /**
     * Relationship with ProgramaFormacion
     *
     * @return object
     */
    public function programasFormacionImpactados()
    {
        return $this->belongsToMany(ProgramaFormacion::class, 'proyecto_programa_formacion_impactados', 'proyecto_id', 'programa_formacion_id');
    }

    /**
     * Relationship with ProgramaFormacion
     *
     * @return object
     */
    public function programasFormacionArticulados()
    {
        return $this->belongsToMany(ProgramaFormacionArticulado::class, 'proyecto_programa_formacion_articulados', 'proyecto_id', 'programa_formacion_articulado_id');
    }

    /**
     * Relationship with ProgramaFormacion
     *
     * @return object
     */
    public function taProgramasFormacion()
    {
        return $this->belongsToMany(ProgramaFormacion::class, 'ta_programa_formacion', 'proyecto_id', 'programa_formacion_id');
    }

    /**
     * Relationship with GrupoInvestigacion
     *
     * @return object
     */
    public function gruposInvestigacion()
    {
        return $this->belongsToMany(GrupoInvestigacion::class, 'proyecto_grupo_investigacion', 'proyecto_id', 'grupo_investigacion_id');
    }

    /**
     * Relationship with LineaInvestigacion
     *
     * @return object
     */
    public function lineasInvestigacion()
    {
        return $this->belongsToMany(LineaInvestigacion::class, 'proyecto_linea_investigacion', 'proyecto_id', 'linea_investigacion_id');
    }

    /**
     * Relationship with SemilleroInvestigacion
     *
     * @return object
     */
    public function semillerosInvestigacion()
    {
        return $this->belongsToMany(SemilleroInvestigacion::class, 'proyecto_semillero_investigacion', 'proyecto_id', 'semillero_investigacion_id');
    }

    /**
     * Relationship with TecnoacademiaLineaTecnoacademia
     *
     * @return object
     */
    public function tecnoacademiaLineasTecnoacademia()
    {
        return $this->belongsToMany(TecnoacademiaLineaTecnoacademia::class, 'proyecto_linea_tecnoacademia', 'proyecto_id', 'tecnoacademia_linea_tecnoacademia_id');
    }

    /**
     * Relationship with DisCurricular
     *
     * @return object
     */
    public function disCurriculares()
    {
        return $this->belongsToMany(DisCurricular::class, 'proyecto_dis_curricular', 'proyecto_id', 'dis_curricular_id');
    }

    /**
     * Relationship with participantes
     *
     * @return object
     */
    public function participantes()
    {
        return $this->belongsToMany(User::class, 'proyecto_participantes', 'proyecto_id', 'user_id')
            ->withPivot([
                'user_id',
                'es_formulador',
                'cantidad_meses',
                'cantidad_horas',
                'rol_sennova'
            ]);
    }

    public static function getLog($proyectoId)
    {
        return DB::table('notifications')->select('data', 'created_at')->whereRaw("data->>'proyectoId' = '" . $proyectoId . "'")->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Get codigo e.g. SGPS-8000-2021
     *
     * @return string
     */
    public function getCodigoAttribute()
    {
        $fechaFinalizacion = null;
        if ($this->idi()->exists()) $fechaFinalizacion =  $this->idi->fecha_finalizacion;
        if ($this->ta()->exists()) $fechaFinalizacion  =  $this->ta->fecha_finalizacion;
        if ($this->tp()->exists()) $fechaFinalizacion  =  $this->tp->fecha_finalizacion;
        if ($this->servicioTecnologico()->exists()) $fechaFinalizacion =  $this->servicioTecnologico->fecha_finalizacion;
        if ($this->culturaInnovacion()->exists()) $fechaFinalizacion =  $this->culturaInnovacion->fecha_finalizacion;

        return 'SGPS-' . ($this->id + 8000) . '-' . date('Y', strtotime($fechaFinalizacion));
    }

    public function getFechaInicioAttribute()
    {
        $fechaInicio = null;
        if ($this->idi()->exists()) {
            $fechaInicio = $this->idi->fecha_inicio;
        } else if ($this->ta()->exists()) {
            $fechaInicio = $this->ta->fecha_inicio;
        } else if ($this->tp()->exists()) {
            $fechaInicio = $this->tp->fecha_inicio;
        } else if ($this->servicioTecnologico()->exists()) {
            $fechaInicio = $this->servicioTecnologico->fecha_inicio;
        } else if ($this->culturaInnovacion()->exists()) {
            $fechaInicio = $this->culturaInnovacion->fecha_inicio;
        }

        return $fechaInicio;
    }

    public function getFechaFinalizacionAttribute()
    {
        $fechaInicio = null;
        if ($this->idi()->exists()) {
            $fechaInicio = $this->idi->fecha_finalizacion;
        } else if ($this->ta()->exists()) {
            $fechaInicio = $this->ta->fecha_finalizacion;
        } else if ($this->tp()->exists()) {
            $fechaInicio = $this->tp->fecha_finalizacion;
        } else if ($this->servicioTecnologico()->exists()) {
            $fechaInicio = $this->servicioTecnologico->fecha_finalizacion;
        } else if ($this->culturaInnovacion()->exists()) {
            $fechaInicio = $this->culturaInnovacion->fecha_finalizacion;
        }

        return $fechaInicio;
    }

    /**
     * getDiffMesesAttribute
     *
     * @return int
     */
    public function getDiffMesesAttribute()
    {
        $cantidadMesesEjecucion = 0;
        if ($this->idi()->exists()) {
            $cantidadMesesEjecucion = $this->idi->max_meses_ejecucion;
        }

        if ($this->ta()->exists()) {
            $cantidadMesesEjecucion = $this->ta->max_meses_ejecucion;
        }

        if ($this->tp()->exists()) {
            $cantidadMesesEjecucion = $this->tp->max_meses_ejecucion;
        }

        if ($this->servicioTecnologico()->exists()) {
            $cantidadMesesEjecucion = $this->servicioTecnologico->max_meses_ejecucion;
        }

        if ($this->culturaInnovacion()->exists()) {
            $cantidadMesesEjecucion = $this->culturaInnovacion->max_meses_ejecucion;
        }

        return $cantidadMesesEjecucion;
    }


    public function getTotalProyectoPresupuestoAttribute()
    {
        $total = 0;

        foreach ($this->proyectoPresupuesto as $proyectoPresupuesto) {
            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto) {
                $total += $proyectoPresupuesto->valor_total;
            }
        }

        return $total;
    }

    /**
     * getTotalRolesSennovaAttribute
     *
     * @return int
     */
    public function getTotalRolesSennovaAttribute()
    {
        $total = 0;

        foreach ($this->proyectoRolesSennova as $proyectoRolSennova) {
            if ($proyectoRolSennova->convocatoriaRolSennova->rolSennova->sumar_al_presupuesto) {
                $total += $proyectoRolSennova->getTotalRolSennova();
            }
        }

        return $total;
    }

    /**
     * getPrecioProyecto
     *
     * @return int
     */
    public function getPrecioProyectoAttribute()
    {
        return $this->getTotalProyectoPresupuestoAttribute() + $this->getTotalRolesSennovaAttribute();
    }

    public function getMetaAprendicesAttribute()
    {
        $valorEstandarizado = 0;
        if ($this->ta()->exists()) {
            $modalidad = $this->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->modalidad;
            if ($modalidad == 1) {
                $valorEstandarizado = 460000;
            } else if ($modalidad == 2) {
                $valorEstandarizado = 490000;
            } else if ($modalidad == 3) {
                $valorEstandarizado = 520000;
            }
        }

        $total = 0;
        if ($valorEstandarizado > 0) {
            $total = $this->getPrecioProyectoAttribute() / $valorEstandarizado;
        }

        return round($total);
    }

    public function getMaxMaterialFormacionAttribute()
    {
        $valorAprendiz = 0;
        if ($this->ta()->exists()) {
            $modalidad = $this->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->modalidad;
            if ($modalidad == 1) {
                $valorAprendiz = 20000;
            } else if ($modalidad == 2) {
                $valorAprendiz = 35000;
            } else if ($modalidad == 3) {
                $valorAprendiz = 63000;
            }
        }

        $total = round($this->getMetaAprendicesAttribute() * $valorAprendiz);

        if ($this->tecnoacademiaLineasTecnoacademia()->first() && $this->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->id == 18) {
            $total = 32098065 + $this->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_mantenimiento_equipos;
        } else if ($this->tecnoacademiaLineasTecnoacademia()->first() && $this->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->id == 16) {
            $total = 23174417 + $this->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_mantenimiento_equipos;
        }

        return $total;
    }

    public function getMaxBienestarAprendizAttribute()
    {
        $total = 0;
        if ($this->ta()->exists()) {
            $total = round($this->getMetaAprendicesAttribute() * 10200);
        }
        return $total;
    }

    public function getEstadoEvaluacionAttribute()
    {
        $evaluaciones = $this->evaluaciones()->where('habilitado', true)->get();

        $puntajeTotal = 0;
        $totalRecomendaciones = 0;
        $estadoEvaluacion = '';
        foreach ($evaluaciones as $evaluacion) {
            $puntajeTotal += $evaluacion->total_evaluacion;
            $totalRecomendaciones += $evaluacion->total_recomendaciones;
        }

        count($evaluaciones) > 0 ? $puntajeTotal = $puntajeTotal / count($evaluaciones) : $puntajeTotal = 0;

        if ($puntajeTotal == 0 && $totalRecomendaciones == 0) {
            $estadoEvaluacion = "a. No priorizado anexo 1C";
        } elseif ($puntajeTotal >= 91 && $totalRecomendaciones == 0) { // Preaprobado
            $estadoEvaluacion = "b. Pre-aprobado >= 91";
        } elseif ($puntajeTotal >= 91 && $totalRecomendaciones > 0) { // Pre-aprobado con observaciones
            $estadoEvaluacion = "c. Pre-aprobado con observaciones";
        } elseif ($puntajeTotal >= 70 && $puntajeTotal < 91 && $totalRecomendaciones == 0) { // Pre-aprobado con observaciones
            $estadoEvaluacion = "d. Pre-aprobado con observaciones";
        } elseif ($puntajeTotal >= 70 && $puntajeTotal < 91 && $totalRecomendaciones > 0) { // Pre-aprobado con observaciones
            $estadoEvaluacion = "e. Pre-aprobado con observaciones";
        } elseif ($puntajeTotal < 70) { // Rechazado
            $estadoEvaluacion = "f. Rechazado";
        }

        return $estadoEvaluacion;
    }
}
