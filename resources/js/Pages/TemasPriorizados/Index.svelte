<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Components/Button'
    import Pagination from '@/Components/Pagination'
    import DataTable from '@/Components/DataTable'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'

    export let temasPriorizados

    $title = 'Temas priorizados'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexTemasPriorizados = authUser.can.find((element) => element == 'temas-priorizados.index') == 'temas-priorizados.index'

    let canShowTemasPriorizados = authUser.can.find((element) => element == 'temas-priorizados.show') == 'temas-priorizados.show'

    let canCreateTemasPriorizados = authUser.can.find((element) => element == 'temas-priorizados.create') == 'temas-priorizados.create'

    let canEditTemasPriorizados = authUser.can.find((element) => element == 'temas-priorizados.edit') == 'temas-priorizados.edit'

    let canDestroyTemasPriorizados = authUser.can.find((element) => element == 'temas-priorizados.destroy') == 'temas-priorizados.destroy'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Temas priorizados</div>

        <div slot="actions">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('temas-priorizados.create'))} variant="raised">Crear tema priorizado</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Nombre </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Sector productivo </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Mesa técnica de servicios tecnológicos </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Acciones </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each temasPriorizados.data as temaPriorizado (temaPriorizado.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {temaPriorizado.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {temaPriorizado.sector_productivo?.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {temaPriorizado.mesa_tecnica?.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('temas-priorizados.edit', temaPriorizado.id))}>
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

            {#if temasPriorizados.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={temasPriorizados.links} />
</AuthenticatedLayout>
