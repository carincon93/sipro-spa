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
    export let idi

    $title = 'Proyectos I+D+i'

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
        <div slot="title">
            I+D+i {#if convocatoria.tipo_convocatoria == 2}- Proyectos de ejercicio (DEMO){/if}
        </div>

        <div slot="filters">
            <label for="estructuracion_proyectos" class="block text-gray-700">Filtros:</label>
            <select id="estructuracion_proyectos" class="mt-1 w-full form-select" bind:value={filters.estructuracion_proyectos}>
                <option value={null}>Seleccione una opción</option>
                <option value={false}>Ver - Proyectos de la convocatoria</option>
                <option value={true}>Ver - Curso de estructuración de proyectos</option>
            </select>
        </div>

        <div slot="actions">
            {#if isSuperAdmin || (checkPermission(authUser, [1]) && convocatoria.fase == 1) || checkPermissionByUser(authUser, [1])}
                <Button on:click={() => Inertia.visit(route('convocatorias.idi.create', [convocatoria.id]))} variant="raised">Crear proyecto I+D+i</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Título </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Fecha de ejecución </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">
                    Estado {#if convocatoria.tipo_convocatoria == 2}(No aplica para proyectos demo){/if}
                </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each idi.data as { id, proyecto, titulo, fecha_ejecucion }}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyecto.codigo}
                            {#if JSON.parse(proyecto.estado)?.requiereSubsanar && proyecto.mostrar_recomendaciones == true && proyecto.mostrar_requiere_subsanacion == true}
                                <span class="bg-red-100 inline-block mt-2 p-2 rounded text-red-400"> Requiere ser subsanado </span>
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
                    <td class="border-t">
                        {#if isSuperAdmin || (checkRole( authUser, [4], ) && proyecto.mostrar_recomendaciones && convocatoria.tipo_convocatoria == 1) || (checkRole( authUser, [4], ) && proyecto.mostrar_recomendaciones && convocatoria.tipo_convocatoria == 3) || (convocatoria.fase == 5 && proyecto.mostrar_recomendaciones && convocatoria.tipo_convocatoria == 1) || (convocatoria.fase == 5 && proyecto.mostrar_recomendaciones && convocatoria.tipo_convocatoria == 3)}
                            <p class="px-6 py-4">
                                {proyecto.estado_evaluacion_idi.estado}
                                {#if isSuperAdmin}
                                    <br />
                                    <small>
                                        Puntaje: {proyecto.estado_evaluacion_idi.puntaje}
                                        <br />
                                        Número de recomendaciones: {proyecto.estado_evaluacion_idi.numeroRecomendaciones}
                                        <br />
                                        Evaluaciones: {proyecto.estado_evaluacion_idi.evaluacionesHabilitadas} habilitada(s) / {proyecto.estado_evaluacion_idi.evaluacionesFinalizadas} finalizada(s)
                                        <br />
                                        {#if proyecto.estado_evaluacion_idi.alerta}
                                            <strong class="text-red-500">
                                                Importante: {proyecto.estado_evaluacion_idi.alerta}
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
                        <DataTableMenu class={idi.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkPermissionByUser(authUser, [1]) || checkPermission(authUser, [3, 4, 14])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.idi.edit', [convocatoria.id, id]))}>
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

            {#if idi.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="5"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={idi.links} />

    <Dialog bind:open={dialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mb-10">Importante</div>
        <div slot="content">
            {#if convocatoria.tipo_convocatoria == 1}
                <small>Noviembre 4</small>

                <hr class="mt-10 mb-10" />
                <div>
                    <p>
                        Actualmente la plataforma SGPS-SIPRO permite consultar el estado del proyecto y las observaciones formuladas por el equipo evaluador al término de la Segunda Etapa de Evaluación, sin embargo, de acuerdo con los Lineamientos de la Convocatoria, el equipo SENNOVA verificará la aplicación final de causales de rechazo y las reglas relativas a la bolsa regional; lo que podrá dar
                        lugar a cambios en la información actual. Los resultados finales del proceso se consignarán en el respectivo informe y se reflejarán en la plataforma en la semana 45 del año.
                    </p>
                </div>
            {:else}
                <hr class="mt-10 mb-10" />
                <div>
                    <p>Estos proyectos son de ejercicio y NO son válidos para metas de gerente público y son diferentes a los proyectos de capacidad instalada y proyectos de convocatorias.</p>
                </div>
            {/if}
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button variant="raised" on:click={(event) => (dialogOpen = false)}>Entendido</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
