<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Components/Pagination'
    import Button from '@/Components/Button'
    import DataTable from '@/Components/DataTable'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'

    import Stepper from '@/Components/Stepper'

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

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <DataTable class="mt-20">
        <div slot="title">Presupuesto</div>

        <h2 class="text-center mt-10 mb-24" slot="caption">
            Ingrese cada uno de los rubros que requiere el proyecto. Actualmente el total del costo de los productos o servicios requeridos es: ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_proyecto_presupuesto) ? proyecto.total_proyecto_presupuesto : 0)} COP
        </h2>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Concepto SENA</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Regla</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Estado</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
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
                    <td class="border-t p-4"> Viáticos - Bienestar alumnos </td>
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
            {/if}
        </tbody>

        <tfoot slot="tfoot">
            <tr>
                <td colspan="3" class="border-t p-4">
                    <strong>Nota:</strong> Los valores en paréntesis son los valores calculados del proyecto.
                </td>
            </tr>
        </tfoot>
    </DataTable>

    <div class="px-4 mt-20">
        <h1 class="mb-4 text-center">Filtros</h1>
        <ul class="flex flex-wrap">
            {#each segundoGrupoPresupuestal as { nombre }}
                <li class="mr-2 mb-2 inline-flex">
                    <a class="bg-indigo-100 hover:bg-indigo-200 px-2 py-1 rounded-3xl text-center text-indigo-400" use:inertia={{ preserveScroll: true }} href="?search={nombre}">{nombre}</a>
                </li>
            {/each}
            <li class="mr-2 mb-2 inline-flex">
                <a class="bg-green-100 hover:bg-green-200 px-2 py-1 rounded-3xl text-center text-green-400" use:inertia={{ preserveScroll: true }} href="?search=">Todos</a>
            </li>
        </ul>
    </div>

    <DataTable class="mt-20">
        <div slot="actions">
            {#if isSuperAdmin || checkPermission(authUser, [1, 5, 8])}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.proyecto-presupuesto.create', [convocatoria.id, proyecto.id]))}>
                    <div>
                        <span>Crear</span>
                        <span class="hidden md:inline">presupuesto</span>
                    </div>
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Información</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Subtotal del costo de los productos o servicios requeridos</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each proyectoPresupuesto.data as proyectoPresupuesto (proyectoPresupuesto.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <div class="flex flex-col focus:text-indigo-500 px-6 py-4">
                            <div class="mt-3">
                                <small>Concepto interno SENA</small>
                                <p>
                                    {proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal.nombre}
                                </p>
                            </div>
                            <div class="mt-3">
                                <small>Rubro</small>
                                <p>
                                    {proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal.nombre}
                                </p>
                            </div>
                            <div class="mt-3">
                                <small>Uso presupuestal</small>
                                <p>
                                    {proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.uso_presupuestal.descripcion}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="border-t">
                        <div class="mt-3">
                            {#if proyectoPresupuesto.promedio > 0}
                                ${new Intl.NumberFormat('de-DE').format(proyectoPresupuesto.promedio)} COP
                            {:else if proyectoPresupuesto.totalByBudgetWithoutMarketResearch > 0}
                                ${new Intl.NumberFormat('de-DE').format(proyectoPresupuesto.totalByBudgetWithoutMarketResearch)}
                            {:else}
                                No ha generado el estudio de mercado aún
                            {/if}
                        </div>
                        {#if !proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.sumar_al_presupuesto}
                            <span class="text-red-400 text-center text-xs"> Este uso presupuestal NO suma al total del presupuesto </span>
                        {/if}
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.proyecto-presupuesto.edit', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]))}>
                                    <Text>Ver detalles</Text>
                                </Item>
                            {:else}
                                <Item>
                                    <Text>No tiene permisos</Text>
                                </Item>
                            {/if}
                        </ResourceMenu>
                    </td>
                </tr>
            {/each}

            {#if proyectoPresupuesto.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={proyectoPresupuesto.links} />
</AuthenticatedLayout>
