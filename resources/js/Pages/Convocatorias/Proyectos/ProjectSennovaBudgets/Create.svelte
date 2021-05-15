<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import DropdownCallBudget from '@/Dropdowns/DropdownCallBudget'
    import Textarea from '@/Components/Textarea'
    import InputError from '@/Components/InputError'

    export let call
    export let project
    export let errors
    export let licenceTypes
    export let softwareTypes

    $: $title =
        $_('Create') +
        ' ' +
        $_('Project sennova budgets.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexProjectSennovaBudgets =
        authUser.can.find(
            (element) => element == 'project-sennova-budgets.index',
        ) == 'project-sennova-budgets.index'
    let canShowProjectSennovaBudgets =
        authUser.can.find(
            (element) => element == 'project-sennova-budgets.show',
        ) == 'project-sennova-budgets.show'
    let canCreateProjectSennovaBudgets =
        authUser.can.find(
            (element) => element == 'project-sennova-budgets.create',
        ) == 'project-sennova-budgets.create'
    let canEditProjectSennovaBudgets =
        authUser.can.find(
            (element) => element == 'project-sennova-budgets.edit',
        ) == 'project-sennova-budgets.edit'
    let canDestroyProjectSennovaBudgets =
        authUser.can.find(
            (element) => element == 'project-sennova-budgets.delete',
        ) == 'project-sennova-budgets.delete'

    let showQtyInput = true
    let sending = false
    let form = useForm({
        call_budget_id: null,
        description: '',
        justification: '',
        value: '',
        qty_items: '',
        software_type: '',
        license_type: '',
        fecha_inicio: '',
        fecha_finalizacion: '',
        budget_usage_code: '',
    })

    function submit() {
        if (canCreateProjectSennovaBudgets || isSuperAdmin) {
            ;(sending = true),
                $form.post(
                    route('calls.projects.project-sennova-budgets.store', [
                        call.id,
                        project.id,
                    ]),
                    {
                        onStart: () => (sending = true),
                        onFinish: () => (sending = false),
                    },
                )
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1>
                    {#if canIndexProjectSennovaBudgets || canCreateProjectSennovaBudgets || isSuperAdmin}
                        <a
                            use:inertia
                            href={route(
                                'calls.projects.project-sennova-budgets.index',
                                [call.id, project.id],
                            )}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Project sennova budgets.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canCreateProjectSennovaBudgets || isSuperAdmin
                    ? undefined
                    : true}
            >
                <div class="mt-4">
                    <DropdownCallBudget
                        bind:selectedBudgetUsage={$form.call_budget_id}
                        bind:showQtyInput
                        bind:budgetUsageCode={$form.budget_usage_code}
                        message={errors.call_budget_id}
                        {call}
                        programmaticLine={project.project_type
                            .programmatic_line}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="description"
                        value="Describa el bien o servicio a adquirir. Sea específico"
                    />
                    <Textarea
                        rows="4"
                        id="description"
                        error={errors.description}
                        bind:value={$form.description}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="justification"
                        value="Justificación de la necesidad: ¿por qué se requiere este producto o servicio?"
                    />
                    <Textarea
                        rows="4"
                        id="justification"
                        error={errors.justification}
                        bind:value={$form.justification}
                        required
                    />
                </div>

                {#if !showQtyInput}
                    <div class="mt-4">
                        <Label
                            required
                            class="mb-4"
                            labelFor="qty_items"
                            value="Indique la cantidad requerida del producto o servicio relacionado"
                        />
                        <Input
                            id="qty_items"
                            type="number"
                            min="1"
                            class="mt-1 block w-full"
                            bind:value={$form.qty_items}
                            error={errors.qty_items}
                            required
                        />
                    </div>
                    <div class="mt-4">
                        <Label
                            required
                            class="mb-4"
                            labelFor="value"
                            value="Valor"
                        />
                        <Input
                            id="value"
                            type="number"
                            min="1"
                            class="mt-1 block w-full"
                            bind:value={$form.value}
                            error={errors.value}
                            required
                        />
                    </div>
                {/if}

                {#if $form.budget_usage_code == '2010100600203101'}
                    <div class="mt-4">
                        <Label
                            required
                            class="mb-4"
                            labelFor="license_type"
                            value="Tipo de licencia"
                        />
                        <select
                            id="license_type"
                            bind:value={$form.license_type}
                            required
                        >
                            <option value=""
                                >Seleccione el tipo de licencia
                            </option>
                            {#each licenceTypes as { value, label }}
                                <option {value}>{label}</option>
                            {/each}
                        </select>
                    </div>

                    <div class="mt-4">
                        <Label
                            required
                            class="mb-4"
                            labelFor="software_type"
                            value="Tipo de software"
                        />
                        <select
                            id="software_type"
                            bind:value={$form.software_type}
                            required
                        >
                            <option value=""
                                >Seleccione el tipo de software
                            </option>
                            {#each softwareTypes as { value, label }}
                                <option {value}>{label}</option>
                            {/each}
                        </select>
                    </div>

                    <div class="mt-8">
                        <p>Periodo de uso</p>
                        <div class="mt-4">
                            <Label
                                required
                                class="mb-4"
                                labelFor="fecha_inicio"
                                value="Fecha de inicio"
                            />
                            <Input
                                id="fecha_inicio"
                                type="date"
                                class="mt-1 block w-full"
                                bind:value={$form.fecha_inicio}
                                required
                            />
                        </div>
                        <div class="mt-4">
                            <Label
                                class="mb-4"
                                labelFor="fecha_finalizacion"
                                value="Fecha de finalización"
                            />
                            <Input
                                id="fecha_finalizacion"
                                type="date"
                                class="mt-1 block w-full"
                                bind:value={$form.fecha_finalizacion}
                            />
                        </div>
                    </div>
                    {#if errors.fecha_inicio || errors.fecha_finalizacion}
                        <InputError
                            message={errors.fecha_inicio ||
                                errors.fecha_finalizacion}
                        />
                    {/if}
                {/if}
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if (canCreateProjectSennovaBudgets && $form.call_budget_id != '') || (isSuperAdmin && $form.call_budget_id != '')}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear
                        {$_('Project sennova budgets.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
