<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProyectoIdiTecnoacademia extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyectos_idi_tecnoacademia';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['estado_formateado', 'fecha_ejecucion', 'codigo'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tecnoacademia_id',
        'semillero_investigacion_id',
        'proyecto_id',
        'resultados_obtenidos',
        'documentos_resultados',
        'observaciones_resultados',
        'titulo',
        'fecha_inicio',
        'fecha_finalizacion',
        'resumen',
        'palabras_clave',
        'especies',
        'tiene_linea_investigacion',
        'lineas_investigacion',
        'proyecto_nuevo',
        'proyecto_con_continuidad',
        'productos_premios',
        'texto_exposicion',
        'pdf_proyecto',
        'nombre_aprendices_vinculados',
        'nombre_instituciones_educativas',
        'nuevas_instituciones_educativas',
        'programa_sennova_participante',
        'programa_formacion_articulado_media',
        'entidades_vinculadas',
        'fuente_recursos',
        'presupuesto',
        'hace_parte_de_semillero',
        'estado_proyecto',
        'poblacion_beneficiada',
        'nombre_centro_programa'
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
     * Relationship with Tecnoacademia
     *
     * @return object
     */
    public function tecnoacademia()
    {
        return $this->belongsTo(Tecnoacademia::class);
    }

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
     * Relationship with Proyecto
     *
     * @return object
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Relationship with ProgramaSennova
     *
     * @return object
     */
    public function programasSennova()
    {
        return $this->belongsToMany(ProgramaSennova::class, 'proyecto_idi_tecnoacademia_programa_sennova', 'proyecto_idi_tecnoacademia_id', 'programa_sennova_id');
    }

    /**
     * Relationship with ProyectoIdiTecnoacademiaBeneficiado
     *
     * @return object
     */
    public function beneficiados()
    {
        return $this->belongsToMany(TipoBeneficiadoTa::class, 'proyecto_idi_tecnoacademia_beneficiados', 'proyecto_idi_tecnoacademia_id', 'beneficiado');
    }

    /**
     * Relationship with ProyectoIdiTecnoacademiaLinea
     *
     * @return object
     */
    public function lineas()
    {
        return $this->belongsToMany(TecnoacademiaLineaTecnoacademia::class, 'proyecto_idi_tecnoacademia_linea', 'proyecto_idi_tecnoacademia_id', 'tecnoacademia_linea_tecnoacademia_id');
    }

    /**
     * Relationship with ProyectoIdiTecnoacademiaParticipante
     *
     * @return object
     */
    public function participantes()
    {
        return $this->belongsToMany(User::class, 'proyecto_idi_tecnoacademia_participante', 'proyecto_idi_tecnoacademia_id', 'user_id')->withPivot('rol_sennova', 'cantidad_meses', 'cantidad_horas', 'autor_principal');
    }

    /**
     * Relationship with ProyectoIdiTecnoacademiaProducto
     *
     * @return object
     */
    public function productos()
    {
        return $this->hasMany(ProyectoIdiTecnoacademiaProducto::class);
    }

    /**
     * Relationship with Municipio
     *
     * @return object
     */
    public function municipios()
    {
        return $this->belongsToMany(Municipio::class, 'proyecto_idi_tecnoacademia_municipio', 'proyecto_idi_tecnoacademia_linea_id', 'municipio_id')->orderBy('municipios.nombre', 'ASC');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoIdiTecnoacademia($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('titulo', 'ilike', '%' . $search . '%');
        });
    }

    /**
     * getEstadoFormateadoAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getEstadoFormateadoAttribute()
    {
        switch ($this->estado_proyecto) {
            case 1:
                $this->estado_proyecto = 'En diseño';
                break;
            case 2:
                $this->estado_proyecto = 'Iniciando ejecución';
                break;
            case 3:
                $this->estado_proyecto = 'En ejecución, entre 2 a 11 meses';
                break;
            case 4:
                $this->estado_proyecto = 'En ejecución de 11 meses a 23 meses';
                break;
            case 5:
                $this->estado_proyecto = 'En ejecución más de 23 meses';
                break;
            case 6:
                $this->estado_proyecto = 'En cierre';
                break;
            default:
                break;
        }
        return $this->estado_proyecto;
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
     * @return object
     */
    public static function getProyectosPorRol()
    {
        $authUser = Auth::user();
        if ($authUser->hasRole(1)) { // Admin
            $proyectoIdiTecnoacademia = ProyectoIdiTecnoacademia::distinct('proyectos_idi_tecnoacademia.id')
                ->orderBy('proyectos_idi_tecnoacademia.id', 'ASC')
                ->filterProyectoIdiTecnoacademia(request()->only('search'))->paginate();
        } else {
            $proyectoIdiTecnoacademia = ProyectoIdiTecnoacademia::select('proyectos_idi_tecnoacademia.*')->join('proyecto_idi_tecnoacademia_participante', 'proyectos_idi_tecnoacademia.id', 'proyecto_idi_tecnoacademia_participante.proyecto_idi_tecnoacademia_id')
                ->where('proyecto_idi_tecnoacademia_participante.user_id', $authUser->id)
                ->distinct('proyectos_idi_tecnoacademia.id')
                ->orderBy('proyectos_idi_tecnoacademia.id', 'ASC')
                ->filterProyectoIdiTecnoacademia(request()->only('search'))->paginate();
        }
        return $proyectoIdiTecnoacademia;
    }

    /**
     * Get codigo e.g. IDITA-00001
     *
     * @return string
     */
    public function getCodigoAttribute()
    {
        $numeroConsecutivo = sprintf("%05s", $this->id);
        $codigo = 'IDITA-' . $numeroConsecutivo;

        return $codigo;
    }

    /**
     *
     * @return string
     */
    public function filename($path)
    {
        $pathExplode = explode("/", $this->{$path});

        return end($pathExplode);
    }
}
