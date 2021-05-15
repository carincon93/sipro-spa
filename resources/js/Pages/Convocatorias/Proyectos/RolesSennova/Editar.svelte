<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import DropdownProjectSennovaRole from '@/Dropdowns/DropdownProjectSennovaRole'
    import Dialog from '@/Components/Dialog'

    export let call
    export let project
    export let programmaticLine
    export let roleName
    export let projectSennovaRole = {}
    export let errors

    let projectSennovaRoleInfo

    $: $title = roleName.name

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexProjectSennovaRoles = authUser.can.find((element) => element == 'project-sennova-roles.index') == 'project-sennova-roles.index'
    let canShowProjectSennovaRoles = authUser.can.find((element) => element == 'project-sennova-roles.show') == 'project-sennova-roles.show'
    let canCreateProjectSennovaRoles = authUser.can.find((element) => element == 'project-sennova-roles.create') == 'project-sennova-roles.create'
    let canEditProjectSennovaRoles = authUser.can.find((element) => element == 'project-sennova-roles.edit') == 'project-sennova-roles.edit'
    let canDestroyProjectSennovaRoles = authUser.can.find((element) => element == 'project-sennova-roles.delete') == 'project-sennova-roles.delete'

    let dialog_open = false
    let sending = false
    let form = useForm({
        qty_months: projectSennovaRole.qty_months,
        qty_roles: projectSennovaRole.qty_roles,
        description: projectSennovaRole.description,
        call_sennova_role_id: projectSennovaRole.call_sennova_role_id,
    })

    $: if (projectSennovaRoleInfo?.qty_months != null && projectSennovaRoleInfo?.qty_roles) {
        $form.qty_months = projectSennovaRoleInfo.qty_months
        $form.qty_roles = projectSennovaRoleInfo.qty_roles
    } else {
        $form.qty_months = projectSennovaRole.qty_months
        $form.qty_roles = projectSennovaRole.qty_roles
    }

    function submit() {
        if (canEditProjectSennovaRoles || isSuperAdmin) {
            $form.put(route('calls.projects.project-sennova-roles.update', [call.id, project.id, projectSennovaRole.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (canDestroyProjectSennovaRoles || isSuperAdmin) {
            $form.delete(route('calls.projects.project-sennova-roles.destroy', [call.id, project.id, projectSennovaRole.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if canIndexProjectSennovaRoles || canShowProjectSennovaRoles || canEditProjectSennovaRoles || canDestroyProjectSennovaRoles || isSuperAdmin}
                        <a use:inertia href={route('calls.projects.project-sennova-roles.index', [call.id, project.id])} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Project sennova roles.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {roleName.name}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canEditProjectSennovaRoles || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="call_sennova_role_id" value="Rol SENNOVA" />
                    <DropdownProjectSennovaRole id="call_sennova_role_id" {call} {programmaticLine} bind:formProjectSennovaRole={$form.call_sennova_role_id} bind:projectSennovaRoleInfo message={errors.call_sennova_role_id} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="description" value="Descripción" />
                    <Textarea rows="4" id="description" error={errors.description} bind:value={$form.description} required />
                </div>

                {#if projectSennovaRoleInfo?.months_experience}
                    <div class="mt-4">
                        <p class="block font-medium text-sm text-gray-700 ">
                            Experiencia (meses)
                            <span class="block border-gray-300 p-4 rounded-md shadow-sm">
                                {projectSennovaRoleInfo.months_experience}
                            </span>
                        </p>
                    </div>
                {/if}

                {#if projectSennovaRoleInfo?.qty_months}
                    <div class="mt-4">
                        <p class="block font-medium text-sm text-gray-700 ">
                            Número de meses que requiere el apoyo:
                            <span class="block border-gray-300 p-4 rounded-md shadow-sm">
                                {projectSennovaRoleInfo?.qty_months}
                            </span>
                        </p>
                    </div>
                {:else}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="qty_months" value="Número de meses que requiere el apoyo" />
                        <Input id="qty_months" type="number" min="1" max={project.diff_months} class="mt-1 block w-full" error={errors.qty_months} bind:value={$form.qty_months} required />
                    </div>
                {/if}

                {#if projectSennovaRoleInfo?.qty_roles}
                    <div class="mt-4">
                        <p class="block font-medium text-sm text-gray-700 ">
                            Número de personas requeridas:
                            <span class="block border-gray-300 p-4 rounded-md shadow-sm">
                                {projectSennovaRoleInfo?.qty_roles}
                            </span>
                        </p>
                    </div>
                {:else}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="qty_roles" value="Número de personas requeridas" />
                        <Input id="qty_roles" type="number" min="1" class="mt-1 block w-full" error={errors.qty_roles} bind:value={$form.qty_roles} required />
                    </div>
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canDestroyProjectSennovaRoles || isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}>
                        Eliminar
                        {$_('Project sennova roles.singular').toLowerCase()}
                    </button>
                {/if}
                {#if canEditProjectSennovaRoles || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                        Editar
                        {$_('Project sennova roles.singular')}
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
