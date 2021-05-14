<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAnalysis extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'risk_analysis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level',
        'type',
        'description',
        'impact',
        'probability',
        'effects',
        'mitigation_measures'
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
     * Relationship with Project
     *
     * @return void
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterRiskAnalysis($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('description', 'ilike', '%'.$search.'%');
        });
    }

    /**
     * getLevelAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getLevelAttribute($value)
    {
        switch ($value) {
            case 1:
                $value = 'A nivel del objetivo general';
                break;
            case 2:
                $value = 'A nivel de productos';
                break;
            case 3:
                $value = 'A nivel de actividades';
                break;
            default:
                break;
        }
        return $value;
    }

    /**
     * getTypeAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getTypeAttribute($value)
    {
        switch ($value) {
            case 1:
                $value = 'Mercado';
                break;
            case 2:
                $value = 'Operacionales';
                break;
            case 3:
                $value = 'Legales';
                break;
            case 4:
                $value = 'Administrativos';
                break;
            default:
                break;
        }
        return $value;
    }

    /**
     * getProbabilityAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getProbabilityAttribute($value)
    {
        switch ($value) {
            case 1:
                $value = 'Posible';
                break;
            case 2:
                $value = 'Probable';
                break;
            default:
                break;
        }
        return $value;
    }

    /**
     * getImpactAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getImpactAttribute($value)
    {
        switch ($value) {
            case 1:
                $value = 'Alto';
                break;
            case 2:
                $value = 'Moderado';
                break;
            case 3:
                $value = 'Leve';
                break;
            default:
                break;
        }

        return $value;
    }
}
