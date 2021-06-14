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

    export let notificaciones

    $title = 'Notificaciones'

    console.log(notificaciones)

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Notificaciones</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Notificación</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each notificaciones.data as notificacion}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <div class="px-6 py-4">
                            {#if notificacion.read_at}
                                <span class="bg-green-500 focus:text-indigo-500 px-4 rounded-full text-center text-white text-xs"> Leído </span>
                            {:else}
                                <span class="bg-red-500 focus:text-indigo-500 px-4 rounded-full text-center text-white text-xs"> Sin leer </span>
                            {/if}
                            <p class="focus:text-indigo-500">
                                {notificacion.data.message}
                            </p>
                        </div>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={notificaciones.data.length < 4 ? 'z-50' : ''}>
                            {#if notificacion.data.action}
                                <Item on:SMUI:action={() => Inertia.visit(notificacion.data.action)}>
                                    <Text>Ver detalles</Text>
                                </Item>
                            {:else}
                                <Item>
                                    <Text>No hay acciones disponibles</Text>
                                </Item>
                            {/if}
                        </DataTableMenu>
                    </td>
                </tr>
            {/each}

            {#if notificaciones.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={notificaciones.links} />
</AuthenticatedLayout>
