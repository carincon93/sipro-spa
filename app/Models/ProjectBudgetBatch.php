<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBudgetBatch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_sennova_budget_id',
        'qty_items',
        'fact_sheet'
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
     * Relationship with ProjectSennovaBudget
     *
     * @return void
     */
    public function projectSennovaBudget()
    {
        return $this->belongsTo(ProjectSennovaBudget::class);
    }

    /**
     * Relationship with MarketResearch
     *
     * @return void
     */
    public function marketResearch()
    {
        return $this->hasMany(MarketResearch::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterProjectBudgetBatch($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('qty_items', 'ilike', '%'.$search.'%');
        });
    }

    /**
     * getAverageAttribute
     *
     * @return void
     */
    public function getAverageAttribute()
    {
        $average    = 0;

        foreach ($this->marketResearch as $marketResearch) {
            $average += (int) $marketResearch->price_quote;
        }

        return $this->marketResearch->count() > 0 ? ($average / $this->marketResearch->count()) : 0;
    }
}
