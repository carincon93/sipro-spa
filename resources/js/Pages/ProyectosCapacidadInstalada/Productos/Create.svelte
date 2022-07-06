<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'

    export let errors
    export let proyectoCapacidadInstalada
    export let resultados
    export let tipologiasMinciencias

    $: $title = 'Crear producto'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let isDinamizadorSennova = proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4]) && proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion.id && authUser.centro_formacion_id
    let isAutorPrincipal = proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id)

    let form = useForm({
        descripcion: '',
        resultado_id: null,
        tipologia_minciencias: null,
    })

    function submit() {
        if (isSuperAdmin || isDinamizadorSennova || isAutorPrincipal) {
            $form.post(route('proyectos-capacidad-instalada.productos.store', [proyectoCapacidadInstalada.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('proyectos-capacidad-instalada.index')} class="text-cyan-400 hover:text-cyan-600"> Proyectos de capacidad instalada </a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.edit', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Información básica</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.integrantes.index', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Integrantes</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.objetivos-especificos.index', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Objetivos específicos y resultados</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.productos.index', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600 font-extrabold underline">Productos</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.finalizar', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Finalizar</a>
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || isDinamizadorSennova || isAutorPrincipal ? undefined : true}>
                <div class="mt-8">
                    <Textarea label="Descripción del producto" maxlength="255" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>
                <div class="mt-4">
                    <Select id="resultado_id" items={resultados} bind:selectedValue={$form.resultado_id} error={errors.resultado_id} autocomplete="off" placeholder="Seleccione un resultado" required />
                </div>
                <div class="mt-4">
                    <Select id="tipologia_minciencias" items={tipologiasMinciencias} bind:selectedValue={$form.tipologia_minciencias} error={errors.tipologia_minciencias} autocomplete="off" placeholder="Seleccione una tipología" required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || isDinamizadorSennova || isAutorPrincipal}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Crear producto</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
