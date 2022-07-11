<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'

    import Dialog from '@/Shared/Dialog'
    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import Textarea from '@/Shared/Textarea'
    import LoadingButton from '@/Shared/LoadingButton'
    import SelectMulti from '@/Shared/SelectMulti'
    import Select from '@/Shared/Select'
    import InfoMessage from '@/Shared/InfoMessage'
    import File from '@/Shared/File'

    export let errors
    export let semilleroInvestigacion
    export let lineasInvestigacion
    export let grupoInvestigacion
    export let redesConocimiento
    export let redesConocimientoSemilleroInvestigacion
    export let programasFormacionSemilleroInvestigacion
    export let lineasInvestigacionSemilleroInvestigacion

    $: $title = semilleroInvestigacion ? semilleroInvestigacion.nombre : null

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]
    let dialogOpen = false

    let form = useForm({
        _method: 'put',
        nombre: semilleroInvestigacion.nombre,
        fecha_creacion_semillero: semilleroInvestigacion.fecha_creacion_semillero,
        nombre_lider_semillero: semilleroInvestigacion.nombre_lider_semillero,
        email_contacto: semilleroInvestigacion.email_contacto,
        reconocimientos_semillero_investigacion: semilleroInvestigacion.reconocimientos_semillero_investigacion,
        vision: semilleroInvestigacion.vision,
        mision: semilleroInvestigacion.mision,
        objetivo_general: semilleroInvestigacion.objetivo_general,
        objetivos_especificos: semilleroInvestigacion.objetivos_especificos,
        link_semillero: semilleroInvestigacion.link_semillero,

        linea_investigacion_id: {
            value: lineasInvestigacion.find((item) => item.value == semilleroInvestigacion.linea_investigacion_id)?.value,
            label: lineasInvestigacion.find((item) => item.value == semilleroInvestigacion.linea_investigacion_id)?.label,
        },
        linea_investigacion: null,
        centro_formacion_id: semilleroInvestigacion.linea_investigacion?.grupo_investigacion?.centro_formacion_id,
        redes_conocimiento: redesConocimientoSemilleroInvestigacion.length > 0 ? redesConocimientoSemilleroInvestigacion : null,
        programas_formacion: programasFormacionSemilleroInvestigacion.length > 0 ? programasFormacionSemilleroInvestigacion : null,
        lineas_investigacion: lineasInvestigacionSemilleroInvestigacion.length > 0 ? lineasInvestigacionSemilleroInvestigacion : null,
        es_semillero_tecnoacademia: {
            value: semilleroInvestigacion.es_semillero_tecnoacademia,
            label: opcionesSiNo.find((item) => item.value == semilleroInvestigacion.es_semillero_tecnoacademia)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [4]) && authUser.centro_formacion_id == semilleroInvestigacion.linea_investigacion.grupo_investigacion.centro_formacion_id) || checkRole(authUser, [21, 20, 18, 19, 5, 17])) {
            $form.post(route('grupos-investigacion.semilleros-investigacion.update', [grupoInvestigacion.id, semilleroInvestigacion.id]), {
                preserveScroll: true,
            })
        }
    }

    let filesForm = useForm({
        formato_gic_f_021: semilleroInvestigacion.formato_gic_f_021?.includes('gruposennova') ? null : semilleroInvestigacion.formato_gic_f_021,
        formato_gic_f_032: semilleroInvestigacion.formato_gic_f_032?.includes('gruposennova') ? null : semilleroInvestigacion.formato_gic_f_032,
        formato_aval_semillero: semilleroInvestigacion.formato_aval_semillero?.includes('gruposennova') ? null : semilleroInvestigacion.formato_aval_semillero,
    })

    function submitFiles() {}

    let programasFormacion = []
    let oldLineaInvestigacionIdValue = null

    $: if ($form.linea_investigacion) {
        if (oldLineaInvestigacionIdValue != $form.linea_investigacion) {
            getProgramasFormacion($form.linea_investigacion?.value)
        }
    }
    async function getProgramasFormacion(lineaInvestigacionId) {
        let res = await axios.get(route('web-api.linea-investigacion-programa-formacion', lineaInvestigacionId))
        res.status == '200'
        programasFormacion = res.data
        oldLineaInvestigacionIdValue = $form.linea_investigacion
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('grupos-investigacion.semilleros-investigacion.index', grupoInvestigacion.id)} class="text-cyan-400 hover:text-cyan-600"> Semilleros de investigación </a>
                    <span class="text-cyan-400 font-medium">/</span>
                    {semilleroInvestigacion.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-3 gap-4">
        <div class="sticky top-0">
            <h1 class="font-black text-4xl uppercase">Archivos</h1>

            <form on:submit|preventDefault={submitFiles} class="bg-white rounded shadow col-span-2">
                <fieldset class="p-8" disabled={isSuperAdmin || (checkRole(authUser, [4]) && authUser.centro_formacion_id == semilleroInvestigacion.linea_investigacion.grupo_investigacion.centro_formacion_id) || checkRole(authUser, [21, 20, 18, 19, 5, 17]) ? undefined : true}>
                    <div class="mt4-">
                        <Label class="mb-4 mt-8" labelFor="formato_gic_f_021" value="Formato GIC – F – 021" />
                        <File id="formato_gic_f_021" maxSize="10000" bind:value={$filesForm.formato_gic_f_021} valueDb={semilleroInvestigacion.formato_gic_f_021} error={errors.formato_gic_f_021} route={semilleroInvestigacion.formato_gic_f_021?.includes('http') ? null : route('semilleros-investigacion.download-file-sharepoint', [grupoInvestigacion, semilleroInvestigacion, 'formato_gic_f_021'])} />
                    </div>

                    <hr class="mt-10 mb-10" />

                    <div class="mt4-">
                        <Label class="mb-4 mt-8" labelFor="formato_gic_f_032" value="Formato GIC – F – 032" />
                        <File id="formato_gic_f_032" maxSize="10000" bind:value={$filesForm.formato_gic_f_032} valueDb={semilleroInvestigacion.formato_gic_f_032} error={errors.formato_gic_f_032} route={semilleroInvestigacion.formato_gic_f_032?.includes('http') ? null : route('semilleros-investigacion.download-file-sharepoint', [grupoInvestigacion, semilleroInvestigacion, 'formato_gic_f_032'])} />
                    </div>

                    <hr class="mt-10 mb-10" />

                    <div class="mt4-">
                        <Label class="mb-4 mt-8" labelFor="formato_aval_semillero" value="Aval del semillero" />
                        <File
                            id="formato_aval_semillero"
                            maxSize="10000"
                            bind:value={$filesForm.formato_aval_semillero}
                            valueDb={semilleroInvestigacion.formato_aval_semillero}
                            error={errors.formato_aval_semillero}
                            route={semilleroInvestigacion.formato_aval_semillero?.includes('http') ? null : route('semilleros-investigacion.download-file-sharepoint', [grupoInvestigacion, semilleroInvestigacion, 'formato_aval_semillero'])}
                        />
                    </div>
                </fieldset>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkRole(authUser, [4]) && authUser.centro_formacion_id == semilleroInvestigacion.linea_investigacion.grupo_investigacion.centro_formacion_id) || checkRole(authUser, [21, 20, 18, 19, 5, 17])}
                        <LoadingButton loading={$filesForm.processing} class="ml-auto" type="submit">Guardar formatos</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>

        <div class="col-span-2">
            <h1 class="font-black text-4xl uppercase">{semilleroInvestigacion.nombre}</h1>
            <form on:submit|preventDefault={submit} class="bg-white rounded shadow">
                <fieldset class="p-8" disabled={isSuperAdmin || (checkRole(authUser, [4]) && authUser.centro_formacion_id == semilleroInvestigacion.linea_investigacion.grupo_investigacion.centro_formacion_id) || checkRole(authUser, [21, 20, 18, 19, 5, 17]) ? undefined : true}>
                    <div class="mt-4">
                        <Label labelFor="nombre" value="Código" />
                        <Input disabled id="codigo" type="text" class="mt-1" bind:value={semilleroInvestigacion.codigo} error={errors.codigo} required />
                    </div>

                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="es_semillero_tecnoacademia" value="¿Es semillero de TecnoAcademia?" />
                        <Select items={opcionesSiNo} id="es_semillero_tecnoacademia" bind:selectedValue={$form.es_semillero_tecnoacademia} error={errors.es_semillero_tecnoacademia} autocomplete="off" placeholder="Seleccione una opción" required />
                    </div>

                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea de investigación principal" />
                        <Select id="linea_investigacion_id" items={lineasInvestigacion} bind:selectedValue={$form.linea_investigacion_id} error={errors.linea_investigacion_id} autocomplete="off" placeholder="Seleccione una línea de investigación" required />
                    </div>

                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="lineas_investigacion" value="Articulación con líneas de investigación" />
                        <SelectMulti id="lineas_investigacion" bind:selectedValue={$form.lineas_investigacion} items={lineasInvestigacion} isMulti={true} error={errors.lineas_investigacion} placeholder="Buscar por el nombre de la línea de investigación" required />
                    </div>

                    <div class="mt-4">
                        <Label required labelFor="nombre" value="Nombre del semillero" />
                        <Input id="nombre" type="text" class="mt-1" bind:value={$form.nombre} error={errors.nombre} required />
                    </div>

                    <div class="mt-4 ">
                        <Label required labelFor="fecha_creacion_semillero" value="Fecha creación del semillero" />
                        <input id="fecha_creacion_semillero" type="date" class="mt-1 p-2" bind:value={$form.fecha_creacion_semillero} required />
                    </div>

                    <div class="mt-4">
                        <Label required labelFor="nombre_lider_semillero" value="Nombre del líder del semillero" />
                        <Input id="nombre_lider_semillero" type="text" class="mt-1" bind:value={$form.nombre_lider_semillero} error={errors.nombre_lider_semillero} required />
                    </div>

                    <div class="mt-4">
                        <Label required labelFor="email_contacto" value="Email de contacto" />
                        <Input id="email_contacto" type="email" class="mt-1" bind:value={$form.email_contacto} error={errors.email_contacto} required />
                    </div>

                    <div class="mt-4">
                        <Label required labelFor="reconocimientos_semillero_investigacion" value="Reconocimientos semillero de investigación" />
                        <Textarea maxlength="40000" id="reconocimientos_semillero_investigacion" bind:value={$form.reconocimientos_semillero_investigacion} error={errors.reconocimientos_semillero_investigacion} required />
                    </div>

                    <div class="mt-4">
                        <Label required labelFor="vision" value="Visión" />
                        <Textarea maxlength="40000" id="vision" bind:value={$form.vision} error={errors.vision} required />
                    </div>

                    <div class="mt-4">
                        <Label required labelFor="mision" value="Misión" />
                        <Textarea maxlength="40000" id="mision" bind:value={$form.mision} error={errors.mision} required />
                    </div>

                    <div class="mt-4">
                        <Label required labelFor="objetivo_general" value="Objetivo general" />
                        <Textarea maxlength="40000" id="objetivo_general" bind:value={$form.objetivo_general} error={errors.objetivo_general} required />
                    </div>

                    <div class="mt-4">
                        <Label required labelFor="objetivos_especificos" value="Objetivos específicos " />
                        <Textarea maxlength="40000" id="objetivos_especificos" bind:value={$form.objetivos_especificos} error={errors.objetivos_especificos} required />
                    </div>

                    <div class="mt-4">
                        <Label labelFor="link_semillero" value="Link del semillero" />
                        <Input id="link_semillero" type="url" class="mt-1" bind:value={$form.link_semillero} error={errors.link_semillero} />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="redes_conocimiento" value="Red o redes de conocimiento afines al Semillero de Investigación" />
                        <SelectMulti id="redes_conocimiento" bind:selectedValue={$form.redes_conocimiento} items={redesConocimiento} isMulti={true} error={errors.redes_conocimiento} placeholder="Buscar redes de conocimiento" required />
                    </div>

                    <hr class="mt-10 mb-10" />

                    <div class="mt-4">
                        <Label required={$form.programas_formacion?.length > 0 ? undefined : 'required'} class="mb-4" labelFor="linea_investigacion" value="Seleccione una línea de investigación y posteriormente asocie los programas de formación" />
                        <Select id="linea_investigacion" items={lineasInvestigacion} bind:selectedValue={$form.linea_investigacion} error={errors.linea_investigacion} autocomplete="off" placeholder="Seleccione una línea de investigación" required={$form.programas_formacion?.length > 0 ? undefined : 'required'} />
                    </div>

                    {#if ($form.linea_investigacion && programasFormacion.length > 0) || programasFormacionSemilleroInvestigacion.length > 0}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="programas_formacion" value="Programa(s) de formación" />
                            <SelectMulti id="programas_formacion" bind:selectedValue={$form.programas_formacion} items={programasFormacion} isMulti={true} error={errors.programas_formacion} placeholder="Programa(s) de formación" required />
                        </div>
                    {:else}
                        <InfoMessage alertMsg={true} class="mt-10">
                            <p class="mt-3 py-4 text-justify">La línea de investigación seleccionada no tiene programas de formación asociados, por favor antes de crear/modificar semilleros de investigación debe actualizar las líneas de investigación.</p>
                        </InfoMessage>
                    {/if}
                </fieldset>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkRole(authUser, [4]) && authUser.centro_formacion_id == semilleroInvestigacion.linea_investigacion.grupo_investigacion.centro_formacion_id) || checkRole(authUser, [21, 20, 18, 19, 5, 17])}
                        <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Guardar</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>
    </div>
</AuthenticatedLayout>
