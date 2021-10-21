<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import Button from '@/Shared/Button'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import Stepper from '@/Shared/Stepper'

    export let convocatoria
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
    <Stepper {convocatoria} {proyecto} />

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
                            {#if proyecto.total_maquinaria_industrial == 0 || proyecto.total_servicios_especiales_construccion <= proyecto.total_maquinaria_industrial * 0.05}
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

    <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]} bind:filters showFilters={true}>
        <div slot="filters">
            <label for="presupuestos" class="block text-gray-700">Presupuestos:</label>
            <select id="presupuestos" class="mt-1 w-full form-select" bind:value={filters.presupuestos}>
                <option value={null}>Seleccione un presupuesto</option>
                {#each segundoGrupoPresupuestal as { nombre }}
                    <option value={nombre}>{nombre}</option>
                {/each}
            </select>
        </div>

        <div slot="actions">
            {#if isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.presupuesto.create', [convocatoria.id, proyecto.id]))}>
                    <div>
                        <span>Crear</span>
                        <span class="hidden md:inline">presupuesto</span>
                    </div>
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Código</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Información</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Subtotal del costo de los productos o servicios requeridos</th>
                {#if isSuperAdmin || proyecto.mostrar_recomendaciones}
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Evaluación</th>
                {/if}
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each proyectoPresupuesto.data as presupuesto (presupuesto.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6">
                            PRE-{presupuesto.id}
                        </p>
                    </td>
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
                    {#if isSuperAdmin || proyecto.mostrar_recomendaciones}
                        <td class="border-t">
                            <div class="px-6 py-4">
                                {presupuesto.presupuesto_aprobado}
                            </div>
                        </td>
                    {/if}
                    <td class="border-t td-actions">
                        <DataTableMenu class={proyectoPresupuesto.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 21, 14, 16, 15, 20])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.presupuesto.edit', [convocatoria.id, proyecto.id, presupuesto.id]))}>
                                    <Text>Ver detalles</Text>
                                </Item>
                                {#if presupuesto.convocatoria_presupuesto?.presupuesto_sennova?.requiere_estudio_mercado}
                                    <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.presupuesto.soportes.index', [convocatoria.id, proyecto.id, presupuesto.id]))}>
                                        <Text>Soportes</Text>
                                    </Item>
                                {/if}
                            {:else}
                                <Item>
                                    <Text>No tiene permisos</Text>
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
