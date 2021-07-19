<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import Button from '@/Shared/Button'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import InfoMessage from '@/Shared/InfoMessage'
    import { Item, Text } from '@smui/list'

    export let convocatoria
    export let proyecto
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
                    {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19, 21, 14, 16, 15, 20])}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block"> Presupuesto </a>
                    {/if}
                    <span class="text-indigo-400 font-medium ml-2 mr-2">/</span>
                    {#if isSuperAdmin || checkPermission(authUser, [3, 5, 8, 11, 21, 14, 16, 15, 20])}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.edit', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])} class="text-indigo-400 hover:text-indigo-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block">
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

    <InfoMessage class="mb-8">
        <strong>Importante:</strong>
        <p class="mt-4">
            Deberá adjuntar mínimo 2 soportes (Máximo 3 soportes) de los valores relacionados en el <strong>Formato guía 4: Estudio de mercado - Convocatoria Sennova 2021</strong>, que podrán ser precotizaciones, precios de catálogos de canales comerciales oficiales de proveedores o de almacenes de grandes superficies, o valores de acuerdos marco de precios de Colombia Compra. Los valores del
            estudio deberán corresponder a proveedores ubicados en Colombia y tener una fecha no mayor a 4 meses.
        </p>
    </InfoMessage>

    <div>
        <div class="mb-6 flex justify-end items-center">
            {#if isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19]) && proyecto.modificable == true)}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.presupuesto.soportes.create', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]))}>
                    <div>
                        <span>Crear</span>
                        <span class="hidden md:inline">soporte</span>
                    </div>
                </Button>
            {/if}
        </div>
    </div>
    <div class="bg-white rounded shadow">
        <table class="w-full whitespace-no-wrap table-fixed data-table">
            <thead>
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Empresa</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Soporte/Cotización</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Acciones</th>
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

                        <td class="border-t px-6 pt-6 pb-4">
                            <DataTableMenu class={soportesEstudioMercado.data.length < 4 ? 'z-50' : ''}>
                                {#if isSuperAdmin || (checkPermission(authUser, [3, 5, 8, 11, 21, 14, 16, 15, 20]) && proyecto.modificable == true)}
                                    <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.presupuesto.soportes.edit', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, soporte.id]))}>
                                        <Text>Ver detalles</Text>
                                    </Item>
                                {:else}
                                    <Item>
                                        <Text>No tiene permisos</Text>
                                    </Item>
                                {/if}
                            </DataTableMenu>
                        </td>
                    </tr>
                {/each}

                {#if soportesEstudioMercado.data.length === 0}
                    <tr>
                        <td class="border-t px-6 py-4" colspan="3">Sin información registrada</td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>
    <Pagination links={soportesEstudioMercado.links} />
</AuthenticatedLayout>
