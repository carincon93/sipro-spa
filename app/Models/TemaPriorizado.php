<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemaPriorizado extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'temas_priorizados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'sector_productivo_id',
        'mesa_tecnica_id'
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
    public function sectorProductivo()
    {
        return $this->belongsTo(SectorProductivo::class);
    }

    /**
     * Relationship with MesaTecnica
     *
     * @return object
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
    public function scopeFilterTemaPriorizado($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre', 'ilike', '%' . $search . '%');
        });
    }
}
