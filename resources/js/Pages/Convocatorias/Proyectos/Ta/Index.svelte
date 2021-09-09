<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import Button from '@/Shared/Button'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Shared/DataTable'

    export let convocatoria
    export let ta

    $title = 'Proyectos Tecnoacademia'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {
        estructuracion_proyectos: $page.props.filters.estructuracion_proyectos,
    }
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20" routeParams={[convocatoria.id]} bind:filters showFilters={true}>
        <div slot="title">Tecnoacademia</div>

        <div slot="filters">
            <label for="estructuracion_proyectos" class="block text-gray-700">Filtros:</label>
            <select id="estructuracion_proyectos" class="mt-1 w-full form-select" bind:value={filters.estructuracion_proyectos}>
                <option value={null}>Seleccione una opción</option>
                <option value={false}>Ver - Proyectos de la convocatoria</option>
                <option value={true}>Ver - Curso de estructuración de proyectos</option>
            </select>
        </div>

        <div slot="actions">
            {#if isSuperAdmin || checkPermission(authUser, [8])}
                <Button on:click={() => Inertia.visit(route('convocatorias.ta.create', [convocatoria.id]))} variant="raised">Crear proyecto Tecnoacademia</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Teacnoacademia </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Fecha de ejecución </th>
                {#if isSuperAdmin || convocatoria.fase == 5}
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Evaluación </th>
                {/if}
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each ta.data as proyecto_ta}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyecto_ta.proyecto.codigo}
                            {#if JSON.parse(proyecto_ta.proyecto.estado)?.requiereSubsanar && convocatoria.fase == 3}
                                <span class="bg-red-100 inline-block mt-2 p-2 rounded text-red-400"> Requiere modificaciones </span>
                            {/if}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyecto_ta.proyecto.tecnoacademia_lineas_tecnoacademia[0]?.tecnoacademia.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {proyecto_ta.fecha_ejecucion}
                        </p>
                    </td>
                    {#if isSuperAdmin || convocatoria.fase == 5}
                        <td class="border-t">
                            <p class="px-6 py-4">
                                {proyecto_ta.proyecto.estado_evaluacion_ta.estado}

                                {#if isSuperAdmin}
                                    <br />
                                    <small>
                                        <br />
                                        Número de recomendaciones: {proyecto_ta.proyecto.estado_evaluacion_ta.numeroRecomendaciones}
                                        <br />
                                        Evaluaciones: {proyecto_ta.proyecto.estado_evaluacion_ta.evaluacionesHabilitadas} habilitada(s) / {proyecto_ta.proyecto.estado_evaluacion_ta.evaluacionesFinalizadas} finalizada(s)
                                        <br />
                                        {#if proyecto_ta.proyecto.estado_evaluacion_ta.alerta}
                                            <strong class="text-red-500">
                                                Importante: {proyecto_ta.proyecto.estado_evaluacion_ta.alerta}
                                            </strong>
                                        {/if}
                                    </small>
                                {/if}
                            </p>
                        </td>
                    {/if}
                    <td class="border-t td-actions">
                        <DataTableMenu class={ta.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkPermission(authUser, [9, 10, 15])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.ta.edit', [convocatoria.id, proyecto_ta.id]))}>
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

            {#if ta.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={ta.links} />
</AuthenticatedLayout>
