<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'

    export let proyectos

    $title = 'Proyectos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {
        year: $page.props.filters.year,
    }
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20" bind:filters showFilters={false}>
        <div slot="title">Proyectos</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                {#if isSuperAdmin}
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Evaluación </th>
                {/if}
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each proyectos.data as { id, estado, titulo, codigo, fecha_ejecucion }}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {codigo}
                            {#if JSON.parse(estado)?.requiereSubsanar}
                                <span class="bg-red-100 inline-block mt-2 p-2 rounded text-red-400"> Requiere modificaciones </span>
                            {/if}
                        </p>
                    </td>
                    {#if isSuperAdmin}
                        <td class="border-t">
                            <p class="px-6 py-4">
                                <!-- {estado_evaluacion_proyectos.estado}
                                {#if isSuperAdmin}
                                    <br />
                                    <small>
                                        Puntaje: {estado_evaluacion_proyectos.puntaje}
                                        <br />
                                        Número de recomendaciones: {estado_evaluacion_proyectos.numeroRecomendaciones}
                                        <br />
                                        Evaluaciones: {estado_evaluacion_proyectos.evaluacionesHabilitadas} habilitada(s) / {estado_evaluacion_proyectos.evaluacionesFinalizadas} finalizada(s)
                                        <br />
                                        {#if estado_evaluacion_proyectos.alerta}
                                            <strong class="text-red-500">
                                                Importante: {estado_evaluacion_proyectos.alerta}
                                            </strong>
                                        {/if}
                                    </small>
                                {/if} -->
                            </p>
                        </td>
                    {/if}

                    <td class="border-t td-actions">
                        <DataTableMenu class={proyectos.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('proyectos.edit', [id]))}>
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

            {#if proyectos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="5"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={proyectos.links} />
</AuthenticatedLayout>
