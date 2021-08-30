<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SemilleroInvestigacion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'semilleros_investigacion';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'linea_investigacion_id',
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
     * Relationship with LineaInvestigacion
     *
     * @return object
     */
    public function lineaInvestigacion()
    {
        return $this->belongsTo(LineaInvestigacion::class);
    }

    /**
     * Relationship with Proyecto
     *
     * @return object
     */
    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_semillero_investigacion', 'semillero_investigacion_id', 'proyecto_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterSemilleroInvestigacion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace(' ', '%%', $search);
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $query->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id');
            $query->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id');
            $query->whereRaw("unaccent(semilleros_investigacion.nombre) ilike unaccent('%" . $search . "%')");
            $query->orWhereRaw("unaccent(lineas_investigacion.nombre) ilike unaccent('%" . $search . "%')");
            $query->orWhereRaw("unaccent(grupos_investigacion.nombre) ilike unaccent('%" . $search . "%')");
        });
    }

    /**
     * getSemillerosInvestigacionByRol
     *
     * @return object
     */
    public static function getSemillerosInvestigacionByRol()
    {
        $user = Auth::user();
        if ($user->hasRole(1)) {
            $semilerosInvestigacion = SemilleroInvestigacion::select('semilleros_investigacion.id', 'semilleros_investigacion.nombre', 'semilleros_investigacion.linea_investigacion_id')->with('lineaInvestigacion', 'lineaInvestigacion.grupoInvestigacion')->filterSemilleroInvestigacion(request()->only('search'))->orderBy('semilleros_investigacion.nombre', 'ASC')->paginate();
        } else if ($user->hasRole([4, 21])) {
            $centroFormacionId = null;
            if ($user->dinamizadorCentroFormacion()->exists()) {
                $centroFormacionId = $user->dinamizadorCentroFormacion->id;
            } else if ($user->hasRole(21)) {
                $centroFormacionId = $user->centroFormacion->id;
            }

            $semilerosInvestigacion = SemilleroInvestigacion::select('semilleros_investigacion.id', 'semilleros_investigacion.nombre', 'semilleros_investigacion.linea_investigacion_id')->with('lineaInvestigacion', 'lineaInvestigacion.grupoInvestigacion.centroFormacion')
                ->whereHas(
                    'lineaInvestigacion.grupoInvestigacion.centroFormacion',
                    function ($query) use ($centroFormacionId) {
                        $query->where('id', $centroFormacionId);
                    }
                )
                ->filterSemilleroInvestigacion(request()->only('search'))->paginate();
        }

        return $semilerosInvestigacion;
    }
}
