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

    export let mincienciasTypologies = []

    $title = $_('Minciencias typologies.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.index',
        ) == 'minciencias-typologies.index'
    let canShowMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.show',
        ) == 'minciencias-typologies.show'
    let canCreateMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.create',
        ) == 'minciencias-typologies.create'
    let canEditMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.edit',
        ) == 'minciencias-typologies.edit'
    let canDestroyMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.delete',
        ) == 'minciencias-typologies.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">{$_('Minciencias typologies.plural')}</div>

        <div slot="actions">
            {#if canCreateMincienciasTypologies || isSuperAdmin}
                <Button
                    on:click={() =>
                        Inertia.visit(route('minciencias-typologies.create'))}
                    variant="raised"
                >
                    Crear
                    {$_('Minciencias typologies.singular')}
                </Button>
            {/if}
        </div>

        <tr class="text-left font-bold" slot="thead">
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                >Nombre</th
            >
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                >Acciones</th
            >
        </tr>

        <tbody slot="tbody">
            {#each mincienciasTypologies.data as mincienciasTypology (mincienciasTypology.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {mincienciasTypology.name}
                        </p>
                    </td>
                    <ResourceMenu>
                        {#if canShowMincienciasTypologies || canEditMincienciasTypologies || canDestroyMincienciasTypologies || isSuperAdmin}
                            <Item
                                on:SMUI:action={() =>
                                    Inertia.visit(
                                        route(
                                            'minciencias-typologies.edit',
                                            mincienciasTypology.id,
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
                </tr>
            {/each}
        </tbody>
    </DataTable>
    <Pagination links={mincienciasTypologies.links} />
</AuthenticatedLayout>
