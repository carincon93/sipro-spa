<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import InputError from '@/Components/InputError'
    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import Textarea from '@/Components/Textarea'

    export let errors
    export let allPermissions

    $: $title = $_('Create') + ' ' + $_('System roles.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexRoles = authUser.can.find((element) => element == 'roles.index') == 'roles.index'
    let canShowRoles = authUser.can.find((element) => element == 'roles.show') == 'roles.show'
    let canCreateRoles = authUser.can.find((element) => element == 'roles.create') == 'roles.create'
    let canEditRoles = authUser.can.find((element) => element == 'roles.edit') == 'roles.edit'
    let canDestroyRoles = authUser.can.find((element) => element == 'roles.destroy') == 'roles.delete'

    let sending = false
    let form = useForm({
        name: '',
        description: '',
        permission_id: [],
    })

    function submit() {
        if (canCreateRoles || isSuperAdmin) {
            $form.post(route('roles.store'), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('roles.index')} class="text-indigo-400 hover:text-indigo-600">
                            {$_('System roles.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset disabled={canCreateRoles || isSuperAdmin ? undefined : true}>
            <div class="bg-white rounded shadow max-w-3xl p-8">
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="name" value="Nombre" />
                    <Input id="name" type="text" class="mt-1 block w-full" bind:value={$form.name} error={errors.name} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="description" value="Descripción" />
                    <Textarea rows="4" id="description" bind:value={$form.description} error={errors.description} required />
                </div>
            </div>

            <div class="bg-white rounded shadow overflow-hidden mt-20">
                <div class="p-4">
                    <Label required class="mb-4" labelFor="permission_id" value="Seleccione los permisos" />
                    <InputError message={errors.permission_id} />
                </div>
                <div class="grid grid-cols-6">
                    {#each allPermissions as { id, only_name, method }, i}
                        {#if i % 5 === 0}
                            <div class="p-3 border-t border-b flex items-center text-sm">
                                {$_(only_name + '.plural')}
                            </div>
                        {/if}
                        <div class="pt-8 pb-8 border-t border-b flex flex-col-reverse items-center justify-between">
                            <FormField>
                                <Checkbox bind:group={$form.permission_id} value={id} />
                                <span slot="label">{$_(method)}</span>
                            </FormField>
                        </div>
                    {/each}
                </div>
            </div>
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                    Crear
                    {$_('System roles.singular')}
                </LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
