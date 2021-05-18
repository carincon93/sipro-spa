<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CausaIndirecta extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'causas_indirectas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'causa_directa_id',
        'descripcion'
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
     * Relationship with CausaDirecta
     *
     * @return object
     */
    public function causaDirecta()
    {
        return $this->belongsTo(CausaDirecta::class)->orderBy('id', 'ASC');
    }

    /**
     * Relationship with Actividad
     *
     * @return object
     */
    public function actividad()
    {
        return $this->hasOne(Actividad::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterCausaIndirecta($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('descripcion', 'ilike', '%' . $search . '%');
        });
    }
}
