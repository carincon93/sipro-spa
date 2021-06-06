<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadAliadaIdi extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'entidad_aliada_idi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entidad_aliada_id',
        'descripcion_convenio',
        'grupo_investigacion',
        'codigo_gruplac',
        'enlace_gruplac',
        'actividades_transferencia_conocimiento',
        'carta_intencion',
        'carta_propiedad_intelectual',
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
     * @return object
     */
    public function entidadAliada()
    {
        return $this->belongsTo(EntidadAliada::class);
    }
}
