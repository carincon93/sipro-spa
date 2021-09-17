<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Stepper from '@/Shared/Stepper'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let convocatoria
    export let proyecto
    export let evaluaciones

    $: $title = 'Comentarios generales'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let formPrimeraEvaluacion = useForm({
        replicas: evaluaciones[0] ? evaluaciones[0].replicas : '',
    })

    function submitReplicaPrimeraEval() {
        if (isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])) {
            $formPrimeraEvaluacion.post(route('convocatorias.proyectos.update-comentarios', [convocatoria.id, evaluaciones[0].id]), {
                onStart: () => (sending = true),
                onFinish: () => {
                    sending = false
                },
                preserveScroll: true,
            })
        }
    }

    let formSegundaEvaluacion = useForm({
        replicas: evaluaciones[1] ? evaluaciones[1].replicas : '',
    })

    function submitReplicaSegundaEval() {
        if (isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])) {
            $formSegundaEvaluacion.post(route('convocatorias.proyectos.update-comentarios', [convocatoria.id, evaluaciones[1].id]), {
                onStart: () => (sending = true),
                onFinish: () => {
                    sending = false
                },
                preserveScroll: true,
            })
        }
    }

    let formTerceraEvaluacion = useForm({
        replicas: evaluaciones[2] ? evaluaciones[2].replicas : '',
    })

    function submitReplicaTerceraEval() {
        if (isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])) {
            $formTerceraEvaluacion.post(route('convocatorias.proyectos.update-comentarios', [convocatoria.id, evaluaciones[2].id]), {
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
    <Stepper {convocatoria} {proyecto} />

    <h1 class="mt-24 mb-8 text-center text-3xl">Comentarios generales</h1>
    <InfoMessage>Este es un espacio para que envie comentarios a los evaluadores.</InfoMessage>

    {#if evaluaciones[0] && evaluaciones[0].habilitado}
        <form on:submit|preventDefault={submitReplicaPrimeraEval}>
            <div class="mt-28">
                <h1 class="font-black">Evaluador COD-{evaluaciones[0].id}</h1>

                <!-- <p class="whitespace-pre-line">
                    {evaluaciones[0].comentarios_generales ? evaluaciones[0].comentarios_generales : 'Sin información registrada'}
                </p> -->

                <div class="mt-8 mb-8">
                    <Label labelFor="replicas" value="Comentarios" />

                    <Textarea maxlength="40000" id="replicas" error={errors.replicas} bind:value={$formPrimeraEvaluacion.replicas} />
                </div>
            </div>
            <div class="py-4 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Enviar comentario</LoadingButton>
                {/if}
            </div>
        </form>
    {/if}

    {#if evaluaciones[1] && evaluaciones[1].habilitado}
        <hr class="mt-10 mb-10 border-black-200" />

        <form on:submit|preventDefault={submitReplicaSegundaEval}>
            <div class="mt-28">
                <h1 class="font-black">Evaluador COD-{evaluaciones[1].id}</h1>

                <!-- <p class="whitespace-pre-line">
                    {evaluaciones[1].comentarios_generales ? evaluaciones[1].comentarios_generales : 'Sin información registrada'}
                </p> -->

                <div class="mt-8 mb-8">
                    <Label labelFor="replicas" value="Comentarios" />

                    <Textarea maxlength="40000" id="replicas" error={errors.replicas} bind:value={$formSegundaEvaluacion.replicas} />
                </div>
            </div>
            <div class="py-4 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Enviar comentario</LoadingButton>
                {/if}
            </div>
        </form>
    {/if}

    {#if evaluaciones[2] && evaluaciones[2].habilitado}
        <hr class="mt-10 mb-10 border-black-200" />

        <form on:submit|preventDefault={submitReplicaTerceraEval}>
            <div class="mt-28">
                <h1 class="font-black">Evaluador COD-{evaluaciones[2].id}</h1>

                <!-- <p class="whitespace-pre-line">
                    {evaluaciones[2].comentarios_generales ? evaluaciones[2].comentarios_generales : 'Sin información registrada'}
                </p> -->

                <div class="mt-8 mb-8">
                    <Label labelFor="replicas" value="Comentarios" />

                    <Textarea maxlength="40000" id="replicas" error={errors.replicas} bind:value={$formTerceraEvaluacion.replicas} />
                </div>
            </div>
            <div class="py-4  flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Enviar comentario</LoadingButton>
                {/if}
            </div>
        </form>
    {/if}
</AuthenticatedLayout>
