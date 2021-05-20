<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineaTecnologica extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'lineas_tecnologicas';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tecnoacademia_id',
        'nombre'
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
     * Relationship with Tecnoacademia
     *
     * @return object
     */
    public function tecnoacademias()
    {
        return $this->belongsToMany(Tecnoacademia::class, 'tecnoacademia_linea_tecnologica', 'linea_tecnologica_id', 'tecnoacademia_id');
    }

    /**
     * Relationship with IDi
     *
     * @return object
     */

    public function idi()
    {
        return $this->belongsToMany(IDi::class, 'idi_linea_tecnologica', 'tecnoacademia_linea_tecnologica_id', 'idi_id');
    }

    /**
     * Relationship with TaTp
     *
     * @return object
     */
    public function tatp()
    {
        return $this->hasMany('tecnoacademia_linea_tecnologica', 'linea_tecnologica_id', 'tecnoacademia_linea_tecnologica_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterLineaTecnologica($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre', 'ilike', '%' . $search . '%');
        });
    }
}
