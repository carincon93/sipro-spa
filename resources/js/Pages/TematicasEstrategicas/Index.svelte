<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Components/Button'
    import Pagination from '@/Components/Pagination'
    import DataTable from '@/Components/DataTable'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'

    export let tematicasEstrategicas

    $title = 'Temáticas estratégicas SENA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.index') == 'tematicas-estrategicas.index'

    let canShowTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.show') == 'tematicas-estrategicas.show'

    let canCreateTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.create') == 'tematicas-estrategicas.create'

    let canEditTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.edit') == 'tematicas-estrategicas.edit'

    let canDestroyTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.destroy') == 'tematicas-estrategicas.destroy'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Temáticas estratégicas SENA</div>

        <div slot="actions">
            {#if canCreateTematicasEstrategicas || isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('tematicas-estrategicas.create'))} variant="raised">Crear temática estratégica SENA</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Nombre </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Acciones </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each tematicasEstrategicas.data as tematicaEstrategica (tematicaEstrategica.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {tematicaEstrategica.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowTematicasEstrategicas || canEditTematicasEstrategicas || canDestroyTematicasEstrategicas || isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('tematicas-estrategicas.edit', tematicaEstrategica.id))}>
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

            {#if tematicasEstrategicas.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={tematicasEstrategicas.links} />
</AuthenticatedLayout>
