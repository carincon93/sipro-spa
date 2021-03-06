<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import InputError from '@/Shared/InputError'
    import Input from '@/Shared/Input'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let convocatoria
    export let nodosTecnoParque
    export let rolesTp

    $: $title = 'Crear proyecto Tecnoparque'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        centro_formacion_id: null,
        fecha_inicio: null,
        fecha_finalizacion: null,
        max_meses_ejecucion: 0,
        tecnoacademia_id: null,
        cantidad_meses: 0,
        cantidad_horas: 0,
        rol_sennova: null,
        nodo_tecnoparque_id: null,
        linea_programatica: null,
    })

    $: if ($form.fecha_inicio && $form.fecha_finalizacion) {
        $form.max_meses_ejecucion = monthDiff($form.fecha_inicio, $form.fecha_finalizacion)
    }

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [17])) {
            $form.post(route('convocatorias.tp.store', [convocatoria.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }

    $: console.log($form.linea_programatica)
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin || checkPermission(authUser, [17])}
                        <a use:inertia href={route('convocatorias.tp.index', [convocatoria.id])} class="text-indigo-400 hover:text-indigo-600"> Tecnoparque </a>
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
                <InfoMessage message={convocatoria.fecha_maxima_tp} class="my-5" />

                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_tp} max={convocatoria.max_fecha_finalizacion_proyectos_tp} error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_tp} max={convocatoria.max_fecha_finalizacion_proyectos_tp} error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
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

            {#if nodosTecnoParque.length > 0}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="nodo_tecnoparque_id" value="Nodo Tecnoparque" />
                    </div>
                    <div>
                        <Select id="nodo_tecnoparque_id" items={nodosTecnoParque} bind:selectedValue={$form.nodo_tecnoparque_id} error={errors.nodo_tecnoparque_id} autocomplete="off" placeholder="Seleccione un nodo TecnoParque" required />
                    </div>
                </div>
            {:else}
                <div class="mt-44">
                    <InfoMessage message="Su regional no cuenta con nodos TecnoParque." alertMsg={true} />
                </div>
            {/if}

            <hr class="mt-5 mb-5" />

            <p class="text-center mt-36 mb-8">Información de mi participación en el proyecto</p>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="rol_sennova" value="Rol SENNOVA" />
                </div>
                <div>
                    <Select id="rol_sennova" items={rolesTp} bind:selectedValue={$form.rol_sennova} error={errors.rol_sennova} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                </div>
            </div>
            {#if $form.fecha_inicio && $form.fecha_finalizacion}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="cantidad_meses" value="Número de meses de vinculación al proyecto" />
                    </div>
                    <div>
                        <Input label="Número de meses de vinculación" id="cantidad_meses" type="number" input$step="0.1" input$min="1" input$max={monthDiff($form.fecha_inicio, $form.fecha_finalizacion)} class="mt-1" bind:value={$form.cantidad_meses} error={errors.cantidad_meses} placeholder="Número de meses de vinculación" autocomplete="off" required />
                        <InfoMessage>Este proyecto será ejecutado en {monthDiff($form.fecha_inicio, $form.fecha_finalizacion)} meses.</InfoMessage>
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="cantidad_horas" value="Número de horas semanales dedicadas para el desarrollo del proyecto (basarse en los lineamientos operativos SENNOVA 2021 y en la circular 01-3-2021-000034)" />
                </div>
                <div>
                    <Input label="Número de horas semanales dedicadas para el desarrollo del proyecto" id="cantidad_horas" type="number" input$step="1" input$min="1" input$max={$form.rol_sennova?.maxHoras} class="mt-1" bind:value={$form.cantidad_horas} error={errors.cantidad_horas} placeholder="Número de horas semanales dedicadas para el desarrollo del proyecto" autocomplete="off" required />
                    <InfoMessage>Horas máximas permitidas para este rol: {$form.rol_sennova?.maxHoras ? $form.rol_sennova?.maxHoras : 0}.</InfoMessage>
                </div>
            </div>
        </fieldset>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || checkPermission(authUser, [17])}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                    {$_('Continue')}
                </LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
