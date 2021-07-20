<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ServicioTecnologico extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'servicios_tecnologicos';

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
        'tipo_proyecto_st_id',
        'titulo',
        'resumen',
        'antecedentes',
        'problema_central',
        'justificacion_problema',
        'identificacion_problema',
        'pregunta_formulacion_problema',
        'objetivo_general',
        'metodologia',
        'fecha_inicio',
        'fecha_finalizacion',
        'propuesta_sostenibilidad',
        'bibliografia',
        'max_meses_ejecucion',
        'video',
        'especificaciones_area',
        'infraestructura_adecuada'
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
     * Relationship with TipoProyectoSt
     *
     * @return object
     */
    public function tipoProyectoSt()
    {
        return $this->belongsTo(TipoProyectoSt::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterServicioTecnologico($query, array $filters)
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
        $authUser = Auth::user();
        if ($authUser->hasRole(1)) {
            $servicioTecnologico = ServicioTecnologico::select('servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->distinct()
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        } else if ($authUser->hasRole(2)) { // Director regional
            $servicioTecnologico = ServicioTecnologico::select('servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->join('centros_formacion', 'users.centro_formacion_id', 'centros_formacion.id')
                ->where('centros_formacion.regional_id', $authUser->directorRegional->id)
                ->distinct()
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        } else if ($authUser->hasRole(4) && $authUser->dinamizadorCentroFormacion || $authUser->hasRole(3) && $authUser->subdirectorCentroFormacion) { // Dinamizador SENNOVA o Subdirector de centro
            $centroFormacionId = null;
            if ($authUser->hasRole(4)) {
                $centroFormacionId = $authUser->dinamizadorCentroFormacion->id;
            } else if ($authUser->hasRole(3)) {
                $centroFormacionId = $authUser->subdirectorCentroFormacion->id;
            }
            $servicioTecnologico = ServicioTecnologico::select('servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->where('users.centro_formacion_id', $centroFormacionId)
                ->distinct()
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        } else if ($authUser->getAllPermissions()->where('id', 16)->first()) {
            $servicioTecnologico = ServicioTecnologico::select('servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->distinct()
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        } else {
            $servicioTecnologico = ServicioTecnologico::select('servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->where('proyecto_participantes.user_id', $authUser->id)
                ->distinct()
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        }
        $servicioTecnologico->load('proyecto');

        return $servicioTecnologico;
    }
}
