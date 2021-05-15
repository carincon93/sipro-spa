<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    use HasFactory;

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['start_year', 'start_month', 'start_day', 'end_year', 'end_month', 'end_day'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_result_id',
        'name',
        'fecha_inicio',
        'fecha_finalizacion'
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
     * Relationship with ProjectResult
     *
     * @return void
     */
    public function projectResult()
    {
        return $this->belongsTo(ProjectResult::class);
    }

    /**
     * Relationship with RDIOutput
     *
     * @return void
     */
    public function rdiOutput()
    {
        return $this->hasOne(RDIOutput::class);
    }

        /**
     * Relationship with Activity
     *
     * @return void
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_output', 'output_id', 'activity_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterOutput($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'ilike', '%'.$search.'%');
        });
    }

    public function getStartYearAttribute()
    {
        return date('Y', strtotime($this->fecha_inicio));
    }

    public function getStartMonthAttribute()
    {
        return date('m', strtotime($this->fecha_inicio));
    }

    public function getStartDayAttribute()
    {
        return date('d', strtotime($this->fecha_inicio));
    }

    public function getEndYearAttribute()
    {
        return date('Y', strtotime($this->fecha_finalizacion));
    }

    public function getEndMonthAttribute()
    {
        return date('m', strtotime($this->fecha_finalizacion));
    }

    public function getEndDayAttribute()
    {
        return date('d', strtotime($this->fecha_finalizacion));
    }
}
