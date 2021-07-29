<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Pagination from '@/Shared/Pagination'
    import InfoMessage from '@/Shared/InfoMessage'

    export let convocatoria
    export let proyecto
    export let evaluacion
    export let proyectoPresupuesto
    export let soportesEstudioMercado

    $title = 'Soportes'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="flex">
                    {#if isSuperAdmin || checkRole(authUser, [11])}
                        <a use:inertia href={route('convocatorias.evaluaciones.presupuesto.index', [convocatoria.id, evaluacion.id])} class="text-indigo-400 hover:text-indigo-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block"> Presupuesto </a>
                    {/if}
                    <span class="text-indigo-400 font-medium ml-2 mr-2">/</span>
                    {#if isSuperAdmin || checkRole(authUser, [11])}
                        <a use:inertia href={route('convocatorias.evaluaciones.presupuesto.edit', [convocatoria.id, evaluacion.id, proyectoPresupuesto.id])} class="text-indigo-400 hover:text-indigo-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block">
                            {proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium ml-2 mr-2">/</span>
                    Soportes
                </h1>
            </div>
        </div>
    </header>

    <h1 class="mt-24 mb-8 text-center text-3xl">Soportes</h1>

    <div class="bg-white rounded shadow">
        <table class="w-full whitespace-no-wrap table-fixed data-table">
            <thead>
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Empresa</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Soporte/Cotización</th>
                </tr>
            </thead>

            <tbody>
                {#each soportesEstudioMercado.data as soporte, i}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 pt-6 pb-4">
                            {soporte.empresa}
                        </td>

                        <td class="border-t px-6 pt-6 pb-4">
                            <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.presupuesto.soportes.download', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, soporte.id])}>Descargar soporte/cotización</a>
                        </td>
                    </tr>
                {/each}

                {#if soportesEstudioMercado.data.length === 0}
                    <tr>
                        <td class="border-t px-6 py-4" colspan="2">Sin información registrada</td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>
    <Pagination links={soportesEstudioMercado.links} />
</AuthenticatedLayout>
