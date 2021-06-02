<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import InputError from '@/Components/InputError'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Textarea from '@/Components/Textarea'
    import Select from '@/Components/Select'

    export let errors
    export let convocatoria
    export let mesasTecnicas

    $: $title = 'Crear proyecto de servicios tecnológicos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

    let sending = false
    let form = useForm({
        centro_formacion_id: null,
        tipo_proyecto_id: null,
        linea_programatica_id: null,
        tema_priorizado_id: null,
        disciplina_subarea_conocimiento_id: null,
        tematica_estrategica_id: null,
        red_conocimiento_id: null,
        actividad_economica_id: null,
        mesa_tecnica_id: null,
        sector_productivo_id: null,
        tema_priorizado_id: null,
        titulo: 'Escriba aquí el título del proyecto. No mayor a 20 palabras.',
        titulo_proyecto_articulado: '',
        fecha_inicio: '',
        fecha_finalizacion: '',
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('convocatorias.servicios-tecnologicos.store', [convocatoria.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
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
                    value="Debe corresponder al contenido del proyecto y responder a los siguientes interrogantes: ¿Qué?, ¿Cómo?, ¿Cuándo?, ¿Dónde?, ¿Con quién? Tiene que estar escrito de manera breve y concisa. Un buen título describe con exactitud y usando el menor número posible de palabras el tema central del proyecto."
                />
                <Textarea rows="4" id="titulo" error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos} max={convocatoria.max_fecha_finalizacion__proyectos} error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos} max={convocatoria.max_fecha_finalizacion__proyectos} error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion}
                    <div class="mb-20 flex">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                    </div>
                {/if}
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                </div>
                <div>
                    <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipo_proyecto_id" value="Código dependencia presupuestal (SIIF)" />
                </div>
                <div>
                    <DynamicList id="tipo_proyecto_id" bind:value={$form.tipo_proyecto_id} routeWebApi={route('web-api.tipos-proyecto', 3)} classes="min-h" placeholder="Busque por el nombre de la dependencia presupuestal, línea programática" message={errors.tipo_proyecto_id} required />
                </div>
            </div>

            <div class="mt-44 text-center">
                <h1 class="text-2xl">Articulación con otras estrategias SENNOVA</h1>
                <p>Si el proyecto necesita articularse con otra línea programática para su desarrollo, Por favor registre la siguiente información:</p>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="linea_programatica_id" value="Línea programática" />
                </div>
                <div>
                    <DynamicList id="linea_programatica_id" bind:value={$form.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas')} classes="min-h" placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="titulo_proyecto_articulado" value="Título del proyecto de la línea programática con la que se realiza articulación" />
                </div>
                <div>
                    <Textarea rows="4" id="titulo_proyecto_articulado" bind:value={$form.titulo_proyecto_articulado} error={errors.titulo_proyecto_articulado} />
                </div>
            </div>

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
                        <Label required class="mb-4" labelFor="sector_productivo_id" value="Sector productivo" />
                    </div>
                    <div>
                        <DynamicList id="sector_productivo_id" bind:value={$form.sector_productivo_id} routeWebApi={route('web-api.sectores-productivos', [$form.mesa_tecnica_id?.value])} classes="min-h" placeholder="Busque por el nombre del sector productivo" message={errors.sector_productivo_id} required />
                    </div>
                </div>
            {/if}

            {#if $form.mesa_tecnica_id?.value && $form.sector_productivo_id}
                <div class="mt-10 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tema_priorizado_id" value="Tema priorizado" />
                    </div>

                    <div>
                        <DynamicList id="tema_priorizado_id" bind:value={$form.tema_priorizado_id} routeWebApi={route('web-api.temas-priorizados', [$form.mesa_tecnica_id.value, $form.sector_productivo_id])} classes="min-h" placeholder="Busque por el nombre del tema priorizado" message={errors.tema_priorizado_id} required />
                    </div>
                </div>
            {/if}

            <hr class="mt-32" />

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
                    <Label required class="mb-4" labelFor="disciplina_subarea_conocimiento_id" value="Disciplina de la subárea de conocimiento" />
                </div>
                <div>
                    <DynamicList id="disciplina_subarea_conocimiento_id" bind:value={$form.disciplina_subarea_conocimiento_id} routeWebApi={route('web-api.disciplinas-subarea-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento" message={errors.disciplina_subarea_conocimiento_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="actividad_economica_id" value="¿En cuál de estas actividades económicas se puede aplicar el proyecto de investigación?" />
                </div>
                <div>
                    <DynamicList id="actividad_economica_id" bind:value={$form.actividad_economica_id} routeWebApi={route('web-api.actividades-economicas')} placeholder="Busque por el nombre de la actividad económica" classes="min-h" message={errors.actividad_economica_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tematica_estrategica_id" value="Temática estratégica SENA" />
                </div>
                <div>
                    <DynamicList id="tematica_estrategica_id" bind:value={$form.tematica_estrategica_id} routeWebApi={route('web-api.tematicas-estrategicas')} placeholder="Busque por el nombre de la temática estrategica SENA" message={errors.tematica_estrategica_id} required />
                </div>
            </div>
        </fieldset>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                    {$_('Continue')}
                </LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
