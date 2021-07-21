<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglaRolSt extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'reglas_roles_st';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rol_sennova_id',
        'tipo_proyecto_st_id',
        'maximo'
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
     * Relationship with RolSennova
     *
     * @return object
     */
    public function rolSennova()
    {
        return $this->belongsTo(RolSennova::class);
    }

    /**
     * Relationship with TipoProyectoSt
     *
     * @return object
     */
    public function tipoProyectoSt()
    {
        return $this->belongsTo(TipoProyectoSt::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterReglaRolSt($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('roles_sennova.nombre', 'ilike', '%' . $search . '%');
        });
    }
}
