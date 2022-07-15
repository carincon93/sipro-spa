<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import Textarea from '@/Shared/Textarea'
    import InputError from '@/Shared/InputError'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import SelectMulti from '@/Shared/SelectMulti'

    export let errors
    export let codigosSgps
    export let mesasSectoriales
    export let tipologiasAmbientes
    export let areasConocimiento
    export let subareasConocimiento
    export let disciplinasSubareaConocimiento
    export let redesConocimiento
    export let actividadesEconomicas
    export let lineasInvestigacion
    export let tematicasEstrategicas
    export let seguimientoId

    $: $title = 'Crear ambiente de modernización'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        seguimiento_ambiente_modernizacion_id: seguimientoId,
        codigo_proyecto_sgps_id: null,
        nombre_ambiente: '',
        tipologia_ambiente_id: null,
        red_conocimiento_id: null,
        linea_investigacion_id: null,
        actividad_economica_id: null,
        area_conocimiento_id: null,
        subarea_conocimiento_id: null,
        disciplina_subarea_conocimiento_id: null,
        tematica_estrategica_id: null,
        alineado_mesas_sectoriales: false,
        financiado_anteriormente: false,
        mesa_sectorial_id: [],
        codigos_proyectos_id: null,
    })

    function submit() {
        if (isSuperAdmin || checkRole(authUser, [4])) {
            $form.post(route('ambientes-modernizacion.store'))
        }
    }

    let oldAreaConocimientoIdValue = null
    let arraySubareasConocimiento = []
    $: if ($form.area_conocimiento_id) {
        if (oldAreaConocimientoIdValue != $form.area_conocimiento_id?.value) {
            arraySubareasConocimiento = subareasConocimiento.filter(function (obj) {
                oldAreaConocimientoIdValue = $form.area_conocimiento_id?.value
                return obj.area_conocimiento_id == $form.area_conocimiento_id?.value
            })
        }
    }

    let oldSubareaConocimientoIdValue = null
    let arrayDisciplinasSubareaConocimiento = []
    $: if ($form.subarea_conocimiento_id) {
        if (oldSubareaConocimientoIdValue != $form.subarea_conocimiento_id?.value) {
            arrayDisciplinasSubareaConocimiento = disciplinasSubareaConocimiento.filter(function (obj) {
                oldSubareaConocimientoIdValue = $form.subarea_conocimiento_id?.value
                return obj.subarea_conocimiento_id == $form.subarea_conocimiento_id?.value
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('ambientes-modernizacion.index')} class="text-violet-400 hover:text-violet-600"> Ambientes de modernización </a>
                    <span class="text-violet-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8">
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="codigo_proyecto_sgps_id" value="Código proyecto SGPS" />
                </div>
                <div>
                    <Select id="codigo_proyecto_sgps_id" items={codigosSgps} bind:selectedValue={$form.codigo_proyecto_sgps_id} error={errors.codigo_proyecto_sgps_id} autocomplete="off" placeholder="Busque por el título/código del proyecto" required />
                </div>
            </div>

            <div class="mt-28">
                <Label required labelFor="nombre_ambiente" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="Nombre del ambiente(s) de formación modernizado por Sennova. Ejemplo: Ambiente de soldadura - Ambiente de confecciones" />
                <Textarea label="Nombre" id="nombre_ambiente" sinContador={true} error={errors.nombre_ambiente} bind:value={$form.nombre_ambiente} classes="bg-transparent block border-0 {errors.nombre_ambiente ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipologia_ambiente_id" value="Tipologías de los ambientes (Circular 3-2018- 143)" />
                    <a href={window.basePath + '/storage/documentos-descarga/Circular-3-2018-143.pdf'} target="_blank" class="underline text-violet-500">Ver Circular 3-2018-143</a>
                </div>
                <div>
                    <Select id="tipologia_ambiente_id" items={tipologiasAmbientes} bind:selectedValue={$form.tipologia_ambiente_id} error={errors.tipologia_ambiente_id} autocomplete="off" placeholder="Seleccione una tipología" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="area_conocimiento_id" value="Área de conocimiento relacionada con el ambiente modernizado por Sennova" />
                </div>
                <div>
                    <Select id="area_conocimiento_id" items={areasConocimiento} bind:selectedValue={$form.area_conocimiento_id} error={errors.area_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la área de conocimiento" required />
                </div>
            </div>
            {#if $form.area_conocimiento_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="subarea_conocimiento_id" value="Subárea relacionada con el ambiente modernizado por Sennova" />
                    </div>
                    <div>
                        <Select id="subarea_conocimiento_id" items={arraySubareasConocimiento} bind:selectedValue={$form.subarea_conocimiento_id} error={errors.subarea_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la subárea de conocimiento" required />
                    </div>
                </div>
            {/if}
            {#if $form.subarea_conocimiento_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="disciplina_subarea_conocimiento_id" value="Disciplina relacionada con el ambiente modernizado por Sennova" />
                    </div>
                    <div>
                        <Select id="disciplina_subarea_conocimiento_id" items={arrayDisciplinasSubareaConocimiento} bind:selectedValue={$form.disciplina_subarea_conocimiento_id} error={errors.disciplina_subarea_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento" required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="red_conocimiento_id" value="Red de conocimiento relacionada con el ambiente modernizado por Sennova (resolución 335 de 2012)" />
                </div>
                <div>
                    <Select id="red_conocimiento_id" items={redesConocimiento} bind:selectedValue={$form.red_conocimiento_id} error={errors.red_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la red de conocimiento sectorial" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="actividad_economica_id" value="Código CIIU relacionado con el ambiente modernizado por Sennova" />
                </div>
                <div>
                    <Select id="actividad_economica_id" items={actividadesEconomicas} bind:selectedValue={$form.actividad_economica_id} error={errors.actividad_economica_id} autocomplete="off" placeholder="Busque por el nombre de la actividad económica" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tematica_estrategica_id" value="Temática estratégica SENA relacionada con el ambiente modernizado por Sennova" />
                </div>
                <div>
                    <Select id="tematica_estrategica_id" items={tematicasEstrategicas} bind:selectedValue={$form.tematica_estrategica_id} error={errors.tematica_estrategica_id} autocomplete="off" placeholder="Busque por el nombre de la línea de investigación" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea investigación relacionada con el ambiente modernizado por Sennova" />
                </div>
                <div>
                    <Select id="linea_investigacion_id" items={lineasInvestigacion} bind:selectedValue={$form.linea_investigacion_id} error={errors.linea_investigacion_id} autocomplete="off" placeholder="Busque por el nombre de la línea de investigación" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="alineado_mesas_sectoriales" value="¿El proyecto se alinea con las Mesas Sectoriales?" />
                </div>
                <div>
                    <Select
                        items={[
                            { value: 1, label: 'Si' },
                            { value: 2, label: 'No' },
                        ]}
                        id="alineado_mesas_sectoriales"
                        bind:selectedValue={$form.alineado_mesas_sectoriales}
                        error={errors.alineado_mesas_sectoriales}
                        autocomplete="off"
                        placeholder="Seleccione una opción"
                        required
                    />
                </div>
            </div>
            {#if $form.alineado_mesas_sectoriales?.value == 1}
                <div class="bg-violet-100 p-5 mt-10">
                    <InputError message={errors.mesa_sectorial_id} />
                    <div class="grid grid-cols-2">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5" style="transform: translateX(-50px);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-violet-600">Por favor seleccione la o las mesas sectoriales con la cual o las cuales se alinea el proyecto</p>
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
                    <Label required class="mb-4" labelFor="financiado_anteriormente" value="¿El ambiente de formación ha sido financiado en más de una vigencia por Sennova?" />
                </div>
                <div>
                    <Select
                        items={[
                            { value: 1, label: 'Si' },
                            { value: 2, label: 'No' },
                        ]}
                        id="financiado_anteriormente"
                        bind:selectedValue={$form.financiado_anteriormente}
                        error={errors.financiado_anteriormente}
                        autocomplete="off"
                        placeholder="Seleccione una opción"
                        required
                    />
                </div>
            </div>

            {#if $form.financiado_anteriormente?.value == 1}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="codigos_proyectos_id" value="Si la respuesta anterior fue afirmativa, relacione los códigos y nombres de los proyectos beneficiados y/o ejecutados en el ambiente modernizado por Sennova de convocatoria (SGPS) o de capacidad instalada (CAP)" />
                    </div>
                    <div>
                        <SelectMulti id="codigos_proyectos_id" bind:selectedValue={$form.codigos_proyectos_id} items={codigosSgps} isMulti={true} error={errors.codigos_proyectos_id} placeholder="Buscar por el código/título del proyecto" required />
                    </div>
                </div>
            {/if}
        </fieldset>
        <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
            {#if isSuperAdmin || checkRole(authUser, [4])}
                <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Guardar y continuar</LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
