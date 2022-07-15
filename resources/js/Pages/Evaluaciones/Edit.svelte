<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { Inertia } from '@inertiajs/inertia'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Switch from '@/Shared/Switch'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let evaluacion
    export let evaluadores
    export let proyectos

    $: $title = evaluacion ? evaluacion.proyecto.codigo : null

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false

    let form = useForm({
        habilitado: evaluacion.habilitado,
        modificable: evaluacion.modificable,
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
        if (isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])) {
            $form.put(route('evaluaciones.update', evaluacion.id), {
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])) {
            $form.delete(route('evaluaciones.destroy', evaluacion.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap flex items-center">
                    {#if isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])}
                        <a use:inertia href={route('evaluaciones.index')} class="text-violet-400 hover:text-violet-600"> Evaluaciones </a>
                    {/if}
                    <span class="text-violet-400 font-medium mx-1.5">/</span>
                    {evaluacion.proyecto.codigo}
                    <a class="bg-violet-600 text-white p-1 pr-5 rounded ml-2" href={route('convocatorias.evaluaciones.redireccionar', [evaluacion.proyecto.convocatoria.id, evaluacion.id])} target="_blank">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <small> Ver detalles </small>
                        </span>
                    </a>
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8">
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="user_id" value="Evaluador" />
                    <Select disabled={evaluacion.clausula_confidencialidad} id="user_id" items={evaluadores} bind:selectedValue={$form.user_id} error={errors.user_id} autocomplete="off" placeholder="Seleccione un evaluador" required />
                    {#if evaluacion.clausula_confidencialidad}
                        <InfoMessage alertMsg={true}>No se puede modificar el evaluador debido a que la evaluación ya tiene información registrada. Por favor genere una nueva evaluación con el nuevo evaluador y posteriormente deshabilite esta evaluación.</InfoMessage>
                    {/if}
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="proyecto_id" value="Proyecto" />
                    <Select disabled={true} id="proyecto_id" items={proyectos} bind:selectedValue={$form.proyecto_id} error={errors.proyecto_id} autocomplete="off" placeholder="Seleccione un proyecto" required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="habilitado" value="¿La evaluación está habilitada? Nota: Una evaluación habilitada significa que el sistema la puede tomar para hacer el cálculo del promedio y asignar el estado del proyecto." class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.habilitado} />
                    <InputError message={errors.habilitado} />
                </div>

                <div class="mt-4">
                    <Label required labelFor="modificable" value="¿La evaluación es modificable? Nota: Si la evaluación es modificable el evaluador podrá editar la información de la evaluación. Por otro lado el formulador NO podrá modicar la información del proyecto mientras se está realizando una evaluación." class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.modificable} />
                    <InputError message={errors.modificable} />
                </div>
            </fieldset>

            <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                {#if isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={() => (dialogOpen = true)}> Eliminar evaluación </button>
                {/if}
                {#if isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Editar evaluación</LoadingButton>
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
                <Button on:click={() => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
