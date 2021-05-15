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
use App\Http\Controllers\TipoProyectoController;
use App\Http\Controllers\SectorProductivoController;
use App\Http\Controllers\MesaTecnicaController;
use App\Http\Controllers\TemaPriorizadoController;
use App\Http\Controllers\GrupoInvestigacionController;
use App\Http\Controllers\LineaInvestigacionController;
use App\Http\Controllers\SemilleroInvestigacionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\IDiController;
use App\Http\Controllers\ProjectTreeController;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ConvocatoriaSennovaRoleController;
use App\Http\Controllers\SennovaRoleController;
use App\Http\Controllers\ProjectSennovaRoleController;
use App\Http\Controllers\FirstBudgetInfoController;
use App\Http\Controllers\SecondBudgetInfoController;
use App\Http\Controllers\ThirdBudgetInfoController;
use App\Http\Controllers\SennovaBudgetController;
use App\Http\Controllers\CallBudgetController;
use App\Http\Controllers\ProjectSennovaBudgetController;
use App\Http\Controllers\RiskAnalysisController;
use App\Http\Controllers\EntidadAliadaController;
use App\Http\Controllers\AnexoController;
use App\Http\Controllers\ActividadEconomicaController;
use App\Http\Controllers\ProjectAnexoController;
use App\Http\Controllers\BudgetUsageController;
use App\Http\Controllers\ProjectBudgetBatchController;
use App\Http\Controllers\MarketResearchController;
use App\Http\Controllers\MiembroEntidadAliadaController;
use App\Http\Controllers\TecnoacademiaController;
use App\Http\Controllers\LineaTecnologicaController;
use App\Http\Controllers\MesaSectorialController;

use App\Models\ActividadEconomica;
use App\Models\LineaInvestigacion;
use App\Models\TipoProyecto;
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
use App\Models\Municipio;
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

    // Muestra el árbol de objetivos
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/objectives-tree', [ProjectTreeController::class, 'showObjectivesTree'])->name('convocatorias.proyectos.objectives-tree');
    // Actualiza el impacto en el arbol de objetivos
    Route::post('proyectos/{proyecto}/impact/{impact}', [ProjectTreeController::class, 'updateImpact'])->name('proyectos.impact');
    // Actualiza el impacto en el arbol de objetivos
    Route::post('proyectos/{proyecto}/proyecto_result/{proyecto_result}', [ProjectTreeController::class, 'updateProjectResult'])->name('proyectos.proyecto_result');
    // Actualiza el problema general del proyecto en el arbol de problemas
    Route::post('proyectos/{proyecto}/primary-objective', [ProjectTreeController::class, 'updateObjective'])->name('proyectos.objetivo_general');
    // Actualiza el objetivo especifico en el arbol de objetivos
    Route::post('proyectos/{proyecto}/specific_objective/{specific_objective}', [ProjectTreeController::class, 'updateSpecificObjective'])->name('proyectos.specific_objective');
    // Actualiza la actividad en el arbol de objetivos
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/activity/{activity}', [ProjectTreeController::class, 'updateActivity'])->name('proyectos.activity');

    // Muestra los participantes
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/participants', [ProyectoController::class, 'participants'])->name('convocatorias.proyectos.participants');
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/proyecto-annexes/{proyecto_annexe}/download', [ProjectAnexoController::class, 'download'])->name('convocatorias.proyectos.proyecto-annexes.download');
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/proyecto-sennova-budgets/{proyecto_sennova_budget}/proyecto-budget-batches/{proyecto_budget_batch}/download', [ProjectBudgetBatchController::class, 'download'])->name('convocatorias.proyectos.proyecto-sennova-budgets.proyecto-budget-batches.download');
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/proyecto-sennova-budgets/{proyecto_sennova_budget}/market-research/{market_research}/download', [ProjectBudgetBatchController::class, 'downloadPriceQuoteFile'])->name('convocatorias.proyectos.proyecto-sennova-budgets.proyecto-budget-batches.download-price-qoute-file');    

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
    Route::get('web-api/calls/{convocatoria}/programmatic-lines/{programmaticLine}/sennova-budgets/second-budget-info/{secondBudgetInfo}/third-budget-info/{thirdBudgetInfo}', function($call, $programmaticLine, $secondBudgetInfo, $thirdBudgetInfo) {
        return response(SennovaBudget::select('call_budgets.id as value', 'budget_usages.description as label', 'budget_usages.codigo', 'sennova_budgets.requires_market_research', 'sennova_budgets.message')
            ->join('budget_usages', 'sennova_budgets.budget_usage_id', 'budget_usages.id')
            ->join('call_budgets', 'sennova_budgets.id', 'call_budgets.sennova_budget_id')
            ->where('call_budgets.call_id', $call)
            ->where('sennova_budgets.linea_programatica_id', $programmaticLine)
            ->where('sennova_budgets.second_budget_info_id', $secondBudgetInfo)
            ->where('sennova_budgets.third_budget_info_id', $thirdBudgetInfo)
            ->orderBy('budget_usages.description', 'ASC')->get());
    })->name('web-api.budget-usages');

    Route::get('web-api/calls/{convocatoria}/{programmaticLine}/proyecto-sennova-roles', function($call, $programmaticLine) {
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
            ->where('call_sennova_roles.linea_programatica_id', $programmaticLine)
            ->where('call_sennova_roles.call_id', $call)
            ->orderBy('sennova_roles.nombre')->get());
    })->name('web-api.convocatorias.proyecto-sennova-roles');


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
    
    /**
     * Tipos de proyecto
     * 
    */
    Route::resource('tipos-proyecto', TipoProyectoController::class)->parameters(['tipos-proyecto' => 'tipo-proyecto'])->except(['show']);

    /**
     * Mesas sectoriales
     * 
    */
    Route::resource('mesas-sectoriales', MesaSectorialController::class)->parameters(['mesas-sectoriales' => 'mesa-sectorial'])->except(['show']);


    /**
     * Proyectos
     * 
     */
    Route::get('web-api/municipios', function() {
        return response(Municipio::select('municipios.id as value', 'municipios.nombre as label', 'departamentos.nombre as group')
        ->join('departamentos', 'departamentos.id', 'municipios.departamento_id')
        ->get());
    })->name('web-api.municipios');

    // Trae las Tecnoacademias
    Route::get('web-api/tecnoacademias', function() {
        return response(Tecnoacademia::select('tecnoacademias.id as value', 'tecnoacademias.nombre as label')->get());
    })->name('web-api.tecnoacademias');

    // Trae las líneas tecnológicas
    Route::get('web-api/tecnoacademias/{tecnoacademia}/lineas-tecnologicas', function($tecnoacademia) {
        return response(LineaTecnologica::select('id', 'nombre')->where('lineas_tecnologicas.tecnoacademia_id', $tecnoacademia)->get());
    })->name('web-api.tecnoacademias.lineas-tecnologicas');

    // Trae los tipos de proyectos
    Route::get('web-api/tipos-proyecto/{tipo_proyecto}', function($tipoProyecto) {
        return response(TipoProyecto::selectRaw('tipos_proyecto.id as value, concat(tipos_proyecto.nombre, chr(10), \'∙ Línea programática: \', lineas_programaticas.codigo, \' - \', lineas_programaticas.nombre) as label')
            ->join('lineas_programaticas', 'tipos_proyecto.linea_programatica_id', 'lineas_programaticas.id')
            ->where('categoria_proyecto', 'ilike', '%'.$tipoProyecto.'%')
            ->get());
    })->name('web-api.tipos-proyecto');

    // Trae las redes de conocimiento 
    Route::get('web-api/redes-conocimiento', function() {
        return response(RedConocimiento::select('redes_conocimiento.id as value', 'redes_conocimiento.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.redes-conocimiento');

    // Trae las disciplinas de subáreas de conocimiento
    Route::get('web-api/disciplinas-subarea-conocimiento', function() {
        return response(DisciplinaSubareaConocimiento::select('disciplinas_subarea_conocimiento.id as value', 'disciplinas_subarea_conocimiento.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.disciplinas-subarea-conocimiento');

    // Trae los actividades económicas
    Route::get('web-api/actividades-economicas', function() {
        return response(ActividadEconomica::select('actividades_economicas.id as value', 'actividades_economicas.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.actividades-economicas');

    // Trae las temáticas estrategicas SENA
    Route::get('web-api/tematicas-estrategicas', function() {
        return response(TematicaEstrategica::select('tematicas_estrategicas.id as value', 'tematicas_estrategicas.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.tematicas-estrategicas');


    Route::get('convocatorias/{convocatoria}/dashboard', [ConvocatoriaController::class, 'dashboard'])->name('convocatorias.dashboard');

    Route::resource('convocatorias.idi', IDiController::class)->parameters(['convocatorias' => 'convocatoria', 'idi' => 'IDi'])->except(['show']);

    Route::resource('convocatorias.idi.entidades-aliadas', EntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'idi' => 'idi', 'entidades-aliadas' => 'entidad-aliada'])->except(['show']);

    Route::resource('convocatorias.idi.entidades-aliadas.miembros-entidad-aliada', MiembroEntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'idi' => 'idi', 'entidades-aliadas' => 'entidad-aliada', 'miembros-entidad-aliada' => 'miembro-entidad-aliada'])->except(['show']);
    
    Route::resource('convocatorias', ConvocatoriaController::class)->parameters(['convocatorias' => 'convocatoria'])->except(['show']);
    
    // Muestra el árbol de problemas
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/arbol-problemas', [ProjectTreeController::class, 'showProblemTree'])->name('convocatorias.proyectos.arbol-problemas');
    // Actualiza el problema general del proyecto en el arbol de problemas
    Route::post('proyectos/{proyecto}/planteamiento-problema', [ProjectTreeController::class, 'updatePlanteamientoProblema'])->name('proyectos.planteamiento-problema');
    // Actualiza efecto directo en el arbol de problemas
    Route::post('proyectos/{proyecto}/efecto-directo/{efecto_directo}', [ProjectTreeController::class, 'updateEfectoDirecto'])->name('proyectos.efecto-directo');
    // Crea o Actualiza efecto indirecto en el arbol de problemas
    Route::post('proyectos/{proyecto}/efecto-indirecto/{efecto_directo}', [ProjectTreeController::class, 'createOrUpdateEfectoIndirecto'])->name('proyectos.efecto-indirecto');
    // Actualiza causa directa en el arbol de problemas
    Route::post('proyectos/{proyecto}/causa-directa/{causa_directa}', [ProjectTreeController::class, 'updateCausaDirecta'])->name('proyectos.causa-directa');
    // Crea o Actualiza causa indirecta en el arbol de problemas
    Route::post('proyectos/{proyecto}/causa-indirecta/{causa_directa}', [ProjectTreeController::class, 'createOrUpdateCausaIndirecta'])->name('proyectos.causa-indirecta');
    

    
    Route::resource('convocatorias.proyectos.risk-analysis', RiskAnalysisController::class)->parameters(['risk-analysis' => 'risk_analysis']);
    Route::resource('convocatorias.proyectos.proyecto-annexes', ProjectAnexoController::class)->parameters(['proyecto-annexes' => 'proyecto-annexe']);
    Route::resources(
        [
            'convocatorias.proyectos.proyecto-sennova-budgets.proyecto-budget-batches' => ProjectBudgetBatchController::class,
            'budget-usages' => BudgetUsageController::class,
            'minciencias-typologies' => MincienciasTypologyController::class,
            'minciencias-subtypologies' => MincienciasSubtypologyController::class,
            'users' => UserController::class,
            'roles' => RoleController::class,
            'convocatorias.proyectos.outputs' => OutputController::class,
            'convocatorias.proyectos.activities' => ActivityController::class,
            'convocatorias.proyectos.proyecto-sennova-budgets' => ProjectSennovaBudgetController::class,
            'convocatorias.proyectos.proyecto-sennova-roles' => ProjectSennovaRoleController::class,
            'convocatorias.call-sennova-roles' => ConvocatoriaSennovaRoleController::class,
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
