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
    let form = useForm({
        comentarios_generales: evaluacion.comentarios_generales,
    })

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])) {
            $form.post(route('convocatorias.proyectos.update-comentarios-generales', [convocatoria.id, evaluacion.id]), {
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

    {#each evaluaciones as evaluacion}
        <form on:submit|preventDefault={submit}>
            <div class="mt-28">
                <InfoMessage>Este es un espacio para que realice comentarios generales del proyecto. También podrá responder a las replicas del formulador una vez finalice la fase de subsanación.</InfoMessage>
                <h1>Comentarios del evaluador</h1>

                <p class="whitespace-pre-line">
                    {evaluacion.comentarios_generales ? evaluacion.comentarios_generales : 'Sin información registrada'}
                </p>

                <hr class="mt-10 mb-10 border-black-200" />

                <div class="mt-8 mb-8">
                    <Label labelFor="replicas" value="Comentarios" />

                    <Textarea maxlength="40000" id="replicas" error={errors.replicas} bind:value={$form.replicas} />
                </div>
            </div>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                {/if}
            </div>
        </form>
    {/each}
</AuthenticatedLayout>
