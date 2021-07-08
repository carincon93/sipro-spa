<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubtipologiaMinciencias extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'subtipologias_minciencias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipologica_minciencias_id',
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
     * Relationship with TipologiaMinciencias
     *
     * @return object
     */
    public function tipologiaMinciencias()
    {
        return $this->belongsTo(TipologiaMinciencias::class);
    }

    /**
     * Relationship with ProductoIdi
     *
     * @return object
     */
    public function productosIdi()
    {
        return $this->hasMany(ProductoIdi::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterSubtipologiaMinciencias($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = str_replace('"', "", $search);
            $search = str_replace("'", "", $search);
            $search = str_replace(' ', '%%', $search);
            $query->whereRaw("unaccent(nombre) ilike unaccent('%" . $search . "%')");
        });
    }
}
