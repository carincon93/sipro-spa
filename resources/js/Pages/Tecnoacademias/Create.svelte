<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import InputError from '@/Components/InputError'

    export let errors
    export let lineasTecnologicas

    $: $title = 'Crear tecnoacademia'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

    let sending = false
    let form = useForm({
        nombre: '',
        linea_tecnologica_id: [],
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('tecnoacademias.store'), {
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
                    {#if isSuperAdmin}
                        <a use:inertia href={route('tecnoacademias.index')} class="text-indigo-400 hover:text-indigo-600"> Tecnoacademias </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre" />
                    <Input id="nombre" type="text" class="mt-1 block w-full" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-10">
                    <Label required class="mb-4" labelFor="linea_tecnologica_id" value="Líneas tecnológicas" />
                    <div class="mt-10 grid grid-cols-2">
                        {#each lineasTecnologicas as { id, nombre }, i}
                            <FormField>
                                <Checkbox bind:group={$form.linea_tecnologica_id} value={id} />
                                <span slot="label">{nombre}</span>
                            </FormField>
                        {/each}
                    </div>
                    <InputError message={errors.linea_tecnologica_id} />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear tecnoacademia</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
