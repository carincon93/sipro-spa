<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
    protected $appends = ['fecha_ejecucion'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nodo_tecnoparque_id',
        'resumen',
        'resumen_regional',
        'justificacion',
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
            $tp = Tp::select('tp.id', 'tp.nodo_tecnoparque_id', 'tp.fecha_inicio', 'tp.fecha_finalizacion')
                ->join('proyectos', 'tp.id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->orderBy('tp.id', 'ASC')
                ->filterTp(request()->only('search'))->paginate();
        } else if ($user->hasRole(4)) {
            $tp = Tp::select('tp.id', 'tp.nodo_tecnoparque_id', 'tp.fecha_inicio', 'tp.fecha_finalizacion')
                ->join('proyectos', 'tp.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->where('users.centro_formacion_id', Auth::user()->dinamizadorCentroFormacion->id)
                ->orderBy('tp.id', 'ASC')
                ->filterTp(request()->only('search'))->paginate();
        } else if ($user->getAllPermissions()->where('id', 20)->first()) {
            $tp = Tp::select('tp.id', 'tp.nodo_tecnoparque_id', 'tp.fecha_inicio', 'tp.fecha_finalizacion')
                ->join('proyectos', 'tp.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->distinct('tp.id')
                ->orderBy('tp.id', 'ASC')
                ->filterTp(request()->only('search'))->paginate();
        } else {
            $tp = Tp::select('tp.id', 'tp.nodo_tecnoparque_id', 'tp.fecha_inicio', 'tp.fecha_finalizacion')
                ->join('proyectos', 'tp.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->where('proyecto_participantes.user_id', Auth::user()->id)
                ->orderBy('tp.id', 'ASC')
                ->filterTp(request()->only('search'))->paginate();
        }
        $tp->load('proyecto');
        $tp->load('nodoTecnoparque');

        return $tp;
    }
}
