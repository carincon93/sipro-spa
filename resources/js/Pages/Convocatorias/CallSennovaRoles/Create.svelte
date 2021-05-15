<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'

    export let call
    export let sennovaRoles
    export let errors

    $: $title = $_('Create') + ' ' + $_('Call sennova roles.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexSennovaRoles = authUser.can.find((element) => element == 'call-sennova-roles.index') == 'call-sennova-roles.index'
    let canShowSennovaRoles = authUser.can.find((element) => element == 'call-sennova-roles.show') == 'call-sennova-roles.show'
    let canCreateSennovaRoles = authUser.can.find((element) => element == 'call-sennova-roles.create') == 'call-sennova-roles.create'
    let canEditSennovaRoles = authUser.can.find((element) => element == 'call-sennova-roles.edit') == 'call-sennova-roles.edit'
    let canDestroySennovaRoles = authUser.can.find((element) => element == 'call-sennova-roles.delete') == 'call-sennova-roles.delete'

    let sending = false
    let form = useForm({
        salary: '',
        qty_months: '',
        qty_roles: '',
        sennova_role_id: '',
    })

    function submit() {
        if (canCreateSennovaRoles || isSuperAdmin) {
            $form.post(route('calls.call-sennova-roles.store', call.id), {
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
                    {#if canIndexSennovaRoles || canCreateSennovaRoles || isSuperAdmin}
                        <a use:inertia href={route('calls.call-sennova-roles.index', call.id)} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Call sennova roles.plural')}
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
            <fieldset class="p-8" disabled={canCreateSennovaRoles || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="sennova_role_id" value={$_('Sennova roles.singular')} />
                    <Select id="sennova_role_id" items={sennovaRoles} bind:selectedValue={$form.sennova_role_id} error={errors.sennova_role_id} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="salary" value="Asignación mensual" />
                    <Input id="salary" type="number" min="0" class="mt-1 block w-full" bind:value={$form.salary} error={errors.salary} required />
                </div>

                <div class="mt-4">
                    <Label class="mb-4" labelFor="qty_months" value="Número de meses que requiere el apoyo" />
                    <Input id="qty_months" type="number" min="0" class="mt-1 block w-full" bind:value={$form.qty_months} error={errors.qty_months} />
                </div>

                <div class="mt-4">
                    <Label class="mb-4" labelFor="qty_roles" value="Número de personas requeridas" />
                    <Input id="qty_roles" type="number" min="0" class="mt-1 block w-full" bind:value={$form.qty_roles} error={errors.qty_roles} />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canCreateSennovaRoles || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                        Crear
                        {$_('Call sennova roles.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
