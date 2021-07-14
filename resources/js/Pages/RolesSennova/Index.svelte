<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import InfoMessage from '@/Shared/InfoMessage'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'

    export let rolesSennova

    $title = 'Roles SENNOVA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Roles SENNOVA</div>

        <div slot="caption">
            <InfoMessage>
                Para modificar las reglas de negocio de los roles de Tecnoacademia haga clic en el siguiente botón:

                <Button on:click={() => Inertia.visit(route('reglas-roles-ta.index'))} variant="raised">Reglas de roles TA</Button>
            </InfoMessage>

            <InfoMessage>
                Para modificar las reglas de negocio de los roles de Tecnoparque haga clic en el siguiente botón:

                <Button on:click={() => Inertia.visit(route('reglas-roles-tp.index'))} variant="raised">Reglas de roles TP</Button>
            </InfoMessage>

            <InfoMessage>
                Para modificar las reglas de negocio de los roles de Servicios Tecnológicos haga clic en el siguiente botón:

                <Button on:click={() => Inertia.visit(route('reglas-roles-st.index'))} variant="raised">Reglas de roles ST</Button>
            </InfoMessage>
        </div>

        <div slot="actions">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('roles-sennova.create'))} variant="raised">Crear rol SENNOVA</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each rolesSennova.data as rolSennova (rolSennova.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {rolSennova.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={rolesSennova.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('roles-sennova.edit', rolSennova.id))}>
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

            {#if rolesSennova.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={rolesSennova.links} />
</AuthenticatedLayout>
