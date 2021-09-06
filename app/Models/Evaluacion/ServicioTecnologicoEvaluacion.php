<?php

namespace App\Models\Evaluacion;

use App\Models\ServicioTecnologico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ServicioTecnologicoEvaluacion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'servicios_tecnologicos_evaluaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo_puntaje',
        'titulo_comentario',
        'video_comentario',
        'resumen_puntaje',
        'resumen_comentario',
        'problema_central_puntaje',
        'problema_central_comentario',
        'cadena_valor_comentario',
        'ortografia_comentario',
        'redaccion_comentario',
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
     * @return object
     */
    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterServicioTecnologicoEvaluacion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->whereRaw("unaccent(titulo) ilike unaccent('%" . $search . "%')");
        });
    }

    /**
     * getProyectosPorEvaluador
     *
     * @param  mixed $convocatoria
     * @return object
     */
    public static function getProyectosPorEvaluador($convocatoria)
    {
        $authUser = Auth::user();
        if ($authUser->hasRole(1)) { // Admin
            $serviciosTecnologicos = ServicioTecnologico::select('evaluaciones.id as evaluacion_id', 'evaluaciones.habilitado', 'evaluaciones.iniciado', 'evaluaciones.finalizado', 'servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')
                ->join('evaluaciones', 'evaluaciones.proyecto_id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->distinct()
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        } else if ($authUser->hasRole(11)) { // Evaluador
            $serviciosTecnologicos = ServicioTecnologico::select('evaluaciones.id as evaluacion_id', 'evaluaciones.habilitado', 'evaluaciones.iniciado', 'evaluaciones.finalizado', 'servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')
                ->join('evaluaciones', 'evaluaciones.proyecto_id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('evaluaciones.user_id', $authUser->id)
                ->distinct()
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        }
        $serviciosTecnologicos->load('proyecto');
        return $serviciosTecnologicos;
    }
}
