<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoIdiTecnoacademiaLinea extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'proyecto_idi_tecnoacademia_linea';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proyecto_idi_tecnoacademia_id',
        'tecnoacademia_linea_tecnoacademia_id'
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
     * Relationship with ProyectoIdiTecnoacademia
     *
     * @return object
     */
    public function proyectoIdiTecnoacademia()
    {
        return $this->belongsTo(ProyectoIdiTecnoacademia::class);
    }

    /**
     * Relationship with TecnoacademiaLineaTecnoacademia
     *
     * @return object
     */
    public function tecnoacademiaLineaTecnoacademia()
    {
        return $this->belongsTo(TecnoacademiaLineaTecnoacademia::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoIdiTecnoacademiaLinea($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('tecnoacademia_linea_tecnoacademia_id', 'ilike', '%' . $search . '%');
        });
    }
}
