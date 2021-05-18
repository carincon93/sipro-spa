<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineaProgramatica extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'lineas_programaticas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'categoria_proyecto'
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
     * Relationship with TipoProyecto
     *
     * @return object
     */
    public function tiposProyecto()
    {
        return $this->hasMany(TipoProyecto::class);
    }

    /**
     * Relationship with SennovaBudget
     *
     * @return object
     */
    public function sennovaBudgets()
    {
        return $this->hasMany(SennovaBudget::class);
    }

    /**
     * Relationship with CallRolSennova
     *
     * @return object
     */
    public function callRolSennovas()
    {
        return $this->hasMany(CallRolSennova::class);
    }

    /**
     * Relationship with Anexo
     *
     * @return object
     */
    public function anexos()
    {
        return $this->belongsToMany(Anexo::class, 'anexos_linea_programatica', 'linea_programatica_id', 'anexo_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterLineaProgramatica($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('nombre', 'ilike', '%' . $search . '%');
        });
    }
}
