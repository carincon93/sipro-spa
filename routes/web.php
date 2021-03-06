<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\WebController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\CentroFormacionController;
use App\Http\Controllers\ProgramaFormacionController;
use App\Http\Controllers\LineaProgramaticaController;
use App\Http\Controllers\RedConocimientoController;
use App\Http\Controllers\TematicaEstrategicaController;
use App\Http\Controllers\MesaTecnicaController;
use App\Http\Controllers\GrupoInvestigacionController;
use App\Http\Controllers\LineaInvestigacionController;
use App\Http\Controllers\SemilleroInvestigacionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\IdiController;
use App\Http\Controllers\ArbolProyectoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AmbienteModernizacionController;
use App\Http\Controllers\ConvocatoriaRolSennovaController;
use App\Http\Controllers\RolSennovaController;
use App\Http\Controllers\ProyectoRolSennovaController;
use App\Http\Controllers\PrimerGrupoPresupuestalController;
use App\Http\Controllers\SegundoGrupoPresupuestalController;
use App\Http\Controllers\TercerGrupoPresupuestalController;
use App\Http\Controllers\PresupuestoSennovaController;
use App\Http\Controllers\ConvocatoriaPresupuestoController;
use App\Http\Controllers\ProyectoPresupuestoController;
use App\Http\Controllers\AnalisisRiesgoController;
use App\Http\Controllers\EntidadAliadaController;
use App\Http\Controllers\AnexoController;
use App\Http\Controllers\ReglaRolStController;
use App\Http\Controllers\ReglaRolTaController;
use App\Http\Controllers\TaController;
use App\Http\Controllers\TpController;
use App\Http\Controllers\ProyectoAnexoController;
use App\Http\Controllers\UsoPresupuestalController;
use App\Http\Controllers\MiembroEntidadAliadaController;
use App\Http\Controllers\TecnoacademiaController;
use App\Http\Controllers\LineaTecnoacademiaController;
use App\Http\Controllers\MesaSectorialController;
use App\Http\Controllers\ServicioTecnologicoController;
use App\Http\Controllers\HelpDeskController;
use App\Http\Controllers\CulturaInnovacionController;
use App\Http\Controllers\DisCurricularController;
use App\Http\Controllers\EdtController;
use App\Http\Controllers\ProyectoCapacidadInstaladaController;
use App\Http\Controllers\Evaluacion\EvaluacionController;
use App\Http\Controllers\Evaluacion\IdiEvaluacionController;
use App\Http\Controllers\Evaluacion\CulturaInnovacionEvaluacionController;
use App\Http\Controllers\Evaluacion\ServicioTecnologicoEvaluacionController;
use App\Http\Controllers\Evaluacion\TaEvaluacionController;
use App\Http\Controllers\Evaluacion\TpEvaluacionController;
use App\Http\Controllers\InventarioEquipoController;
use App\Http\Controllers\ReglaRolCulturaController;
use App\Http\Controllers\ReglaRolTpController;
use App\Http\Controllers\SoporteEstudioMercadoController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProyectoIdiTecnoacademiaController;
use App\Http\Controllers\ReporteController;

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

/**
 * Trae los centros de formaci??n
 */
Route::get('web-api/centros-formacion', [WebController::class, 'centrosFormacion'])->name('web-api.centros-formacion');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('manual-usuario/download', [ProyectoController::class, 'downloadManualUsuario'])->name('manual-usuario.download');

    Route::get('/', [WebController::class, 'dashboard'])->name('dashboard');

    // Notificaciones
    Route::get('notificaciones', [UserController::class, 'showAllNotifications'])->name('notificaciones.index');
    Route::post('notificaciones/marcar-leido', [UserController::class, 'markAsReadNotification'])->name('notificaciones.marcar-leido');

    // Redirecciona seg??n el tipo de proyecto
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/editar', [ProyectoController::class, 'edit'])->name('convocatorias.proyectos.edit');

    //Exporta resumen proyecto PDF
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/pdf', [PdfController::class, 'generateResumenProyecto'])->name('convocatorias.proyectos.pdf');

    //Exporta proyecto post-cierre del ambiente de modernizacion en PDF
    Route::get('ambientes-modernizacion/{ambiente_modernizacion}/pdf', [AmbienteModernizacionController::class, 'descargarPdfAmbienteModernizacion'])->name('ambientes-modernizacion.descargar-pdf');

    // Reportar problemas
    // Route::get('reportar-problemas/crear', [HelpDeskController::class, 'create'])->name('reportar-problemas.create');
    // Route::post('reportar-problemas/reportar', [HelpDeskController::class, 'report'])->name('reportar-problemas.report');

    // Cambiar contrase??a
    Route::put('/users/cambiar-password', [UserController::class, 'changePassword'])->name('users.change-password');
    Route::get('/users/cambiar-password', [UserController::class, 'showChangePasswordForm'])->name('users.change-password-form');

    // Muestra los participantes
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes', [ProyectoController::class, 'participantes'])->name('convocatorias.proyectos.participantes');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/users', [ProyectoController::class, 'filterParticipantes'])->name('convocatorias.proyectos.participantes.users');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/users/link', [ProyectoController::class, 'linkParticipante'])->name('convocatorias.proyectos.participantes.users.link');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/users/link', [ProyectoController::class, 'updateParticipante'])->name('convocatorias.proyectos.participantes.users.update');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/users/register', [ProyectoController::class, 'registerParticipante'])->name('convocatorias.proyectos.participantes.users.register');
    Route::delete('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/users/unlink', [ProyectoController::class, 'unlinkParticipante'])->name('convocatorias.proyectos.participantes.users.unlink');

    // Vincula y filtra los programas
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/programas-formacion', [ProyectoController::class, 'filterProgramasFormacion'])->name('convocatorias.proyectos.participantes.programas-formacion');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/programas-formacion/link', [ProyectoController::class, 'linkProgramaFormacion'])->name('convocatorias.proyectos.participantes.programas-formacion.link');
    Route::delete('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/programas-formacion/unlink', [ProyectoController::class, 'unlinkProgramaFormacion'])->name('convocatorias.proyectos.participantes.programas-formacion.unlink');

    // Vincula y filtra los semilleros
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/semilleros-investigacion', [ProyectoController::class, 'filterSemillerosInvestigacion'])->name('convocatorias.proyectos.participantes.semilleros-investigacion');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/semilleros-investigacion/link', [ProyectoController::class, 'linkSemilleroInvestigacion'])->name('convocatorias.proyectos.participantes.semilleros-investigacion.link');
    Route::delete('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/semilleros-investigacion/unlink', [ProyectoController::class, 'unlinkSemilleroInvestigacion'])->name('convocatorias.proyectos.participantes.semilleros-investigacion.unlink');

    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/cadena-valor/propuesta-sostenibilidad', [ProyectoController::class, 'updatePropuestaSostenibilidad'])->name('convocatorias.proyectos.propuesta-sostenibilidad');
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/cadena-valor', [ProyectoController::class, 'showCadenaValor'])->name('convocatorias.proyectos.cadena-valor');
    Route::get('anexos/{anexo}/download', [AnexoController::class, 'download'])->name('anexos.download');

    // Trae los centros de formaci??n - Cultura innovaci??n
    Route::get('web-api/cultura-innovacion/centros-formacion', [WebController::class, 'culturaInnovacionCentrosFormacion'])->name('web-api.cultura-innovacion.centros-formacion');

    // Trae las actividades por resultado
    Route::get('web-api/resultados/{resultado}/actividades', [WebController::class, 'resultadosActividades'])->name('web-api.resultados.actividades');

    // Trae los conceptos internos SENA
    Route::get('web-api/segundo-grupo-presupuestal/{linea_programatica}', [WebController::class, 'segundoGrupoPresupuestal'])->name('web-api.segundo-grupo-presupuestal');

    Route::get('web-api/tercer-grupo-presupuestal/{segundo_grupo_presupuestal}', [WebController::class, 'tercerGrupoPresupuestal'])->name('web-api.tercer-grupo-presupuestal');

    // Trae los usos presupuestales
    Route::get('web-api/convocatorias/{convocatoria}/lineas-programaticas/{linea_programatica}/presupuesto-sennova/segundo-grupo-presupuestal/{segundo_grupo_presupuestal}/tercer-grupo-presupuestal/{tercer_grupo_presupuestal}', [WebController::class, 'usosPresupuestales'])->name('web-api.usos-presupuestales');

    Route::get('web-api/convocatorias/{convocatoria}/proyectos/{proyecto}/{linea_programatica}/roles-sennova', [WebController::class, 'rolesSennova'])->name('web-api.convocatorias.roles-sennova');

    /**
     * Programas de formaci??n
     * 
     */
    Route::get('web-api/centros-formacion/{centro_formacion}/programas-formacion', [WebController::class, 'programasFormacion'])->name('web-api.programas-formacion');

    /**
     * Estados de sistema de gesti??n
     * 
     */
    Route::get('web-api/estados-sistema-gestion/{tipo_proyecto_st}', [WebController::class, 'estadosSistemaGestion'])->name('web-api.estados-sistema-gestion');

    /**
     * Programas de formaci??n articulados
     * 
     */
    Route::get('web-api/programas-formacion-articulados', [WebController::class, 'programasFormacionArticulados'])->name('web-api.programas-formacion-articulados');

    /**
     * Regionales
     * 
     * Trae las regiones
     */
    Route::get('web-api/regiones', [WebController::class, 'regiones'])->name('web-api.regiones');

    /**
     * Trae las regionales
     */
    Route::get('web-api/regionales', [WebController::class, 'regionales'])->name('web-api.regionales');

    Route::resource('regionales', RegionalController::class)->parameters(['regionales' => 'regional'])->except(['show']);

    /**
     * Trae los centros de formaci??n por regional
     */
    Route::get('web-api/regional/{regional}/centros-formacion', [WebController::class, 'centrosFormacionRegional'])->name('web-api.centros-formacion-ejecutor');

    /**
     * Centros de formaci??n
     * 
     * Trae los subdirectores
     */
    Route::get('web-api/users/{rol}', [WebController::class, 'subdirectores'])->name('web-api.users');

    Route::resource('centros-formacion', CentroFormacionController::class)->except(['show'])->parameters(['centros-formacion' => 'centro-formacion']);

    /**
     * Programas de formaci??n
     * 
     */
    Route::resource('programas-formacion', ProgramaFormacionController::class)->parameters(['programas-formacion' => 'programa-formacion'])->except(['show']);

    /**
     * Tem??ticas estrat??gicas
     * 
     */
    Route::resource('tematicas-estrategicas', TematicaEstrategicaController::class)->parameters(['tematicas-estrategicas' => 'tematica-estrategica'])->except(['show']);

    /**
     * Mesas t??cnicas
     * 
     */
    Route::resource('mesas-tecnicas', MesaTecnicaController::class)->parameters(['mesas-tecnicas' => 'mesa-tecnica'])->except(['show']);

    /**
     * Anexos
     * 
     */
    Route::resource('anexos', AnexoController::class)->parameters(['anexos' => 'anexo'])->except(['show']);

    /**
     * L??neas program??ticas
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
     * L??neas tecnoacademia
     * 
     */
    Route::resource('lineas-tecnoacademia', LineaTecnoacademiaController::class)->parameters(['lineas-tecnoacademia' => 'linea-tecnoacademia'])->except(['show']);

    /**
     * Grupos de investigaci??n
     * 
     */
    Route::get('grupos-investigacion/{grupo_investigacion}/download/{formato}', [GrupoInvestigacionController::class, 'descargarFormato'])->name('grupos-investigacion.download');

    Route::resource('grupos-investigacion', GrupoInvestigacionController::class)->parameters(['grupos-investigacion' => 'grupo-investigacion'])->except(['show']);

    /**
     * L??neas de investigaci??n
     * 
     * Trae los grupos de investigaci??n
     * 
     */
    Route::get('web-api/grupos-investigacion', [WebController::class, 'gruposInvestigacion'])->name('web-api.grupos-investigacion');

    Route::get('web-api/centros-formacion-grupo-investigacion/{grupo_investigacion}', [WebController::class, 'centrosFormacionGrupoInvestigacion'])->name('web-api.centros-formacion-grupo-investigacion');

    Route::resource('grupos-investigacion.lineas-investigacion', LineaInvestigacionController::class)->parameters(['grupos-investigacion' => 'grupo-investigacion', 'lineas-investigacion' => 'linea-investigacion'])->except(['show']);

    /**
     * Semilleros de investigaci??n
     * 
     * Trae las l??neas de investigaci??n
     */
    Route::get('web-api/lineas-investigacion/{centro_formacion}', [WebController::class, 'lineasInvestigacion'])->name('web-api.lineas-investigacion');

    Route::resource('grupos-investigacion.semilleros-investigacion', SemilleroInvestigacionController::class)->parameters(['grupos-investigacion' => 'grupo-investigacion', 'semilleros-investigacion' => 'semillero-investigacion'])->except(['show']);
    Route::get('grupos-investigacion/{grupo_investigacion}/semilleros-investigacion/{semillero_investigacion}/download/{formato}', [SemilleroInvestigacionController::class, 'descargarFormato'])->name('grupos-investigacion.semilleros-investigacion.download');

    Route::resource('grupos-investigacion.semilleros-investigacion', SemilleroInvestigacionController::class)->parameters(['grupos-investigacion' => 'grupo-investigacion', 'semilleros-investigacion' => 'semillero-investigacion'])->except(['show']);

    /**
     * Proyectos de capacidad instalada
     * 
     */
    Route::post('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/{integrante}', [ProyectoCapacidadInstaladaController::class, 'cambiarAutorPrincipal'])->name('proyectos-capacidad-instalada.nuevo-autor-principal');
    Route::put('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/column/{column}', [ProyectoCapacidadInstaladaController::class, 'updateLongColumn'])->name('proyectos-capacidad-instalada.updateLongColumn');
    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/objetivos-especificos', [ProyectoCapacidadInstaladaController::class, 'indexObjetivosEspecificos'])->name('proyectos-capacidad-instalada.objetivos-especificos.index');
    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/objetivos-especificos/crear', [ProyectoCapacidadInstaladaController::class, 'createObjetivoEspecifico'])->name('proyectos-capacidad-instalada.objetivos-especificos.create');
    Route::post('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/objetivos-especificos/crear', [ProyectoCapacidadInstaladaController::class, 'storeObjetivoEspecifico'])->name('proyectos-capacidad-instalada.objetivos-especificos.store');
    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/objetivos-especificos/{objetivo_especifico}/editar', [ProyectoCapacidadInstaladaController::class, 'editObjetivoEspecifico'])->name('proyectos-capacidad-instalada.objetivos-especificos.edit');
    Route::put('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/objetivos-especificos/{objetivo_especifico}/editar', [ProyectoCapacidadInstaladaController::class, 'updateObjetivoEspecifico'])->name('proyectos-capacidad-instalada.objetivos-especificos.update');
    Route::delete('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/objetivos-especificos/{objetivo_especifico}', [ProyectoCapacidadInstaladaController::class, 'destroyObjetivoEspecifico'])->name('proyectos-capacidad-instalada.objetivos-especificos.destroy');

    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/integrantes', [ProyectoCapacidadInstaladaController::class, 'indexIntegrantes'])->name('proyectos-capacidad-instalada.integrantes.index');
    Route::post('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/integrantes/users', [ProyectoCapacidadInstaladaController::class, 'filterIntegrantes'])->name('proyectos-capacidad-instalada.integrantes.users');
    Route::post('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/integrantes/users/link', [ProyectoCapacidadInstaladaController::class, 'linkIntegrante'])->name('proyectos-capacidad-instalada.integrantes.users.link');
    Route::put('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/integrantes/users/link', [ProyectoCapacidadInstaladaController::class, 'updateParticipante'])->name('proyectos-capacidad-instalada.integrantes.users.update');
    Route::delete('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/integrantes/users/unlink', [ProyectoCapacidadInstaladaController::class, 'unlinkIntegrante'])->name('proyectos-capacidad-instalada.integrantes.users.unlink');
    Route::post('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/integrantes/users/register', [ProyectoCapacidadInstaladaController::class, 'registerIntegrante'])->name('proyectos-capacidad-instalada.integrantes.users.register');

    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/entidades-aliadas/crear', [ProyectoCapacidadInstaladaController::class, 'createEntidadAliada'])->name('proyectos-capacidad-instalada.entidades-aliadas.create');
    Route::post('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/entidades-aliadas/crear', [ProyectoCapacidadInstaladaController::class, 'storeEntidadAliada'])->name('proyectos-capacidad-instalada.entidades-aliadas.store');

    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/entidades-aliadas/{entidad_aliada}/download', [ProyectoCapacidadInstaladaController::class, 'download'])->name('proyectos-capacidad-instalada.entidades-aliadas.download');
    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/entidades-aliadas/{entidad_aliada}/editar', [ProyectoCapacidadInstaladaController::class, 'editEntidadAliada'])->name('proyectos-capacidad-instalada.entidades-aliadas.edit');
    Route::put('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/entidades-aliadas/{entidad_aliada}/editar', [ProyectoCapacidadInstaladaController::class, 'updateEntidadAliada'])->name('proyectos-capacidad-instalada.entidades-aliadas.update');
    Route::delete('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/entidades-aliadas/{entidad_aliada}', [ProyectoCapacidadInstaladaController::class, 'destroyEntidadAliada'])->name('proyectos-capacidad-instalada.entidades-aliadas.destroy');

    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/productos', [ProyectoCapacidadInstaladaController::class, 'indexProductos'])->name('proyectos-capacidad-instalada.productos.index');
    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/productos/crear', [ProyectoCapacidadInstaladaController::class, 'createProducto'])->name('proyectos-capacidad-instalada.productos.create');
    Route::post('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/productos/crear', [ProyectoCapacidadInstaladaController::class, 'storeProducto'])->name('proyectos-capacidad-instalada.productos.store');
    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/productos/{producto}/editar', [ProyectoCapacidadInstaladaController::class, 'editProducto'])->name('proyectos-capacidad-instalada.productos.edit');
    Route::put('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/productos/{producto}/editar', [ProyectoCapacidadInstaladaController::class, 'updateProducto'])->name('proyectos-capacidad-instalada.productos.update');
    Route::delete('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/productos/{producto}', [ProyectoCapacidadInstaladaController::class, 'destroyProducto'])->name('proyectos-capacidad-instalada.productos.destroy');

    Route::post('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/finalizar/proyecto', [ProyectoCapacidadInstaladaController::class, 'storeFinalizar'])->name('proyectos-capacidad-instalada.store.finalizar');
    Route::get('proyectos-capacidad-instalada/{proyecto_capacidad_instalada}/finalizar', [ProyectoCapacidadInstaladaController::class, 'finalizar'])->name('proyectos-capacidad-instalada.finalizar');

    Route::resource('proyectos-capacidad-instalada', ProyectoCapacidadInstaladaController::class)->parameters(['proyectos-capacidad-instalada' => 'proyecto-capacidad-instalada'])->except(['show']);

    /**
     * Proyectos I+D+i de Tecnoacademias
     * 
     */
    Route::get('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/download/{archivo}', [ProyectoIdiTecnoacademiaController::class, 'descargarPdf'])->name('proyectos-idi-tecnoacademia.download');
    Route::get('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/productos/{producto}/download', [ProyectoIdiTecnoacademiaController::class, 'descargarSoportesProducto'])->name('proyectos-idi-tecnoacademia.productos.download');

    Route::get('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/productos', [ProyectoIdiTecnoacademiaController::class, 'indexProductos'])->name('proyectos-idi-tecnoacademia.productos.index');
    Route::get('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/productos/crear', [ProyectoIdiTecnoacademiaController::class, 'createProducto'])->name('proyectos-idi-tecnoacademia.productos.create');
    Route::post('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/productos/crear', [ProyectoIdiTecnoacademiaController::class, 'storeProducto'])->name('proyectos-idi-tecnoacademia.productos.store');
    Route::get('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/productos/{producto}/editar', [ProyectoIdiTecnoacademiaController::class, 'editProducto'])->name('proyectos-idi-tecnoacademia.productos.edit');
    Route::put('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/productos/{producto}/editar', [ProyectoIdiTecnoacademiaController::class, 'updateProducto'])->name('proyectos-idi-tecnoacademia.productos.update');
    Route::delete('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/productos/{producto}', [ProyectoIdiTecnoacademiaController::class, 'destroyProducto'])->name('proyectos-idi-tecnoacademia.productos.destroy');

    Route::get('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/participantes', [ProyectoIdiTecnoacademiaController::class, 'indexParticipantes'])->name('proyectos-idi-tecnoacademia.participantes.index');

    Route::post('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/participantes/users', [ProyectoIdiTecnoacademiaController::class, 'filterParticipantes'])->name('proyectos-idi-tecnoacademia.participantes.users');
    Route::post('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/participantes/users/link', [ProyectoIdiTecnoacademiaController::class, 'linkParticipante'])->name('proyectos-idi-tecnoacademia.participantes.users.link');
    Route::put('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/participantes/users/link', [ProyectoIdiTecnoacademiaController::class, 'updateParticipante'])->name('proyectos-idi-tecnoacademia.participantes.users.update');
    Route::delete('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/participantes/users/unlink', [ProyectoIdiTecnoacademiaController::class, 'unlinkParticipante'])->name('proyectos-idi-tecnoacademia.participantes.users.unlink');
    Route::post('proyectos-idi-tecnoacademia/{proyecto_idi_tecnoacademia}/participantes/users/register', [ProyectoIdiTecnoacademiaController::class, 'registerParticipante'])->name('proyectos-idi-tecnoacademia.participantes.users.register');

    Route::resource('proyectos-idi-tecnoacademia', ProyectoIdiTecnoacademiaController::class)->parameters(['proyectos-idi-tecnoacademia' => 'proyecto-idi-tecnoacademia']);

    /**
     * Mesas sectoriales
     * 
     */
    Route::resource('mesas-sectoriales', MesaSectorialController::class)->parameters(['mesas-sectoriales' => 'mesa-sectorial'])->except(['show']);

    /**
     * Web api
     * 
     */
    Route::get('web-api/municipios', [WebController::class, 'municipios'])->name('web-api.municipios');

    /**
     * Web api
     * 
     * Trae las Tecnoacademias
     */
    Route::get('web-api/tecnoacademias', [WebController::class, 'tecnoacademias'])->name('web-api.tecnoacademias');

    /**
     * Web api
     * 
     * Trae las tecnoacademias centro_formacion
     */
    Route::get('web-api/centros-formacion/{centro_formacion}/tecnoacademias', [WebController::class, 'tecnoacademiasCentroFormacion'])->name('web-api.centros-formacion.tecnoacademias');

    /**
     * Web api
     * 
     * Trae las l??neas tecnoacademia
     */
    Route::get('web-api/tecnoacademias/{tecnoacademia}/lineas-tecnoacademia', [WebController::class, 'l??neasTecnoacademia'])->name('web-api.tecnoacademias.lineas-tecnoacademia');

    /**
     * Web api
     * 
     * Trae los nodos tecnoparque
     */
    Route::get('web-api/nodos-tecnoparque/{centro_formacion}', [WebController::class, 'nodosTecnoparque'])->name('web-api.nodos-tecnoparque');

    /**
     * Web api
     * 
     * Trae las l??neas program??ticas
     */
    Route::get('web-api/lineas-programaticas/{categoria_proyecto}', [WebController::class, 'l??neasProgramaticas'])->name('web-api.lineas-programaticas');

    /**
     * Web api
     * 
     * Trae los programas de formaci??n por l??nea de investigaci??n
     */
    Route::get('web-api/linea-investigacion-programa-formacion/{linea_investigacion}', [WebController::class, 'l??neaInvestigacionProgramaFormacion'])->name('web-api.linea-investigacion-programa-formacion');

    /**
     * Web api
     * 
     * Trae las redes de conocimiento 
     */
    Route::get('web-api/redes-conocimiento', [WebController::class, 'redesConocimiento'])->name('web-api.redes-conocimiento');

    /**
     * Web api
     * 
     * Trae las ??reas de conocimiento
     */
    Route::get('web-api/areas-conocimiento', [WebController::class, 'areasConocimiento'])->name('web-api.areas-conocimiento');

    /**
     * Web api
     * 
     * Trae las sub??reas de conocimiento
     */
    Route::get('web-api/subareas-conocimiento/{area_conocimiento}', [WebController::class, 'subareasConocimiento'])->name('web-api.subareas-conocimiento');

    /**
     * Web api
     * 
     * Trae las disciplinas de sub??reas de conocimiento
     */
    Route::get('web-api/disciplinas-subarea-conocimiento/{subarea_conocimiento}', [WebController::class, 'disciplinasSubareaConocimiento'])->name('web-api.disciplinas-subarea-conocimiento');

    /**
     * Web api
     * 
     * Trae los tipos de proyectos de capacidad instalada
     */
    Route::get('web-api/tipos-proyecto-capacidad-instalada', [WebController::class, 'tiposProyectoCapacidadInstalada'])->name('web-api.tipos-proyecto-capacidad-instalada');

    /**
     * Web api
     * 
     * Trae los subtipos de proyectos de capacidad instalada
     */
    Route::get('web-api/subtipos-proyecto-capacidad-instalada/{tipo_proy_capacidad_instalada}', [WebController::class, 'subtiposProyectoCapacidadInstalada'])->name('web-api.subtipos-proyecto-capacidad-instalada');

    /**
     * Web api
     * 
     * Trae los semilleros de investigaci??n
     */
    Route::get('web-api/semilleros-conocimiento/{linea_investigacion}', [WebController::class, 'semillerosInvestigacion'])->name('web-api.semilleros-investigacion');

    /**
     * Web api
     * 
     * Trae los actividades econ??micas
     */
    Route::get('web-api/actividades-economicas', [WebController::class, 'actividadesEconomicas'])->name('web-api.actividades-economicas');

    /**
     * Web api
     * 
     * Trae las tem??ticas estrategicas SENA
     */
    Route::get('web-api/tematicas-estrategicas', [WebController::class, 'tematicasEstrategicas'])->name('web-api.tematicas-estrategicas');

    /**
     * Web api
     * 
     * Trae las subtipolog??as Minciencias
     */
    Route::get('web-api/subtipologias-minciencias', [WebController::class, 'subtipologiasMinciencias'])->name('web-api.subtipologias-minciencias');

    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/finalizar-proyecto', [ProyectoController::class, 'summary'])->name('convocatorias.proyectos.summary');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/finalizar-proyecto', [ProyectoController::class, 'finalizarProyecto'])->name('convocatorias.proyectos.finish');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/enviar-proyecto-evaluar', [ProyectoController::class, 'enviarAEvaluacion'])->name('convocatorias.proyectos.send');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/comentario-proyecto', [ProyectoController::class, 'devolverProyecto'])->name('convocatorias.proyectos.return-project');
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/descargar-version/{version}', [ProyectoController::class, 'descargarPdf'])->name('convocatorias.proyectos.version');
    /**
     * Inventario equipos - Estrategia regional
     * 
     */
    Route::resource('convocatorias.proyectos.inventario-equipos', InventarioEquipoController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'inventario-equipos' => 'inventario-equipo'])->except(['show']);

    /**
     * Idi - Estrategia regional
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/entidades-aliadas/{entidad_aliada}/{archivo}/download', [EntidadAliadaController::class, 'download'])->name('convocatorias.proyectos.entidades-aliadas.download');
    Route::resource('convocatorias.idi', IdiController::class)->parameters(['convocatorias' => 'convocatoria', 'idi' => 'idi'])->except(['show']);

    Route::put('convocatorias/{convocatoria}/idi/{idi}/column/{column}', [IdiController::class, 'updateLongColumn'])->name('convocatorias.idi.updateLongColumn');

    Route::resource('convocatorias.proyectos.entidades-aliadas', EntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'entidades-aliadas' => 'entidad-aliada'])->except(['show']);

    Route::resource('convocatorias.proyectos.entidades-aliadas.miembros-entidad-aliada', MiembroEntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'entidades-aliadas' => 'entidad-aliada', 'miembros-entidad-aliada' => 'miembro-entidad-aliada'])->except(['show']);

    /**
     * Cultura innovacion - Estrategia Nacional
     * 
     */
    Route::post('convocatorias/{convocatoria}/cultura-innovacion/{cultura_innovacion}/column/{column}', [CulturaInnovacionController::class, 'updateLongColumn'])->name('convocatorias.cultura-innovacion.updateLongColumn');
    Route::resource('reglas-roles-cultura', ReglaRolCulturaController::class)->parameters(['reglas-roles-cultura' => 'regla-rol-cultura'])->except(['show']);
    Route::resource('convocatorias.cultura-innovacion', CulturaInnovacionController::class)->parameters(['convocatorias' => 'convocatoria', 'cultura-innovacion' => 'cultura-innovacion'])->except(['show']);

    /**
     * Tp - Estrategia nacional
     * 
     */
    Route::resource('reglas-roles-tp', ReglaRolTpController::class)->parameters(['reglas-roles-tp' => 'regla-rol-tp'])->except(['show']);
    Route::resource('convocatorias.tp', TpController::class)->parameters(['convocatorias' => 'convocatoria', 'tp' => 'tp'])->except(['show']);
    Route::put('convocatorias/{convocatoria}/tp/{tp}/column/{column}', [TpController::class, 'updateLongColumn'])->name('convocatorias.tp.updateLongColumn');

    /**
     * Ta - Estrategia nacional
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/articulacion', [TaController::class, 'showArticulacionSennova'])->name('convocatorias.proyectos.articulacion-sennova');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/articulacion', [TaController::class, 'storeArticulacionSennova'])->name('convocatorias.proyectos.articulacion-sennova.store');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/programas-formacion', [ProyectoController::class, 'storeProgramaFormacion'])->name('convocatorias.proyectos.programas-formacion.store');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/discurriculares', [DisCurricularController::class, 'storeDisCurricular'])->name('convocatorias.proyectos.dis-curriculares.store');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/rol/sennova/ta', [TaController::class, 'updateCantidadRolesTa'])->name('convocatorias.proyectos.rol-sennova-ta.update');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/infraestructura', [TaController::class, 'updateInfraestructura'])->name('convocatorias.ta.infraestrucutra.update');

    Route::resource('reglas-roles-ta', ReglaRolTaController::class)->parameters(['reglas-roles-ta' => 'regla-rol-ta'])->except(['show']);
    Route::resource('convocatorias.proyectos.edt', EdtController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'edt' => 'edt'])->except(['show']);
    Route::resource('convocatorias.ta', TaController::class)->parameters(['convocatorias' => 'convocatoria', 'ta' => 'ta'])->except(['show']);
    Route::put('convocatorias/{convocatoria}/ta/{ta}/column/{column}', [TaController::class, 'updateLongColumn'])->name('convocatorias.ta.updateLongColumn');

    /**
     * Servicios tecnol??gicos - Estrategia  nacional
     * 
     */
    Route::resource('reglas-roles-st', ReglaRolStController::class)->parameters(['reglas-roles-st' => 'regla-rol-st'])->except(['show']);
    Route::put('convocatorias/{convocatoria}/servicios-tecnologicos/{servicio_tecnologico}/infraestructura', [ServicioTecnologicoController::class, 'updateEspecificacionesInfraestructura'])->name('convocatorias.servicios-tecnologicos.infraestructura');
    Route::resource('convocatorias.servicios-tecnologicos', ServicioTecnologicoController::class)->parameters(['convocatorias' => 'convocatoria', 'servicios-tecnologicos' => 'servicio-tecnologico'])->except(['show']);
    Route::put('convocatorias/{convocatoria}/servicios-tecnologicos/{servicio_tecnologico}/column/{column}', [ServicioTecnologicoController::class, 'updateLongColumn'])->name('convocatorias.servicios-tecnologicos.updateLongColumn');

    /**
     * Convocatorias
     * 
     */
    Route::get('nuevos-proyectos-ta-tp', [ConvocatoriaController::class, 'nuevosProyectosTaTp'])->name('nuevos-proyectos-ta-tp');
    Route::get('convocatorias/{convocatoria}/dashboard', [ConvocatoriaController::class, 'dashboard'])->name('convocatorias.dashboard');
    Route::put('convocatorias/{convocatoria}/update-fase', [ConvocatoriaController::class, 'updateFase'])->name('convocatorias.update-fase');

    Route::resource('convocatorias', ConvocatoriaController::class)->parameters(['convocatorias' => 'convocatoria'])->except(['show']);

    /**
     * Comentarios generales
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/comentarios-generales', [ProyectoController::class, 'showComentariosGeneralesForm'])->name('convocatorias.proyectos.comentarios-generales-form');
    Route::post('convocatorias/{convocatoria}/{evaluacion}/comentarios-generales', [ProyectoController::class, 'udpdateComentariosGenerales'])->name('convocatorias.proyectos.update-comentarios');

    /**
     * Muestra el ??rbol de objetivos
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/arbol-objetivos', [ArbolProyectoController::class, 'showArbolObjetivos'])->name('convocatorias.proyectos.arbol-objetivos');
    // Actualiza el impacto en el arbol de objetivos
    Route::post('proyectos/{proyecto}/impacto/{impacto}', [ArbolProyectoController::class, 'updateImpacto'])->name('proyectos.impacto');
    Route::post('proyectos/{proyecto}/impacto/{impacto}/destroy', [ArbolProyectoController::class, 'destroyImpacto'])->name('proyectos.impacto.destroy');
    // Actualiza el impacto en el arbol de objetivos
    Route::post('proyectos/{proyecto}/resultado/{resultado}', [ArbolProyectoController::class, 'updateResultado'])->name('proyectos.resultado');
    Route::post('proyectos/{proyecto}/resultado/{resultado}/destroy', [ArbolProyectoController::class, 'destroyResultado'])->name('proyectos.resultado.destroy');
    // Actualiza el problema general del proyecto en el arbol de problemas
    Route::post('proyectos/{proyecto}/objetivo-general', [ArbolProyectoController::class, 'updateObjetivoGeneral'])->name('proyectos.objetivo-general');
    // Actualiza el objetivo especifico en el arbol de objetivos
    Route::post('proyectos/{proyecto}/objetivo-especifico/{objetivo_especifico}', [ArbolProyectoController::class, 'updateObjetivoEspecifico'])->name('proyectos.objetivo-especifico');
    Route::post('proyectos/{proyecto}/objetivo-especifico/{objetivo_especifico}/destroy', [ArbolProyectoController::class, 'destroyObjetivoEspecifico'])->name('proyectos.objetivo-especifico.destroy');
    // Actualiza la actividad en el arbol de objetivos
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/actividad/{actividad}', [ArbolProyectoController::class, 'updateActividad'])->name('proyectos.actividad');
    Route::post('proyectos/{proyecto}/actividad/{actividad}/destroy', [ArbolProyectoController::class, 'destroyActividad'])->name('proyectos.actividad.destroy');

    /**
     * Muestra el ??rbol de problemas
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/arbol-problemas', [ArbolProyectoController::class, 'showArbolProblemas'])->name('convocatorias.proyectos.arbol-problemas');
    // Actualiza el problema general del proyecto en el arbol de problemas
    Route::post('proyectos/{proyecto}/problema-central', [ArbolProyectoController::class, 'updateProblemaCentral'])->name('proyectos.problema-central');
    // Actualiza efecto directo en el arbol de problemas
    Route::post('proyectos/{proyecto}/efecto-directo/{efecto_directo}', [ArbolProyectoController::class, 'updateEfectoDirecto'])->name('proyectos.efecto-directo');
    Route::post('proyectos/{proyecto}/efecto-directo/{efecto_directo}/destroy', [ArbolProyectoController::class, 'destroyEfectoDirecto'])->name('proyectos.efecto-directo.destroy');
    // Crea o Actualiza efecto indirecto en el arbol de problemas
    Route::post('proyectos/{proyecto}/efecto-indirecto/{efecto_directo}', [ArbolProyectoController::class, 'createOrUpdateEfectoIndirecto'])->name('proyectos.efecto-indirecto');
    Route::post('proyectos/{proyecto}/efecto-indirecto/{efecto_indirecto}/destroy', [ArbolProyectoController::class, 'destroyEfectoIndirecto'])->name('proyectos.efecto-indirecto.destroy');

    // Actualiza causa directa en el arbol de problemas
    Route::post('proyectos/{proyecto}/causa-directa/{causa_directa}', [ArbolProyectoController::class, 'updateCausaDirecta'])->name('proyectos.causa-directa');
    Route::post('proyectos/{proyecto}/causa-directa/{causa_directa}/destroy', [ArbolProyectoController::class, 'destroyCausaDirecta'])->name('proyectos.causa-directa.destroy');
    // Crea o Actualiza causa indirecta en el arbol de problemas
    Route::post('proyectos/{proyecto}/causa-indirecta/{causa_directa}', [ArbolProyectoController::class, 'createOrUpdateCausaIndirecta'])->name('proyectos.causa-indirecta');
    Route::post('proyectos/{proyecto}/causa-indirecta/{causa_indirecta}/destroy', [ArbolProyectoController::class, 'destroyCausaIndirecta'])->name('proyectos.causa-indirecta.destroy');

    /**
     * Productos
     * 
     */
    Route::resource('convocatorias.proyectos.productos', ProductoController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'productos' => 'producto'])->except(['show']);

    /**
     * Actividades
     * 
     */
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/actividades/metodologia', [ActividadController::class, 'updateMetodologia'])->name('convocatorias.proyectos.metodologia');
    Route::resource('convocatorias.proyectos.actividades', ActividadController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'actividades' => 'actividad'])->except(['show']);

    /**
     * Roles SENNOVA de proyecto
     * 
     */
    Route::resource('convocatorias.proyectos.proyecto-rol-sennova', ProyectoRolSennovaController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'proyecto-rol-sennova' => 'proyecto-rol-sennova'])->except(['show']);

    /**
     * An??lisis de riesgos
     * 
     */
    Route::resource('convocatorias.proyectos.analisis-riesgos', AnalisisRiesgoController::class)->parameters(['analisis-riesgos' => 'analisis-riesgo'])->except(['show']);

    /**
     * Anexos de proyecto
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/proyecto-anexos/{proyecto_anexo}/download', [ProyectoAnexoController::class, 'download'])->name('convocatorias.proyectos.proyecto-anexos.download');
    Route::resource('convocatorias.proyectos.proyecto-anexos', ProyectoAnexoController::class)->parameters(['proyecto-anexos' => 'proyecto-anexo'])->except(['show']);

    /**
     * Presupuesto de proyecto
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/formato-estudio-mercado', [ProyectoPresupuestoController::class, 'downloadFormatoEstudioMercado'])->name('convocatorias.proyectos.presupuesto.download-formato-estudio-mercado');
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/presupuesto/{presupuesto}/download', [ProyectoPresupuestoController::class, 'download'])->name('convocatorias.proyectos.presupuesto.download');
    Route::resource('convocatorias.proyectos.presupuesto', ProyectoPresupuestoController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'presupuesto' => 'presupuesto'])->except(['show']);

    /**
     * Soportes de estudio de mercado
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/presupuesto/{presupuesto}/soportes/{soporte}/download', [SoporteEstudioMercadoController::class, 'download'])->name('convocatorias.proyectos.presupuesto.soportes.download');
    Route::resource('convocatorias.proyectos.presupuesto.soportes', SoporteEstudioMercadoController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'presupuesto' => 'presupuesto', 'soportes' => 'soporte'])->except(['show']);

    /**
     * Rol SENNOVA de convocatoria
     * 
     */
    Route::resource('convocatorias.convocatoria-rol-sennova',  ConvocatoriaRolSennovaController::class)->parameters(['convocatorias' => 'convocatoria', 'convocatoria-rol-sennova' => 'convocatoria-rol-sennova'])->except(['show']);

    /**
     * Presupuesto de convocatoria
     * 
     */
    Route::resource('convocatorias.convocatoria-presupuesto',  ConvocatoriaPresupuestoController::class)->parameters(['convocatorias' => 'convocatoria', 'convocatoria-presupuesto' => 'convocatoria-presupuesto'])->except(['show']);

    /**
     * Primer grupo presupuestal
     * 
     */
    Route::resource('primer-grupo-presupuestal',  PrimerGrupoPresupuestalController::class)->parameters(['primer-grupo-presupuestal' => 'primer-grupo-presupuestal'])->except(['show']);

    /**
     * Segundo grupo presupuestal
     * 
     */
    Route::resource('segundo-grupo-presupuestal',  SegundoGrupoPresupuestalController::class)->parameters(['segundo-grupo-presupuestal' => 'segundo-grupo-presupuestal'])->except(['show']);

    /**
     * Tercer grupo presupuestal
     * 
     */
    Route::resource('tercer-grupo-presupuestal',  TercerGrupoPresupuestalController::class)->parameters(['tercer-grupo-presupuestal' => 'tercer-grupo-presupuestal'])->except(['show']);

    /**
     * Usos presupuestales
     * 
     */
    Route::resource('usos-presupuestales',  UsoPresupuestalController::class)->parameters(['usos-presupuestales' => 'uso-presupuestal'])->except(['show']);

    /**
     * Presupuesto SENNOVA
     * 
     */
    Route::resource('presupuesto-sennova',  PresupuestoSennovaController::class)->parameters(['presupuesto-sennova' => 'presupuesto-sennova'])->except(['show']);

    Route::get('configuracion-presupuesto-sennova',  [PresupuestoSennovaController::class, 'configuracionPresupuestoSennova'])->name('configuracion-presupuesto-sennova');


    /**
     * Roles SENNOVA
     * 
     */
    Route::resource('roles-sennova',  RolSennovaController::class)->parameters(['roles-sennova' => 'rol-sennova'])->except(['show']);

    /**
     * Usuarios
     * 
     */
    Route::get('users/online', [UserController::class, 'enLinea'])->name('users.online');
    Route::resource('users',  UserController::class)->except(['show']);

    /**
     * Roles de sistema
     * 
     */
    Route::resource('roles', RoleController::class)->except(['show']);

    /**
     * Proyectos modernizaci??n
     * 
     */
    Route::put('ambientes-modernizacion/{ambiente_modernizacion}/column/{column}', [AmbienteModernizacionController::class, 'updateLongColumn'])->name('ambientes-modernizacion.updateLongColumn');
    Route::get('ambientes-modernizacion/{ambiente_modernizacion}/download', [AmbienteModernizacionController::class, 'download'])->name('ambientes-modernizacion.download');
    Route::post('ambientes-modernizacion/{ambiente_modernizacion}/equipos-ambiente-modernizacion/store', [AmbienteModernizacionController::class, 'equiposStore'])->name('equipos-ambiente-modernizacion.store');
    Route::delete('ambientes-modernizacion/equipos-ambiente-modernizacion/{equipo_ambiente_modernizacion}/destroy', [AmbienteModernizacionController::class, 'destroyEquipo'])->name('equipos-ambiente-modernizacion.destroy');
    Route::resource('ambientes-modernizacion', AmbienteModernizacionController::class)->parameters(['ambientes-modernizacion' => 'ambiente-modernizacion'])->except(['show']);

    /**
     * Proyectos
     * 
     */
    Route::get('proyectos/activos', [ProyectoController::class, 'activos'])->name('proyectos.activos');
    Route::get('proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
    Route::get('proyectos/{proyecto}/editar', [ProyectoController::class, 'editProyecto'])->name('proyectos.edit');
    Route::put('proyectos/{proyecto}/editar', [ProyectoController::class, 'update'])->name('proyectos.update');
    Route::post('proyectos/actualizar-estados-proyectos', [ProyectoController::class, 'udpdateEstadosProyectos'])->name('proyectos.update.actualizar-estados-proyectos');

    /**
     * Evaluaciones
     * 
     */
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/finalizar-evaluacion', [ProyectoController::class, 'summaryEvaluacion'])->name('convocatorias.evaluaciones.summary');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/redireccionar', [EvaluacionController::class, 'redireccionar'])->name('convocatorias.evaluaciones.redireccionar');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/participantes', [ProyectoController::class, 'participantesEvaluacion'])->name('convocatorias.evaluaciones.participantes');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/proyecto-rol-sennova', [ProyectoRolSennovaController::class, 'proyectoRolEvaluacion'])->name('convocatorias.evaluaciones.proyecto-rol-sennova.index');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/proyecto-rol-sennova/{proyecto_rol_sennova}/editar', [ProyectoRolSennovaController::class, 'evaluacionForm'])->name('convocatorias.evaluaciones.proyecto-rol-sennova.edit');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/proyecto-rol-sennova/{proyecto_rol_sennova}', [ProyectoRolSennovaController::class, 'updateEvaluacion'])->name('convocatorias.evaluaciones.proyecto-rol-sennova.update');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/articulacion', [TaController::class, 'showArticulacionSennovaEvaluacion'])->name('convocatorias.evaluaciones.articulacion-sennova');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/articulacion', [TaController::class, 'updatedArticulacionSennovaEvaluacion'])->name('convocatorias.evaluaciones.articulacion-sennova.guardar-evaluacion');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/presupuesto', [ProyectoPresupuestoController::class, 'updatedProyectoPresupuestoEvaluacion'])->name('convocatorias.evaluaciones.proyecto-presupuesto.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/presupuesto', [ProyectoPresupuestoController::class, 'proyectoPresupuestoEvaluacion'])->name('convocatorias.evaluaciones.presupuesto.index');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/presupuesto/{presupuesto}/editar', [ProyectoPresupuestoController::class, 'evaluacionForm'])->name('convocatorias.evaluaciones.presupuesto.edit');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/presupuesto/{presupuesto}', [ProyectoPresupuestoController::class, 'updateEvaluacion'])->name('convocatorias.evaluaciones.presupuesto.update');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/edt', [EdtController::class, 'showEdtEvaluacion'])->name('convocatorias.evaluaciones.edt');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/edt/{edt}/editar', [EdtController::class, 'showEdtEvaluacionForm'])->name('convocatorias.evaluaciones.edt.edit');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/edt', [EdtController::class, 'updateEdtEvaluacion'])->name('convocatorias.evaluaciones.edt.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/inventario-equipos', [InventarioEquipoController::class, 'showInventarioEquiposEvaluacion'])->name('convocatorias.evaluaciones.inventario-equipos');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/inventario-equipos/{inventario_equipo}/editar', [InventarioEquipoController::class, 'showInventarioEquiposEvaluacionForm'])->name('convocatorias.evaluaciones.inventario-equipos.edit');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/inventario-equipos', [InventarioEquipoController::class, 'updateInventarioEquiposEvaluacion'])->name('convocatorias.evaluaciones.inventario-equipos.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/presupuesto/{presupuesto}/soportes', [SoporteEstudioMercadoController::class, 'soportesEvaluacion'])->name('convocatorias.evaluaciones.presupuesto.soportes');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/actividades/{actividad}', [ActividadController::class, 'actividadEvaluacion'])->name('convocatorias.evaluaciones.actividades.edit');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/productos/{producto}', [ProductoController::class, 'productoEvaluacion'])->name('convocatorias.evaluaciones.productos.edit');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/analisis-riesgos/{analisis_riesgo}', [AnalisisRiesgoController::class, 'analisisRiesgoEvaluacion'])->name('convocatorias.evaluaciones.analisis-riesgos.edit');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/entidades-aliadas/{entidad_aliada}', [EntidadAliadaController::class, 'entidadAliadaEvaluacion'])->name('convocatorias.evaluaciones.entidades-aliadas.edit');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/anexos', [ProyectoAnexoController::class, 'anexoEvaluacion'])->name('convocatorias.evaluaciones.anexos');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/anexos', [ProyectoAnexoController::class, 'updateAnexosEvaluacion'])->name('convocatorias.evaluaciones.anexos.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/entidades-aliadas', [EntidadAliadaController::class, 'showEntidadesAliadasEvaluacion'])->name('convocatorias.evaluaciones.entidades-aliadas');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/entidades-aliadas/verificar', [EntidadAliadaController::class, 'validarEntidadAliada'])->name('convocatorias.evaluaciones.entidades-aliadas.verificar');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/entidades-aliadas', [EntidadAliadaController::class, 'updateEntidadAliadaEvaluacion'])->name('convocatorias.evaluaciones.entidades-aliadas.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/actividades', [ActividadController::class, 'showMetodologiaEvaluacion'])->name('convocatorias.evaluaciones.actividades');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/actividades', [ActividadController::class, 'updateMetodologiaEvaluacion'])->name('convocatorias.evaluaciones.actividades.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/productos', [ProductoController::class, 'showProductosEvaluacion'])->name('convocatorias.evaluaciones.productos');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/productos', [ProductoController::class, 'updateProductosEvaluacion'])->name('convocatorias.evaluaciones.productos.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/analisis-riesgos', [AnalisisRiesgoController::class, 'showAnalisisRiesgosEvaluacion'])->name('convocatorias.evaluaciones.analisis-riesgos');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/analisis-riesgos', [AnalisisRiesgoController::class, 'updateAnalisisRiesgosEvaluacion'])->name('convocatorias.evaluaciones.analisis-riesgos.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/cadena-valor', [ProyectoController::class, 'showCadenaValorEvaluacion'])->name('convocatorias.evaluaciones.cadena-valor');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/cadena-valor', [ProyectoController::class, 'updateCadenaValorEvaluacion'])->name('convocatorias.evaluaciones.cadena-valor.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/arbol-problemas', [ArbolProyectoController::class, 'showArbolProblemasEvaluacion'])->name('convocatorias.evaluaciones.arbol-problemas');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/arbol-problemas', [ArbolProyectoController::class, 'updateArbolProblemasEvaluacion'])->name('convocatorias.evaluaciones.arbol-problemas.guardar-evaluacion');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/arbol-objetivos', [ArbolProyectoController::class, 'showArbolObjetivosEvaluacion'])->name('convocatorias.evaluaciones.arbol-objetivos');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/arbol-objetivos', [ArbolProyectoController::class, 'updateArbolObjetivosEvaluacion'])->name('convocatorias.evaluaciones.arbol-objetivos.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/comentarios-generales', [EvaluacionController::class, 'showComentariosGeneralesForm'])->name('convocatorias.evaluaciones.comentarios-generales-form');
    Route::post('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/comentarios-generales', [EvaluacionController::class, 'udpdateComentariosGenerales'])->name('convocatorias.evaluaciones.update-comentarios-generales');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/causales-rechazo', [EvaluacionController::class, 'editCausalRechazo'])->name('convocatorias.evaluaciones.causales-rechazo');
    Route::post('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/causales-rechazo', [EvaluacionController::class, 'updateCausalRechazo'])->name('convocatorias.evaluaciones.update-causal-rechazo');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/finalizar-evaluacion', [ProyectoController::class, 'finalizarEvaluacion'])->name('convocatorias.evaluaciones.finish');

    Route::get('evaluaciones/activas', [EvaluacionController::class, 'activas'])->name('evaluaciones.activas');

    Route::resource('convocatorias.cultura-innovacion-evaluaciones', CulturaInnovacionEvaluacionController::class)->parameters(['convocatorias' => 'convocatoria', 'cultura-innovacion-evaluaciones' => 'cultura-innovacion-evaluacion'])->except(['create', 'store', 'show']);
    Route::resource('convocatorias.idi-evaluaciones', IdiEvaluacionController::class)->parameters(['convocatorias' => 'convocatoria', 'idi-evaluaciones' => 'idi-evaluacion'])->except(['create', 'store', 'show']);
    Route::resource('convocatorias.ta-evaluaciones', TaEvaluacionController::class)->parameters(['convocatorias' => 'convocatoria', 'ta-evaluaciones' => 'ta-evaluacion'])->except(['create', 'store', 'show']);
    Route::resource('convocatorias.tp-evaluaciones', TpEvaluacionController::class)->parameters(['convocatorias' => 'convocatoria', 'tp-evaluaciones' => 'tp-evaluacion'])->except(['create', 'store', 'show']);
    Route::resource('convocatorias.servicios-tecnologicos-evaluaciones', ServicioTecnologicoEvaluacionController::class)->parameters(['convocatorias' => 'convocatoria', 'servicios-tecnologicos-evaluaciones' => 'servicio-tecnologico-evaluacion'])->except(['create', 'store', 'show']);
    Route::resource('evaluaciones', EvaluacionController::class)->parameters(['evaluaciones' => 'evaluacion'])->except(['show']);

    /**
     * Reportes
     * 
     */
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/convocatoria/{convocatoria}/resumen', [ReporteController::class, 'resumenProyectos'])->name('reportes.resumen-proyectos');
    Route::get('reportes/convocatoria/{convocatoria}/presupuestos-roles', [ReporteController::class, 'resumePresupuestos'])->name('reportes.resumePresupuestos');
    Route::get('reportes/convocatoria/{convocatoria}/reportes/evaluaciones', [ReporteController::class, 'evaluacionesExcel'])->name('reportes.evaluaciones');
    Route::get('reportes/convocatoria/{convocatoria}/comentarios-evaluaciones', [ReporteController::class, 'comentariosEvaluacionesExcel'])->name('reportes.comentarios-evaluaciones');
    Route::get('proyectos/{proyecto}/invetario-equipos', [InventarioEquipoController::class, 'inventarioEquiposExcel'])->name('reportes.inventario-equipos');
    Route::get('reportes/convocatoria/{convocatoria}/resumen-presupuesto-aprobado', [ReporteController::class, 'EvaluacionesProyectosPresupuestoExport'])->name('reportes.resumeProyectoPresupuestoAprobado');
    Route::get('reportes/convocatoria/{convocatoria}/proyectos-ta', [ReporteController::class, 'proyectosTaExport'])->name('reportes.proyectos-ta');
    Route::get('reportes/convocatoria/{convocatoria}/proyectos-idi', [ReporteController::class, 'proyectosIdiExport'])->name('reportes.proyectos-idi');
    Route::get('reportes/convocatoria/{convocatoria}/proyectos-tp', [ReporteController::class, 'proyectosTpExport'])->name('reportes.proyectos-tp');
    Route::get('reportes/convocatoria/{convocatoria}/proyectos-st', [ReporteController::class, 'proyectosStExport'])->name('reportes.proyectos-st');
    Route::get('reportes/convocatoria/{convocatoria}/proyectos-cultura-innovacion', [ReporteController::class, 'proyectosCulturaInnovacionExport'])->name('reportes.proyectos-cultura-innovacion');
    Route::get('reportes/grupos-lineas-semilleros', [ReporteController::class, 'gruposLineasSemillerosExport'])->name('reportes.grupos-lineas-semilleros');
    Route::get('reportes/proyectos-capacidad-instalada', [ReporteController::class, 'proyectosCapacidadInstaladaExport'])->name('reportes.proyectos-capacidad-instalada');

    /**
     * Tokens
     * 
     */
    Route::prefix('tokens')->group(function () {
        Route::post('create', [ApiController::class, 'CreateToken'])->name('tokens.create');
    });
});

Route::middleware(['checkToken'])->name('v1.')->prefix('api/v1')->group(function () {
    // API Resources
    Route::get('user_sennova', [ApiController::class, 'isUserSennova'])->name('user_sennova');
    Route::get('user_sennova/{id}/projects', [ApiController::class, 'projectsByUser'])->name('projects_by_user');
    Route::get('center/{id}/projects', [ApiController::class, 'projectsByCenter'])->name('projects_by_center');
    Route::get('projects/{id}', [ApiController::class, 'summaryProject'])->name('project');
});

require __DIR__ . '/auth.php';
