<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import InputError from '@/Shared/InputError'
    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import Textarea from '@/Shared/Textarea'

    export let errors
    export let allPermissions

    $: $title = 'Crear rol de sistema'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        name: '',
        description: '',
        permission_id: [],
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('roles.store'), {
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
                        <a use:inertia href={route('roles.index')} class="text-violet-400 hover:text-violet-600"> Roles de sistema </a>
                    <span class="text-violet-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset disabled={isSuperAdmin ? undefined : true}>
            <div class="bg-white rounded shadow max-w-3xl p-8">
                <div class="mt-4">
                    <Input label="Nombre" id="name" type="text" class="mt-1" bind:value={$form.name} error={errors.name} required />
                </div>

                <div class="mt-4">
                    <Textarea label="Descripción" maxlength="40000" id="description" bind:value={$form.description} error={errors.description} required />
                </div>
            </div>

            <div class="bg-white rounded shadow overflow-hidden mt-20">
                <div class="p-4">
                    <Label required class="mb-4" labelFor="permission_id" value="Seleccione los permisos" />
                    <InputError message={errors.permission_id} />
                </div>
                <div class="grid grid-cols-6">
                    {#each allPermissions as { id, name }, i}
                        <div class="pt-8 pb-8 border-t border-b flex flex-col-reverse items-center justify-between">
                            <FormField>
                                <Checkbox bind:group={$form.permission_id} value={id} />
                                <span slot="label">{name}</span>
                            </FormField>
                        </div>
                    {/each}
                </div>
            </div>
        </fieldset>
        <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
            {#if isSuperAdmin}
                <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Crear rol de sistema</LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
