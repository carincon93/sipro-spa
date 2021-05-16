<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiembroEntidadAliada extends Model
{
    use HasFactory;
        
    /**
     * table
     *
     * @var string
     */
    protected $table = 'miembros_entidad_aliada';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entidad_aliada_id',
        'nombre',
        'email',
        'tipo_documento',
        'numero_documento',
        'numero_celular'
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
     * Relationship with EntidadAliada
     *
     * @return void
     */
    public function EntidadAliada()
    {
        return $this->belongsTo(EntidadAliada::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterMiembroEntidadAliada($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre', 'ilike', '%'.$search.'%');
        });
    }
}
