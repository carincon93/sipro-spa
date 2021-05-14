<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['start_year', 'start_month', 'start_day', 'end_year', 'end_month', 'end_day'];

    /**
     * table
     *
     * @var string
     */
    protected $table = 'activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'specific_objective_id',
        'indirect_cause_id',
        'fecha_incio',
        'fecha_finalizacion',
        'description',
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
     * Relationship with SpecificObjective
     *
     * @return void
     */
    public function specificObjective()
    {
        return $this->belongsTo(SpecificObjective::class);
    }

    /**
     * Relationship with IndirectCause
     *
     * @return void
     */
    public function indirectCause()
    {
        return $this->belongsTo(IndirectCause::class);
    }

    /**
     * Relationship with Output
     *
     * @return void
     */
    public function outputs()
    {
        return $this->belongsToMany(Output::class, 'activity_output', 'activity_id', 'output_id');
    }

    /**
     * Relationship with ProjectSennovaBudget
     *
     * @return void
     */
    public function projectSennovaBudgets()
    {
        return $this->belongsToMany(ProjectSennovaBudget::class, 'activity_project_sennova_budget', 'activity_id', 'project_sennova_budget_id');
    }

    /**
     * Relationship with PartnerOrganization
     *
     * @return void
     */
    public function partnerOrganizations()
    {
        return $this->belongsToMany(PartnerOrganization::class, 'activity_partner_organization', 'activity_id', 'partner_organization_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterActivity($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('description', 'ilike', '%'.$search.'%');
        });
    }

    public function getStartYearAttribute()
    {
        return date('Y', strtotime($this->fecha_incio));
    }

    public function getStartMonthAttribute()
    {
        return date('m', strtotime($this->fecha_incio));
    }

    public function getStartDayAttribute()
    {
        return date('d', strtotime($this->fecha_incio));
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
