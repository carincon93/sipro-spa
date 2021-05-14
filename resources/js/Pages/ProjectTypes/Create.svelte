<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'

    export let errors
    export let programmaticLines

    $: $title = $_('Create') + ' ' + $_('Project types.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexProjectTypes =
        authUser.can.find((element) => element == 'project-types.index') ==
        'project-types.index'
    let canShowProjectTypes =
        authUser.can.find((element) => element == 'project-types.show') ==
        'project-types.show'
    let canCreateProjectTypes =
        authUser.can.find((element) => element == 'project-types.create') ==
        'project-types.create'
    let canEditProjectTypes =
        authUser.can.find((element) => element == 'project-types.edit') ==
        'project-types.edit'
    let canDestroyProjectTypes =
        authUser.can.find((element) => element == 'project-types.destroy') ==
        'project-types.delete'

    let sending = false
    let form = useForm({
        name: '',
        maximum_value: '',
        programmatic_line_id: '',
    })

    function submit() {
        $form.post(route('project-types.store'), {
            onStart: () => (sending = true),
            onFinish: () => (sending = false),
        })
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1>
                    {#if canIndexProjectTypes || canCreateProjectTypes || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('project-types.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Project types.plural')}
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
                disabled={canCreateProjectTypes || isSuperAdmin
                    ? undefined
                    : true}
            >
                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="name"
                        value="Nombre"
                    />
                    <Input
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        bind:value={$form.name}
                        error={errors.name}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="maximum_value"
                        value="Valor máximo"
                    />
                    <Input
                        id="maximum_value"
                        type="number"
                        min="0"
                        class="mt-1 block w-full"
                        bind:value={$form.maximum_value}
                        error={errors.maximum_value}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="programmatic_line_id"
                        value="Línea programática"
                    />
                    <Select
                        id="programmatic_line_id"
                        items={programmaticLines}
                        bind:selectedValue={$form.programmatic_line_id}
                        error={errors.programmatic_line_id}
                        autocomplete="off"
                        placeholder="Seleccione una subárea de conocimiento"
                        required
                    />
                </div>
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canCreateProjectTypes || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear
                        {$_('Project types.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
