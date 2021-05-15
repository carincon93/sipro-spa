<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Select from '@/Components/Select'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import DropdownProgrammaticLine from '@/Dropdowns/DropdownProgrammaticLine'

    export let errors
    export let academicDegrees

    $: $title = $_('Create') + ' ' + $_('Sennova roles.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexSennovaRoles =
        authUser.can.find((element) => element == 'sennova-roles.index') ==
        'sennova-roles.index'
    let canShowSennovaRoles =
        authUser.can.find((element) => element == 'sennova-roles.show') ==
        'sennova-roles.show'
    let canCreateSennovaRoles =
        authUser.can.find((element) => element == 'sennova-roles.create') ==
        'sennova-roles.create'
    let canEditSennovaRoles =
        authUser.can.find((element) => element == 'sennova-roles.edit') ==
        'sennova-roles.edit'
    let canDestroySennovaRoles =
        authUser.can.find((element) => element == 'sennova-roles.destroy') ==
        'sennova-roles.delete'

    let sending = false
    let form = useForm({
        name: '',
        description: '',
        academic_degree: '',
        linea_programatica_id: null,
    })

    function submit() {
        if (canCreateSennovaRoles || isSuperAdmin) {
            $form.post(route('sennova-roles.store'), {
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
                    {#if canIndexSennovaRoles || canCreateSennovaRoles || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('sennova-roles.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Sennova roles.plural')}
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
                disabled={canCreateSennovaRoles || isSuperAdmin
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
                        labelFor="description"
                        value="Descripción"
                    />
                    <Textarea
                        rows="4"
                        id="description"
                        bind:value={$form.description}
                        error={errors.description}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="linea_programatica_id"
                        value={$_('Programmatic lines.singular')}
                    />
                    <DropdownProgrammaticLine
                        id="linea_programatica_id"
                        bind:formProgrammaticLine={$form.linea_programatica_id}
                        message={errors.linea_programatica_id}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="academic_degree"
                        value={$_('Academic degrees.singular')}
                    />
                    <Select
                        id="academic_degree"
                        items={academicDegrees}
                        bind:selectedValue={$form.academic_degree}
                        error={errors.academic_degree}
                        autocomplete="off"
                        placeholder="Seleccione un nivel académico"
                        required
                    />
                </div>
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canCreateSennovaRoles || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear
                        {$_('Sennova roles.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
