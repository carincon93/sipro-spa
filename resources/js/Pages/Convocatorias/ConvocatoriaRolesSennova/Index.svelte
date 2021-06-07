<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import Button from '@/Shared/Button'
    import ResourceMenu from '@/Shared/ResourceMenu'
    import { Item, Text } from '@smui/list'

    export let convocatoria
    export let convocatoriaRolesSennova

    $title = 'Roles SENNOVA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Roles SENNOVA</div>

        <div slot="actions">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('convocatorias.convocatoria-rol-sennova.create', convocatoria.id))} variant="raised">Crear rol SENNOVA</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Asignación mensual</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Línea programática</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each convocatoriaRolesSennova.data as convocatoriaRolSennova (convocatoriaRolSennova.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {convocatoriaRolSennova.nombre}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {convocatoriaRolSennova.asignacion_mensual}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {convocatoriaRolSennova.linea_programatica_nombre}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <ResourceMenu class={convocatoriaRolesSennova.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.convocatoria-rol-sennova.edit', [convocatoria.id, convocatoriaRolSennova.id]))}>
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

            {#if convocatoriaRolesSennova.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={convocatoriaRolesSennova.links} />
</AuthenticatedLayout>
