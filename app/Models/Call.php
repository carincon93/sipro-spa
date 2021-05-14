<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Call extends Model
{
    use HasFactory;

    public $appends = ['year', 'fecha_incio_for_humans', 'fecha_finalizacion_for_humans'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'fecha_incio',
        'fecha_finalizacion',
        'active',
        'project_fecha_incio',
        'project_fecha_finalizacion',
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
     * Relationship with RDI
     *
     * @return void
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Relationship with CallBudget
     *
     * @return void
     */
    public function callBudgets()
    {
        return $this->hasMany(CallBudget::class);
    }

    /**
     * Relationship with CallSennovaRole
     *
     * @return void
     */
    public function callSennovaRoles()
    {
        return $this->hasMany(CallSennovaRole::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterCall($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('fecha_incio', 'ilike', '%'.$search.'%');
        });
    }

    /**
     * getYearAttribute
     *
     * @return void
     */
    public function getYearAttribute()
    {
        return date('Y', strtotime($this->fecha_finalizacion));
    }

    /**
     * getStartDateForHumansAttribute
     *
     * @return void
     */
    public function getStartDateForHumansAttribute()
    {
        return Carbon::parse($this->fecha_incio, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY');
    }

    /**
     * getEndDateForHumansAttribute
     *
     * @return void
     */
    public function getEndDateForHumansAttribute()
    {
        return Carbon::parse($this->fecha_finalizacion, 'UTC')->locale('es')->isoFormat('DD [de] MMMM [de] YYYY');
    }
}
