<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Stepper from '@/Shared/Stepper'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import InfoMessage from '@/Shared/InfoMessage'
    import Label from '@/Shared/Label'
    import Password from '@/Shared/Password'
    import Textarea from '@/Shared/Textarea'
    import LoadingButton from '@/Shared/LoadingButton'
    import Switch from '@/Shared/Switch'

    export let errors
    export let convocatoria
    export let proyecto

    $: $title = 'Finalizar proyecto'

    let finishProjectDialogOpen = errors.password != undefined ? true : false
    let sendProjectDialogOpen = errors.password != undefined ? true : false
    let sending = false

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let proyectoCompleto = false

    let form = useForm({
        password: '',
    })

    function finishProject() {
        if (isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])) {
            $form.put(route('convocatorias.proyectos.finish', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => {
                    sending = false
                    $form.password = ''

                    if (!errors.password) {
                        finishProjectDialogOpen = false
                    }
                },
                preserveScroll: true,
            })
            $form.password = ''
        }
    }

    function sendProject() {
        if (isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])) {
            $form.put(route('convocatorias.proyectos.send', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => {
                    sending = false
                    $form.password = ''

                    if (!errors.password) {
                        sendProjectDialogOpen = false
                    }
                },
                preserveScroll: true,
            })
            $form.password = ''
        }
    }

    let comentarioForm = useForm({
        comentario: '',
    })
    function submitComentario() {
        if (isSuperAdmin || checkRole(authUser, [4])) {
            $comentarioForm.put(route('convocatorias.proyectos.return-project', [convocatoria.id, proyecto.id]), {
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

    <div class="mt-20">
        {#if proyecto.finalizado == true && !checkRole(authUser, [4])}
            <InfoMessage class="mb-2" message="El proyecto se ha finalizado con éxito. Espere la respuesta del dinamizador SENNOVA." />
        {:else if proyecto.radicado == true}
            <InfoMessage class="mb-2" message="El proyecto se ha radicado con éxito." />
        {/if}

        {#if (isSuperAdmin && proyecto.finalizado == true && proyecto.radicado == false) || (checkRole(authUser, [4]) && proyecto.finalizado == true && proyecto.radicado == false)}
            <InfoMessage>
                <p>¿El proyecto está completo?</p>
                <Switch bind:checked={proyectoCompleto} />
                {#if proyectoCompleto}
                    <p class="mb-2 mt-8">Si desea radicar el proyecto de clic en <strong>Radicar proyecto</strong> y a continuación, escriba la contraseña de su usuario.</p>
                    <Button on:click={(event) => (sendProjectDialogOpen = true)} variant="raised">Radicar proyecto</Button>
                {:else if proyectoCompleto == false}
                    <form on:submit|preventDefault={submitComentario}>
                        <fieldset disabled={isSuperAdmin || checkRole(authUser, [4]) ? undefined : true}>
                            <div class="mt-8">
                                <p class="mb-2">Si considera que el proyecto está incompleto por favor haga un comentario al proponente detallando que información o ítems debe completar.</p>
                                <Textarea label="Comentario" maxlength="40000" id="comentario" error={errors.comentario} bind:value={$comentarioForm.comentario} required />
                            </div>
                        </fieldset>
                        <div class="mt-10 flex items-center">
                            {#if isSuperAdmin || checkRole(authUser, [4])}
                                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Enviar comentario</LoadingButton>
                            {/if}
                        </div>
                    </form>
                {/if}
            </InfoMessage>
        {:else if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])}
            {#if proyecto.finalizado == false}
                <InfoMessage class="mb-2" message="Si desea finalizar el proyecto de clic en <strong>Finalizar proyecto</strong> y a continuación, escriba la contraseña de su usuario. Se le notificará al dinamizador SENNOVA de su centro de formación para que haga la respectiva revisión y radique el proyecto." />
                <Button on:click={(event) => (finishProjectDialogOpen = true)} variant="raised">Finalizar proyecto</Button>
            {/if}
        {/if}
    </div>
    <hr class="mt-10 mb-10" />
    <div>
        <InfoMessage>
            <h1><strong>Historial de acciones</strong></h1>
            <ul>
                {#each proyecto.logs as log}
                    <li>{log.created_at} - {JSON.parse(log.data).subject}</li>
                {/each}
                {#if proyecto.logs.length == 0}
                    <li>No se ha generado un historial aún</li>
                {/if}
            </ul>
        </InfoMessage>
    </div>

    <Dialog bind:open={finishProjectDialogOpen}>
        <div slot="titulo" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Finalizar proyecto
        </div>
        <div slot="content">
            <InfoMessage class="mb-2" message="¿Está seguro (a) que desea finalizar el proyecto?<br />Una vez finalizado el proyecto no se podrá modificar." />

            <form on:submit|preventDefault={finishProject} id="finalizar-proyecto" class="mt-10 mb-28" on:load={($form.password = '')}>
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea finalizar este proyecto" class="mb-4" />
                <Password id="password" class="w-full" bind:value={$form.password} error={errors.password} required autocomplete="current-password" />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (finishProjectDialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="finalizar-proyecto">Finalizar proyecto</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={sendProjectDialogOpen}>
        <div slot="titulo" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Radicar proyecto
        </div>
        <div slot="content">
            <InfoMessage class="mb-2" message="¿Está seguro (a) que desea radicar el proyecto?<br />Una vez radicado el proyecto no se podrá modificar." />

            <form on:submit|preventDefault={sendProject} id="radicar-proyecto" class="mt-10 mb-28" on:load={($form.password = '')}>
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea radicar este proyecto" class="mb-4" />
                <Password id="password" class="w-full" bind:value={$form.password} error={errors.password} required autocomplete="current-password" />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (sendProjectDialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="radicar-proyecto">Radicar proyecto</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
