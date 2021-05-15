<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoRolSennova extends Model
{
    use HasFactory;
        
    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyecto_rol_sennova';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proyecto_id',
        'convocatoria_rol_sennova_id',
        'descripcion',
        'numero_meses',
        'numero_roles'
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
     * @return void
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Relationship with ConvocatoriaRolSennova
     *
     * @return void
     */
    public function convocatoriaRolSennova()
    {
        return $this->belongsTo(ConvocatoriaRolSennova::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoRolSennova($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->join('convocatoria_roles_sennova', 'proyecto_roles_sennova.convocatoria_sennova_role_id', 'convocatoria_roles_sennova.id')
                ->join('roles_sennova', 'convocatoria_roles_sennova.sennova_role_id', 'roles_sennova.id')
                ->where('convocatoria_roles_sennova.salary', 'ilike', '%'.$search.'%')->orWhere('roles_sennova.nombre', 'ilike', '%'.$search.'%');
        });
    }
}
