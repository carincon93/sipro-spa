<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'

    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import InputError from '@/Shared/InputError'
    import SelectMulti from '@/Shared/SelectMulti'

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
        tecnoacademia_linea_tecnoacademia_id: [],
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

    let lineasTecnoaAcademia
    $: if ($form.tecnoacademia_id) {
        getLineasTecnoacademia()
    }
    async function getLineasTecnoacademia() {
        let res = await axios.get(route('web-api.tecnoacademias.lineas-tecnoacademia', [$form.tecnoacademia_id]))
        if (res.status == '200') {
            lineasTecnoaAcademia = res.data
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
                        <Label required class="mb-4" labelFor="tecnoacademia_linea_tecnoacademia_id" value="Líneas temáticas a ejecutar en la vigencia del proyecto:" />
                    </div>
                    <div>
                        <SelectMulti id="tecnoacademia_linea_tecnoacademia_id" bind:selectedValue={$form.tecnoacademia_linea_tecnoacademia_id} items={lineasTecnoaAcademia} isMulti={true} error={errors.tecnoacademia_linea_tecnoacademia_id} placeholder="Buscar por el nombre de la línea" required />
                        {#if lineasTecnoaAcademia?.length == 0}
                            <div>
                                <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                                <button on:click={getLineasTecnoacademia} type="button" class="flex underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Refrescar
                                </button>
                            </div>
                        {/if}
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
