<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LineaInvestigacion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'lineas_investigacion';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grupo_investigacion_id',
        'nombre',
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
     * Relationship with GrupoInvestigacion
     *
     * @return object
     */
    public function grupoInvestigacion()
    {
        return $this->belongsTo(GrupoInvestigacion::class);
    }

    /**
     * Relationship with SemilleroInvestigacion
     *
     * @return object
     */
    public function semillerosInvestigacion()
    {
        return $this->hasMany(SemilleroInvestigacion::class);
    }

    /**
     * Relationship with Idi
     *
     * @return object
     */
    public function idi()
    {
        return $this->hasMany(Idi::class);
    }

    /**
     * Relationship with CulturaInnovacion
     *
     * @return object
     */
    public function culturaInnovacion()
    {
        return $this->hasMany(CulturaInnovacion::class);
    }

    /**
     * Relationship with Proyecto
     *
     * @return object
     */
    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_linea_investigacion', 'linea_investigacion_id', 'proyecto_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterLineaInvestigacion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace(' ', '%%', $search);
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $query->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id');
            $query->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id');
            $query->whereRaw("unaccent(lineas_investigacion.nombre) ilike unaccent('%" . $search . "%')");
            $query->orWhereRaw("unaccent(grupos_investigacion.nombre) ilike unaccent('%" . $search . "%')");
            $query->orWhereRaw("unaccent(centros_formacion.nombre) ilike unaccent('%" . $search . "%')");
        });
    }

    /**
     * getUsersByRol
     *
     * @return object
     */
    public static function getLineasInvestigacionByRol()
    {
        $user = Auth::user();
        if ($user->hasRole(1)) {
            $lineasInvestigacion = LineaInvestigacion::select('lineas_investigacion.id', 'lineas_investigacion.nombre', 'lineas_investigacion.grupo_investigacion_id')->with('grupoInvestigacion')->filterLineaInvestigacion(request()->only('search'))->orderBy('lineas_investigacion.nombre', 'ASC')->paginate();
        } else if ($user->hasRole([4, 21])) {
            $centroFormacionId = null;
            if ($user->dinamizadorCentroFormacion()->exists()) {
                $centroFormacionId = $user->dinamizadorCentroFormacion->id;
            } else if ($user->hasRole(21)) {
                $centroFormacionId = $user->centroFormacion->id;
            }

            $lineasInvestigacion = LineaInvestigacion::select('lineas_investigacion.id', 'lineas_investigacion.nombre', 'lineas_investigacion.grupo_investigacion_id')->with('grupoInvestigacion.centroFormacion')
                ->whereHas(
                    'grupoInvestigacion.centroFormacion',
                    function ($query) use ($centroFormacionId) {
                        $query->where('id', $centroFormacionId);
                    }
                )
                ->filterLineaInvestigacion(request()->only('search'))->paginate();
        }

        return $lineasInvestigacion;
    }
}
