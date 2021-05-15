<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Dialog from '@/Components/Dialog'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import Select from '@/Components/Select'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import DropdownProgrammaticLine from '@/Dropdowns/DropdownProgrammaticLine'

    export let errors
    export let sennovaRole = {}
    export let academicDegrees

    $: $title = sennovaRole ? sennovaRole.name : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexSennovaRoles = authUser.can.find((element) => element == 'sennova-roles.index') == 'sennova-roles.index'
    let canShowSennovaRoles = authUser.can.find((element) => element == 'sennova-roles.show') == 'sennova-roles.show'
    let canCreateSennovaRoles = authUser.can.find((element) => element == 'sennova-roles.create') == 'sennova-roles.create'
    let canEditSennovaRoles = authUser.can.find((element) => element == 'sennova-roles.edit') == 'sennova-roles.edit'
    let canDestroySennovaRoles = authUser.can.find((element) => element == 'sennova-roles.destroy') == 'sennova-roles.delete'

    let dialog_open = false
    let sending = false
    let form = useForm({
        name: sennovaRole.name,
        description: sennovaRole.description,
        academic_degree: {
            value: sennovaRole.academic_degree,
            label: academicDegrees.find((item) => item.value == sennovaRole.academic_degree)?.label,
        },
        linea_programatica_id: sennovaRole.linea_programatica_id,
    })

    function submit() {
        if (canEditSennovaRoles || isSuperAdmin) {
            $form.put(route('sennova-roles.update', sennovaRole.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (canDestroySennovaRoles || isSuperAdmin) {
            $form.delete(route('sennova-roles.destroy', sennovaRole.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if canIndexSennovaRoles || canShowSennovaRoles || canEditSennovaRoles || canDestroySennovaRoles || isSuperAdmin}
                        <a use:inertia href={route('sennova-roles.index')} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Sennova roles.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {sennovaRole.name}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canCreateSennovaRoles || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="name" value="Nombre" />
                    <Input id="name" type="text" class="mt-1 block w-full" bind:value={$form.name} error={errors.name} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="description" value="Descripción" />
                    <Textarea rows="4" id="description" bind:value={$form.description} error={errors.description} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="linea_programatica_id" value={$_('Programmatic lines.singular')} />
                    <DropdownProgrammaticLine id="linea_programatica_id" bind:formProgrammaticLine={$form.linea_programatica_id} message={errors.linea_programatica_id} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="academic_degree" value={$_('Academic degrees.singular')} />
                    <Select id="academic_degree" items={academicDegrees} bind:selectedValue={$form.academic_degree} error={errors.academic_degree} autocomplete="off" placeholder="Seleccione un nivel académico" required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canDestroySennovaRoles || isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}>
                        Eliminar
                        {$_('Sennova roles.singular').toLowerCase()}
                    </button>
                {/if}
                {#if canEditSennovaRoles || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                        Editar
                        {$_('Sennova roles.singular')}
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
