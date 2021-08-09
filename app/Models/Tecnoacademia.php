<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnoacademia extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'tecnoacademias';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['suma_max_valores'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'modalidad',
        'fecha_creacion',
        'foco',
        'linea_tecnoacademia_id',
        'centro_formacion_id',
        'max_valor_viaticos_interior',
        'max_valor_edt',
        'max_valor_mantenimiento_equipos',
        'max_valor_roles'

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
     * Relationship with CentroFormacion
     *
     * @return object
     */
    public function centroFormacion()
    {
        return $this->belongsTo(CentroFormacion::class);
    }

    /**
     * Relationship with LineaTecnoacademia
     *
     * @return object
     */
    public function lineasTecnoacademia()
    {
        return $this->belongsToMany(LineaTecnoacademia::class, 'tecnoacademia_linea_tecnoacademia', 'tecnoacademia_id', 'linea_tecnoacademia_id');
    }

    /**
     * Relationship with ReglaRolTa
     *
     * @return void
     */
    public function reglasRolesTa()
    {
        return $this->hasMany(ReglaRolTa::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterTecnoacademia($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->join('centros_formacion', 'tecnoacademias.centro_formacion_id', 'centros_formacion.id');
            $query->whereRaw("unaccent(tecnoacademias.nombre) ilike unaccent('%" . $search . "%')");
            $query->whereRaw("unaccent(centros_formacion.nombre) ilike unaccent('%" . $search . "%')");
        });
    }

    public function getSumaMaxValoresAttribute()
    {
        return $this->max_valor_viaticos_interior + $this->max_valor_edt + $this->max_valor_mantenimiento_equipos + $this->max_valor_roles;
    }
}
