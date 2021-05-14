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

    export let roles = []

    $title = $_('System roles.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexRoles =
        authUser.can.find((element) => element == 'roles.index') ==
        'roles.index'
    let canShowRoles =
        authUser.can.find((element) => element == 'roles.show') == 'roles.show'
    let canCreateRoles =
        authUser.can.find((element) => element == 'roles.create') ==
        'roles.create'
    let canEditRoles =
        authUser.can.find((element) => element == 'roles.edit') == 'roles.edit'
    let canDestroyRoles =
        authUser.can.find((element) => element == 'roles.destroy') ==
        'roles.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">{$_('System roles.plural')}</div>

        <div slot="actions">
            {#if canCreateRoles || isSuperAdmin}
                <Button
                    on:click={() => Inertia.visit(route('roles.create'))}
                    variant="raised"
                >
                    Crear
                    {$_('System roles.singular')}
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Nombre</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Acciones</th
                >
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each roles.data as role (role.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {role.name}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowRoles || canEditRoles || canDestroyRoles || isSuperAdmin}
                                <Item
                                    on:SMUI:action={() =>
                                        Inertia.visit(
                                            route('roles.edit', role.id),
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

            {#if roles.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"
                        >Sin información registrada</td
                    >
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={roles.links} />
</AuthenticatedLayout>
