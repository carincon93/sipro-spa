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

    export let semillerosInvestigacion

    $title = 'Semilleros de investigación'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    // prettier-ignore
    let canIndexSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.index') == 'semilleros-investigacion.index'
    // prettier-ignore
    let canShowSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.show') == 'semilleros-investigacion.show'
    // prettier-ignore
    let canCreateSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.create') == 'semilleros-investigacion.create'
    // prettier-ignore
    let canEditSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.edit') == 'semilleros-investigacion.edit'
    // prettier-ignore
    let canDestroySemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.destroy') == 'semilleros-investigacion.destroy'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Semilleros de investigación</div>

        <div slot="actions">
            {#if canCreateSemillerosInvestigacion || isSuperAdmin}
                <Button
                    on:click={() =>
                        Inertia.visit(route('semilleros-investigacion.create'))}
                    variant="raised"
                >
                    Crear semillero de investigación
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">
                    Nombre
                </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">
                    Línea de investigación
                </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">
                    Grupo de investigación
                </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each semillerosInvestigacion.data as semilleroInvestigacion (semilleroInvestigacion.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {semilleroInvestigacion.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {semilleroInvestigacion.linea_investigacion?.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {semilleroInvestigacion.linea_investigacion
                                ?.grupo_investigacion?.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowSemillerosInvestigacion || canEditSemillerosInvestigacion || canDestroySemillerosInvestigacion || isSuperAdmin}
                                <Item
                                    on:SMUI:action={() =>
                                        Inertia.visit(
                                            route(
                                                'semilleros-investigacion.edit',
                                                semilleroInvestigacion.id,
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

            {#if semillerosInvestigacion.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">
                        Sin información registrada
                    </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={semillerosInvestigacion.links} />
</AuthenticatedLayout>
