<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import Textarea from '@/Components/Textarea'
    import InputError from '@/Components/InputError'
    import Dialog from '@/Components/Dialog'

    export let errors
    export let call
    export let project
    export let activity
    export let activityOutputs
    export let activityProjectSennovaBudgets
    export let projectSennovaBudgets = []
    export let outputs = []

    $: $title = activity ? activity.name : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexActivities = authUser.can.find((element) => element == 'activities.index') == 'activities.index'
    let canShowActivities = authUser.can.find((element) => element == 'activities.show') == 'activities.show'
    let canCreateActivities = authUser.can.find((element) => element == 'activities.create') == 'activities.create'
    let canEditActivities = authUser.can.find((element) => element == 'activities.edit') == 'activities.edit'
    let canDestroyActivities = authUser.can.find((element) => element == 'activities.destroy') == 'activities.delete'

    let dialog_open = false
    let sending = false
    let form = useForm({
        description: activity.description,
        fecha_inicio: activity.fecha_inicio,
        fecha_finalizacion: activity.fecha_finalizacion,
        output_id: activityOutputs,
        project_sennova_budget_id: activityProjectSennovaBudgets,
    })

    function submit() {
        if (canEditActivities || isSuperAdmin) {
            $form.put(route('calls.projects.activities.update', [call.id, project.id, activity.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (canDestroyActivities || isSuperAdmin) {
            $form.delete(route('calls.projects.activities.destroy', [call.id, project.id, activity.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if canIndexActivities || canShowActivities || canEditActivities || canDestroyActivities || isSuperAdmin}
                        <a use:inertia href={route('calls.projects.activities.index', [call.id, project.id])} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Activities.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {activity.description}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canEditActivities || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <p class="text-center">Fecha de ejecución</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio" type="date" class="mt-1 block w-full" bind:value={$form.fecha_inicio} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion" type="date" class="mt-1 block w-full" bind:value={$form.fecha_finalizacion} required />
                            </div>
                        </div>
                    </div>
                    {#if errors.fecha_inicio || errors.fecha_finalizacion}
                        <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                    {/if}
                </div>

                <div class="mt-20">
                    <Label required class="mb-4" labelFor="description" value="Descripción" />
                    <Textarea rows="4" id="description" error={errors.description} bind:value={$form.description} required />
                </div>

                <h6 class="mt-20 mb-12 text-2xl">{$_('Outputs.plural')}</h6>
                <div class="bg-white rounded shadow overflow-hidden">
                    <div class="p-4">
                        <Label required class="mb-4" labelFor="output_id" value="Relacione algún producto" />
                        <InputError message={errors.output_id} />
                    </div>
                    <div class="grid grid-cols-2">
                        {#each outputs as { id, name }, i}
                            <FormField>
                                <Checkbox bind:group={$form.output_id} value={id} />
                                <span slot="label">{$_(name)}</span>
                            </FormField>
                        {/each}
                    </div>
                </div>

                <h6 class="mt-20 mb-12 text-2xl">
                    {$_('Project sennova budgets.plural')}
                </h6>
                <div class="bg-white rounded shadow overflow-hidden">
                    <div class="p-4">
                        <Label required class="mb-4" labelFor="project_sennova_budget_id" value="Relacione algún rubro" />
                        <InputError message={errors.project_sennova_budget_id} />
                    </div>
                    <div class="grid grid-cols-2">
                        {#each projectSennovaBudgets as projectSennovaBudget, i}
                            <FormField class="border-b border-l">
                                <Checkbox bind:group={$form.project_sennova_budget_id} value={projectSennovaBudget.id} />
                                <span slot="label">
                                    <div class="mb-8 mt-4">
                                        <small class="block">Concepto interno SENA</small>
                                        {projectSennovaBudget.call_budget?.sennova_budget?.second_budget_info.name}
                                    </div>
                                    <div class="mb-8">
                                        <small class="block">Rubro</small>
                                        {projectSennovaBudget.call_budget?.sennova_budget?.third_budget_info.name}
                                    </div>
                                    <div class="mb-8">
                                        <small class="block">Uso presupuestal</small>
                                        {projectSennovaBudget.call_budget?.sennova_budget?.budget_usage.description}
                                    </div>
                                    <div class="mb-8">
                                        <small class="block">Descripción</small>
                                        {projectSennovaBudget.description}
                                    </div>
                                </span>
                            </FormField>
                        {/each}
                    </div>
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canDestroyActivities || isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}>
                        Eliminar
                        {$_('Activities.singular').toLowerCase()}
                    </button>
                {/if}
                {#if canEditActivities || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                        Editar
                        {$_('Activities.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
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
