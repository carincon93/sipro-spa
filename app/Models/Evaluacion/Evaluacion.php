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
    protected $appends = ['total_evaluacion'];

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
