<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudioMercado extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'estudios_mercado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proyecto_lote_estudio_mercado_id',
        'valor',
        'empresa',
        'soporte'
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
     * Relationship with ProyectoLoteEstudioMercado
     *
     * @return void
     */
    public function proyectoLoteEstudioMercado()
    {
        return $this->belongsTo(ProyectoLoteEstudioMercado::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterEstudioMercado($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('company_name', 'ilike', '%'.$search.'%');
        });
    }
}
