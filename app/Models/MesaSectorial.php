<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesaSectorial extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'mesas_sectoriales';

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
        return $this->belongsToMany(Idi::class, 'idi_mesa_sectorial', 'mesa_sectorial_id', 'idi_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterMesaSectorial($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->whereRaw("unaccent(nombre) ilike unaccent('%" . $search . "%')");
        });
    }

    /**
     * getNombreAttribute
     *
     * @return void
     */
    public function getNombreAttribute($value)
    {
        return ucfirst($value);
    }
}
