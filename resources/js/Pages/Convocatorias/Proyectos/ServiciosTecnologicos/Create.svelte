<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'

    export let errors
    export let convocatoria
    export let mesasTecnicas
    export let roles
    export let authUserRegional
    export let tipologiasSt
    export let tiposProyectoST

    $: $title = 'Crear proyecto de servicios tecnológicos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        centro_formacion_id: null,
        linea_programatica_id: null,
        mesa_tecnica_id: null,
        mesa_tecnica_sector_productivo_id: null,
        titulo: 'Escriba aquí el título del proyecto. No mayor a 20 palabras.',
        fecha_inicio: '',
        fecha_finalizacion: '',
        max_meses_ejecucion: 0,
        cantidad_meses: 0,
        cantidad_horas: 0,
        rol_sennova: null,
        tipo_proyecto_st: null,
        estado_sistema_gestion_id: null,
        tipologia_st: null,
        subclasificacion_tipologia_st_id: null,
    })

    $: if ($form.fecha_inicio && $form.fecha_finalizacion) {
        $form.max_meses_ejecucion = monthDiff($form.fecha_inicio, $form.fecha_finalizacion)
    }

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [5])) {
            $form.post(route('convocatorias.servicios-tecnologicos.store', [convocatoria.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin || checkPermission(authUser, [5])}
                        <a use:inertia href={route('convocatorias.servicios-tecnologicos.index', [convocatoria.id])} class="text-indigo-400 hover:text-indigo-600"> Servicios tecnológicos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8">
            <div class="mt-28">
                <Label
                    required
                    labelFor="titulo"
                    class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full"
                    value="Debe corresponder al contenido del proyecto y responder a los siguientes interrogantes: ¿Qué se va a hacer?, ¿Sobre qué o quiénes se hará?, ¿Cómo?, ¿Dónde se llevará a cabo? Tiene que estar escrito de manera breve y concisa. Un buen título describe con exactitud y usando el menor número posible de palabras el tema central del proyecto. Nota: las respuestas a las preguntas anteriormente formuladas no necesariamente deben responderse en mismo orden en el que aparecen."
                />
                <Textarea label="Título" maxlength="40000" id="titulo" error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_st} max={convocatoria.max_fecha_finalizacion_proyectos_st} error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_st} max={convocatoria.max_fecha_finalizacion_proyectos_st} error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion || errors.max_meses_ejecucion}
                    <div class="mb-20 flex justify-center mt-4">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                        <InputError classes="text-center" message={errors.max_meses_ejecucion} />
                    </div>
                {/if}
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                </div>
                <div>
                    <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion-ejecutor', authUserRegional)} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="linea_programatica_id" value="Código dependencia presupuestal (SIIF)" />
                </div>
                <div>
                    <DynamicList id="linea_programatica_id" bind:value={$form.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas', 3)} classes="min-h" placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipologia_st" value="Tipología ST" />
                </div>
                <div>
                    <Select id="tipologia_st" items={tipologiasSt} bind:selectedValue={$form.tipologia_st} error={errors.tipologia_st} autocomplete="off" placeholder="Seleccione una tipología de ST" required />
                </div>
            </div>

            {#if $form.tipologia_st?.value}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="subclasificacion_tipologia_st_id" value="Subclasificación de tipología ST" />
                    </div>
                    <div>
                        <DynamicList id="subclasificacion_tipologia_st_id" bind:value={$form.subclasificacion_tipologia_st_id} routeWebApi={route('web-api.subclasificacion-tipologia-st', $form.tipologia_st?.value)} classes="min-h" placeholder="Busque por el de la subclasificación de tipología ST" message={errors.subclasificacion_tipologia_st_id} required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipo_proyecto_st" value="Tipo de proyecto" />
                </div>
                <div>
                    <Select id="tipo_proyecto_st" items={tiposProyectoST} bind:selectedValue={$form.tipo_proyecto_st} error={errors.tipo_proyecto_st} autocomplete="off" placeholder="Seleccione un tipo de proyecto ST" required />
                </div>
            </div>

            {#if $form.tipo_proyecto_st?.value}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="estado_sistema_gestion_id" value="Estado del sistema de gestión" />
                    </div>
                    <div>
                        <DynamicList id="estado_sistema_gestion_id" bind:value={$form.estado_sistema_gestion_id} routeWebApi={route('web-api.estados-sistema-gestion', $form.tipo_proyecto_st?.value)} classes="min-h" placeholder="Busque por el nombre del estado de sistema de gestión" message={errors.estado_sistema_gestion_id} required />
                    </div>
                </div>
            {/if}

            <hr class="mt-32" />

            <div class="mt-10">
                <h1 class="text-2xl text-center">Mesa sectorial a la que se encuentra articulado el proyecto</h1>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="mesa_tecnica_id" value="Mesa técnica ST" />
                </div>
                <div>
                    <Select id="mesa_tecnica_id" items={mesasTecnicas} bind:selectedValue={$form.mesa_tecnica_id} error={errors.mesa_tecnica_id} autocomplete="off" placeholder="Seleccione una mesa técnica" required />
                </div>
            </div>

            {#if $form.mesa_tecnica_id?.value}
                <div class="mt-10 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="mesa_tecnica_sector_productivo_id" value="Sector productivo" />
                    </div>
                    <div>
                        <DynamicList id="mesa_tecnica_sector_productivo_id" bind:value={$form.mesa_tecnica_sector_productivo_id} routeWebApi={route('web-api.sectores-productivos', [$form.mesa_tecnica_id?.value])} classes="min-h" placeholder="Busque por el nombre del sector productivo" message={errors.mesa_tecnica_sector_productivo_id} required />
                    </div>
                </div>
            {/if}

            <hr class="mt-32" />

            <p class="text-center mt-36 mb-8">Información de mi participación en el proyecto</p>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="rol_sennova" value="Rol SENNOVA" />
                </div>
                <div>
                    <Select id="rol_sennova" items={roles} bind:selectedValue={$form.rol_sennova} error={errors.rol_sennova} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                </div>
            </div>
        </fieldset>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || checkPermission(authUser, [5])}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                    {$_('Continue')}
                </LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
