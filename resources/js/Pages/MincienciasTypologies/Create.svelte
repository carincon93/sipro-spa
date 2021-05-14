<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'

    export let errors

    $: $title =
        $_('Create') + ' ' + $_('Minciencias typologies.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.index',
        ) == 'minciencias-typologies.index'
    let canShowMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.show',
        ) == 'minciencias-typologies.show'
    let canCreateMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.create',
        ) == 'minciencias-typologies.create'
    let canEditMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.edit',
        ) == 'minciencias-typologies.edit'
    let canDestroyMincienciasTypologies =
        authUser.can.find(
            (element) => element == 'minciencias-typologies.delete',
        ) == 'minciencias-typologies.delete'

    let sending = false
    let form = useForm({
        name: '',
    })

    function submit() {
        if (canCreateMincienciasTypologies || isSuperAdmin) {
            $form.post(route('minciencias-typologies.store'), {
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
                    {#if canIndexMincienciasTypologies || canCreateMincienciasTypologies || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('minciencias-typologies.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Minciencias typologies.plural')}
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
                disabled={canCreateMincienciasTypologies || isSuperAdmin
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
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canCreateMincienciasTypologies || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear
                        {$_('Minciencias typologies.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
