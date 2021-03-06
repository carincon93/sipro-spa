<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoSistemaGestion extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'estados_sistema_gestion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'estado',
        'tipo_proyecto'
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
     * Relationship with ServicioTecnologico
     *
     * @return void
     */
    public function serviciosTecnologicos()
    {
        return $this->belongsTo(ServicioTecnologico::class);
    }

    /**
     * Relationship with SecondRelatedModel
     *
     * @return void
     */
    public function secondRelatedModel()
    {
        return $this->hasOne(SecondRelatedModel::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterEstadoSistemaGestion($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('estado', 'ilike', '%' . $search . '%');
        });
    }
}
