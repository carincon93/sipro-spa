<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import ResourceMenu from '@/Shared/ResourceMenu'
    import { Item, Text } from '@smui/list'

    export let lineasProgramaticas

    $title = 'Líneas programáticas'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Líneas programáticas</div>

        <div slot="actions">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('lineas-programaticas.create'))} variant="raised">Crear línea programática</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Nombre </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Categoría </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each lineasProgramaticas.data as lineaProgramatica (lineaProgramatica.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {lineaProgramatica.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {lineaProgramatica.codigo}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {lineaProgramatica.categoria_proyecto}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu class={lineasProgramaticas.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('lineas-programaticas.edit', lineaProgramatica.id))}>
                                    <Text>Ver detalles</Text>
                                </Item>
                            {:else}
                                <Item>
                                    <Text>No tiene permisos</Text>
                                </Item>
                            {/if}
                        </ResourceMenu>
                    </td>
                </tr>
            {/each}

            {#if lineasProgramaticas.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={lineasProgramaticas.links} />
</AuthenticatedLayout>
