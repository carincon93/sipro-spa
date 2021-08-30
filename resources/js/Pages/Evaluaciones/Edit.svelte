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
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'

    export let errors
    export let evaluacion
    export let evaluadores
    export let proyectos

    $: $title = evaluacion ? evaluacion.proyecto.codigo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        habilitado: evaluacion.habilitado,
        modificable: evaluacion.modificable,
        finalizado: evaluacion.finalizado,
        proyecto_id: {
            value: evaluacion.proyecto_id,
            label: proyectos.find((item) => item.value == evaluacion.proyecto_id)?.label,
        },
        user_id: {
            value: evaluacion.user_id,
            label: evaluadores.find((item) => item.value == evaluacion.user_id)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('evaluaciones.update', evaluacion.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin) {
            $form.delete(route('evaluaciones.destroy', evaluacion.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin}
                        <a use:inertia href={route('evaluaciones.index')} class="text-indigo-400 hover:text-indigo-600"> Evaluaciones </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {evaluacion.proyecto.codigo}
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
                    <Select disabled={true} id="proyecto_id" items={proyectos} bind:selectedValue={$form.proyecto_id} error={errors.proyecto_id} autocomplete="off" placeholder="Seleccione un proyecto" required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="habilitado" value="¿La evaluación está habilitada?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.habilitado} />
                    <InputError message={errors.habilitado} />
                </div>

                <div class="mt-4">
                    <Label required labelFor="modificable" value="¿La evaluación es modificable?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.modificable} />
                    <InputError message={errors.modificable} />
                </div>

                <div class="mt-4">
                    <Label required labelFor="finalizado" value="¿La evaluación está finalizada?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.finalizado} />
                    <InputError message={errors.finalizado} />
                </div>
            </fieldset>

            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar usuario </button>
                {/if}
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar evaluación</LoadingButton>
                {/if}
            </div>
        </form>
    </div>

    <Dialog bind:open={dialogOpen}>
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
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
