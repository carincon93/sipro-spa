<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'

    export let errors

    $: $title = $_('Create') + ' ' + ' ' + $_('Strategic thematics.singular').toLowerCase()

    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.index') == 'tematicas-estrategicas.index'

    let canShowTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.show') == 'tematicas-estrategicas.show'

    let canCreateTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.create') == 'tematicas-estrategicas.create'

    let canEditTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.edit') == 'tematicas-estrategicas.edit'

    let canDestroyTematicasEstrategicas = authUser.can.find((element) => element == 'tematicas-estrategicas.destroy') == 'tematicas-estrategicas.destroy'

    let sending = false
    let form = useForm({
        nombre: '',
    })

    console.log($page.props.auth.user.can)

    function submit() {
        if (canCreateTematicasEstrategicas || isSuperAdmin) {
            $form.post(route('tematicas-estrategicas.store'), {
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
                    {#if canIndexTematicasEstrategicas || canCreateTematicasEstrategicas || isSuperAdmin}
                        <a use:inertia href={route('tematicas-estrategicas.index')} class="text-indigo-400 hover:text-indigo-600"> Temáticas estratégicas SENA </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canCreateTematicasEstrategicas || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre" />
                    <Input id="nombre" type="text" class="mt-1 block w-full" bind:value={$form.nombre} error={errors.nombre} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canCreateTematicasEstrategicas || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear temática estratégica SENA</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
