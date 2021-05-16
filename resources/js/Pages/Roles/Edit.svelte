<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Dialog from '@/Components/Dialog'

    import InputError from '@/Components/InputError'
    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import Textarea from '@/Components/Textarea'

    export let errors
    export let role = {}
    export let allPermissions
    export let rolePermissions

    $: $title = role ? role.name : null

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

    let dialog_open = false
    let sending = false
    let form = useForm({
        name: role.name,
        description: role.description,
        permission_id: rolePermissions,
    })

    function submit() {
        if (canEditRoles || isSuperAdmin) {
            $form.put(route('roles.update', role.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (canDestroyRoles || isSuperAdmin) {
            $form.delete(route('roles.destroy', role.id))
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
                    {role.name}
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset disabled={canEditRoles || isSuperAdmin ? undefined : true}>
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
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}>
                    Eliminar
                    {$_('System roles.singular').toLowerCase()}
                </button>
            {/if}
            {#if isSuperAdmin}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                    Editar
                    {$_('System roles.singular')}
                </LoadingButton>
            {/if}
        </div>
    </form>

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
