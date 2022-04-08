<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProyectoCapacidadInstalada extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyectos_capacidad_instalada';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['fecha_ejecucion', 'codigo'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'semillero_investigacion_id',
        'subtipo_proyecto_capacidad_instalada_id',
        'estado_proyecto',
        'titulo',
        'red_conocimiento_id',
        'actividad_economica_id',
        'disciplina_subarea_conocimiento_id',
        'beneficia_a',
        'fecha_inicio',
        'fecha_finalizacion',
        'planteamiento_problema',
        'justificacion',
        'objetivo_general',
        'primer_objetivo_especifico',
        'segundo_objetivo_especifico',
        'tercer_objetivo_especifico',
        'cuarto_objetivo_especifico',
        'metodologia',
        'infraestructura_desarrollo_proyecto',
        'materiales_formacion_a_usar',
        'conclusiones',
        'bibliografia',
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
     * Relationship with SemilleroInvestigacion
     *
     * @return object
     */
    public function semilleroInvestigacion()
    {
        return $this->belongsTo(SemilleroInvestigacion::class);
    }

    /**
     * Relationship with RedConocimiento
     *
     * @return object
     */
    public function redConocimiento()
    {
        return $this->belongsTo(RedConocimiento::class);
    }

    /**
     * Relationship with DisciplinaSubareaConocimiento
     *
     * @return object
     */
    public function disciplinaSubareaConocimiento()
    {
        return $this->belongsTo(DisciplinaSubareaConocimiento::class);
    }

    /**
     * Relationship with SubtipoProyectoCapacidadInstalada
     *
     * @return object
     */
    public function subtipoProyectoCapacidadInstalada()
    {
        return $this->belongsTo(SubtipoProyectoCapacidadInstalada::class);
    }

    /**
     * Relationship with ActividadEconomica
     *
     * @return object
     */
    public function actividadEconomica()
    {
        return $this->belongsTo(ActividadEconomica::class);
    }

    /**
     * Relationship with ProgramaFormacion
     *
     * @return object
     */
    public function programasFormacionImpactados()
    {
        return $this->belongsToMany(ProgramaFormacion::class, 'proyecto_capacidad_programa_formacion', 'proyecto_capacidad_instalada_id', 'programa_formacion_id');
    }

    /**
     * Relationship with ProgramaFormacionArticulado
     *
     * @return object
     */
    public function programasFormacionArticulados()
    {
        return $this->belongsToMany(ProgramaFormacionArticulado::class, 'proyecto_capacidad_programa_formacion_articulado', 'proyecto_capacidad_instalada_id', 'programa_formacion_articulado_id');
    }

    /**
     * Relationship with ProyectoCapacidadInstaladaEntidadAliada
     *
     * @return object
     */
    public function entidadesAliadas()
    {
        return $this->hasMany(ProyectoCapacidadInstaladaEntidadAliada::class);
    }

    /**
     * Relationship with ProyectoCapacidadInstaladaObjetivoEspecifico
     *
     * @return object
     */
    public function objetivosEspecificos()
    {
        return $this->hasMany(ProyectoCapacidadInstaladaObjetivoEspecifico::class);
    }

    /**
     * Relationship with User
     *
     * @return object
     */
    public function integrantes()
    {
        return $this->belongsToMany(User::class, 'proyecto_capacidad_instalada_integrante', 'proyecto_capacidad_instalada_id', 'user_id')->withPivot('rol_sennova', 'cantidad_meses', 'cantidad_horas', 'autor_principal');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoCapacidadInstalada($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('titulo', 'ilike', '%' . $search . '%');
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
     * getEstadoProyectoAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getEstadoProyectoAttribute($value)
    {
        switch ($value) {
            case 1:
                $value = 'Propuesta';
                break;
            case 2:
                $value = 'En ejecución';
                break;
            case 3:
                $value = 'Finalizado';
                break;
            default:
                break;
        }
        return $value;
    }

    /**
     * getProyectosPorRol
     *
     * @return object
     */
    public static function getProyectosPorRol()
    {
        $authUser = Auth::user();
        if ($authUser->hasRole(1)) { // Admin
            $proyectoCapacidadInstalada = ProyectoCapacidadInstalada::select('proyectos_capacidad_instalada.*')
                ->distinct('proyectos_capacidad_instalada.id')
                ->orderBy('proyectos_capacidad_instalada.id', 'ASC')
                ->filterProyectoCapacidadInstalada(request()->only('search'))->paginate();
        } else {
            $proyectoCapacidadInstalada = ProyectoCapacidadInstalada::select('proyectos_capacidad_instalada.*')
                ->join('proyecto_capacidad_instalada_integrante', 'proyectos_capacidad_instalada.id', 'proyecto_capacidad_instalada_integrante.proyecto_capacidad_instalada_id')
                ->where('proyecto_capacidad_instalada_integrante.user_id', $authUser->id)
                ->distinct('proyectos_capacidad_instalada.id')
                ->orderBy('proyectos_capacidad_instalada.id', 'ASC')
                ->filterProyectoCapacidadInstalada(request()->only('search'))->paginate();
        }
        return $proyectoCapacidadInstalada;
    }

    /**
     * Get codigo e.g. SGPS-8000-2021
     *
     * @return string
     */
    public function getCodigoAttribute()
    {
        $numeroConsecutivo = sprintf("%05s", $this->id);
        $codigo = 'CAP-' . $numeroConsecutivo;

        return $codigo;
    }
}
