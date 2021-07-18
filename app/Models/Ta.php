<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
    protected $appends = ['fecha_ejecucion', 'meta_aprendices', 'max_material_formacion', 'max_bienestar_aprendiz'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resumen',
        'resumen_regional',
        'justificacion',
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
        'modificable',
        'proyectos_ejecucion',
        'proyectos_macro',
        'lineas_medulares_centro',
        'lineas_tecnologicas_centro',
        'proyeccion_nuevas_tecnoacademias',
        'proyeccion_articulacion_media',
        'articulacion_semillero',
        'semilleros_en_formalizacion',
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
     * Relationship with Edt
     *
     * @return object
     */
    public function edt()
    {
        return $this->hasMany(Edt::class);
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
            $ta = Ta::select('ta.id', 'ta.fecha_inicio', 'ta.fecha_finalizacion')
                ->join('proyectos', 'ta.id', 'proyectos.id')
                ->where('proyectos.convocatoria_id', $convocatoria->id)
                ->orderBy('ta.id', 'ASC')
                ->filterTa(request()->only('search'))->paginate();
        } else if ($user->hasRole(4)) {
            $ta = Ta::select('ta.id', 'ta.fecha_inicio', 'ta.fecha_finalizacion')
                ->join('proyectos', 'ta.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->where('users.centro_formacion_id', Auth::user()->dinamizadorCentroFormacion->id)
                ->orderBy('ta.id', 'ASC')
                ->filterTa(request()->only('search'))->paginate();
        } else if ($user->getAllPermissions()->where('id', 15)->first()) {
            $ta = Ta::select('ta.id', 'ta.fecha_inicio', 'ta.fecha_finalizacion')
                ->join('proyectos', 'ta.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->join('users', 'proyecto_participantes.user_id', 'users.id')
                ->distinct('ta.id')
                ->orderBy('ta.id', 'ASC')
                ->filterTa(request()->only('search'))->paginate();
        } else {
            $ta = Ta::select('ta.id', 'ta.fecha_inicio', 'ta.fecha_finalizacion')
                ->join('proyectos', 'ta.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)
                ->join('proyecto_participantes', 'proyectos.id', 'proyecto_participantes.proyecto_id')
                ->where('proyecto_participantes.user_id', Auth::user()->id)
                ->orderBy('ta.id', 'ASC')
                ->filterTa(request()->only('search'))->paginate();
        }
        $ta->load('proyecto');
        $ta->load('proyecto.tecnoacademiaLineasTecnoacademia.tecnoacademia');

        return $ta;
    }

    public function getMetaAprendicesAttribute()
    {
        $valorEstandarizado = 0;

        if ($this->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->modalidad == 1) {
            $valorEstandarizado = 460000;
        } else if ($this->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->modalidad == 2) {
            $valorEstandarizado = 490000;
        } else if ($this->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->modalidad == 3) {
            $valorEstandarizado = 520000;
        }

        $total = 0;
        if ($valorEstandarizado > 0) {
            $total = $this->proyecto->getPrecioProyectoAttribute() / $valorEstandarizado;
        }
        return round($total);
    }

    public function getMaxMaterialFormacionAttribute()
    {
        $valorAprendiz = 0;

        if ($this->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->modalidad == 1) {
            $valorAprendiz = 20000;
        } else if ($this->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->modalidad == 2) {
            $valorAprendiz = 35000;
        } else if ($this->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->modalidad == 3) {
            $valorAprendiz = 63000;
        }
        return round($this->getMetaAprendicesAttribute() * $valorAprendiz);
    }

    public function getMaxBienestarAprendizAttribute()
    {
        return round($this->getMetaAprendicesAttribute() * 10200);
    }
}
