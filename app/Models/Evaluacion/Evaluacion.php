<?php

namespace App\Models\Evaluacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'evaluaciones';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['total_evaluacion', 'validar_evaluacion'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proyecto_id',
        'user_id',
        'finalizado',
        'habilitado'
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
        return $this->belongsTo(\App\Models\Proyecto::class);
    }

    /**
     * Relationship with IdiEvaluacion
     *
     * @return object
     */
    public function idiEvaluacion()
    {
        return $this->hasOne(IdiEvaluacion::class, 'id');
    }

    /**
     * Relationship with CulturaInnovacionEvaluacion
     *
     * @return object
     */
    public function culturaInnovacionEvaluacion()
    {
        return $this->hasOne(CulturaInnovacionEvaluacion::class, 'id');
    }

    /**
     * Relationship with ProyectoRolEvaluacion
     *
     * @return object
     */
    public function proyectoRolesEvaluaciones()
    {
        return $this->hasMany(ProyectoRolEvaluacion::class);
    }

    /**
     * Relationship with ProyectoPresupuestoEvaluacion
     *
     * @return object
     */
    public function proyectoPresupuestosEvaluaciones()
    {
        return $this->hasMany(ProyectoPresupuestoEvaluacion::class);
    }

    /**
     * Relationship with User
     *
     * @return object
     */
    public function evaluador()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function getTotalEvaluacionAttribute()
    {
        $total = 0;
        if ($this->proyecto->idi()->exists()) {
            $total = $this->idiEvaluacion->titulo_puntaje +
                $this->idiEvaluacion->video_puntaje +
                $this->idiEvaluacion->resumen_puntaje +
                $this->idiEvaluacion->problema_central_puntaje +
                $this->idiEvaluacion->objetivos_puntaje +
                $this->idiEvaluacion->metodologia_puntaje +
                $this->idiEvaluacion->entidad_aliada_puntaje +
                $this->idiEvaluacion->resultados_puntaje +
                $this->idiEvaluacion->productos_puntaje +
                $this->idiEvaluacion->cadena_valor_puntaje +
                $this->idiEvaluacion->analisis_riesgos_puntaje +
                $this->idiEvaluacion->ortografia_puntaje +
                $this->idiEvaluacion->redaccion_puntaje +
                $this->idiEvaluacion->normas_apa_puntaje;
        } else if ($this->proyecto->culturaInnovacion()->exists()) {
            $total = $this->culturaInnovacionEvaluacion->titulo_puntaje +
                $this->culturaInnovacionEvaluacion->video_puntaje +
                $this->culturaInnovacionEvaluacion->resumen_puntaje +
                $this->culturaInnovacionEvaluacion->problema_central_puntaje +
                $this->culturaInnovacionEvaluacion->objetivos_puntaje +
                $this->culturaInnovacionEvaluacion->metodologia_puntaje +
                $this->culturaInnovacionEvaluacion->entidad_aliada_puntaje +
                $this->culturaInnovacionEvaluacion->resultados_puntaje +
                $this->culturaInnovacionEvaluacion->productos_puntaje +
                $this->culturaInnovacionEvaluacion->cadena_valor_puntaje +
                $this->culturaInnovacionEvaluacion->analisis_riesgos_puntaje +
                $this->culturaInnovacionEvaluacion->ortografia_puntaje +
                $this->culturaInnovacionEvaluacion->redaccion_puntaje +
                $this->culturaInnovacionEvaluacion->normas_apa_puntaje;
        }

        return $total;
    }

    public function getValidarEvaluacionAttribute()
    {
        $itemsPorEvaluar = [];
        if ($this->proyecto->idi()->exists()) {
            $this->idiEvaluacion->titulo_puntaje == null ? array_push($itemsPorEvaluar, 'Título') : null;
            $this->idiEvaluacion->resumen_puntaje == null ? array_push($itemsPorEvaluar, 'Resumen') : null;
            $this->idiEvaluacion->problema_central_puntaje == null ? array_push($itemsPorEvaluar, 'Problema central') : null;
            $this->idiEvaluacion->objetivos_puntaje == null ? array_push($itemsPorEvaluar, 'Objetivos') : null;
            $this->idiEvaluacion->metodologia_puntaje == null ? array_push($itemsPorEvaluar, 'Metodología') : null;
            $this->idiEvaluacion->resultados_puntaje == null ? array_push($itemsPorEvaluar, 'Resultados') : null;
            $this->idiEvaluacion->productos_puntaje == null ? array_push($itemsPorEvaluar, 'Productos') : null;
            $this->idiEvaluacion->cadena_valor_puntaje == null ? array_push($itemsPorEvaluar, 'Cadena de valor') : null;
            $this->idiEvaluacion->analisis_riesgos_puntaje == null ? array_push($itemsPorEvaluar, 'Análisis de riesgos') : null;
            $this->idiEvaluacion->ortografia_puntaje == null ? array_push($itemsPorEvaluar, 'Ortografía') : null;
            $this->idiEvaluacion->redaccion_puntaje == null ? array_push($itemsPorEvaluar, 'Redacción') : null;
            $this->idiEvaluacion->normas_apa_puntaje == null ? array_push($itemsPorEvaluar, 'Normas APA') : null;
        } else if ($this->proyecto->culturaInnovacion()->exists()) {
            $this->culturaInnovacionEvaluacion->titulo_puntaje == null ? array_push($itemsPorEvaluar, 'Título') : null;
            $this->culturaInnovacionEvaluacion->resumen_puntaje == null ? array_push($itemsPorEvaluar, 'Resumen') : null;
            $this->culturaInnovacionEvaluacion->problema_central_puntaje == null ? array_push($itemsPorEvaluar, 'Problema central') : null;
            $this->culturaInnovacionEvaluacion->objetivos_puntaje == null ? array_push($itemsPorEvaluar, 'Objetivos') : null;
            $this->culturaInnovacionEvaluacion->metodologia_puntaje == null ? array_push($itemsPorEvaluar, 'Metodología') : null;
            $this->culturaInnovacionEvaluacion->resultados_puntaje == null ? array_push($itemsPorEvaluar, 'Resultados') : null;
            $this->culturaInnovacionEvaluacion->productos_puntaje == null ? array_push($itemsPorEvaluar, 'Productos') : null;
            $this->culturaInnovacionEvaluacion->cadena_valor_puntaje == null ? array_push($itemsPorEvaluar, 'Cadena de valor') : null;
            $this->culturaInnovacionEvaluacion->analisis_riesgos_puntaje == null ? array_push($itemsPorEvaluar, 'Análisis de riesgos') : null;
            $this->culturaInnovacionEvaluacion->ortografia_puntaje == null ? array_push($itemsPorEvaluar, 'Ortografía') : null;
            $this->culturaInnovacionEvaluacion->redaccion_puntaje == null ? array_push($itemsPorEvaluar, 'Redacción') : null;
            $this->culturaInnovacionEvaluacion->normas_apa_puntaje == null ? array_push($itemsPorEvaluar, 'Normas APA') : null;
        }

        if ($this->proyecto->lineaProgramatica->codigo != 23) {
            $countRolesSinEvaluar = 0;
            foreach ($this->proyecto->proyectoRolesSennova as $proyectoRol) {
                !$proyectoRol->proyectoRolesEvaluaciones()->where('evaluacion_id', $this->id)->first() ? $countRolesSinEvaluar++ : null;
            }
        }

        $countRolesSinEvaluar > 0 ? array_push($itemsPorEvaluar, 'Hay ' . $countRolesSinEvaluar . ' rol(es) sin evaluar') : null;

        $countPresupuestosSinEvaluar = 0;
        foreach ($this->proyecto->proyectoPresupuesto as $proyectoPresupuesto) {
            !$proyectoPresupuesto->proyectoPresupuestosEvaluaciones()->where('evaluacion_id', $this->id)->first() ? $countPresupuestosSinEvaluar++ : null;
        }

        $countPresupuestosSinEvaluar > 0 ? array_push($itemsPorEvaluar, 'Hay ' . $countPresupuestosSinEvaluar . ' rubro(s) presupuestal(es) sin evaluar') : null;

        return $itemsPorEvaluar;
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterEvaluacion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('id', 'ilike', '%' . $search . '%');
        });
    }
}
