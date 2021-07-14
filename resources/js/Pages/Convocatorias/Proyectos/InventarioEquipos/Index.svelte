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
    import Stepper from '@/Shared/Stepper'

    export let convocatoria
    export let proyecto
    export let inventarioEquipos

    $title = 'Inventario de equipos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]}>
        <div slot="title">Inventario de equipos</div>

        <div slot="actions">
            {#if isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.inventario-equipos.create', [convocatoria.id, proyecto.id]))} variant="raised">Crear inventario de equipos</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Fecha de adquisición</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each inventarioEquipos.data as inventarioEquipo (inventarioEquipo.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {inventarioEquipo.nombre}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {inventarioEquipo.fecha_adquisicion}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <DataTableMenu class={inventarioEquipos.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkPermission(authUser, [6, 7, 16])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.inventario-equipos.edit', [convocatoria.id, proyecto.id, inventarioEquipo.id]))}>
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

            {#if inventarioEquipos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={inventarioEquipos.links} />
</AuthenticatedLayout>
