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
        $user = Auth::user();
        if ($user->hasRole(1)) {
            $servicioTecnologico = ServicioTecnologico::select('servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        } else if ($user->hasRole(4)) {
            $servicioTecnologico = ServicioTecnologico::select('servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->where('users.centro_formacion_id', Auth::user()->dinamizadorCentroFormacion->id)
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        } else if ($user->getAllPermissions()->where('id', 16)->first()) {
            $servicioTecnologico = ServicioTecnologico::select('servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->distinct('servicios_tecnologicos.id')
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        } else {
            $servicioTecnologico = ServicioTecnologico::select('servicios_tecnologicos.id', 'servicios_tecnologicos.titulo', 'servicios_tecnologicos.fecha_inicio', 'servicios_tecnologicos.fecha_finalizacion')
                ->join('proyectos', 'servicios_tecnologicos.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->where('proyecto_participantes.user_id', Auth::user()->id)
                ->orderBy('servicios_tecnologicos.id', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate();
        }
        $servicioTecnologico->load('proyecto');

        return $servicioTecnologico;
    }
}
