<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Regional' => 'App\Policies\RegionalPolicy',
        'App\Models\CentroFormacion' => 'App\Policies\CentroFormacionPolicy',
        'App\Models\ProgramaFormacion' => 'App\Policies\ProgramaFormacionPolicy',
        'App\Models\LineaProgramatica' => 'App\Policies\LineaProgramaticaPolicy',
        'App\Models\RedConocimiento' => 'App\Policies\RedConocimientoPolicy',
        'App\Models\TematicaEstrategica' => 'App\Policies\TematicaEstrategicaPolicy',
        'App\Models\ProjectType' => 'App\Policies\ProjectTypePolicy',
        'App\Models\SectorProductivo' => 'App\Policies\SectorProductivoPolicy',
        'App\Models\MesaTecnica' => 'App\Policies\MesaTecnicaPolicy',
        'App\Models\TemaPriorizado' => 'App\Policies\TemaPriorizadoPolicy',
        'App\Models\GrupoInvestigacion' => 'App\Policies\GrupoInvestigacionPolicy',
        'App\Models\LineaInvestigacion' => 'App\Policies\LineaInvestigacionPolicy',
        'App\Models\SemilleroInvestigacion' => 'App\Policies\SemilleroInvestigacionPolicy',
        'App\Models\Role' => 'App\Policies\RolePolicy',
        'App\Models\Call' => 'App\Policies\CallPolicy',
        'App\Models\RDI' => 'App\Policies\RDIPolicy',
        'App\Models\RDIOutput' => 'App\Policies\RDIOutputPolicy',
        'App\Models\Activity' => 'App\Policies\ActivityPolicy',
        'App\Models\CallSennovaRole' => 'App\Policies\CallSennovaRolePolicy',
        'App\Models\SennovaRole' => 'App\Policies\SennovaRolePolicy',
        'App\Models\ProjectSennovaRole' => 'App\Policies\ProjectSennovaRolePolicy',
        'App\Models\FirstBudgetInfo' => 'App\Policies\FirstBudgetInfoPolicy',
        'App\Models\SecondBudgetInfo' => 'App\Policies\SecondBudgetInfoPolicy',
        'App\Models\ThirdBudgetInfo' => 'App\Policies\ThirdBudgetInfoPolicy',
        'App\Models\SennovaBudget' => 'App\Policies\SennovaBudgetPolicy',
        'App\Models\CallBudget' => 'App\Policies\CallBudgetPolicy',
        'App\Models\ProjectSennovaBudget' => 'App\Policies\ProjectSennovaBudgetPolicy',
        'App\Models\RiskAnalysis' => 'App\Policies\RiskAnalysisPolicy',
        'App\Models\PartnerOrganization' => 'App\Policies\PartnerOrganizationPolicy',
        'App\Models\Anexo' => 'App\Policies\AnexoPolicy',
        'App\Models\MincienciasSubtypology' => 'App\Policies\MincienciasSubtypologyPolicy',
        'App\Models\MincienciasTypology' => 'App\Policies\MincienciasTypologyPolicy',
        'App\Models\ProjectAnnexe' => 'App\Policies\ProjectAnnexePolicy',
        'App\Models\BudgetUsage' => 'App\Policies\BudgetUsagePolicy',
        'App\Models\ProjectBudgetBatch' => 'App\Policies\ProjectBudgetBatchPolicy',
        'App\Models\MarketResearch' => 'App\Policies\MarketResearchPolicy',
        'App\Models\PartnerOrganizationMember' => 'App\Policies\PartnerOrganizationMemberPolicy',
        'App\Models\Tecnoacademia' => 'App\Policies\TecnoacademiaPolicy',
        'App\Models\LineaTecnologica' => 'App\Policies\LineaTecnologicaPolicy',
        'App\Models\SectorBasedCommittee' => 'App\Policies\SectorBasedCommitteePolicy',
        'App\Models\Project' => 'App\Policies\ProjectPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerSuperAdminPolicy();
    }

    public function registerSuperAdminPolicy()
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(1) ? true : null;
        });
    }
}
