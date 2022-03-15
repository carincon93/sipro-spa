<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaSennova extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'programas_sennova';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
     * Relationship with ProyectoIdiTecnoacademia
     *
     * @return object
     */
    public function proyectosIdiTecnoacademia()
    {
        return $this->belongsToMany(ProyectoIdiTecnoacademia::class, 'proyecto_idi_tecnoacademia_programa_sennova', 'programa_sennova_id', 'proyecto_idi_tecnoacademia_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProyectoIdiTecnoacademiaProgramaSennova($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre', 'ilike', '%' . $search . '%');
        });
    }
}
