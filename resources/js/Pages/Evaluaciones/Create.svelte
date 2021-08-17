<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Switch from '@/Shared/Switch'
    import Select from '@/Shared/Select'

    export let errors
    export let evaluadores
    export let proyectos

    $: $title = 'Crear evaluación'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        finalizado: false,
        habilitado: false,
        proyecto_id: null,
        user_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('evaluaciones.store'), {
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
                        <a use:inertia href={route('evaluaciones.index')} class="text-indigo-400 hover:text-indigo-600"> Evaluaciones </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8">
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="user_id" value="Evaluador" />
                    <Select id="user_id" items={evaluadores} bind:selectedValue={$form.user_id} error={errors.user_id} autocomplete="off" placeholder="Seleccione un evaluador" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="proyecto_id" value="Proyecto" />
                    <Select id="proyecto_id" items={proyectos} bind:selectedValue={$form.proyecto_id} error={errors.proyecto_id} autocomplete="off" placeholder="Seleccione un proyecto" required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="habilitado" value="¿Está evaluación está habilitada?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.habilitado} />
                    <InputError message={errors.habilitado} />
                </div>

                <div class="mt-4">
                    <Label required labelFor="finalizado" value="¿Está evaluación está finalizada?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.finalizado} />
                    <InputError message={errors.finalizado} />
                </div>
            </fieldset>

            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear evaluación</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
