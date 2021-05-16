<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoSennova extends Model
{
    use HasFactory;
        
    /**
     * table
     *
     * @var string
     */
    protected $table = 'presupuesto_sennova';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'primer_grupo_presupuestal_id',
        'segundo_grupo_presupuestal_id',
        'tercer_grupo_presupuestal_id',
        'uso_presupuestal_id',
        'linea_programatica_id',
        'requiere_estudio_mercado',
        'requiere_lote_estudio_mercado',
        'sumar_al_presupuesto'
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
     * Relationship with FirstBudgetInfo
     *
     * @return void
     */
    public function firstBudgetInfo()
    {
        return $this->belongsTo(FirstBudgetInfo::class);
    }

    /**
     * Relationship with SecondBudgetInfo
     *
     * @return void
     */
    public function secondBudgetInfo()
    {
        return $this->belongsTo(SecondBudgetInfo::class);
    }

    /**
     * Relationship with ThirdBudgetInfo
     *
     * @return void
     */
    public function thirdBudgetInfo()
    {
        return $this->belongsTo(ThirdBudgetInfo::class);
    }

    /**
     * Relationship with BudgetUsage
     *
     * @return void
     */
    public function budgetUsage()
    {
        return $this->belongsTo(BudgetUsage::class);
    }

    /**
     * Relationship with ProgrammaticLine
     *
     * @return void
     */
    public function programmaticLine()
    {
        return $this->belongsTo(ProgrammaticLine::class);
    }

    /**
     * Relationship with CallBudget
     *
     * @return void
     */
    public function rubrosPresupuestalesConvocatoria()
    {
        return $this->hasMany(CallBudget::class);
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterPresupuestoSennova($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('replace', 'ilike', '%'.$search.'%');
        });
    }
}
