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
    export let serviciosTecnologicos

    $title = 'Proyectos Servicios tecnológicos'

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
        <div slot="title">Servicios tecnológicos</div>

        <div slot="filters">
            <label for="estructuracion_proyectos" class="block text-gray-700">Filtros:</label>
            <select id="estructuracion_proyectos" class="mt-1 w-full form-select" bind:value={filters.estructuracion_proyectos}>
                <option value={null}>Seleccione una opción</option>
                <option value={false}>Ver - Proyectos de la convocatoria</option>
                <option value={true}>Ver - Curso de estructuración de proyectos</option>
            </select>
        </div>

        <div slot="actions">
            {#if isSuperAdmin || checkPermission(authUser, [5])}
                <Button on:click={() => Inertia.visit(route('convocatorias.servicios-tecnologicos.create', [convocatoria.id]))} variant="raised">Crear proyecto Servicios tecnológicos</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Título </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Fecha de ejecución </th>
                {#if isSuperAdmin || convocatoria.fase == 5}
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Evaluación </th>
                {/if}
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each serviciosTecnologicos.data as { id, proyecto, titulo, fecha_ejecucion }}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyecto.codigo}
                            {#if JSON.parse(proyecto.estado)?.requiereSubsanar && convocatoria.fase == 3}
                                <span class="bg-red-100 inline-block mt-2 p-2 rounded text-red-400"> Requiere modificaciones </span>
                            {/if}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {titulo}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {fecha_ejecucion}
                        </p>
                    </td>
                    {#if isSuperAdmin || convocatoria.fase == 5}
                        <td class="border-t">
                            <p class="px-6 py-4">
                                {proyecto.estado_evaluacion_servicios_tecnologicos.estado}
                                {#if isSuperAdmin}
                                    <br />
                                    <small>
                                        Puntaje: {proyecto.estado_evaluacion_servicios_tecnologicos.puntaje}
                                        <br />
                                        Número de recomendaciones: {proyecto.estado_evaluacion_servicios_tecnologicos.numeroRecomendaciones}
                                        <br />
                                        Evaluaciones: {proyecto.estado_evaluacion_servicios_tecnologicos.evaluacionesHabilitadas} habilitada(s) / {proyecto.estado_evaluacion_servicios_tecnologicos.evaluacionesFinalizadas} finalizada(s)
                                        <br />
                                        {#if proyecto.estado_evaluacion_servicios_tecnologicos.alerta}
                                            <strong class="text-red-500">
                                                Importante: {proyecto.estado_evaluacion_servicios_tecnologicos.alerta}
                                            </strong>
                                        {/if}
                                    </small>
                                {/if}
                            </p>
                        </td>
                    {/if}
                    <td class="border-t td-actions">
                        <DataTableMenu class={serviciosTecnologicos.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkPermission(authUser, [6, 7, 16])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.servicios-tecnologicos.edit', [convocatoria.id, id]))}>
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

            {#if serviciosTecnologicos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={serviciosTecnologicos.links} />
</AuthenticatedLayout>
