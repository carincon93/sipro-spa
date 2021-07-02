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
use App\Http\Controllers\SectorProductivoController;
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
use App\Http\Controllers\ReglaRolTaController;
use App\Http\Controllers\TaController;
use App\Http\Controllers\TpController;
use App\Http\Controllers\ProyectoAnexoController;
use App\Http\Controllers\UsoPresupuestalController;
use App\Http\Controllers\ProyectoLoteEstudioMercadoController;
use App\Http\Controllers\MiembroEntidadAliadaController;
use App\Http\Controllers\TecnoacademiaController;
use App\Http\Controllers\LineaTecnologicaController;
use App\Http\Controllers\MesaSectorialController;
use App\Http\Controllers\ServicioTecnologicoController;
use App\Http\Controllers\HelpDeskController;
use App\Http\Controllers\CulturaInnovacionController;
use App\Http\Controllers\EdtController;

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
use App\Models\LineaTecnologica;
use App\Models\Municipio;
use App\Models\NodoTecnoparque;
use App\Models\ProgramaFormacion;
use App\Models\SectorProductivo;
use App\Models\SubareaConocimiento;
use App\Models\SubclasificacionTipologiaSt;
use App\Models\TemaPriorizado;
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
    Route::delete('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/users/unlink', [ProyectoController::class, 'unlinkParticipante'])->name('convocatorias.proyectos.participantes.users.unlink');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/participantes/users/register', [ProyectoController::class, 'registerParticipante'])->name('convocatorias.proyectos.participantes.users.register');

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
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/presupuesto/{presupuesto}/lote/{lote}/download', [ProyectoLoteEstudioMercadoController::class, 'download'])->name('convocatorias.proyectos.presupuesto.lote.download');
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/presupuesto/{presupuesto}/estudio-mercado/{estudio_mercado}/download', [ProyectoLoteEstudioMercadoController::class, 'downloadSoporte'])->name('convocatorias.proyectos.presupuesto.download-soporte');
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

    // Trae los conceptos internos SENA
    Route::get('web-api/segundo-grupo-presupuestal/{linea_programatica}', function ($lineaProgramatica) {
        return response(SegundoGrupoPresupuestal::select('segundo_grupo_presupuestal.id as value', 'segundo_grupo_presupuestal.nombre as label')
            ->join('presupuesto_sennova', 'segundo_grupo_presupuestal.id', 'presupuesto_sennova.segundo_grupo_presupuestal_id')
            ->where('presupuesto_sennova.linea_programatica_id', $lineaProgramatica)
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

    Route::get('web-api/convocatorias/{convocatoria}/{linea_programatica}/roles-sennova', function ($convocatoria, $lineaProgramatica) {
        return response(ConvocatoriaRolSennova::selectRaw("convocatoria_rol_sennova.id as value, convocatoria_rol_sennova.perfil, convocatoria_rol_sennova.mensaje,
        CASE nivel_academico
				WHEN '7' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Ninguno', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '1' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Técnico', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '2' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Tecnólogo', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '3' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Pregrado', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '4' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Especalización', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '5' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Maestría', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '6' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Doctorado', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '8' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Técnico con especialización', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '9' THEN	concat(roles_sennova.nombre, ' - Nivel académico: Tecnólogo con especialización', chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
        END as label,
        convocatoria_rol_sennova.experiencia")
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
     * Programas de formación
     * 
     */
    Route::get('web-api/programas-formacion-articulados', function () {
        return response(ProgramaFormacion::selectRaw('id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->whereIn('programas_formacion.codigo', [
            111202, 112005, 121104, 121202, 121502, 121518, 121523, 121524, 122107, 122115, 122120, 122128, 122142, 122145, 122149, 122201, 122320, 122601, 122702, 122703, 122811, 122814, 122901, 123101, 123102, 123111, 123201, 123303, 123401, 123600, 133100, 133202, 133302, 133500, 134101, 134135, 134200, 134400, 134504, 134507, 135304, 135319, 135326, 135329, 135400, 137108, 137132, 137135, 137136, 137201, 180201, 214506, 217111, 217113, 217115, 217218, 217219, 217307, 217317, 221109, 221111, 221115, 221118, 221304, 222102, 222103, 222110, 222114, 222116, 222203, 222239, 222240, 222301, 222313, 222316, 223104, 223130, 223201, 223217, 223219, 223247, 223308, 223309, 223310, 223318, 224102, 224130, 224206, 224207, 224208, 224222, 224309, 224402, 224501, 224503, 225109, 225208, 225217, 225219, 225220, 225221, 225311, 225312, 226101, 226245, 226304, 226502, 227306, 227310, 227402, 228101, 228106, 228108, 228116, 228117, 228122, 228123, 228183, 228187, 231200, 231201, 231202, 233101, 233102, 233103, 233104, 233105, 321301, 321402, 321405, 322101, 322201, 323306, 323401, 323403, 331109, 331110, 331113, 331114, 331115, 331116, 331118, 331119, 331120, 331200, 331308, 331311, 331312, 331314, 331502, 331505, 413116, 417205, 421149, 511105, 511312, 513101, 513206, 513208, 513303, 513406, 513407, 513408, 513504, 513507, 513601, 521200, 521213, 522100, 522101, 522201, 522208, 522209, 522211, 522309, 522503, 522608, 522703, 522706, 522711, 522715, 523204, 524109, 524113, 524139, 524143, 524148, 524149, 524150, 524201, 524300, 524310, 524318, 524319, 524437, 524500, 524525, 524529, 524530, 524532, 524535, 524700, 524703, 525201, 525203, 525209, 525213, 525214, 525306, 525307, 525308, 525309, 525310, 526202, 526300, 526301, 526302, 526600, 621111, 621113, 621114, 621121, 621123, 621124, 621125, 621201, 621208, 621303, 621308, 621600, 623201, 623327, 631101, 631117, 631124, 632105, 632116, 632215, 632223, 632305, 633100, 633113, 633114, 633115, 633200, 633400, 633409, 633410, 633411, 634122, 634124, 634205, 634207, 634214, 634215, 634216, 635170, 635171, 635200, 635201, 635236, 635239, 635301, 635402, 635503, 635601, 635604, 636406, 636700, 637102, 637214, 637300, 637303, 637307, 637321, 637323, 637502, 637503, 637800, 661202, 661203, 662100, 662121, 663102, 663202, 663204, 663301, 664101, 664104, 664209, 664210, 664211, 721103, 721112, 722123, 722125, 722139, 722142, 722143, 722301, 723105, 723106, 723107, 723111, 723112, 723133, 723165, 723166, 723167, 723402, 731168, 731309, 731319, 731323, 731324, 731400, 731405, 731600, 732204, 732208, 733164, 733165, 733171, 733174, 733190, 733191, 733192, 733193, 733194, 733259, 733265, 733290, 733291, 733292, 733293, 733295, 733296, 733302, 733307, 733308, 733401, 733402, 733410, 733450, 733454, 733455, 733510, 734101, 734111, 734112, 761321, 761322, 761324, 761325, 761327, 761328, 761329, 761330, 761331, 761332, 761512, 761513, 821101, 821202, 821222, 821226, 821236, 821238, 821239, 821240, 821300, 821608, 821609, 822200, 822202, 831116, 832102, 832103, 832150, 832202, 832235, 832300, 832333, 832336, 832400, 832402, 832415, 832422, 832424, 833100, 833112, 833300, 833301, 833320, 834104, 834105, 834248, 834250, 834251, 834256, 834258, 834401, 835100, 835101, 835105, 835119, 835123, 836100, 836114, 836135, 836136, 836137, 836201, 836600, 837101, 837102, 837151, 837152, 837153, 837303, 837304, 837305, 837400, 837408, 837409, 837530, 837531, 838100, 838102, 838103, 838106, 838107, 838108, 838109, 838200, 838207, 838208, 838318, 839101, 839401, 841102, 841200, 841600, 842129, 842200, 842403, 842404, 842405, 842407, 842601, 842701, 844201, 845102, 845105, 845111, 845113, 845300, 845301, 845302, 846101, 846202, 847103, 847104, 847105, 847109, 847200, 847202, 847302, 848104, 848107, 848202, 848204, 848303, 848401, 848402, 849205, 861100, 862101, 921219, 921221, 921226, 921232, 921312, 921318, 921321, 921330, 921417, 921418, 921501, 922403, 922500, 922501, 922502, 922601, 922607, 922608, 922609, 922711, 922714, 922717, 923202, 924100, 924102, 924103, 924104, 924200, 924201, 924300, 931101, 931204, 931403, 931404, 931501, 932103, 932211, 932212, 932215, 932216, 932217, 932220, 932221, 932400, 932401, 932402, 932408, 933106, 933107, 934113, 934200, 934205, 934206, 934208, 934307, 934403, 935100, 935153, 935156, 935175, 935176, 935177, 935200, 935249, 935300, 935301, 935302, 935303, 935308, 935310, 935316, 935317, 935500, 935503, 936101, 936105, 936107, 936161, 936166, 936167, 936181, 936186, 936187, 936193, 936196, 936202, 937115, 937116, 937117, 937126, 937127, 937128, 937210, 937214, 937305, 937306, 938102, 938103, 939202, 939203, 939302, 939401, 939504, 939605, 939608, 939800, 939801, 941106, 941206, 961206, 961209, 961520, 961521, 961605
        ])->orderBy('nombre', 'ASC')->get());
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
     * Sectores productivos
     * 
     */
    Route::resource('sectores-productivos', SectorProductivoController::class)->parameters(['sectores-productivos' => 'sector-productivo'])->except(['show']);

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
     * Trae las líneas tecnológicas
     */
    Route::get('web-api/tecnoacademias/{tecnoacademia}/lineas-tecnologicas', function ($tecnoacademia) {
        return response(LineaTecnologica::select('tecnoacademia_linea_tecnologica.id as value', 'lineas_tecnologicas.nombre as label')->join('tecnoacademia_linea_tecnologica', 'lineas_tecnologicas.id', 'tecnoacademia_linea_tecnologica.linea_tecnologica_id')->where('tecnoacademia_linea_tecnologica.tecnoacademia_id', $tecnoacademia)->get());
    })->name('web-api.tecnoacademias.lineas-tecnologicas');

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
     * Trae las subclasificaciones de tipología ST
     */
    Route::get('web-api/tipologia-st/{tipologia_st}/web-api.subclasificacion-tipologia-st', function ($tipologiaSt) {
        return response(SubclasificacionTipologiaSt::select('id as value', 'subclasificacion as label')
            ->where('tipologia_st_id', '=', $tipologiaSt)
            ->get());
    })->name('web-api.subclasificacion-tipologia-st');

    /**
     * Web api
     * 
     * Trae los estados del sistema de gestión
     */
    Route::get('web-api/tipos-proyecto-st/{tipo_proyecto_st}/estados-sistema-gestion', function ($tipoProyectoSt) {
        return response(EstadoSistemaGestion::select('id as value', 'estado as label')
            ->where('tipo_proyecto_st_id', '=', $tipoProyectoSt)
            ->get());
    })->name('web-api.estados-sistema-gestion');

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
     * Trae los sectores productivos
     */
    Route::get('web-api/sectores-productivos/{mesa_tecnica}', function ($mesaTecnica) {
        return response(SectorProductivo::selectRaw('mesa_tecnica_sector_productivo.id as value, sectores_productivos.nombre as label')
            ->join('mesa_tecnica_sector_productivo', 'sectores_productivos.id', 'mesa_tecnica_sector_productivo.sector_productivo_id')
            ->where('mesa_tecnica_sector_productivo.mesa_tecnica_id', $mesaTecnica)->orderBy('nombre', 'ASC')->get());
    })->name('web-api.sectores-productivos');

    /**
     * Web api
     * 
     * Trae las subtipologías Minciencias
     */
    Route::get('web-api/subtipologias-minciencias', function () {
        return response(SubtipologiaMinciencias::selectRaw('subtipologias_minciencias.id as value, concat(subtipologias_minciencias.nombre, chr(10), \'∙ Tipología Minciencias: \', tipologias_minciencias.nombre) as label')->join('tipologias_minciencias', 'subtipologias_minciencias.tipologia_minciencias_id', 'tipologias_minciencias.id')->orderBy('subtipologias_minciencias.nombre')->get());
    })->name('web-api.subtipologias-minciencias');

    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/finalizar-proyecto', [ProyectoController::class, 'summary'])->name('convocatorias.proyectos.summary');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/finalizar-proyecto', [ProyectoController::class, 'finishProject'])->name('convocatorias.proyectos.finish');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/radicar-proyecto', [ProyectoController::class, 'sendProject'])->name('convocatorias.proyectos.send');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/comentario-proyecto', [ProyectoController::class, 'returnProject'])->name('convocatorias.proyectos.return-project');

    /**
     * Idi - Estrategia regional
     * 
     */
    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/entidades-aliadas/{entidad_aliada}/{archivo}/download', [EntidadAliadaController::class, 'download'])->name('convocatorias.proyectos.entidades-aliadas.download');
    Route::resource('convocatorias.idi', IdiController::class)->parameters(['convocatorias' => 'convocatoria', 'idi' => 'idi'])->except(['show']);

    Route::resource('convocatorias.proyectos.entidades-aliadas', EntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'entidades-aliadas' => 'entidad-aliada'])->except(['show']);

    Route::resource('convocatorias.proyectos.entidades-aliadas.miembros-entidad-aliada', MiembroEntidadAliadaController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'entidades-aliadas' => 'entidad-aliada', 'miembros-entidad-aliada' => 'miembro-entidad-aliada'])->except(['show']);

    /**
     * cultura-innovacion - Estrategia Nacional
     * 
     */
    Route::resource('convocatorias.cultura-innovacion', CulturaInnovacionController::class)->parameters(['convocatorias' => 'convocatoria', 'cultura-innovacion' => 'cultura-innovacion'])->except(['show']);


    /**
     * Tp - Estrategia nacional
     * 
     */
    Route::resource('convocatorias.tp', TpController::class)->parameters(['convocatorias' => 'convocatoria', 'tp' => 'tp'])->except(['show']);

    /**
     * Ta - Estrategia nacional
     * 
     */

    Route::get('convocatorias/{convocatoria}/proyectos/{proyecto}/articulacion', [TaController::class, 'showArticulacionSennova'])->name('convocatorias.proyectos.articulacion-sennova');
    Route::post('convocatorias/{convocatoria}/proyectos/{proyecto}/articulacion', [TaController::class, 'storeArticulacionSennova'])->name('convocatorias.proyectos.articulacion-sennova.store');
    Route::put('convocatorias/{convocatoria}/proyectos/{proyecto}/rol/sennova/ta', [TaController::class, 'updateCantidadRolesTa'])->name('convocatorias.proyectos.rol-sennova-ta.update');
    Route::resource('reglas-roles-ta', ReglaRolTaController::class)->parameters(['reglas-roles-ta' => 'regla-rol-ta'])->except(['show']);
    Route::resource('convocatorias.proyectos.edt', EdtController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'edt' => 'edt'])->except(['show']);
    Route::resource('convocatorias.ta', TaController::class)->parameters(['convocatorias' => 'convocatoria', 'ta' => 'ta'])->except(['show']);

    /**
     * Servicios tecnológicos - Estrategia  nacional
     * 
     */
    Route::put('convocatorias/{convocatoria}/servicios-tecnologicos/{servicio_tecnologico}/video', [ServicioTecnologicoController::class, 'updateVideo'])->name('convocatorias.servicios-tecnologicos.video');
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
    Route::resource('convocatorias.proyectos.presupuesto', ProyectoPresupuestoController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'presupuesto' => 'presupuesto'])->except(['show']);

    Route::resource('convocatorias.proyectos.presupuesto.lote', ProyectoLoteEstudioMercadoController::class)->parameters(['convocatorias' => 'convocatoria', 'proyectos' => 'proyecto', 'presupuesto' => 'presupuesto', 'lote' => 'lote'])->except(['show']);


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
});

require __DIR__ . '/auth.php';
