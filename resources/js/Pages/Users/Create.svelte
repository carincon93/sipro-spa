<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import LoadingButton from '@/Components/LoadingButton'
    import Switch from '@/Components/Switch'
    import Select from '@/Components/Select'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import DynamicList from '@/Dropdowns/DynamicList'

    export let errors
    export let documentTypes
    export let participationTypes
    export let roles

    $: $title = $_('Create') + ' ' + $_('Users.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexUsers = authUser.can.find((element) => element == 'users.index') == 'users.index'
    let canShowUsers = authUser.can.find((element) => element == 'users.show') == 'users.show'
    let canCreateUsers = authUser.can.find((element) => element == 'users.create') == 'users.create'
    let canEditUsers = authUser.can.find((element) => element == 'users.edit') == 'users.edit'
    let canDestroyUsers = authUser.can.find((element) => element == 'users.destroy') == 'users.delete'

    let sending = false
    let form = useForm({
        name: '',
        email: '',
        document_type: '',
        document_number: '',
        cellphone_number: '',
        is_enabled: '',
        participation_type: '',
        academic_centre_id: '',
        role_id: [],
    })

    function submit() {
        if (canCreateUsers || isSuperAdmin) {
            $form.post(route('users.store'), {
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
                    {#if canIndexUsers || canCreateUsers || isSuperAdmin}
                        <a use:inertia href={route('users.index')} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Users.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <div class="bg-white rounded shadow max-w-3xl">
            <div class="p-8">
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="name" value="Nombre completo" />
                    <Input id="name" type="text" class="mt-1 block w-full" bind:value={$form.name} error={errors.name} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="email" value="Correo electrónico" />
                    <Input id="email" type="email" class="mt-1 block w-full" bind:value={$form.email} error={errors.email} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="document_type" value="Tipo de documento" />
                    <Select id="document_type" items={documentTypes} bind:selectedValue={$form.document_type} error={errors.document_type} autocomplete="off" placeholder="Seleccione un tipo de documento" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="document_number" value="Número de documento" />
                    <Input id="document_number" type="number" min="0" class="mt-1 block w-full" bind:value={$form.document_number} error={errors.document_number} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="cellphone_number" value="Número de celular" />
                    <Input id="cellphone_number" type="number" min="0" class="mt-1 block w-full" bind:value={$form.cellphone_number} error={errors.cellphone_number} required />
                </div>
                <div class="mt-4">
                    <Label required labelFor="is_enabled" value="¿Usuario habilitado para ingresar al sistema?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.is_enabled} />
                    <InputError message={errors.is_enabled} />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="participation_type" value="Tipo de participación" />
                    <Select id="participation_type" items={participationTypes} bind:selectedValue={$form.participation_type} error={errors.participation_type} autocomplete="off" placeholder="Seleccione el tipo de participación" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="academic_centre_id" value="Centro de formación" />
                    <DynamicList id="academic_centre_id" bind:value={$form.academic_centre_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.academic_centre_id} required />
                </div>

                <div>
                    <p>
                        La contraseña de este usuario es: {#if $form.document_number}
                            Sena{$form.document_number}*{/if}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-hidden mt-20">
            <div class="p-4">
                <Label required class="mb-4" labelFor="role_id" value="Seleccione algún rol" />
                <InputError message={errors.role_id} />
            </div>
            <div class="grid grid-cols-2">
                {#each roles as { id, name }, i}
                    <FormField>
                        <Checkbox bind:group={$form.role_id} value={id} />
                        <span slot="label">{name}</span>
                    </FormField>
                {/each}
            </div>
        </div>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if canCreateUsers || isSuperAdmin}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                    Crear
                    {$_('Users.singular')}
                </LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
