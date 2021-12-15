<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroFormacion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'centros_formacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'regional_id',
        'subdirector_id',
        'dinamizador_sennova_id',
        'nombre',
        'codigo',
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
     * Relationship with Regional
     *
     * @return object
     */
    public function regional()
    {
        return $this->belongsTo(Regional::class);
    }

    /**
     * Relationship with User
     *
     * @return object
     */
    public function subdirector()
    {
        return $this->belongsTo(User::class, 'subdirector_id');
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
     * Relationship with Proyecto
     *
     * @return object
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

    /**
     * Relationship with AmbienteModernizacion
     *
     * @return object
     */
    public function ambientesModernizacion()
    {
        return $this->hasMany(AmbienteModernizacion::class);
    }

    /**
     * Relationship with ProgramaFormacion
     *
     * @return object
     */
    public function programasFormacion()
    {
        return $this->hasMany(ProgramaFormacion::class);
    }

    /**
     * Relationship with GrupoInvestigacion
     *
     * @return object
     */
    public function gruposInvestigacion()
    {
        return $this->hasMany(GrupoInvestigacion::class);
    }

    /**
     * Relationship with NodoTecnoparque
     *
     * @return object
     */
    public function nodosTecnoparque()
    {
        return $this->hasMany(NodoTecnoparque::class);
    }

    /**
     * Relationship with User
     *
     * @return object
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relationship with Tecnoacademia
     *
     * @return object
     */
    public function tecnoacademias()
    {
        return $this->hasMany(Tecnoacademia::class);
    }

    /**
     * Relationship with TipoProyectoSt
     *
     * @return object
     */
    public function tiposProyectoSt()
    {
        return $this->hasMany(TipoProyectoSt::class);
    }

    /**
     * Relationship with ReglaRolCultura
     *
     * @return object
     */
    public function reglasRolesCultura()
    {
        return $this->hasMany(ReglaRolCultura::class);
    }

    /**
     * Relationship with CodigoProyectoSgps
     *
     * @return void
     */
    public function codigosProyectosSgps()
    {
        return $this->hasMany(CodigoProyectoSgps::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterCentroFormacion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->join('regionales', 'centros_formacion.regional_id', 'regionales.id');
            $query->whereRaw("unaccent(centros_formacion.nombre) ilike unaccent('%" . $search . "%')");
            $query->orWhere('centros_formacion.codigo', 'ilike', '%' . $search . '%');
            $query->orWhereRaw("unaccent(regionales.nombre) ilike unaccent('%" . $search . "%')");
        });
    }
}
