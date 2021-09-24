<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import EvaluationStepper from '@/Shared/EvaluationStepper'

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
        comentarios_generales: evaluacion.comentarios_generales,
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

    <form on:submit|preventDefault={submit}>
        <div class="mt-28">
            <!-- <div class="mt-8 mb-8">
                <Label labelFor="comentarios_generales" value="Comentarios" />

                <Textarea maxlength="40000" id="comentarios_generales" error={errors.comentarios_generales} bind:value={$form.comentarios_generales} />
            </div>

            <hr class="mt-10 mb-10 border-black-200" /> -->

            <h1 class="font-black mb-10">Comentarios del formulador</h1>

            <p class="whitespace-pre-line">
                {evaluacion.replicas ? evaluacion.replicas : 'Sin información registrada'}
            </p>
        </div>
        <!-- <div class="py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkRole(authUser, [11]) &&   evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div> -->
    </form>
</AuthenticatedLayout>
