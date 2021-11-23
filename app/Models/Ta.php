<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Auth;

class Ta extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'ta';

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
        'resumen',
        'resumen_regional',
        'antecedentes',
        'antecedentes_tecnoacademia',
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
        'propuesta_sostenibilidad_social',
        'propuesta_sostenibilidad_financiera',
        'propuesta_sostenibilidad_ambiental',
        'articulacion_centro_formacion',
        'bibliografia',
        'numero_instituciones',
        'nombre_instituciones',
        'nombre_instituciones_programas',
        'diseno_curricular',
        'max_meses_ejecucion',
        'cantidad_instructores_planta',
        'cantidad_dinamizadores_planta',
        'cantidad_psicopedagogos_planta',
        'proyectos_ejecucion',
        'proyectos_macro',
        'lineas_medulares_centro',
        'lineas_tecnologicas_centro',
        'proyeccion_nuevas_instituciones',
        'proyeccion_articulacion_media',
        'articulacion_semillero',
        'semilleros_en_formalizacion',
        'infraestructura_tecnoacademia',
        'modificable',
        'otras_nuevas_instituciones',
        'otras_nombre_instituciones_programas',
        'otras_nombre_instituciones'
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
     * Relationship with Edt
     *
     * @return object
     */
    public function edt()
    {
        return $this->hasMany(Edt::class);
    }

    /**
     * Relationship with TematicaEstrategica
     *
     * @return object
     */
    public function tematicasEstrategicas()
    {
        return $this->belongsToMany(TematicaEstrategica::class, 'ta_tematica_estrategica', 'ta_id', 'tematica_estrategica_id');
    }

    /**
     * Relationship with DisciplinaSubareaConocimiento
     *
     * @return object
     */
    public function disciplinasSubareaConocimiento()
    {
        return $this->belongsToMany(DisciplinaSubareaConocimiento::class, 'ta_disciplina_subarea_conocimiento', 'ta_id', 'disciplina_subarea_conocimiento_id');
    }

    /**
     * Relationship with RedConocimiento
     *
     * @return object
     */
    public function redesConocimiento()
    {
        return $this->belongsToMany(RedConocimiento::class, 'ta_red_conocimiento', 'ta_id', 'red_conocimiento_id');
    }

    /**
     * Relationship with ActividadEconomica
     *
     * @return object
     */
    public function actividadesEconomicas()
    {
        return $this->belongsToMany(ActividadEconomica::class, 'ta_actividad_economica', 'ta_id', 'actividad_economica_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterTa($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->where('resumen', 'ilike', '%' . $search . '%');
            if (is_numeric($search)) {
                $query->orWhere('ta.id', $search - 8000);
            }
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
            $ta = Ta::select('ta.id', 'ta.fecha_inicio', 'ta.fecha_finalizacion')
                ->join('proyectos', 'ta.id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->distinct()
                ->orderBy('ta.id', 'ASC')
                ->filterTa(request()->only('search'))->paginate();
        } else if ($authUser->hasRole(2)) { // Director regional
            $ta = Ta::select('ta.id', 'ta.fecha_inicio', 'ta.fecha_finalizacion')
                ->join('proyectos', 'ta.id', 'proyectos.id')
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->join('centros_formacion', 'users.centro_formacion_id', 'centros_formacion.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->where('centros_formacion.regional_id', $authUser->directorRegional->id)
                ->distinct()
                ->orderBy('ta.id', 'ASC')
                ->filterTa(request()->only('search'))->paginate();
        } else if ($authUser->hasRole(4) && $authUser->dinamizadorCentroFormacion || $authUser->hasRole(3) && $authUser->subdirectorCentroFormacion || $authUser->hasRole(21)) { // Dinamizador SENNOVA o Subdirector de centro
            $centroFormacionId = null;
            if ($authUser->hasRole(4)) {
                $centroFormacionId = $authUser->dinamizadorCentroFormacion->id;
            } else if ($authUser->hasRole(21)) {
                $centroFormacionId = $authUser->centroFormacion->id;
            } else if ($authUser->hasRole(3)) {
                $centroFormacionId = $authUser->subdirectorCentroFormacion->id;
            }
            $ta = Ta::select('ta.id', 'ta.fecha_inicio', 'ta.fecha_finalizacion')
                ->join('proyectos', 'ta.id', 'proyectos.id')
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->where('proyectos.centro_formacion_id', $centroFormacionId)
                ->orWhere('proyecto_participantes.user_id', $authUser->id)
                ->distinct()
                ->orderBy('ta.id', 'ASC')
                ->filterTa(request()->only('search'))->paginate();
        } else if ($authUser->getAllPermissions()->where('id', 15)->first()) {
            $ta = Ta::select('ta.id', 'ta.fecha_inicio', 'ta.fecha_finalizacion')
                ->join('proyectos', 'ta.id', 'proyectos.id')
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->distinct()
                ->orderBy('ta.id', 'ASC')
                ->filterTa(request()->only('search'))->paginate();
        } else {
            $ta = Ta::select('ta.id', 'ta.fecha_inicio', 'ta.fecha_finalizacion')
                ->join('proyectos', 'ta.id', 'proyectos.id')
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->where('proyectos.estructuracion_proyectos', request()->only('estructuracion_proyectos'))
                ->where('proyecto_participantes.user_id', $authUser->id)
                ->distinct()
                ->orderBy('ta.id', 'ASC')
                ->filterTa(request()->only('search'))->paginate();
        }
        $ta->load('proyecto');
        $ta->load('proyecto.tecnoacademiaLineasTecnoacademia.tecnoacademia');

        return $ta;
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

    /**
     * getTituloAttribute
     *
     * @return void
     */
    public function getTituloAttribute()
    {
        return $this->proyecto->tecnoacademiaLineasTecnoacademia->first()->tecnoacademia->nombre;
    }
}
