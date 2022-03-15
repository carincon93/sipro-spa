<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoIdiTecnoacademiaProducto extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyecto_idi_tecnoacademia_productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proyecto_idi_tecnoacademia_id',
        'tipo_producto_idi_id',
        'estado',
        'soporte',
        'link',
        'lugar',
        'fecha_realizacion'
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
     * Relationship with ProyectoIdiTecnoacademia
     *
     * @return object
     */
    public function proyectoIdiTecnoacademia()
    {
        return $this->belongsTo(ProyectoIdiTecnoacademia::class);
    }

    /**
     * Relationship with TipoProductoIdi
     *
     * @return object
     */
    public function tipoProductoIdi()
    {
        return $this->belongsTo(TipoProductoIdi::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoIdiTecnoacademiaProducto($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('estado', 'ilike', '%' . $search . '%');
        });
    }
}
