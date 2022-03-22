<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoDemo extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyectos_demo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proyecto_id',
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
     * Relationship with Proyecto
     *
     * @return object
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoDemo($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('id', 'ilike', '%' . $search . '%');
        });
    }
}
