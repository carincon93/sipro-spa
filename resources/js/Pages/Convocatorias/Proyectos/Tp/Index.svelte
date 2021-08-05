<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import Button from '@/Shared/Button'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Shared/DataTable'

    export let convocatoria
    export let tp

    $title = 'Proyectos Tecnoparque'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {
        estructuracion_proyectos: $page.props.filters.estructuracion_proyectos,
    }
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20" routeParams={[convocatoria.id]} bind:filters showFilters={true}>
        <div slot="title">Tecnoparque</div>

        <div slot="filters">
            <label for="estructuracion_proyectos" class="block text-gray-700">Filtros:</label>
            <select id="estructuracion_proyectos" class="mt-1 w-full form-select" bind:value={filters.estructuracion_proyectos}>
                <option value={null}>Seleccione una opción</option>
                <option value={true}>Proyectos del curso de estructuración de proyectos</option>
            </select>
        </div>

        <div slot="actions">
            {#if isSuperAdmin || checkPermission(authUser, [17])}
                <Button on:click={() => Inertia.visit(route('convocatorias.tp.create', [convocatoria.id]))} variant="raised">Crear proyecto Tecnoparque</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Nodo Tecnoparque </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Fecha de ejecución </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each tp.data as proyecto_tp}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyecto_tp.proyecto.codigo}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyecto_tp.titulo}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {proyecto_tp.fecha_ejecucion}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={tp.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkPermission(authUser, [18, 19, 20])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.tp.edit', [convocatoria.id, proyecto_tp.id]))}>
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

            {#if tp.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={tp.links} />
</AuthenticatedLayout>
