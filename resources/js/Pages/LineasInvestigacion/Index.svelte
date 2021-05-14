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

    export let lineasInvestigacion

    $title = 'Líneas de investigación'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    // prettier-ignore
    let canIndexLineasInvestigacion = authUser.can.find((element) => element == 'lineas-investigacion.index') == 'lineas-investigacion.index'
    // prettier-ignore
    let canShowLineasInvestigacion = authUser.can.find((element) => element == 'lineas-investigacion.show') == 'lineas-investigacion.show'
    // prettier-ignore
    let canCreateLineasInvestigacion = authUser.can.find((element) => element == 'lineas-investigacion.create') == 'lineas-investigacion.create'
    // prettier-ignore
    let canEditLineasInvestigacion = authUser.can.find((element) => element == 'lineas-investigacion.edit') == 'lineas-investigacion.edit'
    // prettier-ignore
    let canDestroyLineasInvestigacion = authUser.can.find((element) => element == 'lineas-investigacion.destroy') == 'lineas-investigacion.destroy'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Líneas de investigación</div>

        <div slot="actions">
            {#if canCreateLineasInvestigacion || isSuperAdmin}
                <Button
                    on:click={() =>
                        Inertia.visit(route('lineas-investigacion.create'))}
                    variant="raised"
                >
                    Crear línea de investigación
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">
                    Nombre
                </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">
                    Centro de formación
                </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">
                    Regional
                </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each lineasInvestigacion.data as lineaInvestigacion (lineaInvestigacion.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {lineaInvestigacion.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {lineaInvestigacion.grupo_investigacion?.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {lineaInvestigacion.grupo_investigacion
                                ?.centro_formacion?.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowLineasInvestigacion || canEditLineasInvestigacion || canDestroyLineasInvestigacion || isSuperAdmin}
                                <Item
                                    on:SMUI:action={() =>
                                        Inertia.visit(
                                            route(
                                                'lineas-investigacion.edit',
                                                lineaInvestigacion.id,
                                            ),
                                        )}
                                >
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

            {#if lineasInvestigacion.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">
                        Sin información registrada
                    </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={lineasInvestigacion.links} />
</AuthenticatedLayout>
