<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import Button from '@/Shared/Button'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'

    export let convocatoria
    export let convocatoriaPresupuesto

    $title = 'Rubros presupuestales SENNOVA asociados a la convocatoria'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20" routeParams={[convocatoria.id]}>
        <div slot="title">Rubros presupuestales SENNOVA asociados a la convocatoria</div>

        <div slot="actions">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('convocatorias.convocatoria-presupuesto.create', convocatoria.id))} variant="raised">Asociar rubro presupuestal SENNOVA</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Código</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Segundo grupo presupuestal</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Tercer grupo presupuestal</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Uso presupuestal</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Línea programática</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each convocatoriaPresupuesto.data as rubro (rubro.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-cyan-500">
                            {rubro.presupuesto_sennova.codigo}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-cyan-500">
                            {rubro.presupuesto_sennova.segundo_grupo_presupuestal.nombre}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-cyan-500">
                            {rubro.presupuesto_sennova.tercer_grupo_presupuestal.nombre}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-cyan-500">
                            {rubro.presupuesto_sennova.uso_presupuestal.descripcion}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-cyan-500">
                            {rubro.presupuesto_sennova.linea_programatica.codigo}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <DataTableMenu class={convocatoriaPresupuesto.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.convocatoria-presupuesto.edit', [convocatoria.id, rubro.id]))}>
                                    <Text>Ver detalles</Text>
                                </Item>
                            {:else}
                                <Item>
                                    <Text>No tiene permisos</Text>
                                </Item>
                            {/if}
                        </DataTableMenu>
                    </td>
                </tr>
            {/each}

            {#if convocatoriaPresupuesto.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={convocatoriaPresupuesto.links} />
</AuthenticatedLayout>
