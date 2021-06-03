<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Select from '@/Components/Select'
    import InputError from '@/Components/InputError'

    export let errors
    export let convocatoria
    export let tecnoacademias

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let lineasTecnologicas = []
    let sending = false
    let form = useForm({
        centro_formacion_id: null,
        tipo_proyecto_id: null,
        fecha_inicio: '',
        fecha_finalizacion: '',
        tecnoacademia_id: null,
        tecnoacademia_linea_tecnologica_id: [],
    })

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [5])) {
            $form.post(route('convocatorias.tatp.store', [convocatoria.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }

    $: selectedTecnoacademia = $form.tecnoacademia_id?.value

    $: if (selectedTecnoacademia) {
        getLineasTecnologicas(selectedTecnoacademia)
    }

    async function getLineasTecnologicas(tecnoacademia) {
        let res = await axios.get(route('web-api.tecnoacademias.lineas-tecnologicas', [tecnoacademia]))
        lineasTecnologicas = res.data
    }

    $: $title = 'Crear proyecto Tecnoacademia - Tecnoparque'
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin || checkPermission(authUser, [5])}
                        <a use:inertia href={route('convocatorias.tatp.index', [convocatoria.id])} class="text-indigo-400 hover:text-indigo-600"> I+D+i </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8">
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
                    <Label required class="mb-4" labelFor="tipo_proyecto_id" value="Tipo de proyecto" />
                </div>
                <div>
                    <DynamicList id="tipo_proyecto_id" bind:value={$form.tipo_proyecto_id} routeWebApi={route('web-api.tipos-proyecto', 1)} placeholder="Busque por el nombre del tipo de proyecto, línea programática" message={errors.tipo_proyecto_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tecnoacademia_id" value="Tecnoacademia" />
                </div>
                <div>
                    <Select items={tecnoacademias} id="tecnoacademia_id" bind:selectedValue={$form.tecnoacademia_id} error={errors.tecnoacademia_id} autocomplete="off" placeholder="Seleccione una Tecnoacademia" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tecnoacademia_linea_tecnologica_id" value="Línea tecnológica" />
                </div>
                <div>
                    <select id="tecnoacademia_linea_tecnologica_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200" bind:value={$form.tecnoacademia_linea_tecnologica_id} disabled={lineasTecnologicas.length > 0 ? undefined : true} required>
                        <option value="">Seleccione una línea tecnológica</option>
                        {#each lineasTecnologicas as { id, nombre }, i}
                            <option value={id}>{nombre}</option>
                        {/each}
                    </select>

                    <InputError message={errors.tecnoacademia_linea_tecnologica_id} />
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
