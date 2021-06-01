<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesaTecnica extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'mesas_tecnicas';


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
     * Relationship with SectorProductivo
     *
     * @return object
     */
    public function sectoresProductivos()
    {
        return $this->belongsToMany(SectorProductivo::class, 'mesa_tecnica_sector_productivo', 'mesa_tecnica_id', 'sector_productivo_id');
    }

    /**
     * Relationship with TemaPriorizado
     *
     * @return object
     */
    public function temasPriorizados()
    {
        return $this->hasMany(TemaPriorizado::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterMesaTecnica($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre', 'ilike', '%' . $search . '%');
        });
    }
}
