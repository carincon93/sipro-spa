<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareInfo extends Model
{
    use HasFactory;

    protected $table = 'software_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_licencia',
        'tipo_software',
        'fecha_inicio',
        'fecha_finalizacion',
        'proyecto_presupuesto_id'
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
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterSoftwareInfo($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('tipo_licencia', 'ilike', '%'.$search.'%');
        });
    }
}
