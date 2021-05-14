<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\CentroFormacionController;
use App\Http\Controllers\ProgramaFormacionController;
use App\Http\Controllers\LineaProgramaticaController;
use App\Http\Controllers\RedConocimientoController;
use App\Http\Controllers\TematicaEstrategicaController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\SectorProductivoController;
use App\Http\Controllers\MesaTecnicaController;
use App\Http\Controllers\TemaPriorizadoController;
use App\Http\Controllers\GrupoInvestigacionController;
use App\Http\Controllers\LineaInvestigacionController;
use App\Http\Controllers\SemilleroInvestigacionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\RDIController;
use App\Http\Controllers\ProjectTreeController;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CallSennovaRoleController;
use App\Http\Controllers\SennovaRoleController;
use App\Http\Controllers\ProjectSennovaRoleController;
use App\Http\Controllers\FirstBudgetInfoController;
use App\Http\Controllers\SecondBudgetInfoController;
use App\Http\Controllers\ThirdBudgetInfoController;
use App\Http\Controllers\SennovaBudgetController;
use App\Http\Controllers\CallBudgetController;
use App\Http\Controllers\ProjectSennovaBudgetController;
use App\Http\Controllers\RiskAnalysisController;
use App\Http\Controllers\PartnerOrganizationController;
use App\Http\Controllers\AnexoController;
use App\Http\Controllers\ActividadEconomicaController;
use App\Http\Controllers\MincienciasTypologyController;
use App\Http\Controllers\MincienciasSubtypologyController;
use App\Http\Controllers\ProjectAnexoController;
use App\Http\Controllers\BudgetUsageController;
use App\Http\Controllers\ProjectBudgetBatchController;
use App\Http\Controllers\MarketResearchController;
use App\Http\Controllers\PartnerOrganizationMemberController;
use App\Http\Controllers\TecnoacademiaController;
use App\Http\Controllers\LineaTecnologicaController;
use App\Http\Controllers\SectorBasedCommitteeController;

use App\Models\LineaInvestigacion;
use App\Models\ProjectType;
use App\Models\RedConocimiento;
use App\Models\DisciplinaSubareaConocimiento;
use App\Models\TematicaEstrategica;
use App\Models\CentroFormacion;
use App\Models\Region;
use App\Models\Regional;
use App\Models\GrupoInvestigacion;
use App\Models\MincienciasSubtypology;
use App\Models\LineaProgramatica;
use App\Models\CallSennovaRole;
use App\Models\SecondBudgetInfo;
use App\Models\ThirdBudgetInfo;
use App\Models\SennovaBudget;
use App\Models\Tecnoacademia;
use App\Models\LineaTecnologica;
use App\Models\Ciudad;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin'    => Route::has('login'),
//         'canRegister' => Route::has('register'),
//     ]);
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Muestra las líneas programáticas para empezar la formulación de proyectos
    Route::get('calls/{call}/dashboard', [CallController::class, 'dashboard'])->name('calls.dashboard');
    // Muestra el árbol de problemas
    Route::get('calls/{call}/projects/{project}/problem-tree', [ProjectTreeController::class, 'showProblemTree'])->name('calls.projects.problem-tree');
    // Actualiza el problema general del proyecto en el arbol de problemas
    Route::post('projects/{project}/research-problem', [ProjectTreeController::class, 'updateProblem'])->name('projects.planteamiento_problema');
    // Actualiza efecto directo en el arbol de problemas
    Route::post('projects/{project}/direct-effect/{direct_effect}', [ProjectTreeController::class, 'updateDirectEffect'])->name('projects.direct_effect');
    // Crea o Actualiza efecto indirecto en el arbol de problemas
    Route::post('projects/{project}/indirect-effect/{direct_effect}', [ProjectTreeController::class, 'createOrUpdateIndirectEffect'])->name('projects.indirect_effect');
    // Actualiza causa directa en el arbol de problemas
    Route::post('projects/{project}/direct-cause/{direct_cause}', [ProjectTreeController::class, 'updateDirectCause'])->name('projects.direct_cause');
    // Crea o Actualiza causa indirecta en el arbol de problemas
    Route::post('projects/{project}/indirect-cause/{direct_cause}', [ProjectTreeController::class, 'createOrUpdateIndirectCause'])->name('projects.indirect_cause');

    // Muestra el árbol de objetivos
    Route::get('calls/{call}/projects/{project}/objectives-tree', [ProjectTreeController::class, 'showObjectivesTree'])->name('calls.projects.objectives-tree');
    // Actualiza el impacto en el arbol de objetivos
    Route::post('projects/{project}/impact/{impact}', [ProjectTreeController::class, 'updateImpact'])->name('projects.impact');
    // Actualiza el impacto en el arbol de objetivos
    Route::post('projects/{project}/project_result/{project_result}', [ProjectTreeController::class, 'updateProjectResult'])->name('projects.project_result');
    // Actualiza el problema general del proyecto en el arbol de problemas
    Route::post('projects/{project}/primary-objective', [ProjectTreeController::class, 'updateObjective'])->name('projects.objetivo_general');
    // Actualiza el objetivo especifico en el arbol de objetivos
    Route::post('projects/{project}/specific_objective/{specific_objective}', [ProjectTreeController::class, 'updateSpecificObjective'])->name('projects.specific_objective');
    // Actualiza la actividad en el arbol de objetivos
    Route::post('calls/{call}/projects/{project}/activity/{activity}', [ProjectTreeController::class, 'updateActivity'])->name('projects.activity');

    // Muestra los participantes
    Route::get('calls/{call}/projects/{project}/participants', [ProjectController::class, 'participants'])->name('calls.projects.participants');
    Route::get('calls/{call}/projects/{project}/project-annexes/{project_annexe}/download', [ProjectAnexoController::class, 'download'])->name('calls.projects.project-annexes.download');
    Route::get('calls/{call}/projects/{project}/project-sennova-budgets/{project_sennova_budget}/project-budget-batches/{project_budget_batch}/download', [ProjectBudgetBatchController::class, 'download'])->name('calls.projects.project-sennova-budgets.project-budget-batches.download');
    Route::get('calls/{call}/projects/{project}/project-sennova-budgets/{project_sennova_budget}/market-research/{market_research}/download', [ProjectBudgetBatchController::class, 'downloadPriceQuoteFile'])->name('calls.projects.project-sennova-budgets.project-budget-batches.download-price-qoute-file');

    // Trae las Tecnoacademias
    Route::get('web-api/techno-academies', function() {
        return response(Tecnoacademia::select('techno_academies.id as value', 'techno_academies.nombre as label')->get());
    })->name('web-api.techno-academies');

    // Trae las líneas tecnológicas
    Route::get('web-api/techno-academies/{techno_academy}/technological-lines', function($technoAcademy) {
        return response(LineaTecnologica::select('id', 'name')->where('technological_lines.techno_academy_id', $technoAcademy)->get());
    })->name('web-api.techno-academies.technological-lines');

    // Trae los tipos de proyectos
    Route::get('web-api/project-types/{project_category}', function($projectCategory) {
        return response(ProjectType::selectRaw('project_types.id as value, concat(project_types.nombre, chr(10), \'∙ Línea programática: \', programmatic_lines.code, \' - \', programmatic_lines.nombre) as label')
            ->join('programmatic_lines', 'project_types.programmatic_line_id', 'programmatic_lines.id')
            ->where('project_category', 'ilike', '%'.$projectCategory.'%')
            ->get());
    })->name('web-api.project-types');

    // Trae las redes de conocimiento 
    Route::get('web-api/redes-conocimiento', function() {
        return response(RedConocimiento::select('redes_conocimiento.id as value', 'redes_conocimiento.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.redes-conocimiento');

    // Trae las disciplinas de subáreas de conocimiento
    Route::get('web-api/disciplinas-subarea-conocimiento', function() {
        return response(DisciplinaSubareaConocimiento::select('disciplinas_subarea_conocimiento.id as value', 'disciplinas_subarea_conocimiento.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.disciplinas-subarea-conocimiento');

    // Trae los Actividades económicas
    Route::get('web-api/actividades-economicas', function() {
        return response(ActividadEconomica::select('actividades_economicas.id as value', 'actividades_economicas.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.actividades-economicas');

    // Trae las temáticas estrategicas SENA
    Route::get('web-api/tematicas-estrategicas', function() {
        return response(TematicaEstrategica::select('tematicas_estrategicas.id as value', 'tematicas_estrategicas.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.tematicas-estrategicas');

    
    

    

    // Trae las líneas programáticas
    Route::get('web-api/programmatic-lines', function() {
        return response(LineaProgramatica::select('id as value', 'name as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.programmatic-lines');

    

    // Trae las subtipologías Minciencias
    Route::get('web-api/minciencias-subtypologies', function() {
        return response(MincienciasSubtypology::selectRaw('minciencias_subtypologies.id as value, concat(minciencias_subtypologies.nombre, chr(10), \'∙ Tipología Minciencias: \', minciencias_typologies.nombre) as label')->join('minciencias_typologies', 'minciencias_subtypologies.minciencias_typology_id', 'minciencias_typologies.id')->orderBy('minciencias_subtypologies.nombre')->get());
    })->name('web-api.minciencias-subtypologies');

    // Trae los conceptos internos SENA
    Route::get('web-api/second-budget-info', function() {
        return response(SecondBudgetInfo::select('second_budget_info.id as value', 'second_budget_info.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.second-budget-info');

    Route::get('web-api/third-budget-info/{secondBudgetInfo}', function($secondBudgetInfo) {
        return response(ThirdBudgetInfo::selectRaw('DISTINCT(third_budget_info.id) as value, third_budget_info.nombre as label')
            ->join('sennova_budgets', 'third_budget_info.id', 'sennova_budgets.third_budget_info_id')
            ->where('sennova_budgets.second_budget_info_id', $secondBudgetInfo)
            ->get());
    })->name('web-api.third-budget-info');

    // Trae los usos presupuestales
    Route::get('web-api/calls/{call}/programmatic-lines/{programmaticLine}/sennova-budgets/second-budget-info/{secondBudgetInfo}/third-budget-info/{thirdBudgetInfo}', function($call, $programmaticLine, $secondBudgetInfo, $thirdBudgetInfo) {
        return response(SennovaBudget::select('call_budgets.id as value', 'budget_usages.description as label', 'budget_usages.code', 'sennova_budgets.requires_market_research', 'sennova_budgets.message')
            ->join('budget_usages', 'sennova_budgets.budget_usage_id', 'budget_usages.id')
            ->join('call_budgets', 'sennova_budgets.id', 'call_budgets.sennova_budget_id')
            ->where('call_budgets.call_id', $call)
            ->where('sennova_budgets.programmatic_line_id', $programmaticLine)
            ->where('sennova_budgets.second_budget_info_id', $secondBudgetInfo)
            ->where('sennova_budgets.third_budget_info_id', $thirdBudgetInfo)
            ->orderBy('budget_usages.description', 'ASC')->get());
    })->name('web-api.budget-usages');

    Route::get('web-api/calls/{call}/{programmaticLine}/project-sennova-roles', function($call, $programmaticLine) {
        return response(CallSennovaRole::selectRaw("call_sennova_roles.id as value, call_sennova_roles.message,
        CASE academic_degree
				WHEN '0' THEN	concat(sennova_roles.nombre, ' - Nivel académico: Ninguno', chr(10), '∙ Asignación mensual: ', call_sennova_roles.salary)
                WHEN '1' THEN	concat(sennova_roles.nombre, ' - Nivel académico: Técnico', chr(10), '∙ Asignación mensual: ', call_sennova_roles.salary)
                WHEN '2' THEN	concat(sennova_roles.nombre, ' - Nivel académico: Tecnólogo', chr(10), '∙ Asignación mensual: ', call_sennova_roles.salary)
                WHEN '3' THEN	concat(sennova_roles.nombre, ' - Nivel académico: Pregrado', chr(10), '∙ Asignación mensual: ', call_sennova_roles.salary)
                WHEN '4' THEN	concat(sennova_roles.nombre, ' - Nivel académico: Especalización', chr(10), '∙ Asignación mensual: ', call_sennova_roles.salary)
                WHEN '5' THEN	concat(sennova_roles.nombre, ' - Nivel académico: Maestría', chr(10), '∙ Asignación mensual: ', call_sennova_roles.salary)
                WHEN '6' THEN	concat(sennova_roles.nombre, ' - Nivel académico: Doctorado', chr(10), '∙ Asignación mensual: ', call_sennova_roles.salary)
        END as label,
        call_sennova_roles.qty_months, call_sennova_roles.qty_roles, call_sennova_roles.months_experience")
            ->join('sennova_roles', 'call_sennova_roles.sennova_role_id', 'sennova_roles.id')
            ->where('call_sennova_roles.programmatic_line_id', $programmaticLine)
            ->where('call_sennova_roles.call_id', $call)
            ->orderBy('sennova_roles.nombre')->get());
    })->name('web-api.calls.project-sennova-roles');

    /**
     * Proyectos
     * 
     */
    Route::get('web-api/ciudades', function() {
        return response(Ciudad::select('ciudades.id as value', 'ciudades.nombre as label', 'departamentos.nombre as group')
        ->join('departamentos', 'departamentos.id', 'ciudades.departmento_id')
        ->get());
    })->name('web-api.ciudades');

    /**
     * Regionales
     * 
     * Trae las regiones
    */
    Route::get('web-api/regiones', function() {
        return response(Region::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.regiones');
    /**
    * Trae las regionales
    */
    Route::get('web-api/regionales', function() {
        return response(Regional::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.regionales');

    /**
    * Trae los directores de regional
    */
    Route::get('web-api/directores-regional', function() {
        return response(User::select('users.id as value', 'users.nombre as label')
            ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', 'roles.id')
            ->where('roles.id', 7)
            ->orWhere('roles.nombre', 'ilike', '%director de regional%')
            ->orderBy('users.nombre', 'ASC')->get());
    })->name('web-api.directores-regional');

    Route::resource('regionales', RegionalController::class)->parameters(['regionales' => 'regional'])->except(['show']);


    /**
     * Centros de formación
     * 
     * Trae los subdirectores
    */
    Route::get('web-api/subdirectores', function() {
        return response(User::select('users.id as value', 'users.nombre as label')
            ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', 'roles.id')
            ->where('roles.id', 14)
            ->orWhere('roles.nombre', 'ilike', '%subdirector de centro de formación%')
            ->orderBy('users.nombre', 'ASC')->get());
    })->name('web-api.subdirectores');

    Route::resource('centros-formacion', CentroFormacionController::class)->parameters(['centros-formacion' => 'centro-formacion'])->except(['show']);

    /**
     * Programas de formación
     * 
     * Trae los centros de formación
    */
    Route::get('web-api/centros-formacion', function() {
        return response(CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo, chr(10), \'∙ Regional: \', regionales.nombre) as label')->join('regionales', 'centros_formacion.regional_id', 'regionales.id')->orderBy('centros_formacion.nombre', 'ASC')->get());
    })->name('web-api.centros-formacion');

    Route::resource('programas-formacion', ProgramaFormacionController::class)->parameters(['programas-formacion' => 'programa-formacion'])->except(['show']);

    /**
     * Temáticas estratégicas
     * 
    */
    Route::resource('tematicas-estrategicas', TematicaEstrategicaController::class)->parameters(['tematicas-estrategicas' => 'tematica-estrategica'])->except(['show']);

    /**
     * Sectores productivos
     * 
    */
    Route::resource('sectores-productivos', SectorProductivoController::class)->parameters(['sectores-productivos' => 'sector-productivo'])->except(['show']);
    
    /**
     * Temas priorizados
     * 
    */
    Route::resource('temas-priorizados', TemaPriorizadoController::class)->parameters(['temas-priorizados' => 'tema-priorizado'])->except(['show']);

    /**
     * Mesas técnicas
     * 
    */
    Route::resource('mesas-tecnicas', MesaTecnicaController::class)->parameters(['mesas-tecnicas' => 'mesa-tecnica'])->except(['show']);
    
    /**
     * Anexos
     * 
    */
    Route::resource('anexos', AnexoController::class)->parameters(['anexos' => 'anexo'])->except(['show']);

    /**
     * Líneas programáticas
     * 
    */
    Route::resource('lineas-programaticas', LineaProgramaticaController::class)->parameters(['lineas-programaticas' => 'linea-programatica'])->except(['show']);

    /**
     * Redes de conocimiento
     * 
    */
    Route::resource('redes-conocimiento', RedConocimientoController::class)->parameters(['redes-conocimiento' => 'red-conocimiento'])->except(['show']);

    /**
     * Tecnoacademias
     * 
    */
    Route::resource('tecnoacademias', TecnoacademiaController::class)->parameters(['tecnoacademias' => 'tecnoacademia'])->except(['show']);

    /**
     * Líenas tecnológicas
     * 
    */
    Route::resource('lineas-tecnologicas', LineaTecnologicaController::class)->parameters(['lineas-tecnologicas' => 'linea-tecnologica'])->except(['show']);

    /**
     * Grupos de investigación
     * 
    */
    Route::resource('grupos-investigacion', GrupoInvestigacionController::class)->parameters(['grupos-investigacion' => 'grupo-investigacion'])->except(['show']);

    /**
     * Líneas de investigación
     * 
     * Trae los grupos de investigación
     * 
    */
    Route::get('web-api/grupos-investigacion', function() {
        return response(GrupoInvestigacion::selectRaw('grupos_investigacion.id as value, concat(grupos_investigacion.nombre, chr(10), \'∙ Acrónimo: \', grupos_investigacion.acronimo, chr(10), \'∙ Centro de formación: \', centros_formacion.nombre, chr(10), \'∙ Regional: \', regionales.nombre) as label')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')->join('regionales', 'centros_formacion.regional_id', 'regionales.id')->get());
    })->name('web-api.grupos-investigacion');

    Route::resource('lineas-investigacion', LineaInvestigacionController::class)->parameters(['lineas-investigacion' => 'linea-investigacion'])->except(['show']);

    /**
     * Semilleros de investigación
     * 
     * Trae las líneas de investigación
    */
    Route::get('web-api/lineas-investigacion', function() {
        return response(LineaInvestigacion::selectRaw('lineas_investigacion.id as value, concat(lineas_investigacion.nombre, chr(10), \'∙ Grupo de investigación: \', grupos_investigacion.nombre, chr(10), \'∙ Centro de formación: \', centros_formacion.nombre, chr(10), \'∙ Regional: \', regionales.nombre) as label')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id','centros_formacion.id')->join('regionales', 'centros_formacion.regional_id', 'regionales.id')->get());
    })->name('web-api.lineas-investigacion');

    Route::resource('semilleros-investigacion', SemilleroInvestigacionController::class)->parameters(['semilleros-investigacion' => 'semillero-investigacion'])->except(['show']);


    Route::resource('calls.projects.risk-analysis', RiskAnalysisController::class)->parameters(['risk-analysis' => 'risk_analysis']);
    Route::resource('calls.projects.project-annexes', ProjectAnexoController::class)->parameters(['project-annexes' => 'project-annexe']);
    Route::resources(
        [
            'sector-based-committees' => SectorBasedCommitteeSectorBasedCommitteeController::class,
            'calls.projects.project-sennova-budgets.project-budget-batches' => ProjectBudgetBatchController::class,
            'budget-usages' => BudgetUsageController::class,
            'minciencias-typologies' => MincienciasTypologyController::class,
            'minciencias-subtypologies' => MincienciasSubtypologyController::class,
            'users' => UserController::class,
            'project-types' => ProjectTypeController::class,
            'roles' => RoleController::class,
            'calls' => CallController::class,
            'calls.rdi' => RDIController::class,
            'calls.projects.outputs' => OutputController::class,
            'calls.projects.activities' => ActivityController::class,
            'calls.projects.project-sennova-budgets' => ProjectSennovaBudgetController::class,
            'calls.projects.project-sennova-roles' => ProjectSennovaRoleController::class,
            'calls.rdi.partner-organizations' => PartnerOrganizationController::class,
            'calls.rdi.partner-organizations.partner-organization-members' => PartnerOrganizationMemberController::class,
            'calls.call-sennova-roles' => CallSennovaRoleController::class,
            'first-budget-info' => FirstBudgetInfoController::class,
            'second-budget-info' => SecondBudgetInfoController::class,
            'third-budget-info' => ThirdBudgetInfoController::class,
            'sennova-budgets' => SennovaBudgetController::class,
            'sennova-roles' => SennovaRoleController::class,
            'call-budgets' => CallBudgetController::class,
        ]
    );
});

require __DIR__.'/auth.php';
