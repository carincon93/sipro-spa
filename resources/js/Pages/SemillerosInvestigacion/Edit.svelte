<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import File from '@/Shared/File'

    import Form from './Form'

    export let errors
    export let semilleroInvestigacion
    export let lineasInvestigacion
    export let grupoInvestigacion
    export let redesConocimiento
    export let programasFormacion
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
        if (semilleroInvestigacion.allowed.to_update) {
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

    function submitFiles() {
        $filesForm.post(route('grupos-investigacion.semilleros-investigacion.guardar-archivos', [grupoInvestigacion.id, semilleroInvestigacion.id]))
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('grupos-investigacion.semilleros-investigacion.index', grupoInvestigacion.id)} class="text-violet-400 hover:text-violet-600"> Semilleros de investigación </a>
                    <span class="text-violet-400 font-medium">/</span>
                    {semilleroInvestigacion.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-3 gap-4">
        <div class="sticky top-0">
            <h1 class="font-black text-4xl uppercase">Archivos</h1>

            <form on:submit|preventDefault={submitFiles} class="bg-white rounded shadow col-span-2">
                <fieldset class="p-8" disabled={semilleroInvestigacion.allowed.to_update ? undefined : true}>
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
                <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                    {#if semilleroInvestigacion.allowed.to_update}
                        <LoadingButton loading={$filesForm.processing} class="ml-auto" type="submit">Guardar formatos</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>

        <div class="col-span-2">
            <h1 class="font-black text-4xl uppercase">{semilleroInvestigacion.nombre}</h1>
            <Form {form} {submit} {errors} {opcionesSiNo} {semilleroInvestigacion} {lineasInvestigacion} {grupoInvestigacion} {redesConocimiento} {programasFormacion} {redesConocimientoSemilleroInvestigacion} {programasFormacionSemilleroInvestigacion} {lineasInvestigacionSemilleroInvestigacion} />
        </div>
    </div>
</AuthenticatedLayout>
