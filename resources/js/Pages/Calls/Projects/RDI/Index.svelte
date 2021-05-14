<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Components/Pagination'
    import ResourceMenu from '@/Components/ResourceMenu'
    import Button from '@/Components/Button'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Components/DataTable'

    export let rdi
    export let call

    $title = $_('RDI.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexRDI =
        authUser.can.find((element) => element == 'rdi.index') == 'rdi.index'
    let canCreateRDI =
        authUser.can.find((element) => element == 'rdi.create') == 'rdi.create'
    let canShowRDI =
        authUser.can.find((element) => element == 'rdi.show') == 'rdi.show'
    let canEditRDI =
        authUser.can.find((element) => element == 'rdi.edit') == 'rdi.edit'
    let canDestroyRDI =
        authUser.can.find((element) => element == 'rdi.destroy') == 'rdi.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">{$_('RDI.plural')}</div>

        <div slot="actions">
            {#if canCreateRDI || isSuperAdmin}
                <Button
                    on:click={() =>
                        Inertia.visit(route('calls.rdi.create', [call.id]))}
                    variant="raised"
                >
                    Crear proyecto {$_('RDI.singular')}
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Título</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Fecha de ejecución</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Acciones</th
                >
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each rdi.data as rdi (rdi.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {rdi.title}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {rdi.execution_date}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowRDI || canEditRDI || canDestroyRDI || isSuperAdmin}
                                <Item
                                    on:SMUI:action={() =>
                                        Inertia.visit(
                                            route('calls.rdi.edit', [
                                                call.id,
                                                rdi.id,
                                            ]),
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

            {#if rdi.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"
                        >Sin información registrada</td
                    >
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={rdi.links} />
</AuthenticatedLayout>
