<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import DropdownProjectSennovaRole from '@/Dropdowns/DropdownProjectSennovaRole'

    export let call
    export let project
    export let programmaticLine
    export let errors

    let projectSennovaRoleInfo

    $: $title = $_('Create') + ' ' + $_('Project sennova roles.singular').toLowerCase()

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

    let sending = false
    let form = useForm({
        qty_months: '',
        qty_roles: '',
        description: '',
        call_sennova_role_id: null,
    })

    $: if (projectSennovaRoleInfo) {
        $form.qty_months = projectSennovaRoleInfo.qty_months
        $form.qty_roles = projectSennovaRoleInfo.qty_roles
    }

    function submit() {
        if (canCreateProjectSennovaRoles || isSuperAdmin) {
            $form.post(route('calls.projects.project-sennova-roles.store', [call.id, project.id]), {
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
                    {#if canIndexProjectSennovaRoles || canCreateProjectSennovaRoles || isSuperAdmin}
                        <a use:inertia href={route('calls.projects.project-sennova-roles.index', [call.id, project.id])} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Project sennova roles.plural')}
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
            <fieldset class="p-8" disabled={canCreateProjectSennovaRoles || isSuperAdmin ? undefined : true}>
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

                {#if projectSennovaRoleInfo?.qty_months_by_default}
                    <div class="mt-4">
                        <p class="block font-medium text-sm text-gray-700 ">
                            Número de meses que requiere el apoyo:
                            <span class="block border-gray-300 p-4 rounded-md shadow-sm">
                                {$form.qty_months}
                            </span>
                        </p>
                    </div>
                {:else}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="qty_months" value="Número de meses que requiere el apoyo" />
                        <Input id="qty_months" type="number" min="1" max={project.diff_months} class="mt-1 block w-full" error={errors.qty_months} bind:value={$form.qty_months} required />
                    </div>
                {/if}

                {#if projectSennovaRoleInfo?.qty_roles_by_default}
                    <div class="mt-4">
                        <p class="block font-medium text-sm text-gray-700 ">
                            Número de personas requeridas:
                            <span class="block border-gray-300 p-4 rounded-md shadow-sm">
                                {$form.qty_roles}
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
                {#if canCreateProjectSennovaRoles || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                        Crear
                        {$_('Project sennova roles.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
