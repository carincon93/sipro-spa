<script>
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Button from '@/Shared/Button'
    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import PercentageProgress from '@/Shared/PercentageProgress'

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
        archivo: proyectoAnexo.find((item) => item.anexo_id == anexo.id) ? proyectoAnexo.find((item) => item.anexo_id == anexo.id).archivo : '',
        anexo_id: anexo.id,
    })

    function submit() {
        if ((isSuperAdmin && !sending) || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19]) && proyecto.modificable == true && !sending)) {
            $form.post(route('convocatorias.proyectos.proyecto-anexos.store', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let proyectoAnexoId
    let fechaActualizacion
    $: if (proyectoAnexo) {
        proyectoAnexoId = proyectoAnexo.find((item) => item.anexo_id == anexo.id) ? proyectoAnexo.find((item) => item.anexo_id == anexo.id).id : null
        fechaActualizacion = proyectoAnexo.find((item) => item.anexo_id == anexo.id) ? proyectoAnexo.find((item) => item.anexo_id == anexo.id).fecha_actualizacion : null
    }
</script>

<form on:submit|preventDefault={submit} class="mt-4 p-4">
    {#if proyectoAnexoId}
        <a target="_blank" class="text-green-600 underline mb-4 flex" download href={proyectoAnexo.find((item) => item.anexo_id == anexo.id).archivo}>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            <span>
                Archivo cargado correctamente. Descargar dando clic en este enlace. (<strong>Fecha de carga del archivo: </strong>{fechaActualizacion}).
            </span>
        </a>
    {/if}
    <fieldset disabled={(isSuperAdmin && !sending) || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19]) && proyecto.modificable == true && anexo.habilitado == true) ? undefined : true}>
        <div class="mt-20">
            <Label value="Url del archivo/anexo" />
            <Input type="url" class="mt-1" error={errors?.archivo} placeholder="Url https://www.google.com.co" bind:value={$form.archivo} required />
        </div>
        <div>
            <Button loading={sending} class="w-full mt-4" type="submit">
                Cargar {anexo.nombre}
            </Button>
        </div>
        {#if $form.progress}
            <PercentageProgress percentage={$form.progress.percentage} />
        {/if}
    </fieldset>
</form>
