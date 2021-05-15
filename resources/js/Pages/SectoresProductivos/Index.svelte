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

    export let sectoresProductivos

    $title = 'Sectores productivos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexSectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.index') == 'sectores-productivos.index'

    let canShowSectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.show') == 'sectores-productivos.show'

    let canCreateSectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.create') == 'sectores-productivos.create'

    let canEditSectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.edit') == 'sectores-productivos.edit'

    let canDestroySectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.destroy') == 'sectores-productivos.destroy'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Sectores productivos</div>

        <div slot="actions">
            {#if canCreateSectoresProductivos || isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('sectores-productivos.create'))} variant="raised">Crear sector productivo</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Nombre </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Acciones </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each sectoresProductivos.data as sectorProductivo (sectorProductivo.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {sectorProductivo.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowSectoresProductivos || canEditSectoresProductivos || canDestroySectoresProductivos || isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('sectores-productivos.edit', sectorProductivo.id))}>
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

            {#if sectoresProductivos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={sectoresProductivos.links} />
</AuthenticatedLayout>
