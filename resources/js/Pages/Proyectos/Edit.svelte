<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { Inertia } from '@inertiajs/inertia'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Switch from '@/Shared/Switch'
    import InfoMessage from '@/Shared/InfoMessage'
    import Button from '@/Shared/Button'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'

    export let errors
    export let proyecto
    export let evaluadores
    export let proyectos

    $: $title = proyecto ? proyecto.codigo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        subsanacion: proyecto.a_evaluar == false && proyecto.modificable == true && proyecto.finalizado == false ? true : false,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('proyectos.update', proyecto.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let formEvaluacion = useForm({
        id: null,
        habilitado: false,
        modificable: false,
        finalizado: false,
        proyecto_id: null,
        user_id: null,
    })

    function submitEvaluacion() {
        if (isSuperAdmin) {
            $formEvaluacion.put(route('evaluaciones.update', $formEvaluacion.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let dialogOpen = false
    let showEvaluacionForm = false
    function showEvaluacionDialog(evaluacion) {
        showEvaluacionForm = true
        dialogOpen = true
        $formEvaluacion.id = evaluacion.id
        $formEvaluacion.habilitado = evaluacion.habilitado
        $formEvaluacion.modificable = evaluacion.modificable
        $formEvaluacion.finalizado = evaluacion.finalizado
        $formEvaluacion.clausula_confidencialidad = evaluacion.clausula_confidencialidad
        $formEvaluacion.proyecto_id = {
            value: evaluacion.proyecto_id,
            label: proyectos.find((item) => item.value == evaluacion.proyecto_id)?.label,
        }

        $formEvaluacion.user_id = {
            value: evaluacion.user_id,
            label: evaluadores.find((item) => item.value == evaluacion.user_id)?.label,
        }
    }

    $: if ($formEvaluacion?.modificable) {
        $form.subsanacion = false
    } else {
        $form.subsanacion = true
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin}
                        <a use:inertia href={route('proyectos.index')} class="text-indigo-400 hover:text-indigo-600"> Proyectos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {proyecto.codigo}
                </h1>
            </div>
        </div>
    </header>

    <Button class="mb-2" variant="raised" type="button" on:click={() => Inertia.visit(route('convocatorias.proyectos.edit', [proyecto.convocatoria_id, proyecto.id]))}>Detalles del proyecto</Button>

    <br />

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8">
                <div class="mt-4">
                    <Label labelFor="subsanacion" value="¿El proyecto puede ser subsanado? Nota: Si se selecciona la opción SI se finalizarán todas la evaluaciones del proyecto {proyecto.codigo}" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.subsanacion} />
                    <InputError message={errors.subsanacion} />

                    <InfoMessage class="mt-10">
                        <p class="font-black">Tenga en cuenta: Información del estado del proyecto (Se tienen en cuenta la(s) {JSON.parse(proyecto.estado).evaluacionesHabilitadas} evaluacion(es) habilitada(s))</p>
                        <ul>
                            <li>Estado del proyecto: {JSON.parse(proyecto.estado).estado}</li>
                            <li>Número de recomendaciones: {JSON.parse(proyecto.estado).numeroRecomendaciones}</li>
                            {#if JSON.parse(proyecto.estado).puntaje}
                                <li>Puntaje total: {JSON.parse(proyecto.estado).puntaje}</li>
                            {/if}
                            <li>¿Requiere ser subsanado?: {JSON.parse(proyecto.estado).requiereSubsanar ? 'Si' : 'No'}</li>
                        </ul>
                    </InfoMessage>
                </div>

                <hr class="mt-10 mb-10" />

                <h1 class="p-4 font-black">Evaluadores</h1>

                {#each proyecto.evaluaciones as evaluacion}
                    <div class="flex items-center justify-between p-4 border border-t">
                        <p class="capitalize">{evaluacion.evaluador.nombre}</p>
                        <Button type="button" variant="raised" on:click={() => showEvaluacionDialog(evaluacion)}>Modificar</Button>
                    </div>
                {/each}
            </fieldset>

            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                {/if}
            </div>
        </form>
    </div>

    <Dialog bind:open={dialogOpen}>
        <div slot="title" class="flex items-center">Editar evaluación</div>
        <div slot="content">
            {#if showEvaluacionForm}
                <form on:submit|preventDefault={submitEvaluacion} id="evaluacion">
                    <fieldset class="p-8">
                        <div class="mt-4">
                            <Label class="mb-4" labelFor="user_id" value="Evaluador" />
                            <Select disabled={$formEvaluacion.clausula_confidencialidad} id="user_id" items={evaluadores} bind:selectedValue={$formEvaluacion.user_id} error={errors.user_id} autocomplete="off" placeholder="Seleccione un evaluador" required />
                            {#if $formEvaluacion.clausula_confidencialidad}
                                <InfoMessage alertMsg={true}>No se puede modificar el evaluador debido a que la evaluación ya tiene información registrada. Por favor genere una nueva evaluación con el nuevo evaluador y posteriormente deshabilite esta evaluación.</InfoMessage>
                            {/if}
                        </div>

                        <hr class="mt-10 mb-10" />

                        <div class="mt-4">
                            <Label labelFor="habilitado" value="¿La evaluación está habilitada? Nota: Una evaluación habilitada significa que el sistema la puede tomar para hacer el cálculo del promedio y asignar el estado del proyecto." class="inline-block mb-4" />
                            <br />
                            <Switch bind:checked={$formEvaluacion.habilitado} />
                            <InputError message={errors.habilitado} />
                        </div>

                        <hr class="mt-10 mb-10" />

                        <div class="mt-4">
                            <Label labelFor="modificable" value="¿La evaluación es modificable? Nota: Si la evaluación es modificable el evaluador podrá editar la información de la evaluación. Por otro lado el formulador NO podrá modicar la información del proyecto mientras se está realizando una evaluación." class="inline-block mb-4" />
                            <br />
                            <Switch bind:checked={$formEvaluacion.modificable} />
                            <InputError message={errors.modificable} />
                        </div>
                    </fieldset>
                </form>
            {/if}
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>

                <LoadingButton loading={sending} class="btn-gray ml-auto" type="submit" form="evaluacion">Guardar</LoadingButton>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
