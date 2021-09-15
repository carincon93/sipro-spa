<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Auth;

class Tp extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'tp';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['titulo', 'fecha_ejecucion'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nodo_tecnoparque_id',
        'resumen',
        'resumen_regional',
        'antecedentes',
        'antecedentes_regional',
        'problema_central',
        'justificacion_problema',
        'retos_oportunidades',
        'pertinencia_territorio',
        'marco_conceptual',
        'objetivo_general',
        'metodologia',
        'metodologia_local',
        'impacto_municipios',
        'fecha_inicio',
        'fecha_finalizacion',
        'propuesta_sostenibilidad',
        'impacto_centro_formacion',
        'bibliografia',
        'numero_instituciones',
        'nombre_instituciones',
        'max_meses_ejecucion',
        'modificable',
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
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

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
     * Relationship with NodoTecnoparque
     *
     * @return object
     */
    public function nodoTecnoparque()
    {
        return $this->belongsTo(NodoTecnoparque::class)->orderBy('nombre');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterTp($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->where('resumen', 'ilike', '%' . $search . '%');
        });
    }

    /**
     * getTituloAttribute
     *
     * @return void
     */
    public function getTituloAttribute()
    {
        return "Red Tecnoparque Nodo " . ucfirst($this->nodoTecnoparque->nombre) . " Vigencia " . date('Y', strtotime($this->fecha_inicio));
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
            $tp = Tp::select('tp.id', 'tp.nodo_tecnoparque_id', 'tp.fecha_inicio', 'tp.fecha_finalizacion')
                ->join('proyectos', 'tp.id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->distinct()
                ->orderBy('tp.id', 'ASC')
                ->filterTp(request()->only('search'))->paginate();
        } else if ($authUser->hasRole(2)) { // Director regional
            $tp = Tp::select('tp.id', 'tp.nodo_tecnoparque_id', 'tp.fecha_inicio', 'tp.fecha_finalizacion')
                ->join('proyectos', 'tp.id', 'proyectos.id')
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->join('centros_formacion', 'users.centro_formacion_id', 'centros_formacion.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->where('centros_formacion.regional_id', $authUser->directorRegional->id)
                ->distinct()
                ->orderBy('tp.id', 'ASC')
                ->filterTp(request()->only('search'))->paginate();
        } else if ($authUser->hasRole(4) && $authUser->dinamizadorCentroFormacion || $authUser->hasRole(3) && $authUser->subdirectorCentroFormacion || $authUser->hasRole(21)) { // Dinamizador SENNOVA o Subdirector de centro
            $centroFormacionId = null;
            if ($authUser->hasRole(4)) {
                $centroFormacionId = $authUser->dinamizadorCentroFormacion->id;
            } else if ($authUser->hasRole(21)) {
                $centroFormacionId = $authUser->centroFormacion->id;
            } else if ($authUser->hasRole(3)) {
                $centroFormacionId = $authUser->subdirectorCentroFormacion->id;
            }
            $tp = Tp::select('tp.id', 'tp.nodo_tecnoparque_id', 'tp.fecha_inicio', 'tp.fecha_finalizacion')
                ->join('proyectos', 'tp.id', 'proyectos.id')
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->where('proyectos.centro_formacion_id', $centroFormacionId)
                ->orWhere('proyecto_participantes.user_id', $authUser->id)
                ->distinct()
                ->orderBy('tp.id', 'ASC')
                ->filterTp(request()->only('search'))->paginate();
        } else if ($authUser->getAllPermissions()->where('id', 20)->first()) {
            $tp = Tp::select('tp.id', 'tp.nodo_tecnoparque_id', 'tp.fecha_inicio', 'tp.fecha_finalizacion')
                ->join('proyectos', 'tp.id', 'proyectos.id')
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->distinct()
                ->orderBy('tp.id', 'ASC')
                ->filterTp(request()->only('search'))->paginate();
        } else {
            $tp = Tp::select('tp.id', 'tp.nodo_tecnoparque_id', 'tp.fecha_inicio', 'tp.fecha_finalizacion')
                ->join('proyectos', 'tp.id', 'proyectos.id')
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->where('proyecto_participantes.user_id', $authUser->id)
                ->distinct()
                ->orderBy('tp.id', 'ASC')
                ->filterTp(request()->only('search'))->paginate();
        }
        $tp->load('proyecto');
        $tp->load('nodoTecnoparque');

        return $tp;
    }

    /**
     * getUpdatedAtAttribute
     *
     * @return void
     */
    public function getUpdatedAtAttribute($value)
    {
        return "Última modificación de este formulario: " . Carbon::parse($value, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY [a las] HH:mm:ss');
    }
}
