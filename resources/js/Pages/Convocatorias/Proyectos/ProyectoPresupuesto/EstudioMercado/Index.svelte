<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import CreateMarketResearch from '@/Pages/Calls/Projects/ProjectSennovaBudgets/MarketResearch/Create'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import LoadingButton from '@/Components/LoadingButton'
    import Pagination from '@/Components/Pagination'
    import Button from '@/Components/Button'
    import DataTable from '@/Components/DataTable'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'
    import Dialog from '@/Components/Dialog'

    export let call
    export let project
    export let projectSennovaBudget
    export let projectBudgetBatches = []
    export let budgetUsage
    export let requiresMarketResearch
    export let requiresMarketResearchBatch
    export let callBudget
    export let sennovaBudget
    export let errors

    let dialog_open
    $: dialog_open = Object.keys(errors).length > 0 ? true : false
    let sending = false

    $title = $_('Market research.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexMarketResearch = authUser.can.find((element) => element == 'market-research.index') == 'market-research.index'
    let canShowMarketResearch = authUser.can.find((element) => element == 'market-research.show') == 'market-research.show'
    let canCreateMarketResearch = authUser.can.find((element) => element == 'market-research.create') == 'market-research.create'
    let canEditMarketResearch = authUser.can.find((element) => element == 'market-research.edit') == 'market-research.edit'
    let canDestroyMarketResearch = authUser.can.find((element) => element == 'market-research.destroy') == 'market-research.delete'

    let canEditProjectSennovaBudgets = authUser.can.find((element) => element == 'project-sennova-budgets.edit') == 'project-sennova-budgets.edit'

    let filters = {}
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('calls.projects.project-sennova-budgets.index', [call.id, project.id])} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Project sennova budgets.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('calls.projects.project-sennova-budgets.edit', [call.id, project.id, projectSennovaBudget.id])} class="text-indigo-400 hover:text-indigo-600">
                            {budgetUsage.description}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {$_('Market research.plural')}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-indigo-100 p-5 text-indigo-600 mb-10">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5" style="transform: translateX(-50px)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {#if requiresMarketResearch}
            {#if requiresMarketResearchBatch}
                <p class="mb-4">
                    El uso presupuestal <strong>{budgetUsage.description}</strong>
                    permite que se agreguen multiples estudios de mercado. De clic en el botón
                    <strong
                        >'{$_('Add')}
                        {$_('Market research.singular').toLowerCase()}'</strong
                    > y añada los que considere necesarios.
                </p>
            {/if}
            {#if sennovaBudget.message}
                <p class="mb-4">
                    <strong>Importante: </strong>{sennovaBudget.message} No debe superar los ${project.percentage_industrial_machinery} COP
                </p>
            {/if}
        {/if}

        <DataTable class="mt-20">
            <div slot="title">{$_('Market research.plural')}</div>

            <div slot="actions">
                {#if (!requiresMarketResearchBatch && requiresMarketResearch && projectBudgetBatches.data.length < 1) || (requiresMarketResearch && requiresMarketResearchBatch)}
                    <div class="mb-6 flex justify-end items-center">
                        <!-- <SearchFilter class="w-full max-w-md mr-4" bind:filters /> -->
                        {#if isSuperAdmin}
                            <Button on:click={() => (dialog_open = true)} variant="raised">
                                {$_('Add')}
                                {$_('Market research.singular').toLowerCase()}
                            </Button>
                        {/if}
                    </div>
                {/if}
            </div>

            <thead slot="thead">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl" colspan="3">Estudio(s) de mercado</th>
                </tr>
            </thead>

            <tbody slot="tbody">
                {#each projectBudgetBatches.data as projectBudgetBatch, i}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 pt-6 pb-4">
                            <h1>Estudio de mercado #{i + 1}</h1>
                            <p>
                                Cantidad de items: {projectBudgetBatch.qty_items}
                            </p>
                            <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('calls.projects.project-sennova-budgets.project-budget-batches.download', [call.id, project.id, projectSennovaBudget.id, projectBudgetBatch.id])}>Descargar ficha técnica</a>
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {#each projectBudgetBatch.market_research as { id, company_name, price_quote }}
                                <div>
                                    <strong>Nombre de la compañía: </strong>{company_name}
                                </div>
                                <div>
                                    <strong>Valor: </strong>${new Intl.NumberFormat('de-DE').format(price_quote)} COP
                                </div>

                                <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('calls.projects.project-sennova-budgets.project-budget-batches.download-price-qoute-file', [call.id, project.id, projectSennovaBudget.id, id])}>Descargar soporte</a>
                            {/each}
                        </td>

                        <td class="border-t px-6 pt-6 pb-4">
                            <ResourceMenu>
                                {#if isSuperAdmin}
                                    <Item on:SMUI:action={() => Inertia.visit(route('calls.projects.project-sennova-budgets.project-budget-batches.edit', [call.id, project.id, projectSennovaBudget.id, projectBudgetBatch.id]))}>
                                        <Text>Ver detalles</Text>
                                    </Item>
                                {:else}
                                    <Item>
                                        <Text>{$_("You don't have permissions")}</Text>
                                    </Item>
                                {/if}
                            </ResourceMenu>
                        </td>
                    </tr>
                {/each}

                {#if projectBudgetBatches.data.length === 0}
                    <tr>
                        <td class="border-t px-6 py-4" colspan="3">Sin información registrada</td>
                    </tr>
                {/if}
            </tbody>

            <tfoot slot="tfoot">
                <tr>
                    <td class="border-t px-6 pt-6 pb-4" colspan="3">
                        <h1>
                            Valor promedio del estudio de mercado: <strong>${new Intl.NumberFormat('de-DE').format(projectSennovaBudget.average)} COP</strong>
                        </h1>
                    </td>
                </tr>
            </tfoot>
        </DataTable>
    </div>

    <!-- Dialog -->
    <Dialog bind:open={dialog_open} id="market-reseach">
        <div slot="title" />
        <div slot="content">
            <CreateMarketResearch bind:dialogOpen={dialog_open} {sending} {errors} {call} {project} {projectSennovaBudget} {projectBudgetBatches} {callBudget} />
        </div>

        <div slot="actions" class="block flex w-full">
            <Button on:click={() => (dialog_open = false)} type="button" variant={null}>Cancelar</Button>
            {#if canCreateMarketResearch}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit" form="market-reseach-form">Guardar</LoadingButton>
            {/if}
        </div>
    </Dialog>

    <Pagination links={projectBudgetBatches.links} />
</AuthenticatedLayout>

<style>
    :global(#market-reseach-dialog .mdc-dialog__surface) {
        width: 750px;
        max-width: calc(100vw - 32px) !important;
    }

    :global(#market-reseach-dialog .mdc-dialog__content) {
        padding-top: 40px !important;
    }

    :global(#market-reseach-dialog .mdc-dialog__title) {
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        margin-bottom: 0;
    }
</style>
