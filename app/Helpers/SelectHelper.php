<?php

namespace App\Helpers;

use App\Models\Actividad;
use App\Models\ActividadEconomica;
use App\Models\Anexo;
use App\Models\AreaConocimiento;
use App\Models\LineaInvestigacion;
use App\Models\RedConocimiento;
use App\Models\DisciplinaSubareaConocimiento;
use App\Models\TematicaEstrategica;
use App\Models\CentroFormacion;
use App\Models\CodigoProyectoSgps;
use App\Models\Convocatoria;
use App\Models\Region;
use App\Models\Regional;
use App\Models\GrupoInvestigacion;
use App\Models\SubtipologiaMinciencias;
use App\Models\LineaProgramatica;
use App\Models\ConvocatoriaRolSennova;
use App\Models\DisenoCurricular;
use App\Models\EstadoSistemaGestion;
use App\Models\SegundoGrupoPresupuestal;
use App\Models\TercerGrupoPresupuestal;
use App\Models\PresupuestoSennova;
use App\Models\Tecnoacademia;
use App\Models\LineaTecnoacademia;
use App\Models\Municipio;
use App\Models\NodoTecnoparque;
use App\Models\PrimerGrupoPresupuestal;
use App\Models\ProgramaFormacion;
use App\Models\ProgramaSennova;
use App\Models\Proyecto;
use App\Models\RolSennova;
use App\Models\SemilleroInvestigacion;
use App\Models\SubareaConocimiento;
use App\Models\SubtipoProyectoCapacidadInstalada;
use App\Models\TipoBeneficiadoTa;
use App\Models\TipologiaAmbiente;
use App\Models\TipoProductoIdi;
use App\Models\TipoProyectoCapacidadInstalada;
use App\Models\TipoProyectoSt;
use App\Models\User;

class SelectHelper
{
    /**
     * Web api
     * 
     * Trae los centros de formación 
     */
    public static function centrosFormacion()
    {
        return CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo, chr(10), \'∙ Regional: \', regionales.nombre) as label, regionales.id as regional_id')->join('regionales', 'centros_formacion.regional_id', 'regionales.id')->orderBy('centros_formacion.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las actividades por resultado
     */
    public static function resultadosActividades()
    {
        return Actividad::select('actividades.id', 'actividades.descripcion', 'actividades.resultado_id')->distinct()->get();
    }

    /**
     * Web api
     * 
     * Trae los tipos de beneficiados TA
     */
    public static function tiposBeneficiadosTa()
    {
        return TipoBeneficiadoTa::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los tipos de productos IDi
     */
    public static function tiposProductosIdi()
    {
        return TipoProductoIdi::select('id as value', 'tipo as label')->get();
    }

    /**
     * Web api
     * 
     * Trae el primer grupo presupuestal
     */
    public static function primerGrupoPresupuestal()
    {
        return PrimerGrupoPresupuestal::select('primer_grupo_presupuestal.id as value', 'primer_grupo_presupuestal.nombre as label')
            ->orderBy('primer_grupo_presupuestal.nombre', 'ASC')
            ->get();
    }

    /**
     * Web api
     * 
     * Trae los conceptos internos SENA
     */
    public static function segundoGrupoPresupuestal()
    {
        return SegundoGrupoPresupuestal::select('segundo_grupo_presupuestal.id as value', 'segundo_grupo_presupuestal.nombre as label')
            ->orderBy('segundo_grupo_presupuestal.nombre', 'ASC')
            ->get();
    }

    /**
     * Web api
     * 
     * Trae el tercer grupo presupuestal
     */
    public static function tercerGrupoPresupuestal()
    {
        return TercerGrupoPresupuestal::selectRaw('DISTINCT(tercer_grupo_presupuestal.id) as value, tercer_grupo_presupuestal.nombre as label')
            ->orderBy('tercer_grupo_presupuestal.nombre', 'ASC')
            ->get();
    }

    /**
     * Web api
     * 
     * Trae los usos presupuestales
     */
    public static function usosPresupuestales()
    {
        return PresupuestoSennova::select('convocatoria_presupuesto.id as value', 'usos_presupuestales.descripcion as label', 'usos_presupuestales.codigo', 'presupuesto_sennova.requiere_estudio_mercado', 'presupuesto_sennova.mensaje')
            ->join('usos_presupuestales', 'presupuesto_sennova.uso_presupuestal_id', 'usos_presupuestales.id')
            ->join('convocatoria_presupuesto', 'presupuesto_sennova.id', 'convocatoria_presupuesto.presupuesto_sennova_id')
            ->orderBy('usos_presupuestales.descripcion', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las líneas programáticas
     */
    public static function lineasProgramaticas()
    {
        return LineaProgramatica::selectRaw('lineas_programaticas.id as value, concat(lineas_programaticas.nombre, chr(10), \'∙ Código: \', lineas_programaticas.codigo) as label, lineas_programaticas.categoria_proyecto as categoria_proyecto')->get();
    }

    /**
     * Web api
     * 
     * Trae los anexos
     */
    public static function anexos()
    {
        return Anexo::select('id as value', 'nombre as label', 'anexo_lineas_programaticas.linea_programatica_id')->join('anexo_lineas_programaticas', 'anexos.id', 'anexo_lineas_programaticas.anexo_id')->get();
    }

    /**
     * Web api
     * 
     * Trae los tipos de proyectos ST
     */
    public static function tiposProyectosSt()
    {
        return TipoProyectoSt::selectRaw("tipos_proyecto_st.id as value, CASE subclasificacion
                    WHEN '1' THEN	concat(centros_formacion.nombre, chr(10), '∙ Automatización y TICs', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                    WHEN '2' THEN	concat(centros_formacion.nombre, chr(10), '∙ Calibración', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                    WHEN '3' THEN	concat(centros_formacion.nombre, chr(10), '∙ Consultoría técnica', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                    WHEN '4' THEN	concat(centros_formacion.nombre, chr(10), '∙ Ensayo', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                    WHEN '5' THEN	concat(centros_formacion.nombre, chr(10), '∙ Fabricación especial', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                    WHEN '6' THEN	concat(centros_formacion.nombre, chr(10), '∙ Seguridad y salud en el trabajo', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                    WHEN '7' THEN	concat(centros_formacion.nombre, chr(10), '∙ Servicios de salud', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                END as label, centros_formacion.regional_id as regional_id")
            ->join('centros_formacion', 'tipos_proyecto_st.centro_formacion_id', 'centros_formacion.id')
            ->join('mesas_tecnicas', 'tipos_proyecto_st.mesa_tecnica_id', 'mesas_tecnicas.id')->get();
    }

    /**
     * Web api
     * 
     * Trae los programas de formación
     */
    public static function programasFormacion()
    {
        return ProgramaFormacion::selectRaw('programas_formacion.id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label, programas_formacion.registro_calificado, programas_formacion.centro_formacion_id')->orderBy('programas_formacion.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Estados de sistema de gestión
     * 
     */
    public static function estadosSistemaGestion()
    {
        return EstadoSistemaGestion::selectRaw("id as value, CASE tipo_proyecto
            WHEN '1' THEN   concat(estados_sistema_gestion.estado, chr(10), '∙ Tipo A')
            WHEN '2' THEN   concat(estados_sistema_gestion.estado, chr(10), '∙ Tipo B')
        END as label")->orderBy('id', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las regiones
     */
    public static function regiones()
    {
        return Region::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las regionales
     */
    public static function regionales()
    {
        return Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los grupos de investigación
     * 
     */
    public static function gruposInvestigacion()
    {
        return GrupoInvestigacion::selectRaw('grupos_investigacion.id as value, concat(grupos_investigacion.nombre, chr(10), \'∙ Acrónimo: \', grupos_investigacion.acronimo, chr(10), \'∙ Centro de formación: \', centros_formacion.nombre, chr(10), \'∙ Regional: \', regionales.nombre) as label, centros_formacion.id as centro_formacion_id, regionales.id as regional_id')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')->join('regionales', 'centros_formacion.regional_id', 'regionales.id')->get();
    }

    /**
     * Web api
     * 
     * Trae las líneas de investigación
     */
    public static function lineasInvestigacion()
    {
        return LineaInvestigacion::selectRaw('lineas_investigacion.id as value, concat(lineas_investigacion.nombre, chr(10), \'∙ Grupo de investigación: \', grupos_investigacion.nombre, chr(10)) as label, centros_formacion.id as centro_formacion_id, lineas_investigacion.grupo_investigacion_id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')->join('regionales', 'centros_formacion.regional_id', 'regionales.id')->get();
    }

    /**
     * Web api
     * 
     * Trae los municipios
     */
    public static function municipios()
    {
        return Municipio::select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get();
    }

    /**
     * Web api
     * 
     * Trae las Tecnoacademias
     */
    public static function tecnoacademias()
    {
        return Tecnoacademia::selectRaw("tecnoacademias.id as value, CASE modalidad
                WHEN '1' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '2' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante - vehículo', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '3' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: fija con extensión', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
            END as label, centros_formacion.id as centro_formacion_id")->join('centros_formacion', 'tecnoacademias.centro_formacion_id', 'centros_formacion.id')->get();
    }

    /**
     * Web api
     * 
     * Trae las líneas tecnoacademia
     */
    public static function lineasTecnoacademia()
    {
        return LineaTecnoacademia::select('tecnoacademia_linea_tecnoacademia.id as value', 'lineas_tecnoacademia.nombre as label', 'tecnoacademia_linea_tecnoacademia.tecnoacademia_id as tecnoacademia_id')->join('tecnoacademia_linea_tecnoacademia', 'lineas_tecnoacademia.id', 'tecnoacademia_linea_tecnoacademia.linea_tecnoacademia_id')->get();
    }

    /**
     * Web api
     * 
     * Trae los nodos tecnoparque
     */
    public static function nodosTecnoparque()
    {
        return NodoTecnoparque::select('nodos_tecnoparque.id as value', 'nodos_tecnoparque.nombre as label', 'nodos_tecnoparque.centro_formacion_id', 'centros_formacion.regional_id')->join('centros_formacion', 'nodos_tecnoparque.centro_formacion_id', 'centros_formacion.id')->orderBy('nodos_tecnoparque.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las líneas programáticas
     */
    public static function líneasProgramaticas()
    {
        return LineaProgramatica::selectRaw('id as value, concat(nombre, \' ∙ \', codigo) as label, codigo')->orderBy('nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las redes de conocimiento 
     */
    public static function redesConocimiento()
    {
        return RedConocimiento::select('redes_conocimiento.id as value', 'redes_conocimiento.nombre as label')->orderBy('redes_conocimiento.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los programas de formación por línea de investigación
     */
    public static function líneaInvestigacionProgramaFormacion()
    {
        return ProgramaFormacion::select('programas_formacion.id as value', 'programas_formacion.nombre as label')->orderBy('programas_formacion.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las áreas de conocimiento
     */
    public static function areasConocimiento()
    {
        return AreaConocimiento::select('areas_conocimiento.id as value', 'areas_conocimiento.nombre as label')->orderBy('areas_conocimiento.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las subáreas de conocimiento
     */
    public static function subareasConocimiento()
    {
        return SubareaConocimiento::select('subareas_conocimiento.id as value', 'subareas_conocimiento.nombre as label', 'subareas_conocimiento.area_conocimiento_id')->orderBy('subareas_conocimiento.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las disciplinas de subáreas de conocimiento
     */
    public static function disciplinasSubareaConocimiento()
    {
        return DisciplinaSubareaConocimiento::select('disciplinas_subarea_conocimiento.id as value', 'disciplinas_subarea_conocimiento.nombre as label', 'disciplinas_subarea_conocimiento.subarea_conocimiento_id')->orderBy('nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los tipos de proyecto de capacidad instalada
     */
    public static function tiposProyectoCapacidadInstalada()
    {
        return TipoProyectoCapacidadInstalada::select('tipos_proyecto_capacidad_instalada.id as value', 'tipos_proyecto_capacidad_instalada.tipo as label')->orderBy('tipos_proyecto_capacidad_instalada.tipo', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los subtipos de proyecto de capacidad instalada
     */
    public static function subtiposProyectoCapacidadInstalada()
    {
        return SubtipoProyectoCapacidadInstalada::select('subtipos_proyecto_capacidad_instalada.id as value', 'subtipos_proyecto_capacidad_instalada.subtipo as label')->orderBy('subtipos_proyecto_capacidad_instalada.subtipo', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los semilleros de investigacion
     */
    public static function semillerosInvestigacion()
    {
        return SemilleroInvestigacion::selectRaw('semilleros_investigacion.id as value, concat(semilleros_investigacion.nombre, chr(10), \'∙ Grupo de investigación: \', grupos_investigacion.nombre, chr(10)) as label, grupos_investigacion.centro_formacion_id as centro_formacion_id')->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->orderBy('semilleros_investigacion.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las actividades económicas
     */
    public static function actividadesEconomicas()
    {
        return ActividadEconomica::select('actividades_economicas.id as value', 'actividades_economicas.nombre as label')->orderBy('actividades_economicas.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las temáticas estrategicas SENA
     */
    public static function tematicasEstrategicas()
    {
        return TematicaEstrategica::select('tematicas_estrategicas.id as value', 'tematicas_estrategicas.nombre as label')->orderBy('tematicas_estrategicas.nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los diseños curriculares
     */
    public static function disenoCurriculares()
    {
        return DisenoCurricular::selectRaw('id as value, concat(nombre, \' ∙ Código: \', codigo) as label')->get();
    }

    /**
     * Web api
     * 
     * Trae los programas SENNOVA
     */
    public static function programasSennova()
    {
        return ProgramaSennova::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los codigos de proyectos SGPS
     */
    public static function codigoProyectoSgps()
    {
        return  CodigoProyectoSgps::select('seguimientos_ambiente_modernizacion.codigo_proyecto_sgps_id', 'codigos_proyectos_sgps.codigo_sgps', 'codigos_proyectos_sgps.titulo', 'codigos_proyectos_sgps.year_ejecucion', 'lineas_programaticas.codigo', 'codigos_proyectos_sgps.centro_formacion_id', 'seguimientos_ambiente_modernizacion.codigo_proyecto_sgps_id')
            ->leftJoin('seguimientos_ambiente_modernizacion', 'codigos_proyectos_sgps.id', 'seguimientos_ambiente_modernizacion.codigo_proyecto_sgps_id')
            ->join('lineas_programaticas', 'codigos_proyectos_sgps.linea_programatica_id', 'lineas_programaticas.id')
            ->get();
    }

    /**
     * Web api
     * 
     * Trae las subtipologías Minciencias
     */
    public static function subtipologiasMinciencias()
    {
        return SubtipologiaMinciencias::selectRaw('subtipologias_minciencias.id as value, concat(subtipologias_minciencias.nombre, chr(10), \'∙ Tipología Minciencias: \', tipologias_minciencias.nombre) as label')->join('tipologias_minciencias', 'subtipologias_minciencias.tipologia_minciencias_id', 'tipologias_minciencias.id')->orderBy('subtipologias_minciencias.nombre')->get();
    }

    /**
     * Web api
     * 
     * Trae las tipologías del ambiente
     */
    public static function tipologiasAmbiente()
    {
        return TipologiaAmbiente::select('tipologias_ambientes.id as value', 'tipologias_ambientes.tipo as label')->orderBy('tipologias_ambientes.tipo', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae las convocatorias
     */
    public static function convocatorias()
    {
        return Convocatoria::select('id as value', 'descripcion as label')->orderBy('id', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los roles SENNOVA
     */
    public static function rolesSennova()
    {
        return RolSennova::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get();
    }

    /**
     * Web api
     * 
     * Trae los usuarios por rol
     */
    public static function usuariosPorRol($rol)
    {
        return User::select('users.id as value', 'users.nombre as label')
            ->join('model_has_roles', 'users.id', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', 'roles.id')
            ->where('roles.name', 'ilike', '%' . $rol . '%')
            ->orderBy('users.nombre', 'ASC')->get();
    }

    public static function convocatoriaRolesSennova($convocatoriaId, $proyectoId, $lineaProgramaticaId)
    {
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
            return ConvocatoriaRolSennova::selectRaw("convocatoria_rol_sennova.id as value, convocatoria_rol_sennova.perfil, convocatoria_rol_sennova.mensaje,
                CASE nivel_academico
                    WHEN '7' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Ninguno', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '1' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Técnico', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '2' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Tecnólogo', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '3' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Pregrado', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '4' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Especalización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '5' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Maestría', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '6' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Doctorado', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '8' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Técnico con especialización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                    WHEN '9' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Tecnólogo con especialización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                END as label")
                ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
                ->where('roles_sennova.nombre', 'like', $tipologiaSt)
                ->where('roles_sennova.nombre', '!=', $rol)
                ->orWhere('roles_sennova.nombre', 'like', '%aprendiz sennova (contrato aprendizaje)%')
                ->where('convocatoria_rol_sennova.linea_programatica_id', $lineaProgramaticaId)
                ->where('convocatoria_rol_sennova.convocatoria_id', $convocatoriaId)
                ->orderBy('roles_sennova.nombre')->get();
        }

        return ConvocatoriaRolSennova::selectRaw("convocatoria_rol_sennova.id as value, convocatoria_rol_sennova.perfil, convocatoria_rol_sennova.mensaje,
            CASE nivel_academico
                WHEN '7' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Ninguno', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '1' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Técnico', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '2' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Tecnólogo', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '3' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Pregrado', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '4' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Especalización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '5' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Maestría', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '6' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Doctorado', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '8' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Técnico con especialización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
                WHEN '9' THEN   concat(roles_sennova.nombre, chr(10), '∙ ', 'Nivel académico: Tecnólogo con especialización', chr(10), '∙ ', convocatoria_rol_sennova.experiencia, chr(10), '∙ Asignación mensual: ', convocatoria_rol_sennova.asignacion_mensual)
            END as label")
            ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
            ->where('convocatoria_rol_sennova.linea_programatica_id', $lineaProgramaticaId)
            ->where('convocatoria_rol_sennova.convocatoria_id', $convocatoriaId)
            ->orderBy('roles_sennova.nombre')->get();
    }

    /**
     * Web api
     * 
     * Trae los centros de formación - Cultura innovación
     */
    public static function culturaInnovacionCentrosFormacion()
    {
        return CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo, chr(10), \'∙ Regional: \', regionales.nombre) as label')
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
            ->orderBy('centros_formacion.nombre', 'ASC')->get();
    }
}
