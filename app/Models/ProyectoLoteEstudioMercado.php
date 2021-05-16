<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoLoteEstudioMercado extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyecto_lote_estudio_mercado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proyecto_presupuesto_id',
        'numero_items',
        'ficha_tecnica'
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
     * Relationship with ProyectoPresupuesto
     *
     * @return void
     */
    public function proyectoPresupuesto()
    {
        return $this->belongsTo(ProyectoPresupuesto::class);
    }

    /**
     * Relationship with EstudioMercado
     *
     * @return void
     */
    public function estudiosMercado()
    {
        return $this->hasMany(EstudioMercado::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoLoteEstudioMercado($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('numero_items', 'ilike', '%'.$search.'%');
        });
    }

    /**
     * getPromedioAttribute
     *
     * @return void
     */
    public function getPromedioAttribute()
    {
        $promedio    = 0;

        foreach ($this->estudiosMercado as $estudioMercado) {
            $promedio += (int) $estudioMercado->valor;
        }

        return $this->estudioMercado->count() > 0 ? ($promedio / $this->estudioMercado->count()) : 0;
    }
}
