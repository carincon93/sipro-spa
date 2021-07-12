<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import InputError from '@/Shared/InputError'

    export let errors
    export let convocatoria
    export let authUserCentroFormacion

    $: $title = 'Crear proyecto Tecnoacademia - Tecnoparque'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let tecnoacademia
    let form = useForm({
        fecha_inicio: null,
        fecha_finalizacion: null,
        max_meses_ejecucion: 0,
        tecnoacademia_id: null,
        centro_formacion_id: null,
        tecnoacademia_linea_tecnologica_id: [],
        linea_programatica: null,
    })

    $: if ($form.fecha_inicio && $form.fecha_finalizacion) {
        $form.max_meses_ejecucion = monthDiff($form.fecha_inicio, $form.fecha_finalizacion)
    }

    $: if (tecnoacademia) {
        $form.centro_formacion_id = tecnoacademia.centro_formacion_id
    }

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [8])) {
            $form.post(route('convocatorias.ta.store', [convocatoria.id]), {
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
                    {#if isSuperAdmin || checkPermission(authUser, [8])}
                        <a use:inertia href={route('convocatorias.ta.index', [convocatoria.id])} class="text-indigo-400 hover:text-indigo-600"> Tecnoacademia </a>
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
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_ta} max={convocatoria.max_fecha_finalizacion_proyectos_ta} error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_ta} max={convocatoria.max_fecha_finalizacion_proyectos_ta} error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
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
                    <Label required class="mb-4" labelFor="tecnoacademia_id" value="Tecnoacademia" />
                </div>
                <div>
                    <DynamicList
                        id="tecnoacademia_id"
                        bind:value={$form.tecnoacademia_id}
                        noOptionsText="No hay tecnoacademias registradas para este centro de formación. Por favor seleccione un centro de formación diferente."
                        routeWebApi={route('web-api.centros-formacion.tecnoacademias', [authUserCentroFormacion])}
                        placeholder="Busque por el nombre de la Tecnoacademia"
                        message={errors.tecnoacademia_id}
                        bind:recurso={tecnoacademia}
                        required
                    />
                </div>
            </div>

            {#if $form.tecnoacademia_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tecnoacademia_linea_tecnologica_id" value="Línea tecnológica" />
                    </div>
                    <div>
                        <DynamicList
                            id="tecnoacademia_linea_tecnologica_id"
                            bind:value={$form.tecnoacademia_linea_tecnologica_id}
                            noOptionsText="No hay nodos tecnoparque registrados para este centro de formación. Por favor seleccione un centro de formación diferente."
                            routeWebApi={route('web-api.tecnoacademias.lineas-tecnoacademia', [$form.tecnoacademia_id])}
                            placeholder="Busque por el nombre de la línea tecnológica"
                            message={errors.tecnoacademia_linea_tecnologica_id}
                            required
                        />
                    </div>
                </div>
            {/if}
        </fieldset>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || checkPermission(authUser, [8])}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                    {$_('Continue')}
                </LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
