<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Components/Pagination'
    import Button from '@/Components/Button'
    import DataTable from '@/Components/DataTable'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'

    import Stepper from '@/Components/Stepper'

    export let call
    export let project
    export let projectSennovaBudgets = []
    export let secondBudgetInfo

    $title = $_('Project sennova budgets.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexProjectSennovaBudgets = authUser.can.find((element) => element == 'project-sennova-budgets.index') == 'project-sennova-budgets.index'
    let canShowProjectSennovaBudgets = authUser.can.find((element) => element == 'project-sennova-budgets.show') == 'project-sennova-budgets.show'
    let canCreateProjectSennovaBudgets = authUser.can.find((element) => element == 'project-sennova-budgets.create') == 'project-sennova-budgets.create'
    let canEditProjectSennovaBudgets = authUser.can.find((element) => element == 'project-sennova-budgets.edit') == 'project-sennova-budgets.edit'
    let canDestroyProjectSennovaBudgets = authUser.can.find((element) => element == 'project-sennova-budgets.delete') == 'project-sennova-budgets.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {call} {project} />

    <DataTable class="mt-20">
        <div slot="title">{$_('Project sennova budgets.plural')}</div>

        <h2 class="text-center mt-10 mb-24" slot="caption">
            Ingrese cada uno de los rubros que requiere el proyecto. Actualmente el total del costo de los productos o servicios requeridos es: ${new Intl.NumberFormat('de-DE').format(!isNaN(project.total_project_budget) ? project.total_project_budget : 0)} COP
        </h2>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Concepto SENA</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Regla</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Estado</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t p-4"> Servicios especiales de construcción </td>
                <td class="border-t p-4">
                    El valor no debe superar el 5% (${new Intl.NumberFormat('de-DE').format(!isNaN(project.total_industrial_machinery) ? project.total_industrial_machinery * 0.05 : 0)}) del rubro de "MAQUINARIA INDUSTRIAL" (${new Intl.NumberFormat('de-DE').format(!isNaN(project.total_industrial_machinery) ? project.total_industrial_machinery : 0)}).
                </td>
                <td class="border-t p-4">
                    Valor actual: ${new Intl.NumberFormat('de-DE').format(!isNaN(project.total_special_construction_services) ? project.total_special_construction_services : 0)}
                    {#if project.total_special_construction_services <= project.total_industrial_machinery * 0.05}
                        <span class="bg-green-100 text-green-400 hover:bg-green-200 px-2 py-1 rounded-3xl text-center"> Correcto </span>
                    {:else}
                        <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center"> Incorrecto </span>
                    {/if}
                </td>
            </tr>

            <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t p-4"> Viáticos </td>
                <td class="border-t p-4"> La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.000.000 </td>
                <td class="border-t p-4">
                    Valor actual: ${new Intl.NumberFormat('de-DE').format(!isNaN(project.total_viatics) ? project.total_viatics : 0)}
                    {#if project.total_viatics <= 4000000}
                        <span class="bg-green-100 text-green-400 hover:bg-green-200 px-2 py-1 rounded-3xl text-center"> Correcto </span>
                    {:else}
                        <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center"> Incorrecto </span>
                    {/if}
                </td>
            </tr>

            <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t p-4"> Mantenimiento de maquinaria, equipo, transporte y sofware </td>
                <td class="border-t p-4">
                    El valor no debe superar el 5% (${new Intl.NumberFormat('de-DE').format(!isNaN(project.total_project_budget) ? project.total_project_budget * 0.05 : 0)}) del total del proyecto ( ${new Intl.NumberFormat('de-DE').format(!isNaN(project.total_project_budget) ? project.total_project_budget : 0)}).
                </td>
                <td class="border-t p-4">
                    Valor actual: ${new Intl.NumberFormat('de-DE').format(!isNaN(project.total_machinery_maintenance) ? project.total_machinery_maintenance : 0)}
                    {#if project.total_machinery_maintenance <= project.total_project_budget * 0.05}
                        <span class="bg-green-100 text-green-400 hover:bg-green-200 px-2 py-1 rounded-3xl text-center"> Correcto </span>
                    {:else}
                        <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center"> Incorrecto </span>
                    {/if}
                </td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">
                    <strong>Nota:</strong> Los valores en paréntesis son los valores calculados del proyecto.
                </td>
            </tr>
        </tfoot>
    </DataTable>

    <div class="px-4 mt-20">
        <h1 class="mb-4 text-center">Filtros</h1>
        <ul class="flex flex-wrap">
            {#each secondBudgetInfo as { name }}
                <li class="mr-2 mb-2 inline-flex">
                    <a class="bg-indigo-100 hover:bg-indigo-200 px-2 py-1 rounded-3xl text-center text-indigo-400" use:inertia={{ preserveScroll: true }} href="?search={name}">{name}</a>
                </li>
            {/each}
            <li class="mr-2 mb-2 inline-flex">
                <a class="bg-green-100 hover:bg-green-200 px-2 py-1 rounded-3xl text-center text-green-400" use:inertia={{ preserveScroll: true }} href="?search=">Todos</a>
            </li>
        </ul>
    </div>

    <DataTable class="mt-20">
        <div slot="actions">
            {#if canCreateProjectSennovaBudgets || isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('calls.projects.project-sennova-budgets.create', [call.id, project.id]))}>
                    <div>
                        <span>Crear</span>
                        <span class="hidden md:inline">{$_('Project sennova budgets.singular')}</span>
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
            {#each projectSennovaBudgets.data as projectSennovaBudget (projectSennovaBudget.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <div class="flex flex-col focus:text-indigo-500 px-6 py-4">
                            <div class="mt-3">
                                <small>Concepto interno SENA</small>
                                <p>
                                    {projectSennovaBudget.call_budget?.sennova_budget?.second_budget_info.name}
                                </p>
                            </div>
                            <div class="mt-3">
                                <small>Rubro</small>
                                <p>
                                    {projectSennovaBudget.call_budget?.sennova_budget?.third_budget_info.name}
                                </p>
                            </div>
                            <div class="mt-3">
                                <small>Uso presupuestal</small>
                                <p>
                                    {projectSennovaBudget.call_budget?.sennova_budget?.budget_usage.description}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="border-t">
                        <div class="mt-3">
                            {#if projectSennovaBudget.average > 0}
                                ${new Intl.NumberFormat('de-DE').format(projectSennovaBudget.average)} COP
                            {:else if projectSennovaBudget.totalByBudgetWithoutMarketResearch > 0}
                                ${new Intl.NumberFormat('de-DE').format(projectSennovaBudget.totalByBudgetWithoutMarketResearch)}
                            {/if}
                        </div>
                        {#if !projectSennovaBudget.call_budget?.sennova_budget?.can_be_added}
                            <span class="text-red-400 text-center text-xs"> Este uso presupuestal NO suma al total del presupuesto </span>
                        {/if}
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowProjectSennovaBudgets || canEditProjectSennovaBudgets || canDestroyProjectSennovaBudgets || isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('calls.projects.project-sennova-budgets.edit', [call.id, project.id, projectSennovaBudget.id]))}>
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

            {#if projectSennovaBudgets.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={projectSennovaBudgets.links} />
</AuthenticatedLayout>
