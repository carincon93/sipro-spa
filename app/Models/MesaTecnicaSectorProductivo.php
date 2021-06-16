<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesaTecnicaSectorProductivo extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'mesa_tecnica_sector_productivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mesa_tecnica_id',
        'sector_productivo_id'
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
     * @return void
     */
    public function sectorProductivo()
    {
        return $this->belongsTo(SectorProductivo::class);
    }

    /**
     * Relationship with MesaTecnica
     *
     * @return void
     */
    public function mesaTecnica()
    {
        return $this->belongsTo(MesaTecnica::class);
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
    public function scopeFilterMesaTecnicaSectorProductivo($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('replace', 'ilike', '%' . $search . '%');
        });
    }
}
