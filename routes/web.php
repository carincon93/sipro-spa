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
use App\Http\Controllers\ArbolProyectoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\ConvocatoriaRolSennovaController;
use App\Http\Controllers\RolSennovaController;
use App\Http\Controllers\ProyectoRolSennovaController;
use App\Http\Controllers\PrimerGrupoPresupuestalController;
use App\Http\Controllers\SegundoGrupoPresupuestalController;
use App\Http\Controllers\TercerGrupoPresupuestalController;
use App\Http\Controllers\PresupuestoSennovaController;
use App\Http\Controllers\PresupuestoConvocatoriaController;
use App\Http\Controllers\ProyectoPresupuestoController;
use App\Http\Controllers\AnalisisRiesgoController;
use App\Http\Controllers\EntidadAliadaController;
use App\Http\Controllers\AnexoController;
use App\Http\Controllers\ActividadEconomicaController;
use App\Http\Controllers\ProyectoAnexoController;
use App\Http\Controllers\UsoPresupuestalController;
use App\Http\Controllers\ProyectLoteEstudioMercadoController;
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
use App\Models\SubtipologiaMinciencias;
use App\Models\LineaProgramatica;
use App\Models\ConvocatoriaRolSennova;
use App\Models\SegundoGrupoPresupuestal;
use App\Models\TercerGrupoPresupuestal;
use App\Models\PresupuestoSennova;
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

    // Muestra los participantes
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/participants', [ProyectoController::class, 'participants'])->name('convocatorias.proyectos.participants');
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/proyecto-presupuesto-sennova/{proyecto_sennova_budget}/lote-estudio-mercado/{proyecto_budget_batch}/download', [ProyectLoteEstudioMercadoController::class, 'download'])->name('convocatorias.proyectos.proyecto-presupuesto-sennova.lote-estudio-mercado.download');
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/proyecto-presupuesto-sennova/{proyecto_sennova_budget}/market-research/{market_research}/download', [ProyectLoteEstudioMercadoController::class, 'downloadPriceQuoteFile'])->name('convocatorias.proyectos.proyecto-presupuesto-sennova.lote-estudio-mercado.download-price-qoute-file');    


    // Trae los conceptos internos SENA
    Route::get('web-api/second-budget-info', function() {
        return response(SegundoGrupoPresupuestal::select('second_budget_info.id as value', 'second_budget_info.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.second-budget-info');

    Route::get('web-api/third-budget-info/{secondBudgetInfo}', function($secondBudgetInfo) {
        return response(TercerGrupoPresupuestal::selectRaw('DISTINCT(third_budget_info.id) as value, third_budget_info.nombre as label')
            ->join('sennova_budgets', 'third_budget_info.id', 'sennova_budgets.third_budget_info_id')
            ->where('sennova_budgets.second_budget_info_id', $secondBudgetInfo)
            ->get());
    })->name('web-api.third-budget-info');

    // Trae los usos presupuestales
    Route::get('web-api/convocatorias/{convocatoria}/lineas-programaticas/{linea_programatica}/presupuesto-sennova/second-budget-info/{secondBudgetInfo}/third-budget-info/{thirdBudgetInfo}', function($convocatoria, $lineaProgramatica, $secondBudgetInfo, $thirdBudgetInfo) {
        return response(PresupuestoSennova::select('convocatoria_budgets.id as value', 'budget_usages.description as label', 'budget_usages.codigo', 'sennova_budgets.requires_market_research', 'sennova_budgets.mensaje')
            ->join('budget_usages', 'sennova_budgets.budget_usage_id', 'budget_usages.id')
            ->join('convocatoria_budgets', 'sennova_budgets.id', 'convocatoria_budgets.sennova_budget_id')
            ->where('convocatoria_budgets.convocatoria_id', $convocatoria)
            ->where('sennova_budgets.linea_programatica_id', $lineaProgramatica)
            ->where('sennova_budgets.second_budget_info_id', $secondBudgetInfo)
            ->where('sennova_budgets.third_budget_info_id', $thirdBudgetInfo)
            ->orderBy('budget_usages.description', 'ASC')->get());
    })->name('web-api.usos-presupuestales');

    Route::get('web-api/convocatorias/{convocatoria}/{linea_programatica}/roles-sennova', function($convocatoria, $lineaProgramatica) {
        return response(ConvocatoriaRolSennova::selectRaw("convocatoria_rol_sennova.id as value, convocatoria_rol_sennova.mensaje,
        CASE nivel_academico
				WHEN '0' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Ninguno', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '1' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Técnico', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '2' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Tecnólogo', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '3' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Pregrado', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '4' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Especalización', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '5' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Maestría', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '6' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Doctorado', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
        END as label,
        convocatoria_rol_sennova.meses_experiencia")
            ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
            ->where('convocatoria_rol_sennova.linea_programatica_id', $lineaProgramatica)
            ->where('convocatoria_rol_sennova.convocatoria_id', $convocatoria)
            ->orderBy('roles_sennova.nombre')->get());
    })->name('web-api.convocatorias.roles-sennova');


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
            ->orWhere('roles.name', 'ilike', '%director de regional%')
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
            ->orWhere('roles.name', 'ilike', '%subdirector de centro de formación%')
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
     * Web api
     * 
     */
    Route::get('web-api/municipios', function() {
        return response(Municipio::select('municipios.id as value', 'municipios.nombre as label', 'departamentos.nombre as group')
        ->join('departamentos', 'departamentos.id', 'municipios.departamento_id')
        ->get());
    })->name('web-api.municipios');

    /**
     * Web api
     * 
     * Trae las Tecnoacademias
     */
    Route::get('web-api/tecnoacademias', function() {
        return response(Tecnoacademia::select('tecnoacademias.id as value', 'tecnoacademias.nombre as label')->get());
    })->name('web-api.tecnoacademias');

    /**
     * Web api
     * 
     * Trae las líneas tecnológicas
     */
    Route::get('web-api/tecnoacademias/{tecnoacademia}/lineas-tecnologicas', function($tecnoacademia) {
        return response(LineaTecnologica::select('id', 'nombre')->where('lineas_tecnologicas.tecnoacademia_id', $tecnoacademia)->get());
    })->name('web-api.tecnoacademias.lineas-tecnologicas');

    /**
     * Web api
     * 
     * Trae los tipos de proyectos
     */
    Route::get('web-api/tipos-proyecto/{tipo_proyecto}', function($tipoProyecto) {
        return response(TipoProyecto::selectRaw('tipos_proyecto.id as value, concat(tipos_proyecto.nombre, chr(10), \'∙ Línea programática: \', lineas_programaticas.codigo, \' - \', lineas_programaticas.nombre) as label')
            ->join('lineas_programaticas', 'tipos_proyecto.linea_programatica_id', 'lineas_programaticas.id')
            ->where('categoria_proyecto', 'ilike', '%'.$tipoProyecto.'%')
            ->get());
    })->name('web-api.tipos-proyecto');

    /**
     * Web api
     * 
     * Trae las redes de conocimiento 
     */
    Route::get('web-api/redes-conocimiento', function() {
        return response(RedConocimiento::select('redes_conocimiento.id as value', 'redes_conocimiento.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.redes-conocimiento');

    /**
     * Web api
     * 
     * Trae las disciplinas de subáreas de conocimiento
     */
    Route::get('web-api/disciplinas-subarea-conocimiento', function() {
        return response(DisciplinaSubareaConocimiento::select('disciplinas_subarea_conocimiento.id as value', 'disciplinas_subarea_conocimiento.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.disciplinas-subarea-conocimiento');

    /**
     * Web api
     * 
     * Trae los actividades económicas
     */
    Route::get('web-api/actividades-economicas', function() {
        return response(ActividadEconomica::select('actividades_economicas.id as value', 'actividades_economicas.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.actividades-economicas');

    /**
     * Web api
     * 
     * Trae las temáticas estrategicas SENA
     */
    Route::get('web-api/tematicas-estrategicas', function() {
        return response(TematicaEstrategica::select('tematicas_estrategicas.id as value', 'tematicas_estrategicas.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.tematicas-estrategicas');

    /**
     * Web api
     * 
     * Trae las líneas programáticas
     */
     Route::get('web-api/lineas-programaticas', function() {
        return response(LineaProgramatica::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.lineas-programaticas');

    
    /**
     * Web api
     * 
     * Trae las subtipologías Minciencias
     */
    Route::get('web-api/subtipologias-minciencias', function() {
        return response(SubtipologiaMinciencias::selectRaw('subtipologias_minciencias.id as value, concat(subtipologias_minciencias.nombre, chr(10), \'∙ Tipología Minciencias: \', tipologias_minciencias.nombre) as label')->join('tipologias_minciencias', 'subtipologias_minciencias.tipologia_minciencias_id', 'tipologias_minciencias.id')->orderBy('subtipologias_minciencias.nombre')->get());
    })->name('web-api.subtipologias-minciencias');


    /**
     * Convocatorias
     * 
     */
    Route::get('convocatorias/{convocatoria}/dashboard', [ConvocatoriaController::class, 'dashboard'])->name('convocatorias.dashboard');

    Route::resource('convocatorias.idi', IDiController::class)->parameters(['convocatorias' => 'convocatoria', 'idi' => 'IDi'])->except(['show']);

    Route::resource('convocatorias.idi.entidades-aliadas', EntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'idi' => 'IDi', 'entidades-aliadas' => 'entidad-aliada'])->except(['show']);

    Route::resource('convocatorias.idi.entidades-aliadas.miembros-entidad-aliada', MiembroEntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'idi' => 'IDi', 'entidades-aliadas' => 'entidad-aliada', 'miembros-entidad-aliada' => 'miembro-entidad-aliada'])->except(['show']);
    
    Route::resource('convocatorias', ConvocatoriaController::class)->parameters(['convocatorias' => 'convocatoria'])->except(['show']);

    /**
     * Muestra el árbol de objetivos
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/arbol-objetivos', [ArbolProyectoController::class, 'showArbolObjetivos'])->name('convocatorias.proyectos.arbol-objetivos');
    // Actualiza el impacto en el arbol de objetivos
    Route::post('proyectos/{proyecto}/impacto/{impacto}', [ArbolProyectoController::class, 'updateImpacto'])->name('proyectos.impacto');
    // Actualiza el impacto en el arbol de objetivos
    Route::post('proyectos/{proyecto}/resultado/{resultado}', [ArbolProyectoController::class, 'updateResultado'])->name('proyectos.resultado');
    // Actualiza el problema general del proyecto en el arbol de problemas
    Route::post('proyectos/{proyecto}/objetivo-general', [ArbolProyectoController::class, 'updateObjetivoGeneral'])->name('proyectos.objetivo-general');
    // Actualiza el objetivo especifico en el arbol de objetivos
    Route::post('proyectos/{proyecto}/objetivo-especifico/{objetivo_especifico}', [ArbolProyectoController::class, 'updateObjetivoEspecifico'])->name('proyectos.objetivo-especifico');
    // Actualiza la actividad en el arbol de objetivos
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/actividad/{actividad}', [ArbolProyectoController::class, 'updateActividad'])->name('proyectos.actividad');
    
    /**
     * Muestra el árbol de problemas
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/arbol-problemas', [ArbolProyectoController::class, 'showArbolProblemas'])->name('convocatorias.proyectos.arbol-problemas');
    // Actualiza el problema general del proyecto en el arbol de problemas
    Route::post('proyectos/{proyecto}/planteamiento-problema', [ArbolProyectoController::class, 'updatePlanteamientoProblema'])->name('proyectos.planteamiento-problema');
    // Actualiza efecto directo en el arbol de problemas
    Route::post('proyectos/{proyecto}/efecto-directo/{efecto_directo}', [ArbolProyectoController::class, 'updateEfectoDirecto'])->name('proyectos.efecto-directo');
    // Crea o Actualiza efecto indirecto en el arbol de problemas
    Route::post('proyectos/{proyecto}/efecto-indirecto/{efecto_directo}', [ArbolProyectoController::class, 'createOrUpdateEfectoIndirecto'])->name('proyectos.efecto-indirecto');
    // Actualiza causa directa en el arbol de problemas
    Route::post('proyectos/{proyecto}/causa-directa/{causa_directa}', [ArbolProyectoController::class, 'updateCausaDirecta'])->name('proyectos.causa-directa');
    // Crea o Actualiza causa indirecta en el arbol de problemas
    Route::post('proyectos/{proyecto}/causa-indirecta/{causa_directa}', [ArbolProyectoController::class, 'createOrUpdateCausaIndirecta'])->name('proyectos.causa-indirecta');
    
    /**
     * Productos
     * 
    */
    Route::resource('convocatorias.proyectos.productos', ProductoController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'productos' => 'producto'])->except(['show']);

    /**
     * Actividades
     * 
    */
    Route::resource('convocatorias.proyectos.actividades', ActividadController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'actividades' => 'actividad'])->except(['show']);

    /**
     * Mesas sectoriales
     * 
    */
    Route::resource('convocatorias.proyectos.proyecto-rol-sennova', ProyectoRolSennovaController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'proyecto-rol-sennova' => 'proyecto-rol-sennova'])->except(['show']);

    /**
     * Análisis de riesgos
     * 
    */
    Route::resource('convocatorias.proyectos.analisis-riesgos', AnalisisRiesgoController::class)->parameters(['analisis-riesgos' => 'analisis-riesgo']);

    /**
     * Anexos de proyecto
     * 
    */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/proyecto-anexos/{proyecto_anexo}/download', [ProyectoAnexoController::class, 'download'])->name('convocatorias.proyectos.proyecto-anexos.download');
    Route::resource('convocatorias.proyectos.proyecto-anexos', ProyectoAnexoController::class)->parameters(['proyecto-anexos' => 'proyecto-anexo']);


    Route::resource('convocatorias.proyectos.proyecto-presupuesto', ProyectoPresupuestoController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'proyecto-presupuesto' => 'proyecto-presupuesto']);

    Route::resource('convocatorias.proyectos.proyecto-presupuesto.lotes-estudio-mercado', ProyectLoteEstudioMercadoController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'proyecto-presupuesto' => 'proyecto-presupuesto', 'lotes-estudio-mercado' => 'lote-estudio-mercado']);
    
    Route::resources(
        [
            'convocatorias.convocatoria-sennova-roles' => ConvocatoriaRolSennovaController::class,
            'convocatoria-presupuesto' => PresupuestoConvocatoriaController::class,
            
            'primer-grupo-presupuestal' => PrimerGrupoPresupuestalController::class,
            'segundo-grupo-presupuestal' => SegundoGrupoPresupuestalController::class,
            'tercer-grupo-presupuestal' => TercerGrupoPresupuestalController::class,
            'usos-presupuestales' => UsoPresupuestalController::class,
            'presupuesto-sennova' => PresupuestoSennovaController::class,
            'roles-sennova' => RolSennovaController::class,
            'users' => UserController::class,
            'roles' => RoleController::class,
        ]
    );
});

require __DIR__.'/auth.php';
