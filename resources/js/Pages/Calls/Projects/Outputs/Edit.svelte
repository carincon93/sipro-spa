<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Textarea from '@/Components/Textarea'
    import Dialog from '@/Components/Dialog'

    export let call
    export let project
    export let errors
    export let output
    export let projectResults

    $: $title = output ? output.name : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexOutputs =
        authUser.can.find((element) => element == 'outputs.index') ==
        'outputs.index'
    let canShowOutputs =
        authUser.can.find((element) => element == 'outputs.show') ==
        'outputs.show'
    let canCreateOutputs =
        authUser.can.find((element) => element == 'outputs.create') ==
        'outputs.create'
    let canEditOutputs =
        authUser.can.find((element) => element == 'outputs.edit') ==
        'outputs.edit'
    let canDestroyOutputs =
        authUser.can.find((element) => element == 'outputs.destroy') ==
        'outputs.delete'

    let dialog_open = false
    let sending = false
    let form = useForm({
        name: output.name,
        project_result_id: {
            value: output.project_result_id,
            label: projectResults.find(
                (item) => item.value == output.project_result_id,
            )?.label,
        },
        minciencias_subtypology_id:
            output.rdi_output?.minciencias_subtypology_id,
        fecha_incio: output.fecha_incio,
        fecha_finalizacion: output.fecha_finalizacion,
    })

    function submit() {
        if (canEditOutputs || isSuperAdmin) {
            $form.put(
                route('calls.projects.outputs.update', [
                    call.id,
                    project.id,
                    output.id,
                ]),
                {
                    onStart: () => (sending = true),
                    onFinish: () => (sending = false),
                    preserveScroll: true,
                },
            )
        }
    }

    function destroy() {
        if (canDestroyOutputs || isSuperAdmin) {
            $form.delete(
                route('calls.projects.outputs.destroy', [
                    call.id,
                    project.id,
                    output.id,
                ]),
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
                    {#if canIndexOutputs || canShowOutputs || canEditOutputs || canDestroyOutputs || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('calls.projects.outputs.index', [
                                call.id,
                                project.id,
                            ])}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Outputs.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {output.name}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canEditOutputs || isSuperAdmin ? undefined : true}
            >
                <div class="mt-8 mb-8">
                    <p class="text-center">Fecha de ejecución</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex">
                            <Label
                                required
                                labelFor="fecha_incio"
                                value="Del"
                            />
                            <div class="ml-4">
                                <Input
                                    id="fecha_incio"
                                    type="date"
                                    class="mt-1 block w-full"
                                    bind:value={$form.fecha_incio}
                                    required
                                />
                            </div>
                        </div>
                        <div class="mt-4 flex">
                            <Label
                                required
                                labelFor="fecha_finalizacion"
                                value="hasta"
                            />
                            <div class="ml-4">
                                <Input
                                    id="fecha_finalizacion"
                                    type="date"
                                    class="mt-1 block w-full"
                                    bind:value={$form.fecha_finalizacion}
                                    required
                                />
                            </div>
                        </div>
                    </div>
                    {#if errors.fecha_incio || errors.fecha_finalizacion}
                        <InputError
                            message={errors.fecha_incio ||
                                errors.fecha_finalizacion}
                        />
                    {/if}
                </div>

                <hr />

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="name"
                        value="Nombre"
                    />
                    <Textarea
                        rows="4"
                        id="name"
                        error={errors.name}
                        bind:value={$form.name}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="project_result_id"
                        value={$_('Research results.singular')}
                    />
                    <Select
                        id="project_result_id"
                        items={projectResults}
                        bind:selectedValue={$form.project_result_id}
                        error={errors.project_result_id}
                        autocomplete="off"
                        placeholder="Seleccione un resultado"
                        required
                    />
                </div>

                {#if output.rdi_output}
                    <div class="mt-4">
                        <Label
                            required
                            class="mb-4"
                            labelFor="minciencias_subtypology_id"
                            value={$_('Minciencias subtypologies.singular')}
                        />
                        <DynamicList
                            id="minciencias_subtypology_id"
                            bind:value={$form.minciencias_subtypology_id}
                            routeWebApi={route(
                                'web-api.minciencias-subtypologies',
                            )}
                            placeholder="Busque por el nombre de la subtipología Minciencias"
                            message={errors.minciencias_subtypology_id}
                            required
                        />
                    </div>
                {/if}
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canDestroyOutputs || isSuperAdmin}
                    <button
                        class="text-red-600 hover:underline text-left"
                        tabindex="-1"
                        type="button"
                        on:click={(event) => (dialog_open = true)}
                    >
                        Eliminar
                        {$_('Outputs.singular').toLowerCase()}
                    </button>
                {/if}
                {#if canEditOutputs || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Editar
                        {$_('Outputs.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
    <Dialog bind:open={dialog_open}>
        <div slot="title" class="flex items-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 mr-2 text-red-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                />
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
                <Button
                    on:click={(event) => (dialog_open = false)}
                    variant={null}>Cancelar</Button
                >
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
