<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Components/Button'
    import Pagination from '@/Components/Pagination'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Components/DataTable'

    export let redesConocimiento

    $title = 'Redes de conocimiento'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexRedesConocimiento = authUser.can.find((element) => element == 'redes-conocimiento.index') == 'redes-conocimiento.index'

    let canShowRedesConocimiento = authUser.can.find((element) => element == 'redes-conocimiento.show') == 'redes-conocimiento.show'

    let canCreateRedesConocimiento = authUser.can.find((element) => element == 'redes-conocimiento.create') == 'redes-conocimiento.create'

    let canEditRedesConocimiento = authUser.can.find((element) => element == 'redes-conocimiento.edit') == 'redes-conocimiento.edit'

    let canDestroyRedesConocimiento = authUser.can.find((element) => element == 'redes-conocimiento.destroy') == 'redes-conocimiento.destroy'

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Redes de conocimiento</div>

        <div slot="actions">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('redes-conocimiento.create'))} variant="raised">Crear red de conocimiento</Button>
            {/if}
        </div>

        <tr class="text-left font-bold" slot="thead">
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Nombre </th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Acciones </th>
        </tr>

        <tbody slot="tbody">
            {#each redesConocimiento.data as redConocimiento (redConocimiento.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {redConocimiento.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('redes-conocimiento.edit', redConocimiento.id))}>
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

            {#if redesConocimiento.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={redesConocimiento.links} />
</AuthenticatedLayout>
