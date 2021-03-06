<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, checkPermissionByUser } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import Button from '@/Shared/Button'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Shared/DataTable'
    import Dialog from '@/Shared/Dialog'

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

    let dialogOpen = true
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
            {#if isSuperAdmin || (checkPermission(authUser, [8]) && convocatoria.fase == 1) || checkPermissionByUser(authUser, [8])}
                <Button on:click={() => Inertia.visit(route('convocatorias.ta.create', [convocatoria.id]))} variant="raised">Crear proyecto Tecnoacademia</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Teacnoacademia </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Fecha de ejecución </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Estado </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each ta.data as proyecto_ta}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyecto_ta.proyecto.codigo}
                            {#if JSON.parse(proyecto_ta.proyecto.estado)?.requiereSubsanar && proyecto_ta.proyecto.mostrar_recomendaciones == true && proyecto_ta.proyecto.mostrar_requiere_subsanacion == true}
                                <span class="bg-red-100 inline-block mt-2 p-2 rounded text-red-400"> Requiere ser subsanado </span>
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
                    <td class="border-t">
                        {#if isSuperAdmin || (checkRole(authUser, [4, 22]) && proyecto_ta.proyecto.mostrar_recomendaciones) || (convocatoria.fase == 5 && proyecto_ta.proyecto.mostrar_recomendaciones)}
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
                        {:else}
                            <p class="px-6 py-4">No tiene permisos para ver el estado de este proyecto.</p>
                        {/if}
                    </td>
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
                    <td class="border-t px-6 py-4" colspan="5"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={ta.links} />

    {#if convocatoria.fase == 3}
        <Dialog bind:open={dialogOpen} id="informacion">
            <div slot="title" class="flex items-center flex-col mb-10">Importante</div>
            <div slot="content">
                <small>Octubre 14</small>

                <hr class="mt-10 mb-10" />
                <div>
                    <p>Desde el 14 de octubre (13:00HH) hasta el 21 de octubre (23:59 HH) del 2021, se dará inicio la etapa de subsanación. Únicamente los proyectos que tienen un ítem a subsanar podrán realizar la edición del proyecto.</p>
                </div>

                <hr class="mt-10 mb-10" />
                <div>
                    <p>Revise cuales proyectos tienen el mensaje de <strong>Requiere ser subsanado</strong>, ingrese al proyecto y realice la respectiva subsanación. <br /> <img class="mx-auto" src={window.basePath + '/images/img-subsanacion.png'} alt="" /></p>
                </div>
                <hr class="mt-10 mb-10" />
                <div>
                    <p><strong>Tenga en cuenta:</strong> El estado final de los proyectos se conocerá cuando finalice la etapa de segunda evaluación (Estado Rechazado, pre – aprobado con observaciones y Preaprobado). Fechas segunda evaluación: 22 de octubre (13:00 HH) al 3 de noviembre (23:59 HH).</p>
                </div>
            </div>
            <div slot="actions">
                <div class="p-4">
                    <Button variant="raised" on:click={(event) => (dialogOpen = false)}>Entendido</Button>
                </div>
            </div>
        </Dialog>
    {/if}
</AuthenticatedLayout>
