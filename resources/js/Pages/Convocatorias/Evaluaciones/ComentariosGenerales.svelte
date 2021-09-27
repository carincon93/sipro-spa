<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import Label from '@/Shared/Label'
    import Textarea from '@/Shared/Textarea'
    import LoadingButton from '@/Shared/LoadingButton'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyecto

    $: $title = 'Comentarios generales'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        comentario_formulador: evaluacion.comentario_formulador,
        comentario_evaluador: evaluacion.comentario_evaluador,
    })

    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $form.post(route('convocatorias.evaluaciones.update-comentarios-generales', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => {
                    sending = false
                },
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    <h1 class="mt-24 mb-8 text-center text-3xl">Comentarios generales</h1>
    <InfoMessage>Este es un espacio para que haga un comentario general al formulador del proyecto.</InfoMessage>

    {#if evaluacion.replicas}
        <hr class="mt-10 mb-10 border-black-200" />

        <h1 class="font-black mb-10">Comentario / resupuesta del formulador</h1>

        <p class="whitespace-pre-line">
            {evaluacion.replicas ? evaluacion.replicas : 'Sin información registrada'}
        </p>
    {/if}
    <form on:submit|preventDefault={submit}>
        <div class="mt-28">
            <div class="mt-8 mb-8">
                <Label labelFor=" comentario_evaluador" value="Comentarios" />

                <Textarea maxlength="40000" id=" comentario_evaluador" error={errors.comentario_evaluador} bind:value={$form.comentario_evaluador} />
            </div>
        </div>
        <div class="py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkRole(authUser, [11]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
