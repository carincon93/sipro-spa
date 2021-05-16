<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import LoadingButton from '@/Components/LoadingButton'
    import Button from '@/Components/Button'
    import Textarea from '@/Components/Textarea'
    import Dialog from '@/Components/Dialog'
    import DropdownCallBudget from '@/Dropdowns/DropdownCallBudget'

    export let call
    export let project
    export let errors
    export let projectSennovaBudget
    export let licenceTypes
    export let softwareTypes

    let callBudget = projectSennovaBudget.call_budget
    let sennovaBudget = projectSennovaBudget

    $: $title = projectSennovaBudget.call_budget.sennova_budget.budget_usage ? projectSennovaBudget.call_budget.sennova_budget.budget_usage.description : null

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

    let showQtyInput = projectSennovaBudget.value != null ? false : true
    let dialog_open = false
    let sending = false
    let form = useForm({
        budget_usage_code: 2010100600203101,
        call_budget_id: projectSennovaBudget.call_budget_id,
        description: projectSennovaBudget.description,
        justification: projectSennovaBudget.justification,
        value: projectSennovaBudget.value,
        qty_items: projectSennovaBudget.qty_items,
        software_type: projectSennovaBudget.software_info?.software_type,
        license_type: projectSennovaBudget.software_info?.license_type,
        fecha_inicio: projectSennovaBudget.software_info?.fecha_inicio,
        fecha_finalizacion: projectSennovaBudget.software_info?.fecha_finalizacion,
    })

    console.log()

    function submit() {
        if (canEditProjectSennovaBudgets || isSuperAdmin) {
            $form.put(route('calls.projects.project-sennova-budgets.update', [call.id, project.id, projectSennovaBudget.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (canDestroyProjectSennovaBudgets || isSuperAdmin) {
            $form.delete(route('calls.projects.project-sennova-budgets.destroy', [call.id, project.id, projectSennovaBudget.id]))
        }
    }
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
                    {projectSennovaBudget.call_budget.sennova_budget.budget_usage.description}
                </h1>
            </div>
        </div>
    </header>

    {$form.budget_usage_code}

    <div class="flex">
        <div class="bg-white rounded shadow max-w-3xl flex-1">
            <form on:submit|preventDefault={submit} id="form-project-budget">
                <div class="p-8">
                    <div class="mt-4">
                        <DropdownCallBudget bind:selectedBudgetUsage={$form.call_budget_id} bind:showQtyInput bind:budgetUsageCode={$form.budget_usage_code} {sennovaBudget} message={errors.call_budget_id} {call} programmaticLine={project.project_type.programmatic_line} required />
                        <InputError message={errors.call_budget_id} />
                    </div>

                    {#if !showQtyInput && projectSennovaBudget.project_budget_batches?.length > 0}
                        <div class="bg-indigo-100 p-5 text-indigo-600 mb-10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5" style="transform: translateX(-50px)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>El uso presupuestal seleccionado no requiere de estudio de mercado. Si el ítem a actualizar tenía estudios de mercado generados estos se eliminarán.</p>
                        </div>
                    {/if}

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="description" value="Describa el bien o servicio a adquirir. Sea específico" />
                        <Textarea rows="4" id="description" error={errors.description} bind:value={$form.description} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="justification" value="Justificación de la necesidad: ¿por qué se requiere este producto o servicio?" />
                        <Textarea rows="4" id="justification" error={errors.justification} bind:value={$form.justification} required />
                    </div>

                    {#if showQtyInput != undefined}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="qty_items" value="Indique la cantidad requerida del producto o servicio relacionado" />
                            <Input id="qty_items" type="number" min="0" class="mt-1 block w-full" bind:value={$form.qty_items} error={errors.qty_items} required />
                        </div>
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="value" value="Valor" />
                            <Input id="value" type="number" min="0" class="mt-1 block w-full" bind:value={$form.value} error={errors.value} required />
                        </div>
                    {/if}

                    {#if $form.budget_usage_code == '2010100600203101'}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="license_type" value="Tipo de licencia" />
                            <select id="license_type" bind:value={$form.license_type} required>
                                <option value="">Seleccione el tipo de licencia </option>
                                {#each licenceTypes as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="software_type" value="Tipo de software" />
                            <select id="software_type" bind:value={$form.software_type} required>
                                <option value="">Seleccione el tipo de software </option>
                                {#each softwareTypes as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-8">
                            <p>Periodo de uso</p>
                            <div class="mt-4">
                                <Label required class="mb-4" labelFor="fecha_inicio" value="Fecha de inicio" />
                                <Input id="fecha_inicio" type="date" class="mt-1 block w-full" bind:value={$form.fecha_inicio} required />
                            </div>
                            <div class="mt-4">
                                <Label class="mb-4" labelFor="fecha_finalizacion" value="Fecha de finalización" />
                                <Input id="fecha_finalizacion" type="date" class="mt-1 block w-full" bind:value={$form.fecha_finalizacion} />
                            </div>
                        </div>
                        {#if errors.fecha_inicio || errors.fecha_finalizacion}
                            <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                        {/if}
                    {/if}
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}>
                            Eliminar
                            {$_('Project sennova budgets.singular').toLowerCase()}
                        </button>
                    {/if}
                    {#if (canEditProjectSennovaBudgets && $form.call_budget_id != '') || (isSuperAdmin && $form.call_budget_id != '')}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                            Editar
                            {$_('Project sennova budgets.singular')}
                        </LoadingButton>
                    {/if}
                </div>
            </form>
        </div>
        <div class="px-4">
            <h1 class="mb-4">Enlaces de interés</h1>
            <ul>
                {#if projectSennovaBudget.call_budget.sennova_budget.requires_market_research}
                    <li>
                        <a class="bg-indigo-100 hover:bg-indigo-200 mb-4 px-6 py-2 rounded-3xl text-center text-indigo-400" use:inertia href={route('calls.projects.project-sennova-budgets.project-budget-batches.index', [call.id, project.id, projectSennovaBudget.id])}>{$_('Market research.plural')}</a>
                    </li>
                {/if}
            </ul>
        </div>
    </div>
    <Dialog bind:open={dialog_open}>
        <div slot="title" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro(a) que desea eliminar este recurso?
                <br />
                Todos los datos se eliminarán de forma permanente.
                <br />
                Está acción no se puede deshacer.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialog_open = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
