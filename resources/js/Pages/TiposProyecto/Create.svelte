<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'

    export let errors
    export let lineasProgramaticas

    $: $title = 'Crear tipo de proyecto'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.index') == 'tipos-proyecto.index'

    let canShowTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.show') == 'tipos-proyecto.show'

    let canCreateTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.create') == 'tipos-proyecto.create'

    let canEditTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.edit') == 'tipos-proyecto.edit'

    let canDestroyTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.destroy') == 'tipos-proyecto.destroy'

    let sending = false
    let form = useForm({
        nombre: '',
        linea_programatica_id: null,
    })

    function submit() {
        $form.post(route('tipos-proyecto.store'), {
            onStart: () => (sending = true),
            onFinish: () => (sending = false),
        })
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if canIndexTiposProyecto || canCreateTiposProyecto || isSuperAdmin}
                        <a use:inertia href={route('tipos-proyecto.index')} class="text-indigo-400 hover:text-indigo-600"> Tipos de proyecto </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canCreateTiposProyecto || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre" />
                    <Input id="nombre" type="text" class="mt-1 block w-full" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="linea_programatica_id" value="Línea programática" />
                    <Select id="linea_programatica_id" items={lineasProgramaticas} bind:selectedValue={$form.linea_programatica_id} error={errors.linea_programatica_id} autocomplete="off" placeholder="Seleccione una línea programática" required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canCreateTiposProyecto || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear tipo de proyecto</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
