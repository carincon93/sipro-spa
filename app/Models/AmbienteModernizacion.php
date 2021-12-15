<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbienteModernizacion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'ambientes_modernizacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'centro_formacion_id',
        'codigo_proyecto_id',
        'tipologia_ambiente_id',
        'linea_investigacion_id',
        'nombre_ambiente',
        'disciplina_subarea_conocimiento_id',
        'red_conocimiento_id',
        'actividad_economica_id',
        'tematica_estrategica_id',
        'alineado_mesas_sectoriales',
        'financiado_anteriormente',
        'estado_general_maquinaria',
        'razon_estado_general',
        'ambiente_activo',
        'justificacion_ambiente_inactivo',
        'ambiente_activo_procesos_idi',
        'numero_proyectos_beneficiados',
        'ambiente_formacion_complementaria',
        'numero_total_cursos_comp',
        'numero_cursos_empresas',
        'datos_empresa',
        'cursos_complementarios',
        'coordenada_latitud_ambiente',
        'coordenada_longitud_ambiente',
        'impacto_procesos_formacion',
        'pertinencia_sector_productivo',
        'palabras_clave_ambiente',
        'observaciones_generales_ambiente',
        'soporte_fotos_ambiente',
        'dinamizador_sennova_id'
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
     * Relationship with centroFormacion
     *
     * @return object
     */
    public function centroFormacion()
    {
        return $this->belongsTo(CentroFormacion::class);
    }

    /**
     * Relationship with CodigoProyectoSgps
     *
     * @return object
     */
    public function codigoProyectoSgps()
    {
        return $this->belongsTo(CodigoProyectoSgps::class);
    }

    /**
     * Relationship with tipologiaAmbiente
     *
     * @return object
     */
    public function tipologiaAmbiente()
    {
        return $this->belongsTo(TipologiaAmbiente::class);
    }

    /**
     * Relationship with disciplinaSubareaConocimiento
     *
     * @return object
     */
    public function disciplinaSubareaConocimiento()
    {
        return $this->belongsTo(DisciplinaSubareaConocimiento::class);
    }

    /**
     * Relationship with redConocimiento
     *
     * @return object
     */
    public function redConocimiento()
    {
        return $this->belongsTo(RedConocimiento::class);
    }

    /**
     * Relationship with actividadEconomica
     *
     * @return object
     */
    public function actividadEconomica()
    {
        return $this->belongsTo(ActividadEconomica::class);
    }

    /**
     * Relationship with lineaInvestigacion
     *
     * @return object
     */
    public function lineaInvestigacion()
    {
        return $this->belongsTo(LineaInvestigacion::class);
    }

    /**
     * Relationship with tematicaEstrategica
     *
     * @return object
     */
    public function tematicaEstrategica()
    {
        return $this->belongsTo(TematicaEstrategica::class);
    }

    /**
     * Relationship with User
     *
     * @return object
     */
    public function dinamizadorSennova()
    {
        return $this->belongsTo(User::class, 'dinamizador_sennova_id');
    }

    /**
     * Relationship with MesaSectorial
     *
     * @return object
     */
    public function mesasSectoriales()
    {
        return $this->belongsToMany(MesaSectorial::class, 'ambiente_modernizacion_mesa_sectorial', 'ambiente_modernizacion_id', 'mesa_sectorial_id');
    }

    /**
     * Relationship with CodigoProyectoSgps
     *
     * @return object
     */
    public function codigosProyectosSgps()
    {
        return $this->belongsToMany(CodigoProyectoSgps::class, 'ambiente_modernizacion_cod_proyectos', 'ambiente_modernizacion_id', 'codigo_proyecto_sgps_id');
    }

    /**
     * Relationship with CodigoProyectoSgps
     *
     * @return object
     */
    public function codigosProyectosSgpsBeneficiados()
    {
        return $this->belongsToMany(CodigoProyectoSgps::class, 'ambiente_modernizacion_cod_proyectos_beneficiados', 'ambiente_modernizacion_id', 'codigo_proyecto_sgps_id');
    }

    /**
     * Relationship with ProgramaFormacion
     *
     * @return object
     */
    public function programasFormacionCalificados()
    {
        return $this->belongsToMany(ProgramaFormacion::class, 'ambiente_modernizacion_programa_calificado', 'ambiente_modernizacion_id', 'programa_formacion_calificado_id');
    }

    /**
     * Relationship with ProgramaFormacionArticulado
     *
     * @return object
     */
    public function programasFormacionNoCalificados()
    {
        return $this->belongsToMany(ProgramaFormacionArticulado::class, 'ambiente_modernizacion_programa_sin_calificado', 'ambiente_modernizacion_id', 'programa_formacion_sin_calificado_id');
    }

    /**
     * Relationship with SemilleroInvestigacion
     *
     * @return object
     */
    public function semilerosInvestigacion()
    {
        return $this->belongsToMany(SemilleroInvestigacion::class, 'ambiente_modernizacion_semillero_investigacion', 'ambiente_modernizacion_id', 'semillero_investigacion_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterAmbienteModernizacion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre_ambiente', 'ilike', '%' . $search . '%');
        });
    }
}
