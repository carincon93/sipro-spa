<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaTp extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'ta_tp';

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
        'tecnoacademia_linea_tecnologica_id',
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
        'diseno_curricular',
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
     * Relationship with TecnoacademiaLineaTecnologica
     *
     * @return object
     */
    public function tecnoacademiaLineaTecnologica()
    {
        return $this->belongsTo(TecnoacademiaLineaTecnologica::class);
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
    public function scopeFilterTaTp($query, array $filters)
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
            $tatp = TaTp::select('ta_tp.id', 'ta_tp.tecnoacademia_linea_tecnologica_id', 'ta_tp.nodo_tecnoparque_id', 'ta_tp.fecha_inicio', 'ta_tp.fecha_finalizacion')
                ->join('proyectos', 'ta_tp.id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->orderBy('ta_tp.id', 'ASC')
                ->filterTaTp(request()->only('search'))->paginate();
        } else if ($user->hasRole(4)) {
            $tatp = TaTp::select('ta_tp.id', 'ta_tp.tecnoacademia_linea_tecnologica_id', 'ta_tp.nodo_tecnoparque_id', 'ta_tp.fecha_inicio', 'ta_tp.fecha_finalizacion')
                ->join('proyectos', 'ta_tp.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->where('users.centro_formacion_id', Auth::user()->dinamizadorCentroFormacion->id)
                ->orderBy('ta_tp.id', 'ASC')
                ->filterTaTp(request()->only('search'))->paginate();
        } else {
            $tatp = TaTp::select('ta_tp.id', 'ta_tp.tecnoacademia_linea_tecnologica_id', 'ta_tp.nodo_tecnoparque_id', 'ta_tp.fecha_inicio', 'ta_tp.fecha_finalizacion')
                ->join('proyectos', 'ta_tp.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->where('proyecto_participantes.user_id', Auth::user()->id)
                ->orderBy('ta_tp.id', 'ASC')
                ->filterTaTp(request()->only('search'))->paginate();
        }
        $tatp->load('proyecto');
        $tatp->load('nodoTecnoparque');
        $tatp->load('tecnoacademiaLineaTecnologica.tecnoacademia');

        return $tatp;
    }
}
