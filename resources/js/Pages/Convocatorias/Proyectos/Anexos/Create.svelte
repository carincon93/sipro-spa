<script>
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Shared/LoadingButton'
    import File from '@/Shared/File'
    import PercentageProgress from '@/Shared/PercentageProgress'
    import { onMount } from 'svelte'

    export let errors
    export let convocatoria
    export let proyecto
    export let anexo
    export let proyectoAnexo
    export let sending = false

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        archivo: null,
        anexo_id: anexo.id,
    })

    function submit() {
        if ((isSuperAdmin && !sending) || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10])) {
            $form.post(route('convocatorias.proyectos.proyecto-anexos.store', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }

    let proyectoAnexoId
    onMount(() => {
        if (proyectoAnexo.data) {
            proyectoAnexoId = proyectoAnexo.data.find((item) => item.anexo_id == anexo.id) ? proyectoAnexo.data.find((item) => item.anexo_id == anexo.id).id : null
        }
    })
</script>

<form on:submit|preventDefault={submit} class="mt-4 p-4">
    {#if proyectoAnexoId}
        <a target="_blank" class="text-indigo-400 underline inline-block mb-4 flex" download href={route('convocatorias.proyectos.proyecto-anexos.download', [convocatoria.id, proyecto.id, proyectoAnexoId])}>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            {anexo.nombre}
        </a>
    {/if}
    <fieldset disabled={(isSuperAdmin && !sending) || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10]) ? undefined : true}>
        <div>
            <File type="file" accept="application/pdf" class="mt-1" bind:value={$form.archivo} error={errors?.archivo} required />
        </div>
        <div>
            <LoadingButton loading={sending} class="w-full mt-4" type="submit">
                Cargar {anexo.nombre}
            </LoadingButton>
        </div>
        {#if $form.progress}
            <PercentageProgress percentage={$form.progress.percentage} />
        {/if}
    </fieldset>
</form>
