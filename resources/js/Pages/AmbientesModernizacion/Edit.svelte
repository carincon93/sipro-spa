<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import Textarea from '@/Shared/Textarea'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import InputError from '@/Shared/InputError'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import SelectMulti from '@/Shared/SelectMulti'
    import Tags from '@/Shared/Tags'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import InfoMessage from '@/Shared/InfoMessage'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'

    import { onMount } from 'svelte'
    import { Inertia } from '@inertiajs/inertia'

    export let errors
    export let ambienteModernizacion
    export let codigosSgps
    export let mesasSectoriales
    export let tipologiasAmbientes
    export let semillerosInvestigacion
    export let codigosProyectosRelacionados
    export let programasFormacionCalificadosRelacionados
    export let programasFormacionNoCalificadosRelacionados
    export let codProyectosBeneficiadosRelacionados
    export let semillerosRelacionados
    export let mesasSectorialesRelacionadas
    export let equiposAmbienteModernizacion
    export let centroFormacionId

    let programasFormacion
    let programasFormacionArticular

    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]

    let estados = [
        { value: 1, label: 'Buena' },
        { value: 2, label: 'Regular' },
        { value: 3, label: 'Malo' },
    ]

    let estadosEquipo = [
        { value: 'Bueno', label: 'Bueno' },
        { value: 'Regular', label: 'Regular' },
        { value: 'Malo', label: 'Malo' },
    ]

    $: $title = 'Editar ambiente de modernización'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let destroyAmbienteModernizacionDialog = false
    let infoDialog = true
    let equipoFormDialog = false
    let destroyEquipoDialog = false
    let sending = false
    let form = useForm({
        _method: 'put',
        codigo_proyecto_sgps_id: {
            value: ambienteModernizacion.seguimiento_ambiente_modernizacion.codigo_proyecto_sgps_id,
            label: codigosSgps.find((item) => item.value == ambienteModernizacion.seguimiento_ambiente_modernizacion.codigo_proyecto_sgps_id)?.label,
        },
        nombre_ambiente: ambienteModernizacion.nombre_ambiente,
        tipologia_ambiente_id: {
            value: ambienteModernizacion.tipologia_ambiente_id,
            label: tipologiasAmbientes.find((item) => item.value == ambienteModernizacion.tipologia_ambiente_id)?.label,
        },
        red_conocimiento_id: ambienteModernizacion.red_conocimiento_id,
        linea_investigacion_id: ambienteModernizacion.linea_investigacion_id,
        actividad_economica_id: ambienteModernizacion.actividad_economica_id,
        area_conocimiento_id: ambienteModernizacion.disciplina_subarea_conocimiento.subarea_conocimiento.area_conocimiento_id,
        subarea_conocimiento_id: ambienteModernizacion.disciplina_subarea_conocimiento.subarea_conocimiento_id,
        disciplina_subarea_conocimiento_id: ambienteModernizacion.disciplina_subarea_conocimiento_id,
        tematica_estrategica_id: ambienteModernizacion.tematica_estrategica_id,
        semilleros_investigacion_id: semillerosRelacionados.length > 0 ? semillerosRelacionados : null,
        alineado_mesas_sectoriales: {
            value: ambienteModernizacion.alineado_mesas_sectoriales == 1 ? 1 : 2,
            label: opcionesSiNo.find((item) => item.value == (ambienteModernizacion.alineado_mesas_sectoriales == 1 ? 1 : 2))?.label,
        },
        financiado_anteriormente: {
            value: ambienteModernizacion.financiado_anteriormente == 1 ? 1 : 2,
            label: opcionesSiNo.find((item) => item.value == (ambienteModernizacion.financiado_anteriormente == 1 ? 1 : 2))?.label,
        },
        numero_tecnicas_tecnologias: ambienteModernizacion.numero_tecnicas_tecnologias,
        mesa_sectorial_id: mesasSectorialesRelacionadas,
        codigos_proyectos_id: codigosProyectosRelacionados.length > 0 ? codigosProyectosRelacionados : null,
        estado_general_maquinaria: {
            value: ambienteModernizacion.estado_general_maquinaria,
            label: estados.find((item) => item.value == ambienteModernizacion.estado_general_maquinaria)?.label,
        },
        razon_estado_general: ambienteModernizacion.razon_estado_general,
        ambiente_activo: {
            value: ambienteModernizacion.ambiente_activo == 1 ? 1 : 2,
            label: opcionesSiNo.find((item) => item.value == (ambienteModernizacion.ambiente_activo == 1 ? 1 : 2))?.label,
        },
        programas_formacion_calificados: programasFormacionCalificadosRelacionados.length > 0 ? programasFormacionCalificadosRelacionados : null,
        programas_formacion: programasFormacionNoCalificadosRelacionados.length > 0 ? programasFormacionNoCalificadosRelacionados : null,
        justificacion_ambiente_inactivo: ambienteModernizacion.justificacion_ambiente_inactivo,
        ambiente_activo_procesos_idi: {
            value: ambienteModernizacion.ambiente_activo_procesos_idi == 1 ? 1 : 2,
            label: opcionesSiNo.find((item) => item.value == (ambienteModernizacion.ambiente_activo_procesos_idi == 1 ? 1 : 2))?.label,
        },
        numero_proyectos_beneficiados: ambienteModernizacion.numero_proyectos_beneficiados,
        cod_proyectos_beneficiados_id: codProyectosBeneficiadosRelacionados.length > 0 ? codProyectosBeneficiadosRelacionados : null,
        ambiente_formacion_complementaria: {
            value: ambienteModernizacion.ambiente_formacion_complementaria == 1 ? 1 : 2,
            label: opcionesSiNo.find((item) => item.value == (ambienteModernizacion.ambiente_formacion_complementaria == 1 ? 1 : 2))?.label,
        },
        numero_total_cursos_comp: ambienteModernizacion.numero_total_cursos_comp,
        numero_cursos_empresas: ambienteModernizacion.numero_cursos_empresas,
        numero_personas_certificadas: ambienteModernizacion.numero_personas_certificadas,
        datos_empresa: ambienteModernizacion.datos_empresa,
        cursos_complementarios: ambienteModernizacion.cursos_complementarios,
        coordenada_latitud_ambiente: ambienteModernizacion.coordenada_latitud_ambiente,
        coordenada_longitud_ambiente: ambienteModernizacion.coordenada_longitud_ambiente,
        soporte_fotos_ambiente: ambienteModernizacion.soporte_fotos_ambiente,
        impacto_procesos_formacion: ambienteModernizacion.impacto_procesos_formacion,
        pertinencia_sector_productivo: ambienteModernizacion.pertinencia_sector_productivo,
        numero_publicaciones: ambienteModernizacion.numero_publicaciones,
        numero_aprendices_beneficiados: ambienteModernizacion.numero_aprendices_beneficiados,
        productividad_beneficiarios: ambienteModernizacion.productividad_beneficiarios,
        generacion_empleo: ambienteModernizacion.generacion_empleo,
        creacion_empresas: ambienteModernizacion.creacion_empresas,
        incorporacion_nuevos_conocimientos: ambienteModernizacion.incorporacion_nuevos_conocimientos,
        valor_agregado_entidades: ambienteModernizacion.valor_agregado_entidades,
        fortalecimiento_programas_formacion: ambienteModernizacion.fortalecimiento_programas_formacion,
        transferencia_tecnologias: ambienteModernizacion.transferencia_tecnologias,
        cobertura_perntinencia_formacion: ambienteModernizacion.cobertura_perntinencia_formacion,
        palabras_clave_ambiente: ambienteModernizacion.palabras_clave_ambiente,
        observaciones_generales_ambiente: ambienteModernizacion.observaciones_generales_ambiente,
    })

    function submit() {
        if (isSuperAdmin || checkRole(authUser, [4])) {
            $form.post(route('ambientes-modernizacion.update', ambienteModernizacion.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let formEquipo = useForm({
        id: 0,
        numero_inventario_equipo: '',
        nombre_equipo: '',
        descripcion_tecnica_equipo: '',
        estado_equipo: '',
        equipo_en_funcionamiento: '',
        observaciones_generales: '',
    })

    function configurarDialogoEquipo(equipoAmbienteModernizacion) {
        $formEquipo.id = equipoAmbienteModernizacion.id
        $formEquipo.numero_inventario_equipo = equipoAmbienteModernizacion.numero_inventario_equipo
        $formEquipo.nombre_equipo = equipoAmbienteModernizacion.nombre_equipo
        $formEquipo.descripcion_tecnica_equipo = equipoAmbienteModernizacion.descripcion_tecnica_equipo
        $formEquipo.estado_equipo = {
            value: equipoAmbienteModernizacion.estado_equipo,
            label: estadosEquipo.find((item) => item.value == equipoAmbienteModernizacion.estado_equipo)?.label,
        }
        $formEquipo.equipo_en_funcionamiento = {
            value: equipoAmbienteModernizacion.equipo_en_funcionamiento,
            label: opcionesSiNo.find((item) => item.value == equipoAmbienteModernizacion.equipo_en_funcionamiento)?.label,
        }
        $formEquipo.observaciones_generales = equipoAmbienteModernizacion.observaciones_generales
        equipoFormDialog = true
    }

    function submitEquipo() {
        if (isSuperAdmin || checkRole(authUser, [4])) {
            $formEquipo.post(route('equipos-ambiente-modernizacion.store', ambienteModernizacion.id), {
                onStart: () => (sending = true),
                onFinish: () => ((sending = false), (equipoFormDialog = false)),
                preserveScroll: true,
            })
        }
    }

    let equipoAmbienteModernizacionId
    function configurarDialogoEquipoDestroy(equipoAmbienteModernizacion) {
        equipoAmbienteModernizacionId = equipoAmbienteModernizacion.id
        destroyEquipoDialog = true
    }

    function destroyEquipo() {
        if (isSuperAdmin || checkRole(authUser, [4])) {
            $formEquipo.delete(route('equipos-ambiente-modernizacion.destroy', equipoAmbienteModernizacionId), {
                onFinish: () => ((equipoAmbienteModernizacionId = null), (destroyEquipoDialog = false)),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || checkRole(authUser, [4])) {
            $form.delete(route('ambientes-modernizacion.destroy', ambienteModernizacion.id))
        }
    }

    onMount(() => {
        getProgramasFormacionArticular()
        getProgramasFormacion()
    })

    async function getProgramasFormacion() {
        let res = await axios.get(route('web-api.programas-formacion', centroFormacionId))
        if (res.status == '200') {
            programasFormacion = res.data
        }
    }

    async function getProgramasFormacionArticular() {
        let res = await axios.get(route('web-api.programas-formacion-articulados'))
        if (res.status == '200') {
            programasFormacionArticular = res.data
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkRole(authUser, [4])}
                        <a use:inertia href={route('ambientes-modernizacion.index')} class="text-indigo-400 hover:text-indigo-600"> Ambientes de modernización </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {ambienteModernizacion.nombre_ambiente}
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8">
            <div class="mt-44 grid grid-cols-2">
                <div><p class="block font-medium text-sm text-gray-700 mb-4">Regional:</p></div>
                <div>
                    <p class="capitalize">{ambienteModernizacion.seguimiento_ambiente_modernizacion.centro_formacion.regional.nombre}</p>
                    <p>Código: {ambienteModernizacion.seguimiento_ambiente_modernizacion.centro_formacion.regional.codigo}</p>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div><p class="block font-medium text-sm text-gray-700 mb-4">Centro de formación:</p></div>
                <div>
                    <p>{ambienteModernizacion.seguimiento_ambiente_modernizacion.centro_formacion.nombre}</p>
                    <p>Código: {ambienteModernizacion.seguimiento_ambiente_modernizacion.centro_formacion.codigo}</p>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div><p class="block font-medium text-sm text-gray-700 mb-4">Año de modernización:</p></div>
                <div>
                    <p>{ambienteModernizacion.year_modernizacion}</p>
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="codigo_proyecto_sgps_id" value="1. Código proyecto SGPS" />
                </div>
                <div>
                    <Select id="codigo_proyecto_sgps_id" items={codigosSgps} bind:selectedValue={$form.codigo_proyecto_sgps_id} error={errors.codigo_proyecto_sgps_id} autocomplete="off" placeholder="Busque por el título/código del proyecto" required />
                </div>
            </div>

            <div class="mt-28">
                <Label required labelFor="nombre_ambiente" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="2. Nombre del ambiente(s) de formación modernizado por Sennova. Ejemplo: Ambiente de soldadura - Ambiente de confecciones" />
                <Textarea label="Nombre" id="nombre_ambiente" sinContador={true} error={errors.nombre_ambiente} bind:value={$form.nombre_ambiente} classes="bg-transparent block border-0 {errors.nombre_ambiente ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipologia_ambiente_id" value="3. Tipologías de los ambientes (Circular 3-2018- 143)" />
                    <a href={window.basePath + '/storage/documentos-descarga/Circular-3-2018-143.pdf'} target="_blank" class="underline text-indigo-500">Ver Circular 3-2018-143</a>
                </div>
                <div>
                    <Select id="tipologia_ambiente_id" items={tipologiasAmbientes} bind:selectedValue={$form.tipologia_ambiente_id} error={errors.tipologia_ambiente_id} autocomplete="off" placeholder="Seleccione una tipología" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="area_conocimiento_id" value="4. Área de conocimiento relacionada con el ambiente modernizado por Sennova" />
                </div>
                <div>
                    <DynamicList id="area_conocimiento_id" bind:value={$form.area_conocimiento_id} routeWebApi={route('web-api.areas-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la área de conocimiento" message={errors.area_conocimiento_id} required />
                </div>
            </div>
            {#if $form.area_conocimiento_id}
                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="subarea_conocimiento_id" value="Subárea relacionada con el ambiente modernizado por Sennova" />
                    </div>
                    <div>
                        <DynamicList id="subarea_conocimiento_id" bind:value={$form.subarea_conocimiento_id} routeWebApi={route('web-api.subareas-conocimiento', $form.area_conocimiento_id)} classes="min-h" placeholder="Busque por el nombre de la subárea de conocimiento" message={errors.subarea_conocimiento_id} required />
                    </div>
                </div>
            {/if}
            {#if $form.subarea_conocimiento_id}
                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="disciplina_subarea_conocimiento_id" value="Disciplina relacionada con el ambiente modernizado por Sennova" />
                    </div>
                    <div>
                        <DynamicList id="disciplina_subarea_conocimiento_id" bind:value={$form.disciplina_subarea_conocimiento_id} routeWebApi={route('web-api.disciplinas-subarea-conocimiento', $form.subarea_conocimiento_id)} classes="min-h" placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento" message={errors.disciplina_subarea_conocimiento_id} required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="red_conocimiento_id" value="5. Red de conocimiento relacionada con el ambiente modernizado por Sennova (resolución 335 de 2012)" />
                </div>
                <div>
                    <DynamicList id="red_conocimiento_id" bind:value={$form.red_conocimiento_id} routeWebApi={route('web-api.redes-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la red de conocimiento sectorial" message={errors.red_conocimiento_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="actividad_economica_id" value="6. Código CIIU relacionado con el ambiente modernizado por Sennova" />
                </div>
                <div>
                    <DynamicList id="actividad_economica_id" bind:value={$form.actividad_economica_id} routeWebApi={route('web-api.actividades-economicas')} placeholder="Busque por el nombre de la actividad económica" classes="min-h" message={errors.actividad_economica_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tematica_estrategica_id" value="7. Temática estratégica SENA relacionada con el ambiente modernizado por Sennova" />
                </div>
                <div>
                    <DynamicList id="tematica_estrategica_id" bind:value={$form.tematica_estrategica_id} routeWebApi={route('web-api.tematicas-estrategicas')} placeholder="Busque por el nombre de la temática estrategica SENA" message={errors.tematica_estrategica_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="linea_investigacion_id" value="8. Línea investigación relacionada con el ambiente modernizado por Sennova" />
                </div>
                <div>
                    <DynamicList id="linea_investigacion_id" bind:value={$form.linea_investigacion_id} routeWebApi={route('web-api.lineas-investigacion', centroFormacionId)} classes="min-h" placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional" message={errors.linea_investigacion_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="alineado_mesas_sectoriales" value="9. ¿El proyecto se alinea con las Mesas Sectoriales?" />
                </div>
                <div>
                    <Select items={opcionesSiNo} id="alineado_mesas_sectoriales" bind:selectedValue={$form.alineado_mesas_sectoriales} error={errors.alineado_mesas_sectoriales} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>
            {#if $form.alineado_mesas_sectoriales?.value == 1}
                <div class="bg-indigo-100 p-5 mt-10">
                    <InputError message={errors.mesa_sectorial_id} />
                    <div class="grid grid-cols-2">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5" style="transform: translateX(-50px);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-indigo-600">Por favor seleccione la o las mesas sectoriales con la cual o las cuales se alinea el proyecto</p>
                        </div>
                        <div class="bg-white grid grid-cols-2 max-w-xl overflow-y-scroll shadow-2xl mt-4 h-80">
                            {#each mesasSectoriales as { id, nombre }, i}
                                <FormField>
                                    <Checkbox bind:group={$form.mesa_sectorial_id} value={id} />
                                    <span slot="label">{nombre}</span>
                                </FormField>
                            {/each}
                        </div>
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="financiado_anteriormente" value="10. ¿El ambiente de formación ha sido financiado en más de una vigencia por Sennova?" />
                </div>
                <div>
                    <Select items={opcionesSiNo} id="financiado_anteriormente" bind:selectedValue={$form.financiado_anteriormente} error={errors.financiado_anteriormente} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="numero_tecnicas_tecnologias" value="11. Relacione el número de técnicas o tecnologías adquiridas y/o mejoradas con el ambiente de aprendizaje, modernizado por SENNOVA. " />
                </div>
                <div>
                    <Input label="Número total" id="numero_tecnicas_tecnologias" type="number" input$min="0" input$max="999999" class="mt-1" error={errors.numero_tecnicas_tecnologias} placeholder="Escriba el número de técnicas o tecnologías adquiridas" bind:value={$form.numero_tecnicas_tecnologias} required />
                </div>
            </div>

            {#if $form.financiado_anteriormente?.value == 1}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="codigos_proyectos_id" value="12. Si la respuesta anterior fue afirmativa, relacione los códigos y nombres de los proyectos beneficiados y/o ejecutados en el ambiente modernizado por Sennova de convocatoria (SGPS) o de capacidad instalada (CAP)" />
                    </div>
                    <div>
                        <SelectMulti id="codigos_proyectos_id" bind:selectedValue={$form.codigos_proyectos_id} items={codigosSgps} isMulti={true} error={errors.codigos_proyectos_id} placeholder="Buscar por el código/título del proyecto" required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="estado_general_maquinaria" value="13. Estado general de maquinaria y equipo instalados en el ambiente de aprendizaje, modernizado por SENNOVA." />
                </div>
                <div>
                    <Select items={estados} id="estado_general_maquinaria" bind:selectedValue={$form.estado_general_maquinaria} error={errors.estado_general_maquinaria} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            {#if $form.estado_general_maquinaria?.value == 2 || $form.estado_general_maquinaria?.value == 3}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="razon_estado_general" value="14. Si la respuesta anterior fue regular o malo, describa la razón. Para mayor especificidad listar máquina por máquina para identificación a partir del tiempo de vida útil." />
                    </div>
                    <div>
                        <Textarea label="Razón" maxlength="40000" id="razon_estado_general" error={errors.razon_estado_general} bind:value={$form.razon_estado_general} required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="ambiente_activo" value="15. ¿A la fecha el ambiente modernizado por Sennova está activo para realizar procesos de formación?" />
                </div>
                <div>
                    <Select items={opcionesSiNo} id="ambiente_activo" bind:selectedValue={$form.ambiente_activo} error={errors.ambiente_activo} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            {#if $form.ambiente_activo?.value == 1}
                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="programas_formacion_calificados" value="Si la respuesta anterior fue afirmativa, seleccione los programas de formación con registro calificado beneficiados." />
                    </div>
                    <div>
                        <SelectMulti id="programas_formacion_calificados" bind:selectedValue={$form.programas_formacion_calificados} items={programasFormacion} isMulti={true} error={errors.programas_formacion_calificados} placeholder="Buscar por el nombre del programa de formación" required />
                        {#if programasFormacion?.length == 0}
                            <div>
                                <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                                <button on:click={getProgramasFormacion} type="button" class="flex underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Refrescar
                                </button>
                            </div>
                        {/if}
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label class="mb-4" labelFor="programas_formacion" value="Si la respuesta anterior fue afirmativa, seleccione los programas de formación beneficiados." />
                    </div>
                    <div>
                        <SelectMulti id="programas_formacion" bind:selectedValue={$form.programas_formacion} items={programasFormacionArticular} isMulti={true} error={errors.programas_formacion} placeholder="Buscar por el nombre del programa de formación" required />
                        {#if programasFormacionArticular?.length == 0}
                            <div>
                                <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                                <button on:click={getProgramasFormacionArticular} type="button" class="flex underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Refrescar
                                </button>
                            </div>
                        {/if}
                    </div>
                </div>
            {:else if $form.ambiente_activo?.value == 2}
                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="justificacion_ambiente_inactivo" value="Si la respuesta anterior fue negativa, justifique la respuesta" />
                    </div>
                    <div>
                        <Textarea label="Justificación" maxlength="4000" id="justificacion_ambiente_inactivo" error={errors.justificacion_ambiente_inactivo} bind:value={$form.justificacion_ambiente_inactivo} required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="ambiente_activo_procesos_idi" value="16. ¿A la fecha el ambiente modernizado por Sennova está activo para realizar procesos de investigación, desarrollo tecnológico y/o innovación con semilleros o programas de formación?" />
                </div>
                <div>
                    <Select items={opcionesSiNo} id="ambiente_activo_procesos_idi" bind:selectedValue={$form.ambiente_activo_procesos_idi} error={errors.ambiente_activo_procesos_idi} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            {#if $form.ambiente_activo_procesos_idi?.value == 1}
                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="numero_proyectos_beneficiados" value="Si la respuesta anterior fue afirmativa, relacione el número de proyectos beneficiados y/o ejecutados en el ambiente modernizado por Sennova" />
                    </div>
                    <div>
                        <Input label="Número de proyectos" id="numero_proyectos_beneficiados" type="number" input$min="0" input$max="999999" class="mt-1" error={errors.numero_proyectos_beneficiados} placeholder="Escriba el número de proyectos" bind:value={$form.numero_proyectos_beneficiados} required />
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="cod_proyectos_beneficiados_id" value="Si la respuesta anterior fue afirmativa, relacione los códigos y nombres de los proyectos beneficiados y/o ejecutados en el ambiente modernizado por Sennova" />
                    </div>
                    <div>
                        <SelectMulti id="cod_proyectos_beneficiados_id" bind:selectedValue={$form.cod_proyectos_beneficiados_id} items={codigosSgps} isMulti={true} error={errors.cod_proyectos_beneficiados_id} placeholder="Buscar por el código/título de los proyectos" required />
                        {#if programasFormacion?.length == 0}
                            <div>
                                <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                                <button on:click={getProgramasFormacion} type="button" class="flex underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Refrescar
                                </button>
                            </div>
                        {/if}
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="semilleros_investigacion_id" value="Si la respuesta anterior fue afirmativa, relacione los semilleros de investigación beneficiados con el ambiente modernizado por Sennova" />
                    </div>
                    <div>
                        <SelectMulti id="semilleros_investigacion_id" bind:selectedValue={$form.semilleros_investigacion_id} items={semillerosInvestigacion} isMulti={true} error={errors.semilleros_investigacion_id} placeholder="Buscar por el nombre del semillero de investigación" required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="ambiente_formacion_complementaria" value="17. ¿El ambiente de formación ha generado formación complementaria después de la modernización con Sennova?" />
                </div>
                <div>
                    <Select items={opcionesSiNo} id="ambiente_formacion_complementaria" bind:selectedValue={$form.ambiente_formacion_complementaria} error={errors.ambiente_formacion_complementaria} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            {#if $form.ambiente_formacion_complementaria?.value == 1}
                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="numero_total_cursos_comp" value="Si la respuesta anterior fue afirmativa, relacione el número total de cursos complementarios que se ha brindado formación complementaria" />
                    </div>
                    <div>
                        <Input label="Número total" id="numero_total_cursos_comp" type="number" input$min="0" input$max="999999" class="mt-1" error={errors.numero_total_cursos_comp} placeholder="Escriba el número de proyectos" bind:value={$form.numero_total_cursos_comp} required />
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="numero_cursos_empresas" value="Si la respuesta anterior fue afirmativa, relacione el número de cursos complementarios a empresas que se ha brindado formación complementaria" />
                    </div>
                    <div>
                        <Input id="numero_cursos_empresas" type="number" input$min="0" input$max="999999" class="mt-1" error={errors.numero_cursos_empresas} placeholder="Escriba el número de proyectos" bind:value={$form.numero_cursos_empresas} required />
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required={$form.numero_cursos_empresas > 0 ? 'required' : undefined} class="mb-4" labelFor="datos_empresa" value="Si la respuesta anterior fue afirmativa, relacione el NIT y nombre de las empresas (cuando aplique) que se ha brindada formación complementaria (Separados por coma)" />
                    </div>
                    <div>
                        <Tags id="datos_empresa" class="mt-4" enforceWhitelist={false} bind:tags={$form.datos_empresa} placeholder="Empresas" error={errors.datos_empresa} required={$form.numero_cursos_empresas > 0 ? 'required' : undefined} />
                        <InfoMessage>Separar empresas por coma o dar Enter una vez finalice de escribir</InfoMessage>
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label required class="mb-4" labelFor="numero_personas_certificadas" value="Si la respuesta anterior fue afirmativa, relacione el número total de personas certificadas de las empresas que se ha brindado formación complementaria." />
                    </div>
                    <div>
                        <Input id="numero_personas_certificadas" type="number" input$min="0" input$max="999999" class="mt-1" error={errors.numero_personas_certificadas} placeholder="Escriba el número total de personas certificadas" bind:value={$form.numero_personas_certificadas} required />
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2 pl-4">
                    <div>
                        <Label class="mb-4" labelFor="cursos_complementarios" value="Si la respuesta anterior fue afirmativa, relacione los códigos y nombres Sena de cada curso de formación complementario (Separados por coma)" />
                    </div>
                    <div>
                        <Tags id="cursos_complementarios" class="mt-4" enforceWhitelist={false} bind:tags={$form.cursos_complementarios} placeholder="Cursos de formación complementarios" error={errors.cursos_complementarios} />
                        <InfoMessage>Separar cursos por coma o dar Enter una vez finalice de escribir</InfoMessage>
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="coordenada_latitud_ambiente" value="18. Diligencie la coordenada latitud (W) del ambiente de formación modernizado por Sennova (generado en Google maps). Ejemplo: -74.062916" />
                </div>
                <div>
                    <Input label="Latitud" id="coordenada_latitud_ambiente" type="text" class="mt-1" error={errors.coordenada_latitud_ambiente} placeholder="Escriba la latitud" bind:value={$form.coordenada_latitud_ambiente} required />
                    <InfoMessage>Más información en el siguiente enlace <a href="https://support.google.com/maps/answer/18539?hl=es-MX&co=GENIE.Platform%3DAndroid&oco=1" class="underline" target="_blank">https://support.google.com/maps/answer/18539?hl=es-MX&co=GENIE.Platform%3DAndroid&oco=1</a> (sección “Cómo obtener las coordenadas de un lugar”)</InfoMessage>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="coordenada_longitud_ambiente" value="19. Diligencie la coordenada longitud (N) del ambiente de formación modernizado por Sennova (generado en Google maps). Ejemplo: 4.643244" />
                </div>
                <div>
                    <Input label="Longitud" id="coordenada_longitud_ambiente" type="text" class="mt-1" error={errors.coordenada_longitud_ambiente} placeholder="Escriba la latitud" bind:value={$form.coordenada_longitud_ambiente} required />
                    <InfoMessage>Más información en el siguiente enlace <a href="https://support.google.com/maps/answer/18539?hl=es-MX&co=GENIE.Platform%3DAndroid&oco=1" class="underline" target="_blank">https://support.google.com/maps/answer/18539?hl=es-MX&co=GENIE.Platform%3DAndroid&oco=1</a> (sección “Cómo obtener las coordenadas de un lugar”)</InfoMessage>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="impacto_procesos_formacion" value="20. Describa el impacto generado en los procesos de formación" />
                </div>
                <div>
                    <Textarea label="Impacto" maxlength="4000" id="impacto_procesos_formacion" error={errors.impacto_procesos_formacion} bind:value={$form.impacto_procesos_formacion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="pertinencia_sector_productivo" value="21. Describa la pertinencia obtenida con el sector productivo" />
                </div>
                <div>
                    <Textarea label="Pertinencia" maxlength="4000" id="pertinencia_sector_productivo" error={errors.pertinencia_sector_productivo} bind:value={$form.pertinencia_sector_productivo} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="numero_publicaciones" value="22. Relacione el número de publicaciones derivadas con el ambiente de aprendizaje después de ejecutar el proyecto de modernización SENNOVA." />
                </div>
                <div>
                    <Input label="Número total" id="numero_publicaciones" type="number" input$min="0" input$max="999999" class="mt-1" error={errors.numero_publicaciones} placeholder="Escriba el número de técnicas o tecnologías adquiridas" bind:value={$form.numero_publicaciones} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="numero_aprendices_beneficiados" value="23. Relacione el número de aprendices beneficiados con el ambiente de aprendizaje después de ejecutar el proyecto de modernización SENNOVA. " />
                </div>
                <div>
                    <Input label="Número total" id="numero_aprendices_beneficiados" type="number" input$min="0" input$max="999999" class="mt-1" error={errors.numero_aprendices_beneficiados} placeholder="Escriba el número de técnicas o tecnologías adquiridas" bind:value={$form.numero_aprendices_beneficiados} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="productividad_beneficiarios" value="24. Productividad y competitividad del (los) beneficiario(s) final(es) del proyecto." />
                </div>
                <div>
                    <Textarea label="Descripción" maxlength="4000" id="productividad_beneficiarios" error={errors.productividad_beneficiarios} bind:value={$form.productividad_beneficiarios} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="generacion_empleo" value="25. Generación o mantenimiento de empleo por parte del (los) beneficiario(s) del proyecto." />
                </div>
                <div>
                    <Textarea label="Descripción" maxlength="4000" id="generacion_empleo" error={errors.generacion_empleo} bind:value={$form.generacion_empleo} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="creacion_empresas" value="26. Creación de nuevas empresas y diseño y desarrollo de nuevos productos, procesos o servicios" />
                </div>
                <div>
                    <Textarea label="Descripción" maxlength="4000" id="creacion_empresas" error={errors.creacion_empresas} bind:value={$form.creacion_empresas} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="incorporacion_nuevos_conocimientos" value="27. Incorporación de nuevos conocimientos y competencias laborales en el talento humano en la(s) empresa(s) beneficiaria(s) del proyecto" />
                </div>
                <div>
                    <Textarea label="Descripción" maxlength="4000" id="incorporacion_nuevos_conocimientos" error={errors.incorporacion_nuevos_conocimientos} bind:value={$form.incorporacion_nuevos_conocimientos} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="valor_agregado_entidades" value="28. Generación de valor agregado en la(s) entidad(es) beneficiaria(s) del proyecto" />
                </div>
                <div>
                    <Textarea label="Descripción" maxlength="4000" id="valor_agregado_entidades" error={errors.valor_agregado_entidades} bind:value={$form.valor_agregado_entidades} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="fortalecimiento_programas_formacion" value="29. Fortalecimiento de programas de formación del Sena" />
                </div>
                <div>
                    <Textarea label="Descripción" maxlength="4000" id="fortalecimiento_programas_formacion" error={errors.fortalecimiento_programas_formacion} bind:value={$form.fortalecimiento_programas_formacion} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="transferencia_tecnologias" value="30. Transferencia de tecnologías al Sena y a los sectores productivos relacionados" />
                </div>
                <div>
                    <Textarea label="Descripción" maxlength="4000" id="transferencia_tecnologias" error={errors.transferencia_tecnologias} bind:value={$form.transferencia_tecnologias} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="cobertura_perntinencia_formacion" value="31. Cobertura, calidad y pertinencia de la formación" />
                </div>
                <div>
                    <Textarea label="Descripción" maxlength="4000" id="cobertura_perntinencia_formacion" error={errors.cobertura_perntinencia_formacion} bind:value={$form.cobertura_perntinencia_formacion} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="palabras_clave_ambiente" value="32. Palabras claves relacionadas con el ambiente de formación modernizado por Sennova (Separados por coma)" />
                </div>
                <div>
                    <Tags id="palabras_clave_ambiente" class="mt-4" enforceWhitelist={false} bind:tags={$form.palabras_clave_ambiente} placeholder="Palabras clave" error={errors.palabras_clave_ambiente} />
                    <InfoMessage>Separar palabras clave por coma o dar Enter una vez finalice de escribir</InfoMessage>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="observaciones_generales_ambiente" value="33. Observaciones generales del ambiente modernizado por Sennova" />
                </div>
                <div>
                    <Textarea label="Observaciones" maxlength="4000" id="observaciones_generales_ambiente" error={errors.observaciones_generales_ambiente} bind:value={$form.observaciones_generales_ambiente} />
                </div>
            </div>

            <div class="mt-20">
                <Label class="mb-4" labelFor="soporte_fotos_ambiente" value="34. Url del archivo formato (.pdf) con fotos del ambiente modernizado con el proyecto Sennova" />
                {#if ambienteModernizacion.soporte_fotos_ambiente}
                    <a target="_blank" class="text-green-600 underline mb-4 font-black flex" download href={ambienteModernizacion.soporte_fotos_ambiente}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Archivo cargado correctamente. Descargar dando clic en este enlace.
                    </a>
                {/if}
                <Input
                    label="Url"
                    id="soporte_fotos_ambiente"
                    type="url"
                    message="En enlace debe ser del archivo almacenado en el SharePoint del Sistema Unificado Documental SENNOVA 2022 (carpeta: Actualización y Modernización De Ambientes 23 à 1.SeguimientoPostCierre) y tenga en cuenta que pueden crear subcarpetas con el código de cada registro realizado, código que puede visualizar en el menú principal del módulo 'Seguimiento post cierre - Ambientes de modernización Sennova'"
                    class="mt-1"
                    error={errors.soporte_fotos_ambiente}
                    placeholder="Url https://www.google.com.co"
                    bind:value={$form.soporte_fotos_ambiente}
                    required
                />
            </div>
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || checkRole(authUser, [4])}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (destroyAmbienteModernizacionDialog = true)}> Eliminar ambiente de modernización </button>
            {/if}
            {#if isSuperAdmin || checkRole(authUser, [4])}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar ambiente de modernización</LoadingButton>
            {/if}
        </div>
    </form>

    <hr class="w-full block my-20" />

    <div>
        <h1 class="text-center text-2xl">Relacione únicamente los equipos y maquinaría adquirida con la ejecución del proyecto de modernización SENNOVA:</h1>
        <div class="flex justify-end mt-10">
            <Button on:click={() => ((equipoFormDialog = true), $formEquipo.reset())} variant="raised">Crear equipo</Button>
        </div>

        <table class="w-full bg-white whitespace-no-wrap table-fixed data-table mt-10">
            <thead>
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Número de inventario del equipo o maquina</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre del equipo o maquina</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Descripción general técnica del equipo o maquina</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Estado del equipo o maquina</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">¿El equipo o maquina está funcionamiento?</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Observaciones generales</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {#each equiposAmbienteModernizacion as equipoAmbienteModernizacion}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 pt-6 pb-4">{equipoAmbienteModernizacion.numero_inventario_equipo}</td>
                        <td class="border-t px-6 pt-6 pb-4">{equipoAmbienteModernizacion.nombre_equipo}</td>
                        <td class="border-t px-6 pt-6 pb-4">{equipoAmbienteModernizacion.descripcion_tecnica_equipo}</td>
                        <td class="border-t px-6 pt-6 pb-4">{equipoAmbienteModernizacion.estado_equipo}</td>
                        <td class="border-t px-6 pt-6 pb-4">{equipoAmbienteModernizacion.equipo_en_funcionamiento_text}</td>
                        <td class="border-t px-6 pt-6 pb-4">
                            <p class="paragraph-ellipsis">
                                {equipoAmbienteModernizacion.observaciones_generales}
                            </p>
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            <DataTableMenu>
                                {#if isSuperAdmin || checkRole(authUser, [4])}
                                    <Item on:SMUI:action={() => configurarDialogoEquipo(equipoAmbienteModernizacion)}>
                                        <Text>Editar</Text>
                                    </Item>
                                    <Item on:SMUI:action={() => configurarDialogoEquipoDestroy(equipoAmbienteModernizacion)}>
                                        <Text>Eliminar</Text>
                                    </Item>
                                {/if}
                            </DataTableMenu>
                        </td>
                    </tr>
                {/each}
                {#if equiposAmbienteModernizacion.length === 0}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 pt-6 pb-4" colspan="7"> Sin información registrada </td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>

    <Dialog bind:open={equipoFormDialog} fullscreen>
        <div slot="title" class="flex items-center flex-col mt-4">Registrar equipo</div>
        <div slot="content">
            <form on:submit|preventDefault={submitEquipo} id="equipo-ambiente-modernizacion">
                <fieldset class="p-8">
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="numero_inventario_equipo" value="Número de inventario del equipo o máquina" />
                        <Input id="numero_inventario_equipo" type="text" class="mt-1" error={errors.numero_inventario_equipo} placeholder="Escriba el número de inventario del equipo/maquina" bind:value={$formEquipo.numero_inventario_equipo} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="nombre_equipo" value="Nombre del equipo o máquina" />
                        <Input id="nombre_equipo" type="text" class="mt-1" error={errors.nombre_equipo} placeholder="Escriba el número de inventario del equipo/maquina" bind:value={$formEquipo.nombre_equipo} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="descripcion_tecnica_equipo" value="Descripción general técnica del equipo o máquina" />
                        <Textarea maxlength="40000" id="descripcion_tecnica_equipo" error={errors.descripcion_tecnica_equipo} bind:value={$formEquipo.descripcion_tecnica_equipo} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="estado_equipo" value="Estado del equipo o máquina (Bueno, Regular, Malo)" />
                        <Select items={estadosEquipo} id="estado_equipo" bind:selectedValue={$formEquipo.estado_equipo} error={errors.estado_equipo} autocomplete="off" placeholder="Seleccione una opción" required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="equipo_en_funcionamiento" value="¿El equipo o máquina está funcionamiento? SI/NO" />
                        <Select items={opcionesSiNo} id="equipo_en_funcionamiento" bind:selectedValue={$formEquipo.equipo_en_funcionamiento} error={errors.equipo_en_funcionamiento} autocomplete="off" placeholder="Seleccione una opción" required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="observaciones_generales" value="Observaciones generales" />
                        <Textarea maxlength="40000" id="observaciones_generales" error={errors.observaciones_generales} bind:value={$formEquipo.observaciones_generales} required />
                    </div>
                </fieldset>
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (equipoFormDialog = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" type="submit" form="equipo-ambiente-modernizacion">Guardar</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={destroyEquipoDialog}>
        <div slot="title" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro(a) que desea eliminar este recurso?
                <br />
                Todos los datos se eliminarán de forma permanente.
                <br />
                Está acción no se puede deshacer.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => ((destroyEquipoDialog = false), (equipoAmbienteModernizacionId = null))} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroyEquipo}>Confirmar</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={infoDialog} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/proyecto.png'} alt="Proyecto" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {ambienteModernizacion.seguimiento_ambiente_modernizacion.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Para terminar de diligenciar toda la información del seguimiento post cierre del ambiente de modernización por favor de clic en <strong>Continuar diligenciando</strong>, si ya actualizó todos los campos de clic en <strong>Omitir</strong></h1>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (infoDialog = false)} variant={null}>Omitir</Button>
                <Button variant="raised" on:click={(event) => (infoDialog = false)} on:click={() => Inertia.visit('#financiado_anteriormente')}>Continuar diligenciando</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={destroyAmbienteModernizacionDialog}>
        <div slot="title" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro(a) que desea eliminar este recurso?
                <br />
                Todos los datos se eliminarán de forma permanente.
                <br />
                Está acción no se puede deshacer.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (destroyAmbienteModernizacionDialog = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
