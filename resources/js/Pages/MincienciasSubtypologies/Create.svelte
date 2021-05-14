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
    export let mincienciasTypologies

    $: $title =
        $_('Create') +
        ' ' +
        $_('Minciencias subtypologies.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.index',
        ) == 'minciencias-subtypologies.index'
    let canShowMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.show',
        ) == 'minciencias-subtypologies.show'
    let canCreateMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.create',
        ) == 'minciencias-subtypologies.create'
    let canEditMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.edit',
        ) == 'minciencias-subtypologies.edit'
    let canDestroyMincienciasSubtypologies =
        authUser.can.find(
            (element) => element == 'minciencias-subtypologies.delete',
        ) == 'minciencias-subtypologies.delete'

    let sending = false
    let form = useForm({
        name: '',
        minciencias_typology_id: '',
    })

    function submit() {
        if (canCreateMincienciasSubtypologies || isSuperAdmin) {
            $form.post(route('minciencias-subtypologies.store'), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
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
                    {#if canIndexMincienciasSubtypologies || canCreateMincienciasSubtypologies || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('minciencias-subtypologies.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Minciencias subtypologies.plural')}
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
                disabled={canCreateMincienciasSubtypologies || isSuperAdmin
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
                        labelFor="minciencias_typology_id"
                        value="Tipología Minciencias"
                    />
                    <Select
                        id="minciencias_typology_id"
                        items={mincienciasTypologies}
                        bind:selectedValue={$form.minciencias_typology_id}
                        error={errors.minciencias_typology_id}
                        autocomplete="off"
                        placeholder="Seleccione una tipología Minciencias"
                        required
                    />
                </div>
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canCreateMincienciasSubtypologies || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear
                        {$_('Minciencias subtypologies.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
