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

    export let gruposInvestigacion
    export let gruposInvestigacionCentroFormacion

    $title = 'Grupos de investigación'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {}
</script>

<AuthenticatedLayout>
    <DataTable class="my-20" showSearchInput={false}>
        <div slot="title">Grupos de investigación de mi centro de formación</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Nombre </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Centro de formación </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Regional </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each gruposInvestigacionCentroFormacion as grupoInvestigacion (grupoInvestigacion.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500 first-letter-uppercase">
                            {grupoInvestigacion.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {grupoInvestigacion.centro_formacion?.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {grupoInvestigacion.centro_formacion?.regional?.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={gruposInvestigacionCentroFormacion.length < 4 ? 'z-50' : ''}>
                            <Item on:SMUI:action={() => Inertia.visit(route('grupos-investigacion.edit', grupoInvestigacion.id))}>
                                <Text>Detalles del grupo de investigación</Text>
                            </Item>
                            <Item on:SMUI:action={() => Inertia.visit(route('grupos-investigacion.lineas-investigacion.index', grupoInvestigacion.id))}>
                                <Text>Líneas de investigación</Text>
                            </Item>
                            <Item on:SMUI:action={() => Inertia.visit(route('grupos-investigacion.semilleros-investigacion.index', grupoInvestigacion.id))}>
                                <Text>Semilleros de investigación</Text>
                            </Item>
                        </DataTableMenu>
                    </td>
                </tr>
            {/each}

            {#if gruposInvestigacionCentroFormacion.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>

    <DataTable class="mt-20">
        <div slot="title">Grupos de investigación</div>

        <div slot="actions">
            {#if isSuperAdmin || checkRole(authUser, [4, 21, 20, 18, 19, 5, 17])}
                <Button on:click={() => Inertia.visit(route('grupos-investigacion.create'))} variant="raised">Crear grupo de investigación</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Nombre </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Centro de formación </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Regional </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each gruposInvestigacion.data as grupoInvestigacion (grupoInvestigacion.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500 first-letter-uppercase">
                            {grupoInvestigacion.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {grupoInvestigacion.centro_formacion?.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {grupoInvestigacion.centro_formacion?.regional?.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={gruposInvestigacion.data.length < 4 ? 'z-50' : ''}>
                            <Item on:SMUI:action={() => Inertia.visit(route('grupos-investigacion.edit', grupoInvestigacion.id))}>
                                <Text>Detalles del grupo de investigación</Text>
                            </Item>
                            <Item on:SMUI:action={() => Inertia.visit(route('grupos-investigacion.lineas-investigacion.index', grupoInvestigacion.id))}>
                                <Text>Líneas de investigación</Text>
                            </Item>
                            <Item on:SMUI:action={() => Inertia.visit(route('grupos-investigacion.semilleros-investigacion.index', grupoInvestigacion.id))}>
                                <Text>Semilleros de investigación</Text>
                            </Item>
                        </DataTableMenu>
                    </td>
                </tr>
            {/each}

            {#if gruposInvestigacion.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={gruposInvestigacion.links} />
</AuthenticatedLayout>
