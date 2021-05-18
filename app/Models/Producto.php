<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'productos';

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['year_inicio', 'mes_inicio', 'dia_inicio', 'year_finalizacion', 'mes_finalizacion', 'dia_finalizacion'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resultado_id',
        'nombre',
        'fecha_inicio',
        'fecha_finalizacion'
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
     * Relationship with Resultado
     *
     * @return object
     */
    public function resultado()
    {
        return $this->belongsTo(Resultado::class);
    }

    /**
     * Relationship with IDiProducto
     *
     * @return object
     */
    public function idiProducto()
    {
        return $this->hasOne(IDiProducto::class);
    }

    /**
     * Relationship with Actividad
     *
     * @return object
     */
    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'actividad_producto', 'producto_id', 'actividad_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProducto($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre', 'ilike', '%' . $search . '%');
        });
    }

    public function getYearInicioAttribute()
    {
        return date('Y', strtotime($this->fecha_inicio));
    }

    public function getMesInicioAttribute()
    {
        return date('m', strtotime($this->fecha_inicio));
    }

    public function getDiaInicioAttribute()
    {
        return date('d', strtotime($this->fecha_inicio));
    }

    public function getYearFinalizacionAttribute()
    {
        return date('Y', strtotime($this->fecha_finalizacion));
    }

    public function getMEsFinalizacionAttribute()
    {
        return date('m', strtotime($this->fecha_finalizacion));
    }

    public function getDiaFinalizacionAttribute()
    {
        return date('d', strtotime($this->fecha_finalizacion));
    }
}
