<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import Button from '@/Shared/Button'
    import { Item, Text } from '@smui/list'

    export let proyectosIdiTecnoacademia

    $title = 'Proyectos I+D+i TecnoAcademia'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20" showFilters={false}>
        <div slot="title">Proyectos I+D+i TecnoAcademia</div>

        <div slot="actions">
            {#if isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])}
                <Button on:click={() => Inertia.visit(route('proyectos-idi-tecnoacademia.create'))} variant="raised">Crear proyecto I+D+i TecnoAcademia</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Título </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Estado </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Fecha de ejecución </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each proyectosIdiTecnoacademia.data as { id, titulo, estado_formateado, fecha_ejecucion }}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4">{titulo}</p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {#if estado_formateado == null}
                                Sin estado asignado
                            {:else}
                                {estado_formateado}
                            {/if}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">{fecha_ejecucion}</p>
                    </td>

                    <td class="border-t td-actions">
                        <DataTableMenu class={proyectosIdiTecnoacademia.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])}
                                <Item on:SMUI:action={() => Inertia.visit(route('proyectos-idi-tecnoacademia.edit', [id]))}>
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

            {#if proyectosIdiTecnoacademia.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="5"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={proyectosIdiTecnoacademia.links} />
</AuthenticatedLayout>
