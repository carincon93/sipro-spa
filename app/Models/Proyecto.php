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
    protected $appends = ['codigo', 'diff_meses', 'precio_proyecto', 'total_roles_sennova', 'fecha_inicio', 'fecha_finalizacion'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'convocatoria_id',
        'centro_formacion_id',
        'tipo_proyecto_id',
        'finalizado',
        'modificable',
        'radicado'
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
     * Relationship with TipoProyecto
     *
     * @return object
     */
    public function tipoProyecto()
    {
        return $this->belongsTo(TipoProyecto::class);
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
     * Relationship with TATP
     *
     * @return object
     */
    public function taTp()
    {
        return $this->hasOne(TaTp::class, 'id');
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
     * Relationship with ProgramaFormacion
     *
     * @return object
     */
    public function programasFormacion()
    {
        return $this->belongsToMany(ProgramaFormacion::class, 'proyecto_programa_formacion', 'proyecto_id', 'programa_formacion_id');
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
     * Relationship with participantes
     *
     * @return object
     */
    public function participantes()
    {
        return $this->belongsToMany(User::class, 'proyecto_participantes', 'proyecto_id', 'user_id')
            ->withPivot([
                'user_id',
                'es_autor',
                'es_formulador',
                'cantidad_meses',
                'cantidad_horas',
                'rol_sennova_id'
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
        if ($this->tatp()->exists()) $fechaFinalizacion =  $this->tatp->fecha_finalizacion;
        if ($this->servicioTecnologico()->exists()) $fechaFinalizacion =  $this->servicioTecnologico->fecha_finalizacion;
        if ($this->culturaInnovacion()->exists()) $fechaFinalizacion =  $this->culturaInnovacion->fecha_finalizacion;

        return 'SGPS-' . ($this->id + 8000) . '-' . date('Y', strtotime($fechaFinalizacion));
    }

    public function getFechaInicioAttribute()
    {
        $fechaInicio = null;
        if ($this->idi()->exists()) {
            $fechaInicio = $this->idi->fecha_inicio;
        } else if ($this->taTp()->exists()) {
            $fechaInicio = $this->taTp->fecha_inicio;
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
        } else if ($this->taTp()->exists()) {
            $fechaInicio = $this->taTp->fecha_finalizacion;
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
        if ($this->idi()->exists()) {
            $cantidadMesesEjecucion = $this->idi->max_meses_ejecucion;
        }

        if ($this->tatp()->exists()) {
            $cantidadMesesEjecucion = $this->tatp->max_meses_ejecucion;
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
                $total += $proyectoPresupuesto->getPromedioAttribute();
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
}
