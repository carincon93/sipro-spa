<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'actividades';

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
        'objetivo_especifico_id',
        'causa_indirecta_id',
        'fecha_inicio',
        'fecha_finalizacion',
        'descripcion',
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
     * Relationship with ObjetivoEspecifico
     *
     * @return void
     */
    public function objetivoEspecifico()
    {
        return $this->belongsTo(ObjetivoEspecifico::class);
    }

    /**
     * Relationship with CausaIndirecta
     *
     * @return void
     */
    public function causaIndirecta()
    {
        return $this->belongsTo(CausaIndirecta::class);
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
     * Relationship with EntidadAliada
     *
     * @return void
     */
    public function entidadAliadas()
    {
        return $this->belongsToMany(EntidadAliada::class, 'activity_partner_organization', 'activity_id', 'partner_organization_id');
    }

    /**
     * Filtrar registros
     *
     * @param  mixed $query
     * @param  mixed $filters
     * @return void
     */
    public function scopeFilterActividad($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('descripcion', 'ilike', '%'.$search.'%');
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
