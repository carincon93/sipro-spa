<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'

    export let presupuestoSennova

    $title = 'Presupuesto SENNOVA'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Presupuesto SENNOVA</div>

        <div slot="actions">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('presupuesto-sennova.create'))} variant="raised">Crear rubro presupuestal SENNOVA</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Segundo grupo presupuestal </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Tercer grupo presupuestal </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Uso presupuestal </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Línea programática </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each presupuestoSennova.data as rubroPresupuestal (rubroPresupuestal.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {rubroPresupuestal.codigo}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {rubroPresupuestal.segundo_grupo_presupuestal.nombre}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {rubroPresupuestal.tercer_grupo_presupuestal.nombre}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {rubroPresupuestal.uso_presupuestal.descripcion}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {rubroPresupuestal.linea_programatica.codigo}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <DataTableMenu class={presupuestoSennova.data.length < 3 ? 'z-50' : ''}>
                            {#if isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('presupuesto-sennova.edit', rubroPresupuestal.id))}>
                                    <Text>Ver detalles</Text>
                                </Item>
                            {/if}
                        </DataTableMenu>
                    </td>
                </tr>
            {/each}

            {#if presupuestoSennova.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={presupuestoSennova.links} />
</AuthenticatedLayout>
