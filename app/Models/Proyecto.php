<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proyecto extends Model
{
    use HasFactory;

    protected $appends = ['code', 'diff_months', 'total_project_budget', 'total_industrial_machinery', 'total_special_construction_services', 'total_viatics', 'total_machinery_maintenance'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'call_id',
        'academic_centre_id',
        'project_type_id',
        'is_finished'
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
     * Relationship with Call
     *
     * @return void
     */
    public function call()
    {
        return $this->belongsTo(Call::class);
    }


    /**
     * Relationship with ProjectType
     *
     * @return void
     */
    public function projectType()
    {
        return $this->belongsTo(ProjectType::class);
    }

    /**
     * Relationship with AcademicCentre
     *
     * @return void
     */
    public function academicCentre()
    {
        return $this->belongsTo(AcademicCentre::class);
    }

    /**
     * Relationship with RDI
     *
     * @return void
     */
    public function rdi()
    {
        return $this->hasOne(RDI::class, 'id');
    }

    /**
     * Relationship with City
     *
     * @return void
     */
    public function cities()
    {
        return $this->belongsToMany(City::class, 'project_city', 'project_id', 'city_id')->orderBy('cities.name', 'asc');
    }

    /**
     * Relationship with DirectCause
     *
     * @return void
     */
    public function directCauses()
    {
        return $this->hasMany(DirectCause::class)->orderBy('id', 'asc');
    }

    /**
     * Relationship with DirectEffect
     *
     * @return void
     */
    public function directEffects()
    {
        return $this->hasMany(DirectEffect::class)->orderBy('id', 'asc');
    }

    /**
     * Relationship with ProjectSennovaRole
     *
     * @return void
     */
    public function sennovaRoles()
    {
        return $this->hasMany(ProjectSennovaRole::class);
    }

    /**
     * Relationship with ProjectSennovaBudget
     *
     * @return void
     */
    public function projectSennovaBudgets()
    {
        return $this->hasMany(ProjectSennovaBudget::class);
    }

    /**
     * Relationship with ProjectAnnexe
     *
     * @return void
     */
    public function projectAnnexes()
    {
        return $this->hasMany(ProjectAnnexe::class);
    }

    /**
     * Relationship with RiskAnalysis
     *
     * @return void
     */
    public function riskAnalysis()
    {
        return $this->hasMany(RiskAnalysis::class);
    }

    /**
     * Relationship with Project (participants)
     *
     * @return void
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'project_participants', 'project_id', 'user_id')
            ->withPivot([
                'user_id',
                'is_author',
                'qty_months',
                'qty_hours'
            ]);
    }

    /**
     * Get code e.g. SGPS-8000-2021
     *
     * @return void
     */
    public function getCodeAttribute()
    {
        return 'SGPS-' . ($this->id + 8000) .'-' . date('Y', strtotime($this->rdi->fecha_finalizacion));
    }

    public function getDiffMonthsAttribute()
    {
        $end_month   = Carbon::parse($this->rdi->fecha_finalizacion, 'UTC')->floorMonth();
        $start_month = Carbon::parse($this->rdi->fecha_incio, 'UTC')->floorMonth();
        return $start_month->diffInMonths($end_month);
    }

    public function getTotalProjectBudgetAttribute()
    {
        $total = 0;

        foreach($this->projectSennovaBudgets as $projectSennovaBudget) {
            if ($projectSennovaBudget->callBudget->sennovaBudget->can_be_added) {
                $total += $projectSennovaBudget->getAverageAttribute();
            }
        }

        return $total;
    }

    // Validación de la línea 66
    // Porcentaje total del rubro 'Maquinaria Industrial'
    public function getTotalIndustrialMachineryAttribute()
    {
        $total = 0;
        $secondBudgetInfoID = null;

        foreach($this->projectSennovaBudgets as $projectSennovaBudget) {

            if ($projectSennovaBudget->callBudget->sennovaBudget->can_be_added && $projectSennovaBudget->callBudget->sennovaBudget->secondBudgetInfo->code == '2040115') {
                $total += $projectSennovaBudget->getAverageAttribute();
            }
        }

        return $total;
    }

    public function getPercentageIndustrialMachineryAttribute()
    {
        $total = 0;

        return $this->getTotalIndustrialMachineryAttribute() * 0.05;
    }

    // Validación de la línea 66
    // Total del rubro 'Servicios especiales de construcción'
    public function getTotalSpecialConstructionServicesAttribute()
    {
        $total = 0;

        foreach($this->projectSennovaBudgets as $projectSennovaBudget) {

            if ($projectSennovaBudget->callBudget->sennovaBudget->can_be_added && $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '2020200500405') {
                $total += $projectSennovaBudget->getAverageAttribute();
            }
        }

        return $total;
    }

    // Validación de la línea 66
    // Total del rubro 'Servicios especiales de construcción'
    public function getTotalViaticsAttribute()
    {
        $total = 0;

        foreach($this->projectSennovaBudgets as $projectSennovaBudget) {
            if ($projectSennovaBudget->callBudget->sennovaBudget->can_be_added) {
                if ($projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '2020200600301' || $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '2020200600303' || $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '20202006004') {
                    $total += $projectSennovaBudget->getAverageAttribute();
                }
            }
        }

        return $total;
    }

    // Validación de la línea 66
    // Total del rubro 'MANTENIMIENTO DE MAQUINARIA, EQUIPO, TRANSPORTE Y SOFWARE'
    public function getTotalMachineryMaintenanceAttribute()
    {
        $total = 0;

        foreach($this->projectSennovaBudgets as $projectSennovaBudget) {
            if ($projectSennovaBudget->callBudget->sennovaBudget->can_be_added) {
                if ($projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '20202008007013' || $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '20202008007012' || $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '20202008007014' || $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '20202008007015' || $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '20202008007011') {
                    $total += $projectSennovaBudget->getAverageAttribute();
                }
            }
        }

        return $total;
    }

    // Validación de la línea 66
    // Total del monitores
    public function getTotalStudentTrainees()
    {
        $total = 0;

        foreach($this->projectSennovaBudgets as $projectSennovaBudget) {
            // if ($projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code == '20202008005099') {
            //     $total++;
            // }
            $total = $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage()->where('code', '20202008005099')->count();
        }

        return $total;
    }
}
