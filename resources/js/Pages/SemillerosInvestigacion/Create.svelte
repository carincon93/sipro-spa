<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Form from './Form'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let redesConocimiento
    export let lineasInvestigacion
    export let grupoInvestigacion
    export let programasFormacion
    export let allowedToCreate

    $: $title = 'Crear semillero de investigación'

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
        nombre: '',
        fecha_creacion_semillero: '',
        nombre_lider_semillero: '',
        email_contacto: '',
        reconocimientos_semillero_investigacion: '',
        vision: '',
        mision: '',
        objetivo_general: '',
        objetivos_especificos: '',
        link_semillero: '',
        formato_gic_f_021: '',
        formato_gic_f_032: '',
        formato_aval_semillero: '',
        centro_formacion_id: isSuperAdmin ? null : checkRole(authUser, [4, 21, 20, 18, 19, 5, 17]) ? authUser.centro_formacion_id : null,
        linea_investigacion_id: null,
        linea_investigacion: null,
        redes_conocimiento: null,
        programas_formacion: null,
        lineas_investigacion: null,
        es_semillero_tecnoacademia: null,
    })

    function submit() {
        if (allowedToCreate) {
            $form.post(route('grupos-investigacion.semilleros-investigacion.store', grupoInvestigacion.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if allowedToCreate}
                        <a use:inertia href={route('grupos-investigacion.semilleros-investigacion.index', grupoInvestigacion.id)} class="text-violet-400 hover:text-violet-600"> Semilleros de investigación </a>
                    {/if}
                    <span class="text-violet-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-3 gap-4">
        <div class="sticky top-0">
            <h1 class="font-black text-4xl uppercase">Archivos</h1>
            <InfoMessage class="mt-10">
                <h1 class="font-black m-0 p-2.5">¡Importante!</h1>
                <p class="m-0 mb-4 p-2.5">Luego de crear el semillero de investigación deberá cargar los siguientes archivos: <strong>Formato GIC – F – 021, Formato GIC – F – 032, Aval del semillero</strong></p>
            </InfoMessage>
        </div>

        <div class="col-span-2">
            <Form {form} {submit} {errors} {opcionesSiNo} {lineasInvestigacion} {grupoInvestigacion} {redesConocimiento} {programasFormacion} {allowedToCreate} />
        </div>
    </div>
</AuthenticatedLayout>
