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

    export let mincienciasSubtypologies = []

    $title = $_('Minciencias subtypologies.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.index',
        ) == 'minciencias-subtypologies.index'
    let canShowMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.show',
        ) == 'minciencias-subtypologies.show'
    let canCreateMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.create',
        ) == 'minciencias-subtypologies.create'
    let canEditMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.edit',
        ) == 'minciencias-subtypologies.edit'
    let canDestroyMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.delete',
        ) == 'minciencias-subtypologies.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">{$_('Minciencias subtypologies.plural')}</div>

        <div slot="actions">
            {#if canCreateMincienciasSubtypologies || isSuperAdmin}
                <Button
                    on:click={() =>
                        Inertia.visit(
                            route('minciencias-subtypologies.create'),
                        )}
                    variant="raised"
                >
                    Crear
                    {$_('Minciencias subtypologies.singular')}
                </Button>
            {/if}
        </div>

        <tr class="text-left font-bold" slot="thead">
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                >Nombre</th
            >
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                >Tipología Minciencias</th
            >
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                >Acciones</th
            >
        </tr>

        <tbody slot="tbody">
            {#each mincienciasSubtypologies.data as mincienciasSubtypology (mincienciasSubtypology.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {mincienciasSubtypology.name}
                        </p>
                    </td>
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {mincienciasSubtypology.minciencias_typology?.name}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowMincienciasSubtypologies || canEditMincienciasSubtypologies || canDestroyMincienciasSubtypologies || isSuperAdmin}
                                <Item
                                    on:SMUI:action={() =>
                                        Inertia.visit(
                                            route(
                                                'minciencias-subtypologies.edit',
                                                mincienciasSubtypology.id,
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

            {#if mincienciasSubtypologies.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"
                        >Sin información registrada</td
                    >
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={mincienciasSubtypologies.links} />
</AuthenticatedLayout>
