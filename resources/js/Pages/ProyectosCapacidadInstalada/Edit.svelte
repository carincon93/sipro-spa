<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { onMount } from 'svelte'
    import axios from 'axios'
    import { Inertia } from '@inertiajs/inertia'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import InfoMessage from '@/Shared/InfoMessage'
    import SelectMulti from '@/Shared/SelectMulti'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Input from '@/Shared/Input'

    export let errors
    export let centrosFormacion
    export let proyectoCapacidadInstalada
    export let listaBeneficiados
    export let programasFormacion
    export let programasFormacionArticular
    export let proyectoProgramasFormacion
    export let proyectoProgramasFormacionArticulados
    export let roles
    export let autorPrincipal

    $: $title = 'Editar proyecto de capacidad instalada'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let proyectoDialogOpen = true
    let sending = false

    let formBibliografia = useForm({
        bibliografia: proyectoCapacidadInstalada.bibliografia,
    })

    let formPlanteamientoProblema = useForm({
        planteamiento_problema: proyectoCapacidadInstalada.planteamiento_problema,
    })

    let formJustificacion = useForm({
        justificacion: proyectoCapacidadInstalada.justificacion,
    })

    let formObjetivoGeneral = useForm({
        objetivo_general: proyectoCapacidadInstalada.objetivo_general,
    })

    let formMetodologia = useForm({
        metodologia: proyectoCapacidadInstalada.metodologia,
    })

    let formInfraestructuraDesarrolloProyecto = useForm({
        infraestructura_desarrollo_proyecto: proyectoCapacidadInstalada.infraestructura_desarrollo_proyecto,
    })

    let formMaterialesFormacion = useForm({
        materiales_formacion_a_usar: proyectoCapacidadInstalada.materiales_formacion_a_usar,
    })

    let formConclusiones = useForm({
        conclusiones: proyectoCapacidadInstalada.conclusiones,
    })

    let form = useForm({
        centro_formacion_id: {
            value: centrosFormacion.find((item) => item.value == proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion_id)?.value,
            label: centrosFormacion.find((item) => item.value == proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion_id)?.label,
        },
        linea_investigacion_id: proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion_id,
        semillero_investigacion_id: proyectoCapacidadInstalada.semillero_investigacion_id,
        area_conocimiento_id: proyectoCapacidadInstalada.disciplina_subarea_conocimiento.subarea_conocimiento.area_conocimiento_id,
        subarea_conocimiento_id: proyectoCapacidadInstalada.disciplina_subarea_conocimiento.subarea_conocimiento_id,
        disciplina_subarea_conocimiento_id: proyectoCapacidadInstalada.disciplina_subarea_conocimiento_id,
        red_conocimiento_id: proyectoCapacidadInstalada.red_conocimiento_id,
        actividad_economica_id: proyectoCapacidadInstalada.actividad_economica_id,
        tipo_proyecto_capacidad_instalada_id: proyectoCapacidadInstalada.subtipo_proyecto_capacidad_instalada.tipo_proyecto_capacidad_instalada_id,
        subtipo_proyecto_capacidad_instalada_id: proyectoCapacidadInstalada.subtipo_proyecto_capacidad_instalada_id,
        titulo: proyectoCapacidadInstalada.titulo,
        fecha_inicio: proyectoCapacidadInstalada.fecha_inicio,
        fecha_finalizacion: proyectoCapacidadInstalada.fecha_finalizacion,
        beneficia_a: {
            value: listaBeneficiados.find((item) => item.value == proyectoCapacidadInstalada.beneficia_a)?.value,
            label: listaBeneficiados.find((item) => item.value == proyectoCapacidadInstalada.beneficia_a)?.label,
        },
        programas_formacion: proyectoProgramasFormacion.length > 0 ? proyectoProgramasFormacion : null,
        programas_formacion_articulados: proyectoProgramasFormacionArticulados.length > 0 ? proyectoProgramasFormacionArticulados : null,
        rol_sennova: {
            value: autorPrincipal ? roles.find((item) => item.value == autorPrincipal.pivot.rol_sennova)?.value : null,
            label: autorPrincipal ? roles.find((item) => item.value == autorPrincipal.pivot.rol_sennova)?.label : null,
        },
        cantidad_meses: autorPrincipal ? autorPrincipal.pivot.cantidad_meses : null,
        cantidad_horas: autorPrincipal ? autorPrincipal.pivot.cantidad_horas : null,
    })

    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))) {
            $form.put(route('proyectos-capacidad-instalada.update', proyectoCapacidadInstalada.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || (checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))) {
            $form.delete(route('proyectos-capacidad-instalada.destroy', [proyectoCapacidadInstalada.id]))
        }
    }

    async function syncColumnLong(column, form) {
        return new Promise((resolve) => {
            if ((typeof column !== 'undefined' && typeof form !== 'undefined' && isSuperAdmin) || (typeof column !== 'undefined' && typeof form !== 'undefined' && checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))) {
                Inertia.put(
                    route('proyectos-capacidad-instalada.updateLongColumn', [proyectoCapacidadInstalada.id, column]),
                    { [column]: form[column] },
                    {
                        onStart: () => (sending = true),
                        onError: (resp) => ((sending = false), resolve(resp)),
                        onFinish: () => ((sending = false), resolve({})),
                        preserveScroll: true,
                    },
                )
            } else {
                resolve({})
            }
        })
    }

    onMount(() => {
        getProgramasFormacion()
        getProgramasFormacionArticular()
    })

    async function getProgramasFormacion() {
        let res = await axios.get(route('web-api.programas-formacion', $form.centro_formacion_id?.value))
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
                <h1>
                    <a use:inertia href={route('proyectos-capacidad-instalada.index')} class="text-indigo-400 hover:text-indigo-600"> Proyectos de capacidad instalada </a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.edit', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600 font-extrabold underline">Información básica</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.integrantes.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Integrantes</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.objetivos-especificos.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Objetivos específicos y resultados</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.productos.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Productos</a>
                    {#if isSuperAdmin || (checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))}
                        <span class="text-indigo-400 font-medium">/</span>
                        <a use:inertia href={route('proyectos-capacidad-instalada.finalizar', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Finalizar</a>
                    {/if}
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id)) ? undefined : true}>
            <div class="mt-28">
                <Label required labelFor="titulo" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué. (Máximo 20 palabras)" />
                <Textarea label="Título" id="titulo" sinContador={true} error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion || errors.max_meses_ejecucion}
                    <div class="mb-20 flex justify-center mt-4">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                    </div>
                {/if}
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                </div>
                <div>
                    <Select id="centro_formacion_id" items={centrosFormacion} bind:selectedValue={$form.centro_formacion_id} error={errors.centro_formacion_id} autocomplete="off" placeholder="Busque por el nombre del centro de formación" required />
                </div>
            </div>

            {#if $form.centro_formacion_id?.value}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea de investigación" />
                    </div>
                    <div>
                        <DynamicList id="linea_investigacion_id" bind:value={$form.linea_investigacion_id} routeWebApi={route('web-api.lineas-investigacion', $form.centro_formacion_id?.value)} classes="min-h" placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional" message={errors.linea_investigacion_id} required />
                    </div>
                </div>
            {/if}
            {#if $form.linea_investigacion_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="semillero_investigacion_id" value="Semillero de investigación" />
                    </div>
                    <div>
                        <DynamicList id="semillero_investigacion_id" bind:value={$form.semillero_investigacion_id} routeWebApi={route('web-api.semilleros-investigacion', $form.linea_investigacion_id)} classes="min-h" placeholder="Busque por el nombre del semillero de investigación" message={errors.semillero_investigacion_id} required />
                    </div>
                </div>
            {/if}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="red_conocimiento_id" value="Red de conocimiento sectorial" />
                </div>
                <div>
                    <DynamicList id="red_conocimiento_id" bind:value={$form.red_conocimiento_id} routeWebApi={route('web-api.redes-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la red de conocimiento sectorial" message={errors.red_conocimiento_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="area_conocimiento_id" value="Área de conocimiento" />
                </div>
                <div>
                    <DynamicList id="area_conocimiento_id" bind:value={$form.area_conocimiento_id} routeWebApi={route('web-api.areas-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la área de conocimiento" message={errors.area_conocimiento_id} required />
                </div>
            </div>
            {#if $form.area_conocimiento_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="subarea_conocimiento_id" value="Subárea de conocimiento" />
                    </div>
                    <div>
                        <DynamicList id="subarea_conocimiento_id" bind:value={$form.subarea_conocimiento_id} routeWebApi={route('web-api.subareas-conocimiento', $form.area_conocimiento_id)} classes="min-h" placeholder="Busque por el nombre de la subárea de conocimiento" message={errors.subarea_conocimiento_id} required />
                    </div>
                </div>
            {/if}
            {#if $form.subarea_conocimiento_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="disciplina_subarea_conocimiento_id" value="Disciplina de la subárea de conocimiento" />
                    </div>
                    <div>
                        <DynamicList id="disciplina_subarea_conocimiento_id" bind:value={$form.disciplina_subarea_conocimiento_id} routeWebApi={route('web-api.disciplinas-subarea-conocimiento', $form.subarea_conocimiento_id)} classes="min-h" placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento" message={errors.disciplina_subarea_conocimiento_id} required />
                    </div>
                </div>
            {/if}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="actividad_economica_id" value="¿En cuál de estas actividades económicas se puede aplicar el proyecto?" />
                </div>
                <div>
                    <DynamicList id="actividad_economica_id" bind:value={$form.actividad_economica_id} routeWebApi={route('web-api.actividades-economicas')} placeholder="Busque por el nombre de la actividad económica" classes="min-h" message={errors.actividad_economica_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipo_proyecto_capacidad_instalada_id" value="Tipo de proyecto" />
                </div>
                <div>
                    <DynamicList id="tipo_proyecto_capacidad_instalada_id" bind:value={$form.tipo_proyecto_capacidad_instalada_id} routeWebApi={route('web-api.tipos-proyecto-capacidad-instalada')} classes="min-h" placeholder="Busque por el nombre del tipo de proyecto" message={errors.tipo_proyecto_capacidad_instalada_id} required />
                </div>
            </div>
            {#if $form.tipo_proyecto_capacidad_instalada_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="subtipo_proyecto_capacidad_instalada_id" value="Subtipo de proyecto" />
                    </div>
                    <div>
                        <DynamicList id="subtipo_proyecto_capacidad_instalada_id" bind:value={$form.subtipo_proyecto_capacidad_instalada_id} routeWebApi={route('web-api.subtipos-proyecto-capacidad-instalada', $form.tipo_proyecto_capacidad_instalada_id)} classes="min-h" placeholder="Busque por el nombre del subtipo de proyecto" message={errors.subtipo_proyecto_capacidad_instalada_id} required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="beneficia_a" value="El proyecto beneficiará a:" />
                </div>
                <div>
                    <Select id="beneficia_a" items={listaBeneficiados} bind:selectedValue={$form.beneficia_a} error={errors.centro_formacion_id} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="planteamiento_problema" value="Planteamiento del problema" />
                </div>
                <div>
                    <Textarea label="Planteamiento del problema" maxlength="40000" id="planteamiento_problema" error={errors.planteamiento_problema} bind:value={$formPlanteamientoProblema.planteamiento_problema} on:input={() => syncColumnLong('planteamiento_problema', $formPlanteamientoProblema)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="justificacion" value="Justificación" />
                </div>
                <div>
                    <Textarea label="Justificación" maxlength="40000" id="justificacion" error={errors.justificacion} bind:value={$formJustificacion.justificacion} on:input={() => syncColumnLong('justificacion', $formJustificacion)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="objetivo_general" value="Objetivo general" />
                </div>
                <div>
                    <Textarea label="Objetivo general" maxlength="40000" id="objetivo_general" error={errors.objetivo_general} bind:value={$formObjetivoGeneral.objetivo_general} on:input={() => syncColumnLong('objetivo_general', $formObjetivoGeneral)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="metodologia" value="Metodología" />
                </div>
                <div>
                    <Textarea label="Metodología" maxlength="40000" id="metodologia" error={errors.metodologia} bind:value={$formMetodologia.metodologia} on:input={() => syncColumnLong('metodologia', $formMetodologia)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="infraestructura_desarrollo_proyecto" value="Infraestructura para el desarrollo del proyecto" />
                </div>
                <div>
                    <Textarea
                        label="Describr los ambientes de formación y equipos que se van a usar"
                        maxlength="40000"
                        id="infraestructura_desarrollo_proyecto"
                        error={errors.infraestructura_desarrollo_proyecto}
                        bind:value={$formInfraestructuraDesarrolloProyecto.infraestructura_desarrollo_proyecto}
                        on:input={() => syncColumnLong('infraestructura_desarrollo_proyecto', $formInfraestructuraDesarrolloProyecto)}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="materiales_formacion_a_usar" value="Materiales de formación a utilizar" />
                </div>
                <div>
                    <Textarea label="Describir los materiales de formación que se van a utilizar" maxlength="40000" id="materiales_formacion_a_usar" error={errors.materiales_formacion_a_usar} bind:value={$formMaterialesFormacion.materiales_formacion_a_usar} on:input={() => syncColumnLong('materiales_formacion_a_usar', $formMaterialesFormacion)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="programas_formacion" value="Nombre de los programas de formación con registro calificado" />
                </div>
                <div>
                    <SelectMulti id="programas_formacion" bind:selectedValue={$form.programas_formacion} items={programasFormacion} isMulti={true} error={errors.programas_formacion} placeholder="Buscar por el nombre del programa de formación" required />
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

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="programas_formacion_articulados" value="Nombre de los programas de formación a los cuales está asociado el proyecto " />
                </div>
                <div>
                    <SelectMulti id="programas_formacion_articulados" bind:selectedValue={$form.programas_formacion_articulados} items={programasFormacionArticular} isMulti={true} error={errors.programas_formacion_articulados} placeholder="Buscar por el nombre del programa de formación" required />
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

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="conclusiones" value="Conclusiones" />
                </div>
                <div>
                    <Textarea label="Conclusiones" maxlength="40000" id="conclusiones" error={errors.conclusiones} bind:value={$formConclusiones.conclusiones} on:input={() => syncColumnLong('conclusiones', $formConclusiones)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage class="mb-2" message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Última edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea label="Bibliografía" maxlength="40000" id="bibliografia" error={errors.bibliografia} bind:value={$formBibliografia.bibliografia} on:input={() => syncColumnLong('bibliografia', $formBibliografia)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="rol_sennova" value="Rol SENNOVA" />
                </div>

                <div>
                    <Select id="rol_sennova" items={roles} bind:selectedValue={$form.rol_sennova} error={errors.rol_sennova} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="cantidad_meses" value="Número de meses de vinculación al proyecto" />
                </div>
                <div>
                    <Input label="Número de meses de vinculación" id="cantidad_meses" type="number" input$step="0.1" input$min="1" input$max={monthDiff($form.fecha_inicio, $form.fecha_finalizacion)} class="mt-1" bind:value={$form.cantidad_meses} placeholder="Número de meses de vinculación" autocomplete="off" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="cantidad_horas" value="Número de horas semanales dedicadas para el desarrollo del proyecto" />
                </div>
                <div>
                    <Input label="Número de horas semanales dedicadas para el desarrollo del proyecto" id="cantidad_horas" type="number" input$step="1" input$min="1" input$max={$form.rol_sennova?.maxHoras} class="mt-1" bind:value={$form.cantidad_horas} placeholder="Número de horas semanales dedicadas para el desarrollo del proyecto" autocomplete="off" required />
                    {#if $form.rol_sennova?.maxHoras}
                        <InfoMessage>Horas máximas permitidas para este rol: {$form.rol_sennova?.maxHoras}.</InfoMessage>
                    {/if}
                </div>
            </div>
        </fieldset>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar proyecto de capacdad instalada </button>
            {/if}
            {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar y continuar</LoadingButton>
            {/if}
        </div>
    </form>
    {#if isSuperAdmin || (checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))}
        <Dialog bind:open={dialogOpen}>
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
                    <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                    <Button variant="raised" on:click={destroy}>Confirmar</Button>
                </div>
            </div>
        </Dialog>

        <Dialog bind:open={proyectoDialogOpen} id="informacion">
            <div slot="title" class="flex items-center flex-col mt-4">
                <figure>
                    <img src={window.basePath + '/images/proyecto.png'} alt="Proyecto" class="h-32 mb-6" />
                </figure>
                Código del proyecto: {proyectoCapacidadInstalada.codigo}
            </div>
            <div slot="content">
                <div>
                    <h1 class="text-center mt-4 mb-4">Para terminar el numeral de <strong>Información básica</strong> por favor continue diligenciando los siguientes campos:</h1>
                    <p class="text-center mb-4">Si ya están completos omita esta información.</p>
                    <ul class="list-disc">
                        <li>Justificación</li>
                        <li>Plan tecnológico</li>
                        <li>Objetivo general</li>
                        <li>Metodología</li>
                        <li>Infraestructura para el desarrollo del proyecto</li>
                        <li>Materiales de formación a utilizar</li>
                        <li>Planteamiento del problema</li>
                        <li>Conclusiones</li>
                        <li>Bibliografía</li>
                    </ul>
                </div>
            </div>
            <div slot="actions">
                <div class="p-4">
                    <Button on:click={(event) => (proyectoDialogOpen = false)} variant={null}>Omitir</Button>
                    {#if proyectoCapacidadInstalada.estado_proyecto != 'Finalizado'}
                        <Button variant="raised" on:click={(event) => (proyectoDialogOpen = false)} on:click={() => Inertia.visit('#beneficia_a')}>Continuar diligenciando</Button>
                    {/if}
                </div>
            </div>
        </Dialog>
    {/if}
</AuthenticatedLayout>
