<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Textarea from '@/Components/Textarea'

    export let call
    export let project
    export let errors
    export let projectResults

    $: $title = $_('Create') + ' ' + $_('Outputs.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexOutputs = authUser.can.find((element) => element == 'outputs.index') == 'outputs.index'
    let canShowOutputs = authUser.can.find((element) => element == 'outputs.show') == 'outputs.show'
    let canCreateOutputs = authUser.can.find((element) => element == 'outputs.create') == 'outputs.create'
    let canEditOutputs = authUser.can.find((element) => element == 'outputs.edit') == 'outputs.edit'
    let canDestroyOutputs = authUser.can.find((element) => element == 'outputs.destroy') == 'outputs.delete'

    let sending = false
    let form = useForm({
        name: '',
        project_result_id: '',
        minciencias_subtypology_id: '',
        fecha_inicio: '',
        fecha_finalizacion: '',
    })

    function submit() {
        if (canCreateOutputs || isSuperAdmin) {
            $form.post(route('calls.projects.outputs.store', [call.id, project.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if canIndexOutputs || canCreateOutputs || isSuperAdmin}
                        <a use:inertia href={route('calls.projects.outputs.index', [call.id, project.id])} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Outputs.plural')}
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
            <fieldset class="p-8" disabled={canCreateOutputs || isSuperAdmin ? undefined : true}>
                <div class="mt-8 mb-8">
                    <p class="text-center">Fecha de ejecución</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex">
                            <Label required labelFor="fecha_inicio" value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio" type="date" class="mt-1 block w-full" bind:value={$form.fecha_inicio} required />
                            </div>
                        </div>
                        <div class="mt-4 flex">
                            <Label required labelFor="fecha_finalizacion" value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion" type="date" class="mt-1 block w-full" bind:value={$form.fecha_finalizacion} required />
                            </div>
                        </div>
                    </div>
                    {#if errors.fecha_inicio || errors.fecha_finalizacion}
                        <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                    {/if}
                </div>

                <hr />

                <div class="mt-8">
                    <Label required class="mb-4" labelFor="name" value="Nombre" />
                    <Textarea rows="4" id="name" error={errors.name} bind:value={$form.name} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="project_result_id" value={$_('Research results.singular')} />
                    <Select id="project_result_id" items={projectResults} bind:selectedValue={$form.project_result_id} error={errors.project_result_id} autocomplete="off" placeholder="Seleccione un resultado" required />
                </div>

                {#if project.rdi}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="minciencias_subtypology_id" value={$_('Minciencias subtypologies.singular')} />
                        <DynamicList id="minciencias_subtypology_id" bind:value={$form.minciencias_subtypology_id} routeWebApi={route('web-api.minciencias-subtypologies')} placeholder="Busque por el nombre de la subtipología Minciencias" message={errors.minciencias_subtypology_id} required />
                    </div>
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canCreateOutputs || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                        Crear
                        {$_('Outputs.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
