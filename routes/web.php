<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
use App\Http\Controllers\Evaluacion\EvaluacionController;
use App\Http\Controllers\Evaluacion\IdiEvaluacionController;
use App\Http\Controllers\Evaluacion\CulturaInnovacionEvaluacionController;
use App\Http\Controllers\InventarioEquipoController;
use App\Http\Controllers\ReglaRolCulturaController;
use App\Http\Controllers\ReglaRolTpController;
use App\Http\Controllers\SoporteEstudioMercadoController;
use App\Models\Actividad;
use App\Models\ActividadEconomica;
use App\Models\AreaConocimiento;
use App\Models\LineaInvestigacion;
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
use App\Models\EstadoSistemaGestion;
use App\Models\SegundoGrupoPresupuestal;
use App\Models\TercerGrupoPresupuestal;
use App\Models\PresupuestoSennova;
use App\Models\Tecnoacademia;
use App\Models\LineaTecnoacademia;
use App\Models\Municipio;
use App\Models\NodoTecnoparque;
use App\Models\ProgramaFormacion;
use App\Models\ProgramaFormacionArticulado;
use App\Models\Proyecto;
use App\Models\SubareaConocimiento;
use App\Models\TipoProyectoSt;
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

/**
 * Trae los centros de formación
 */
Route::get('web-api/centros-formacion', function () {
    return response(CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo, chr(10), \'∙ Regional: \', regionales.nombre) as label')->join('regionales', 'centros_formacion.regional_id', 'regionales.id')->orderBy('centros_formacion.nombre', 'ASC')->get());
})->name('web-api.centros-formacion');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('manual-usuario/download', [ProyectoController::class, 'downloadManualUsuario'])->name('manual-usuario.download');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Notificaciones
    Route::get('notificaciones', [UserController::class, 'showAllNotifications'])->name('notificaciones.index');
    Route::post('notificaciones/marcar-leido', [UserController::class, 'markAsReadNotification'])->name('notificaciones.marcar-leido');

    // Redirecciona según el tipo de proyecto
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/editar', [ProyectoController::class, 'edit'])->name('convocatorias.proyectos.edit');

    // Reportar problemas
    Route::get('reportar-problemas/crear', [HelpDeskController::class, 'create'])->name('reportar-problemas.create');
    Route::post('reportar-problemas/reportar', [HelpDeskController::class, 'report'])->name('reportar-problemas.report');

    // Cambiar contraseña
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

    // Trae los centros de formación - Cultura innovación
    Route::get('web-api/cultura-innovacion/centros-formacion', function () {
        return response(CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo, chr(10), \'∙ Regional: \', regionales.nombre) as label')
            ->join('regionales', 'centros_formacion.regional_id', 'regionales.id')
            ->whereIn('centros_formacion.codigo', [
                9309,
                9503,
                9230,
                9124,
                9120,
                9222,
                9116,
                9548,
                9401,
                9403,
                9303,
                9310,
                9529,
                9121
            ])
            ->orderBy('centros_formacion.nombre', 'ASC')->get());
    })->name('web-api.cultura-innovacion.centros-formacion');

    // Trae las actividades por resultado
    Route::get('web-api/resultados/{resultado}/actividades', function ($resultado) {
        return response(Actividad::select('actividades.id', 'actividades.descripcion', 'actividades.resultado_id')
            ->where('actividades.resultado_id', $resultado)
            ->distinct()
            ->get());
    })->name('web-api.resultados.actividades');

    // Trae los conceptos internos SENA
    Route::get('web-api/segundo-grupo-presupuestal/{linea_programatica}', function ($lineaProgramatica) {
        return response(SegundoGrupoPresupuestal::select('segundo_grupo_presupuestal.id as value', 'segundo_grupo_presupuestal.nombre as label')
            ->join('presupuesto_sennova', 'segundo_grupo_presupuestal.id', 'presupuesto_sennova.segundo_grupo_presupuestal_id')
            ->where('presupuesto_sennova.linea_programatica_id', $lineaProgramatica)
            ->where('presupuesto_sennova.habilitado', true)
            ->groupBy('segundo_grupo_presupuestal.id')
            ->orderBy('segundo_grupo_presupuestal.nombre', 'ASC')
            ->get());
    })->name('web-api.segundo-grupo-presupuestal');



    Route::get('web-api/tercer-grupo-presupuestal/{segundo_grupo_presupuestal}', function ($segundoGrupoPresupuestal) {
        return response(TercerGrupoPresupuestal::selectRaw('DISTINCT(tercer_grupo_presupuestal.id) as value, tercer_grupo_presupuestal.nombre as label')
            ->join('presupuesto_sennova', 'tercer_grupo_presupuestal.id', 'presupuesto_sennova.tercer_grupo_presupuestal_id')
            ->where('presupuesto_sennova.segundo_grupo_presupuestal_id', $segundoGrupoPresupuestal)
            ->get());
    })->name('web-api.tercer-grupo-presupuestal');

    // Trae los usos presupuestales
    Route::get('web-api/convocatorias/{convocatoria}/lineas-programaticas/{linea_programatica}/presupuesto-sennova/segundo-grupo-presupuestal/{segundo_grupo_presupuestal}/tercer-grupo-presupuestal/{tercer_grupo_presupuestal}', function ($convocatoria, $lineaProgramatica, $segundoGrupoPresupuestal, $tercerGrupoPresupuestal) {
        return response(PresupuestoSennova::select('convocatoria_presupuesto.id as value', 'usos_presupuestales.descripcion as label', 'usos_presupuestales.codigo', 'presupuesto_sennova.requiere_estudio_mercado', 'presupuesto_sennova.mensaje')
            ->join('usos_presupuestales', 'presupuesto_sennova.uso_presupuestal_id', 'usos_presupuestales.id')
            ->join('convocatoria_presupuesto', 'presupuesto_sennova.id', 'convocatoria_presupuesto.presupuesto_sennova_id')
            ->where('convocatoria_presupuesto.convocatoria_id', $convocatoria)
            ->where('presupuesto_sennova.linea_programatica_id', $lineaProgramatica)
            ->where('presupuesto_sennova.segundo_grupo_presupuestal_id', $segundoGrupoPresupuestal)
            ->where('presupuesto_sennova.tercer_grupo_presupuestal_id', $tercerGrupoPresupuestal)
            ->orderBy('usos_presupuestales.descripcion', 'ASC')->get());
    })->name('web-api.usos-presupuestales');

    Route::get('web-api/convocatorias/{convocatoria}/proyectos/{proyecto}/{linea_programatica}/roles-sennova', function ($convocatoria, $proyectoId, $lineaProgramatica) {
        $proyecto = Proyecto::find($proyectoId);
        if ($proyecto->servicioTecnologico()->exists()) {
            $rol = '';
            $tipologiaSt = '';
            if ($proyecto->servicioTecnologico->estadoSistemaGestion->id == 1) {
                $rol = 'responsable de servicios tecnológicos (laboratorio)';
            }

            if ($proyecto->servicioTecnologico->tipoProyectoSt->tipologia == 1) {
                $tipologiaSt = '%especiales%';
            } else if ($proyecto->servicioTecnologico->tipoProyectoSt->tipologia == 2) {
                $tipologiaSt = '%laboratorio%';
            } else if ($proyecto->servicioTecnologico->tipoProyectoSt->tipologia == 3) {
                $tipologiaSt = '%técnicos%';
            }
            return response(ConvocatoriaRolSennova::selectRaw("convocatoria_rol_sennova.id as value, convocatoria_rol_sennova.perfil, convocatoria_rol_sennova.mensaje,
                CASE nivel_academico
                    WHEN '7' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Ninguno', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '1' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Técnico', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '2' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Tecnólogo', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '3' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Pregrado', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '4' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Especalización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '5' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Maestría', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '6' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Doctorado', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '8' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Técnico con especialización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '9' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Tecnólogo con especialización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                END as label")
                ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
                ->where('roles_sennova.nombre', 'like', $tipologiaSt)
                ->where('roles_sennova.nombre', '!=', $rol)
                ->orWhere('roles_sennova.nombre', 'like', '%aprendiz sennova (contrato aprendizaje)%')
                ->where('convocatoria_rol_sennova.linea_programatica_id', $lineaProgramatica)
                ->where('convocatoria_rol_sennova.convocatoria_id', $convocatoria)
                ->orderBy('roles_sennova.nombre')->get());
        }

        return response(ConvocatoriaRolSennova::selectRaw("convocatoria_rol_sennova.id as value, convocatoria_rol_sennova.perfil, convocatoria_rol_sennova.mensaje,
            CASE nivel_academico
				WHEN '7' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Ninguno', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '1' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Técnico', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '2' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Tecnólogo', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '3' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Pregrado', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '4' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Especalización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '5' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Maestría', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '6' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Doctorado', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '8' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Técnico con especialización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '9' THEN	concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Tecnólogo con especialización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
            END as label")
            ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
            ->where('convocatoria_rol_sennova.linea_programatica_id', $lineaProgramatica)
            ->where('convocatoria_rol_sennova.convocatoria_id', $convocatoria)
            ->orderBy('roles_sennova.nombre')->get());
    })->name('web-api.convocatorias.roles-sennova');

    /**
     * Programas de formación
     * 
     */
    Route::get('web-api/centros-formacion/{centro_formacion}/programas-formacion', function ($centroFormacion) {
        return response(ProgramaFormacion::selectRaw('id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->where('centro_formacion_id', $centroFormacion)->orderBy('nombre', 'ASC')->get());
    })->name('web-api.programas-formacion');

    /**
     * Estados de sistema de gestión
     * 
     */
    Route::get('web-api/estados-sistema-gestion/{tipo_proyecto_st}', function ($tipoProyectoSt) {
        $tipoProyectoStInfo = TipoProyectoSt::find($tipoProyectoSt);
        return response(EstadoSistemaGestion::selectRaw("id as value, CASE tipo_proyecto
            WHEN '1' THEN	concat(estados_sistema_gestion.estado, chr(10), '∙ Tipo A')
            WHEN '2' THEN	concat(estados_sistema_gestion.estado, chr(10), '∙ Tipo B')
        END as label")->where('tipo_proyecto', $tipoProyectoStInfo->tipo_proyecto)->orderBy('id', 'ASC')->get());
    })->name('web-api.estados-sistema-gestion');

    /**
     * Programas de formación
     * 
     */
    Route::get('web-api/programas-formacion-articulados', function () {
        return response(ProgramaFormacionArticulado::selectRaw('id as value, concat(programas_formacion_articulados.nombre, chr(10), \'∙ Código: \', programas_formacion_articulados.codigo) as label')
            ->orderBy('nombre', 'ASC')->get());
    })->name('web-api.programas-formacion-articulados');

    /**
     * Regionales
     * 
     * Trae las regiones
     */
    Route::get('web-api/regiones', function () {
        return response(Region::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.regiones');

    /**
     * Trae las regionales
     */
    Route::get('web-api/regionales', function () {
        return response(Regional::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.regionales');

    Route::resource('regionales', RegionalController::class)->parameters(['regionales' => 'regional'])->except(['show']);

    /**
     * Trae los centros de formación por regional
     */
    Route::get('web-api/regional/{regional}/centros-formacion', function ($regional) {
        return response(CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo) as label')->where('centros_formacion.regional_id', $regional)->orderBy('centros_formacion.nombre', 'ASC')->get());
    })->name('web-api.centros-formacion-ejecutor');

    /**
     * Centros de formación
     * 
     * Trae los subdirectores
     */
    Route::get('web-api/users/{rol}', function ($rol) {
        return response(User::select('users.id as value', 'users.nombre as label')
            ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', 'roles.id')
            ->where('roles.name', 'ilike', '%' . $rol . '%')
            ->orderBy('users.nombre', 'ASC')->get());
    })->name('web-api.users');

    Route::resource('centros-formacion', CentroFormacionController::class)->except(['show'])->parameters(['centros-formacion' => 'centro-formacion']);

    /**
     * Programas de formación
     * 
     */
    Route::resource('programas-formacion', ProgramaFormacionController::class)->parameters(['programas-formacion' => 'programa-formacion'])->except(['show']);

    /**
     * Temáticas estratégicas
     * 
     */
    Route::resource('tematicas-estrategicas', TematicaEstrategicaController::class)->parameters(['tematicas-estrategicas' => 'tematica-estrategica'])->except(['show']);

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
     * Líneas tecnoacadedmia
     * 
     */
    Route::resource('lineas-tecnoacademia', LineaTecnoacademiaController::class)->parameters(['lineas-tecnoacademia' => 'linea-tecnoacademia'])->except(['show']);

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
    Route::get('web-api/grupos-investigacion', function () {
        return response(GrupoInvestigacion::selectRaw('grupos_investigacion.id as value, concat(grupos_investigacion.nombre, chr(10), \'∙ Acrónimo: \', grupos_investigacion.acronimo, chr(10), \'∙ Centro de formación: \', centros_formacion.nombre, chr(10), \'∙ Regional: \', regionales.nombre) as label')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')->join('regionales', 'centros_formacion.regional_id', 'regionales.id')->get());
    })->name('web-api.grupos-investigacion');

    Route::resource('lineas-investigacion', LineaInvestigacionController::class)->parameters(['lineas-investigacion' => 'linea-investigacion'])->except(['show']);

    /**
     * Semilleros de investigación
     * 
     * Trae las líneas de investigación
     */
    Route::get('web-api/lineas-investigacion/{centro_formacion}', function ($centroFormacion) {
        return response(LineaInvestigacion::selectRaw('lineas_investigacion.id as value, concat(lineas_investigacion.nombre, chr(10), \'∙ Grupo de investigación: \', grupos_investigacion.nombre, chr(10)) as label')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')->join('regionales', 'centros_formacion.regional_id', 'regionales.id')->where('centros_formacion.id', $centroFormacion)->get());
    })->name('web-api.lineas-investigacion');

    Route::resource('semilleros-investigacion', SemilleroInvestigacionController::class)->parameters(['semilleros-investigacion' => 'semillero-investigacion'])->except(['show']);

    /**
     * Mesas sectoriales
     * 
     */
    Route::resource('mesas-sectoriales', MesaSectorialController::class)->parameters(['mesas-sectoriales' => 'mesa-sectorial'])->except(['show']);

    /**
     * Web api
     * 
     */
    Route::get('web-api/municipios', function () {
        return response(Municipio::select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')
            ->join('regionales', 'regionales.id', 'municipios.regional_id')
            ->get());
    })->name('web-api.municipios');

    /**
     * Web api
     * 
     * Trae las Tecnoacademias
     */
    Route::get('web-api/tecnoacademias', function () {
        return response(Tecnoacademia::select('tecnoacademias.id as value', 'tecnoacademias.nombre as label')->get());
    })->name('web-api.tecnoacademias');

    /**
     * Web api
     * 
     * Trae las tecnoacademias
     */
    Route::get('web-api/centros-formacion/{centro_formacion}/tecnoacademias', function ($centroFormacion) {
        return response(Tecnoacademia::selectRaw("tecnoacademias.id as value, CASE modalidad
                WHEN '1' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '2' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante - vehículo', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '3' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: fija con extensión', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
            END as label, centros_formacion.id as centro_formacion_id")
            ->join('centros_formacion', 'tecnoacademias.centro_formacion_id', 'centros_formacion.id')
            ->where('tecnoacademias.centro_formacion_id', $centroFormacion)->get());
    })->name('web-api.centros-formacion.tecnoacademias');

    /**
     * Web api
     * 
     * Trae las líneas tecnoacademia
     */
    Route::get('web-api/tecnoacademias/{tecnoacademia}/lineas-tecnoacademia', function ($tecnoacademia) {
        return response(LineaTecnoacademia::select('tecnoacademia_linea_tecnoacademia.id as value', 'lineas_tecnoacademia.nombre as label')->join('tecnoacademia_linea_tecnoacademia', 'lineas_tecnoacademia.id', 'tecnoacademia_linea_tecnoacademia.linea_tecnoacademia_id')->where('tecnoacademia_linea_tecnoacademia.tecnoacademia_id', $tecnoacademia)->get());
    })->name('web-api.tecnoacademias.lineas-tecnoacademia');

    /**
     * Web api
     * 
     * Trae los nodos tecnoparque
     */
    Route::get('web-api/nodos-tecnoparque/{centro_formacion}', function ($centroFormacion) {
        return response(NodoTecnoparque::select('nodos_tecnoparque.id as value', 'nodos_tecnoparque.nombre as label')->where('nodos_tecnoparque.centro_formacion_id', $centroFormacion)->get());
    })->name('web-api.nodos-tecnoparque');

    /**
     * Web api
     * 
     * Trae las líneas programáticas
     */
    Route::get('web-api/lineas-programaticas/{categoria_proyecto}', function ($categoriaProyecto) {
        if ($categoriaProyecto) {
            return response(LineaProgramatica::selectRaw('id as value, concat(nombre, \' ∙ \', codigo) as label, codigo')
                ->where('lineas_programaticas.categoria_proyecto', 'ilike', '%' . $categoriaProyecto . '%')
                ->get());
        } else {
            return response(LineaProgramatica::select('id as value', 'nombre as label')
                ->get());
        }
    })->name('web-api.lineas-programaticas');

    /**
     * Web api
     * 
     * Trae las redes de conocimiento 
     */
    Route::get('web-api/redes-conocimiento', function () {
        return response(RedConocimiento::select('redes_conocimiento.id as value', 'redes_conocimiento.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.redes-conocimiento');

    /**
     * Web api
     * 
     * Trae las áreas de conocimiento
     */
    Route::get('web-api/areas-conocimiento', function () {
        return response(AreaConocimiento::select('areas_conocimiento.id as value', 'areas_conocimiento.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.areas-conocimiento');

    /**
     * Web api
     * 
     * Trae las subáreas de conocimiento
     */
    Route::get('web-api/subareas-conocimiento/{area_conocimiento}', function ($areaConocimiento) {
        return response(SubareaConocimiento::select('subareas_conocimiento.id as value', 'subareas_conocimiento.nombre as label')->where('subareas_conocimiento.area_conocimiento_id', $areaConocimiento)->orderBy('nombre', 'ASC')->get());
    })->name('web-api.subareas-conocimiento');

    /**
     * Web api
     * 
     * Trae las disciplinas de subáreas de conocimiento
     */
    Route::get('web-api/disciplinas-subarea-conocimiento/{subarea_conocimiento}', function ($subareaConocimiento) {
        return response(DisciplinaSubareaConocimiento::select('disciplinas_subarea_conocimiento.id as value', 'disciplinas_subarea_conocimiento.nombre as label')->where('disciplinas_subarea_conocimiento.subarea_conocimiento_id', $subareaConocimiento)->orderBy('nombre', 'ASC')->get());
    })->name('web-api.disciplinas-subarea-conocimiento');

    /**
     * Web api
     * 
     * Trae los actividades económicas
     */
    Route::get('web-api/actividades-economicas', function () {
        return response(ActividadEconomica::select('actividades_economicas.id as value', 'actividades_economicas.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.actividades-economicas');

    /**
     * Web api
     * 
     * Trae las temáticas estrategicas SENA
     */
    Route::get('web-api/tematicas-estrategicas', function () {
        return response(TematicaEstrategica::select('tematicas_estrategicas.id as value', 'tematicas_estrategicas.nombre as label')->orderBy('nombre', 'ASC')->get());
    })->name('web-api.tematicas-estrategicas');

    /**
     * Web api
     * 
     * Trae las subtipologías Minciencias
     */
    Route::get('web-api/subtipologias-minciencias', function () {
        return response(SubtipologiaMinciencias::selectRaw('subtipologias_minciencias.id as value, concat(subtipologias_minciencias.nombre, chr(10), \'∙ Tipología Minciencias: \', tipologias_minciencias.nombre) as label')->join('tipologias_minciencias', 'subtipologias_minciencias.tipologia_minciencias_id', 'tipologias_minciencias.id')->orderBy('subtipologias_minciencias.nombre')->get());
    })->name('web-api.subtipologias-minciencias');

    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/finalizar-proyecto', [ProyectoController::class, 'summary'])->name('convocatorias.proyectos.summary');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/finalizar-proyecto', [ProyectoController::class, 'finalizarProyecto'])->name('convocatorias.proyectos.finish');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/enviar-proyecto-evaluar', [ProyectoController::class, 'enviarAEvaluacion'])->name('convocatorias.proyectos.send');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/comentario-proyecto', [ProyectoController::class, 'devolverProyecto'])->name('convocatorias.proyectos.return-project');

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

    Route::resource('convocatorias.proyectos.entidades-aliadas', EntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'entidades-aliadas' => 'entidad-aliada'])->except(['show']);

    Route::resource('convocatorias.proyectos.entidades-aliadas.miembros-entidad-aliada', MiembroEntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'entidades-aliadas' => 'entidad-aliada', 'miembros-entidad-aliada' => 'miembro-entidad-aliada'])->except(['show']);

    /**
     * Cultura innovacion - Estrategia Nacional
     * 
     */
    Route::resource('reglas-roles-cultura', ReglaRolCulturaController::class)->parameters(['reglas-roles-cultura' => 'regla-rol-cultura'])->except(['show']);
    Route::resource('convocatorias.cultura-innovacion', CulturaInnovacionController::class)->parameters(['convocatorias' => 'convocatoria', 'cultura-innovacion' => 'cultura-innovacion'])->except(['show']);

    /**
     * Tp - Estrategia nacional
     * 
     */
    Route::resource('reglas-roles-tp', ReglaRolTpController::class)->parameters(['reglas-roles-tp' => 'regla-rol-tp'])->except(['show']);
    Route::resource('convocatorias.tp', TpController::class)->parameters(['convocatorias' => 'convocatoria', 'tp' => 'tp'])->except(['show']);

    /**
     * Ta - Estrategia nacional
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/articulacion', [TaController::class, 'showArticulacionSennova'])->name('convocatorias.proyectos.articulacion-sennova');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/articulacion', [TaController::class, 'storeArticulacionSennova'])->name('convocatorias.proyectos.articulacion-sennova.store');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/discurriculares', [DisCurricularController::class, 'storeDisCurricular'])->name('convocatorias.proyectos.dis-curriculares.store');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/rol/sennova/ta', [TaController::class, 'updateCantidadRolesTa'])->name('convocatorias.proyectos.rol-sennova-ta.update');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/infraestructura', [TaController::class, 'updateInfraestructura'])->name('convocatorias.ta.infraestrucutra.update');

    Route::resource('reglas-roles-ta', ReglaRolTaController::class)->parameters(['reglas-roles-ta' => 'regla-rol-ta'])->except(['show']);
    Route::resource('convocatorias.proyectos.edt', EdtController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'edt' => 'edt'])->except(['show']);
    Route::resource('convocatorias.ta', TaController::class)->parameters(['convocatorias' => 'convocatoria', 'ta' => 'ta'])->except(['show']);

    /**
     * Servicios tecnológicos - Estrategia  nacional
     * 
     */
    Route::resource('reglas-roles-st', ReglaRolStController::class)->parameters(['reglas-roles-st' => 'regla-rol-st'])->except(['show']);
    Route::put('convocatorias/{convocatoria}/servicios-tecnologicos/{servicio_tecnologico}/infraestructura', [ServicioTecnologicoController::class, 'updateEspecificacionesInfraestructura'])->name('convocatorias.servicios-tecnologicos.infraestructura');
    Route::resource('convocatorias.servicios-tecnologicos', ServicioTecnologicoController::class)->parameters(['convocatorias' => 'convocatoria', 'servicios-tecnologicos' => 'servicio-tecnologico'])->except(['show']);

    /**
     * Convocatorias
     * 
     */
    Route::get('convocatorias/{convocatoria}/dashboard', [ConvocatoriaController::class, 'dashboard'])->name('convocatorias.dashboard');

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
    Route::post('proyectos/{proyecto}/problema-central', [ArbolProyectoController::class, 'updateProblemaCentral'])->name('proyectos.problema-central');
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
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/actividades/metodologia', [ActividadController::class, 'updateMetodologia'])->name('convocatorias.proyectos.metodologia');
    Route::resource('convocatorias.proyectos.actividades', ActividadController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'actividades' => 'actividad'])->except(['show']);

    /**
     * Roles SENNOVA de proyecto
     * 
     */
    Route::resource('convocatorias.proyectos.proyecto-rol-sennova', ProyectoRolSennovaController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'proyecto-rol-sennova' => 'proyecto-rol-sennova'])->except(['show']);

    /**
     * Análisis de riesgos
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
    Route::resource('convocatoria-presupuesto',  ConvocatoriaPresupuestoController::class)->parameters(['convocatoria-presupuesto' => 'convocatoria-presupuesto'])->except(['show']);

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

    /**
     * Roles SENNOVA
     * 
     */
    Route::resource('roles-sennova',  RolSennovaController::class)->parameters(['roles-sennova' => 'rol-sennova'])->except(['show']);

    /**
     * Usuarios
     * 
     */
    Route::resource('users',  UserController::class)->except(['show']);

    /**
     * Roles de sistema
     * 
     */
    Route::resource('roles', RoleController::class)->except(['show']);

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

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/presupuesto', [ProyectoPresupuestoController::class, 'proyectoPresupuestoEvaluacion'])->name('convocatorias.evaluaciones.presupuesto.index');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/presupuesto/{presupuesto}/editar', [ProyectoPresupuestoController::class, 'evaluacionForm'])->name('convocatorias.evaluaciones.presupuesto.edit');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/presupuesto/{presupuesto}', [ProyectoPresupuestoController::class, 'updateEvaluacion'])->name('convocatorias.evaluaciones.presupuesto.update');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/presupuesto/{presupuesto}/soportes', [SoporteEstudioMercadoController::class, 'soportesEvaluacion'])->name('convocatorias.evaluaciones.presupuesto.soportes');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/actividades/{actividad}', [ActividadController::class, 'actividadEvaluacion'])->name('convocatorias.evaluaciones.actividades.edit');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/productos/{producto}', [ProductoController::class, 'productoEvaluacion'])->name('convocatorias.evaluaciones.productos.edit');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/analisis-riesgos/{analisis_riesgo}', [AnalisisRiesgoController::class, 'analisisRiesgoEvaluacion'])->name('convocatorias.evaluaciones.analisis-riesgos.edit');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/entidades-aliadas/{entidad_aliada}', [EntidadAliadaController::class, 'entidadAliadaEvaluacion'])->name('convocatorias.evaluaciones.entidades-aliadas.edit');
    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/anexos', [ProyectoAnexoController::class, 'anexoEvaluacion'])->name('convocatorias.evaluaciones.anexos');
    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/anexos', [ProyectoAnexoController::class, 'updateAnexosEvaluacion'])->name('convocatorias.evaluaciones.anexos.guardar-evaluacion');

    Route::get('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/entidades-aliadas', [EntidadAliadaController::class, 'showEntidadesAliadasEvaluacion'])->name('convocatorias.evaluaciones.entidades-aliadas');

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

    Route::put('convocatorias/{convocatoria}/evaluaciones/{evaluacion}/finalizar-evaluacion', [ProyectoController::class, 'finalizarEvaluacion'])->name('convocatorias.evaluaciones.finish');

    Route::resource('convocatorias.cultura-innovacion-evaluaciones', CulturaInnovacionEvaluacionController::class)->parameters(['convocatorias' => 'convocatoria', 'cultura-innovacion-evaluaciones' => 'cultura-innovacion-evaluacion'])->except(['create', 'store', 'show']);
    Route::resource('convocatorias.idi-evaluaciones', IdiEvaluacionController::class)->parameters(['convocatorias' => 'convocatoria', 'idi-evaluaciones' => 'idi-evaluacion'])->except(['create', 'store', 'show']);
    Route::resource('evaluaciones', EvaluacionController::class)->parameters(['evaluaciones' => 'evaluacion'])->except(['show']);
});

require __DIR__ . '/auth.php';
