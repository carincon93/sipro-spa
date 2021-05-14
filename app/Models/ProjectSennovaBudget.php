<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSennovaBudget extends Model
{
    use HasFactory;

    protected $appends = ['average'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'call_budget_id',
        'description',
        'justification',
        'value',
        'qty_items'
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
     * Relationship with CallBudget
     *
     * @return void
     */
    public function callBudget()
    {
        return $this->belongsTo(CallBudget::class);
    }

    /**
     * Relationship with ProjectBudgetBatch
     *
     * @return void
     */
    public function projectBudgetBatches()
    {
        return $this->hasMany(ProjectBudgetBatch::class);
    }

    /**
     * Relationship with Activity
     *
     * @return void
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_project_sennova_budget', 'project_sennova_budget_id', 'activity_id');
    }

    /**
     * Relationship with SoftwareInfo
     *
     * @return void
     */
    public function softwareInfo()
    {
        return $this->hasOne(SoftwareInfo::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProjectSennovaBudget($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query
                ->select('project_sennova_budgets.*')
                ->join('call_budgets', 'project_sennova_budgets.call_budget_id', 'call_budgets.id')
                ->join('sennova_budgets', 'call_budgets.sennova_budget_id', 'sennova_budgets.id')
                ->join('second_budget_info', 'sennova_budgets.second_budget_info_id', 'second_budget_info.id')
                ->where('second_budget_info.name', 'ilike', '%'.$search.'%');
        });
    }

    /**
     * getDescriptionAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * getAverageAttribute
     *
     * @return void
     */
    public function getAverageAttribute()
    {
        $total = 0;

        if ($this->projectBudgetBatches()->exists()) {
            if ($this->projectBudgetBatches->count() > 0) {
                foreach ($this->projectBudgetBatches as $projectBudgetBatch) {
                    $total += $projectBudgetBatch->getAverageAttribute();
                }
            } else {
                $total += $this->projectBudgetBatches->getAverageAttribute();
            }
        } elseif (!$this->projectBudgetBatches()->exists() && !$this->callBudget->sennovaBudget->requires_market_research) {
            $this->value > 0 ? $total = ($this->qty_items * $this->value) : $total = 0;
        }

        return $total;
    }
}
