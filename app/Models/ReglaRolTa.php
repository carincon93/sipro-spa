<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglaRolTa extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'reglas_roles_ta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rol_sennova_id',
        'tecnoacademia_id',
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
     * Relationship with Tecnoacademia
     *
     * @return object
     */
    public function tecnoacademia()
    {
        return $this->belongsTo(Tecnoacademia::class);
    }

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
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterReglaRolTa($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('replace', 'ilike', '%' . $search . '%');
        });
    }
}
