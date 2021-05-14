<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificObjective extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'direct_cause_id',
        'description',
        'number'
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
     * Relationship with DirectCause
     *
     * @return void
     */
    public function directCause()
    {
        return $this->belongsTo(DirectCause::class);
    }

    /**
     * Relationship with ProjectResult
     *
     * @return void
     */
    public function projectResult()
    {
        return $this->hasOne(ProjectResult::class);
    }

     /**
     * Relationship with Activity
     *
     * @return void
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterSpecificObjective($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'ilike', '%'.$search.'%');
        });
    }

    public function getNumberAttribute($value)
    {
        switch ($value) {
            case 1:
                $value = 'Primer objetivo específico';
                break;
            case 2:
                $value = 'Segundo objetivo específico';
                break;
            case 3:
                $value = 'Tercer objetivo específico';
                break;
            case 4:
                $value = 'Cuarto objetivo específico';
                break;
            default:
                break;
        }

        return $value;
    }
}
