<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TematicaEstrategica extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'tematicas_estrategicas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
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
     * Relationship with ServicioTecnologico
     *
     * @return object
     */
    public function serviciosTecnologicos()
    {
        return $this->hasMany(ServicioTecnologico::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterTematicaEstrategica($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->whereRaw("unaccent(nombre) ilike unaccent('%" . $search . "%')");
        });
    }
}
