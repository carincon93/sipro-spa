<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import Button from '@/Shared/Button'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import EvaluationStepper from '@/Shared/EvaluationStepper'

    export let convocatoria
    export let evaluacion
    export let proyecto
    export let proyectoPresupuesto
    export let segundoGrupoPresupuestal

    $title = 'Presupuesto'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {
        presupuestos: $page.props.filters.presupuestos,
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    <a class="flex bg-orangered-900 bottom-0 fixed hover:bg-orangered-600 mb-4 px-6 py-2 rounded-3xl shadow-2xl text-center text-white z-50" href="#evaluacion">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        <span>
            <small>¿Cómo realizar la evaluación?</small>
            Clic en <strong>Detalles</strong> y luego en <strong>Evaluar</strong>
        </span>
    </a>

    {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
        <h1 class="mt-24 mb-8 text-center text-3xl">Reglas</h1>
        <div class="bg-white rounded shadow">
            <table class="w-full whitespace-no-wrap table-fixed data-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Concepto SENA</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Regla</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> Servicios especiales de construcción </td>
                        <td class="border-t p-4">
                            El valor no debe superar el 5% (${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_maquinaria_industrial) ? proyecto.total_maquinaria_industrial * 0.05 : 0)}) del rubro de "MAQUINARIA INDUSTRIAL" (${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_maquinaria_industrial) ? proyecto.total_maquinaria_industrial : 0)}).
                        </td>
                        <td class="border-t p-4">
                            Valor actual: ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_servicios_especiales_construccion) ? proyecto.total_servicios_especiales_construccion : 0)}
                            {#if proyecto.total_servicios_especiales_construccion <= proyecto.total_maquinaria_industrial * 0.05}
                                <span class="bg-green-100 text-green-400 hover:bg-green-200 px-2 py-1 rounded-3xl text-center block"> Cumple </span>
                            {:else}
                                <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center block"> No cumple </span>
                            {/if}
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> Viáticos </td>
                        <td class="border-t p-4"> La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.460.000 </td>
                        <td class="border-t p-4">
                            Valor actual: ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_viaticos) ? proyecto.total_viaticos : 0)}
                            {#if proyecto.total_viaticos <= 4460000}
                                <span class="bg-green-100 text-green-400 hover:bg-green-200 px-2 py-1 rounded-3xl text-center block"> Cumple </span>
                            {:else}
                                <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center block"> No cumple </span>
                            {/if}
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> Mantenimiento de maquinaria, equipo, transporte y sofware </td>
                        <td class="border-t p-4">
                            El valor no debe superar el 5% (${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.precio_proyecto) ? proyecto.precio_proyecto * 0.05 : 0)}) del total del proyecto ( ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.precio_proyecto) ? proyecto.precio_proyecto : 0)}).
                        </td>
                        <td class="border-t p-4">
                            Valor actual: ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_mantenimiento_maquinaria) ? proyecto.total_mantenimiento_maquinaria : 0)}
                            {#if proyecto.total_mantenimiento_maquinaria <= proyecto.precio_proyecto * 0.05}
                                <span class="bg-green-100 text-green-400 hover:bg-green-200 px-2 py-1 rounded-3xl text-center block"> Cumple </span>
                            {:else}
                                <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center block"> No cumple </span>
                            {/if}
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3" class="border-t p-4">
                            <strong>Nota:</strong> Los valores en paréntesis son los valores calculados del proyecto.
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    {:else if proyecto.codigo_linea_programatica == 23}
        <h1 class="mt-24 mb-8 text-center text-3xl">Reglas</h1>
        <div class="bg-white rounded shadow">
            <table class="w-full whitespace-no-wrap table-fixed data-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Concepto SENA</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Regla</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> Servicios especiales de construcción </td>
                        <td class="border-t p-4">
                            El valor no debe superar los 100 salarios mínimos (${new Intl.NumberFormat('de-DE').format(proyecto.salarios_minimos)}).
                        </td>
                        <td class="border-t p-4">
                            Valor actual: ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_servicios_especiales_construccion) ? proyecto.total_servicios_especiales_construccion : 0)}
                            {#if proyecto.total_servicios_especiales_construccion <= proyecto.salarios_minimos}
                                <span class="bg-green-100 text-green-400 hover:bg-green-200 px-2 py-1 rounded-3xl text-center block"> Cumple </span>
                            {:else}
                                <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center block"> No cumple </span>
                            {/if}
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> Mantenimiento de maquinaria, equipo, transporte y sofware </td>
                        <td class="border-t p-4">
                            El valor no debe superar el 5% (${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.precio_proyecto) ? proyecto.precio_proyecto * 0.05 : 0)}) del total del proyecto ( ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.precio_proyecto) ? proyecto.precio_proyecto : 0)}).
                        </td>
                        <td class="border-t p-4">
                            Valor actual: ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_mantenimiento_maquinaria) ? proyecto.total_mantenimiento_maquinaria : 0)}
                            {#if proyecto.total_mantenimiento_maquinaria <= proyecto.precio_proyecto * 0.05}
                                <span class="bg-green-100 text-green-400 hover:bg-green-200 px-2 py-1 rounded-3xl text-center block"> Cumple </span>
                            {:else}
                                <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center block"> No cumple </span>
                            {/if}
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3" class="border-t p-4">
                            <strong>Nota:</strong> Los valores en paréntesis son los valores calculados del proyecto.
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    {:else if proyecto.codigo_linea_programatica == 70}
        <h1 class="mt-24 mb-8 text-center text-3xl">Reglas</h1>
        <div class="bg-white rounded shadow">
            <table class="w-full whitespace-no-wrap table-fixed data-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Concepto SENA</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Regla</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> Materiales para formación profesional </td>
                        <td class="border-t p-4">
                            Valor máximo: ${new Intl.NumberFormat('de-DE').format(proyecto.max_valor_materiales_formacion)}
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> Gastos bienestar alumnos </td>
                        <td class="border-t p-4">
                            Valor máximo: ${new Intl.NumberFormat('de-DE').format(proyecto.max_valor_bienestar_alumnos)}
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> Viáticos y gastos de viaje al interior formación profesional </td>
                        <td class="border-t p-4">
                            Valor máximo: ${new Intl.NumberFormat('de-DE').format(proyecto.max_valor_viaticos_interior)}
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> EDT </td>
                        <td class="border-t p-4">
                            Valor máximo: ${new Intl.NumberFormat('de-DE').format(proyecto.max_valor_edt)}
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t p-4"> Equipo de sistemas, adecuaciones y construcciones, mantenimiento de maquinaria, equipo, transporte y sofware, maquinaria industrial, otras compras de equipos.</td>
                        <td class="border-t p-4">
                            Valor máximo: ${new Intl.NumberFormat('de-DE').format(proyecto.max_valor_mantenimiento_equipos)}
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3" class="border-t p-4">
                            <strong>Máximo presupuesto asignado para la TecnoAcademia:</strong> ${new Intl.NumberFormat('de-DE').format(proyecto.max_valor_proyecto)}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    {/if}

    <DataTable class="mt-20" routeParams={[convocatoria.id, evaluacion.id]} bind:filters showFilters={true}>
        <div slot="filters">
            <label for="presupuestos" class="block text-gray-700">Presupuestos:</label>
            <select id="presupuestos" class="mt-1 w-full form-select" bind:value={filters.presupuestos}>
                <option value={null}>Seleccione un presupuesto</option>
                {#each segundoGrupoPresupuestal as { nombre }}
                    <option value={nombre}>{nombre}</option>
                {/each}
            </select>
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Información</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Subtotal del costo de los productos o servicios requeridos</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Estado</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each proyectoPresupuesto.data as presupuesto (presupuesto.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <div class="flex flex-col focus:text-indigo-500 px-6 py-4">
                            <div class="mt-3">
                                <small>Concepto interno SENA</small>
                                <p>
                                    {presupuesto.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal.nombre}
                                </p>
                            </div>
                            <div class="mt-3">
                                <small>Rubro</small>
                                <p>
                                    {presupuesto.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal.nombre}
                                </p>
                            </div>
                            <div class="mt-3">
                                <small>Uso presupuestal</small>
                                <p>
                                    {presupuesto.convocatoria_presupuesto?.presupuesto_sennova?.uso_presupuestal.descripcion}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="border-t">
                        <div class="mt-3 px-6">
                            ${new Intl.NumberFormat('de-DE').format(presupuesto.valor_total)} COP
                        </div>
                        {#if !presupuesto.convocatoria_presupuesto?.presupuesto_sennova?.sumar_al_presupuesto}
                            <span class="text-red-400 text-center text-xs px-6"> Este uso presupuestal NO suma al total del presupuesto </span>
                        {/if}
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {presupuesto.proyecto_presupuestos_evaluaciones?.find((item) => item.evaluacion_id == evaluacion.id) ? 'Evaluado' : 'Sin evaluar'}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={proyectoPresupuesto.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkRole(authUser, [11])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.evaluaciones.presupuesto.edit', [convocatoria.id, evaluacion.id, presupuesto.id]))}>
                                    <Text>Evaluar</Text>
                                </Item>
                            {:else}
                                <Item>
                                    <Text>No tiene permisos</Text>
                                </Item>
                            {/if}
                            {#if presupuesto.convocatoria_presupuesto?.presupuesto_sennova?.requiere_estudio_mercado}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.evaluaciones.presupuesto.soportes', [convocatoria.id, evaluacion.id, presupuesto.id]))}>
                                    <Text>Soportes</Text>
                                </Item>
                            {/if}
                        </DataTableMenu>
                    </td>
                </tr>
            {/each}

            {#if proyectoPresupuesto.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>

        <tfoot slot="tfoot">
            <tr>
                <td colspan="4" class="border-t p-4">
                    <strong>Actualmente el total del costo de los productos o servicios requeridos es de:</strong> ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_proyecto_presupuesto) ? proyecto.total_proyecto_presupuesto : 0)} COP
                </td>
            </tr>
        </tfoot>
    </DataTable>
    <Pagination links={proyectoPresupuesto.links} />
</AuthenticatedLayout>
