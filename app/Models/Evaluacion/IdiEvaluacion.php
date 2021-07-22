<?php

namespace App\Models\Evaluacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdiEvaluacion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'idi_evaluaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo_puntaje',
        'titulo_comentario',
        'video_puntaje',
        'video_comentario',
        'resumen_puntaje',
        'resumen_comentario',
        'problema_central_puntaje',
        'problema_central_comentario',
        'objetivos_puntaje',
        'objetivos_comentario',
        'metodologia_puntaje',
        'metodologia_comentario',
        'entidad_aliada_puntaje',
        'entidad_aliada_comentario',
        'resultados_puntaje',
        'resultados_comentario',
        'productos_puntaje',
        'productos_comentario',
        'cadena_valor_puntaje',
        'cadena_valor_comentario',
        'analisis_riesgos_puntaje',
        'analisis_riesgos_comentario',
        'ortografia_puntaje',
        'ortografia_comentario',
        'redaccion_puntaje',
        'redaccion_comentario',
        'normas_apa_puntaje',
        'normas_apa_comentario',
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
     * Relationship with Evaluacion
     *
     * @return void
     */
    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterIdiEvaluacion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->whereRaw("unaccent(titulo) ilike unaccent('%" . $search . "%')");
        });
    }
}
