<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, checkPermissionByUser } from '@/Utils'
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
    export let versiones
    export let problemaCentral
    export let efectosDirectos
    export let causasIndirectas
    export let causasDirectas
    export let efectosIndirectos
    export let objetivoGeneral
    export let resultados
    export let objetivosEspecificos
    export let actividades
    export let impactos
    // export let actividadesPresupuesto
    export let resultadoProducto
    export let analisisRiesgo
    export let anexos
    export let generalidades
    export let metodologia
    export let propuestaSostenibilidad
    export let productosActividades
    export let articulacionSennova
    export let soportesEstudioMercado
    export let estudiosMercadoArchivo
    export let edt
    export let maxValorRoles
    export let maxValorTAPresupuesto

    $: $title = 'Finalizar proyecto'

    let dialogFinalizarProyecto = errors.password != undefined ? true : false
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
        if (isSuperAdmin || checkPermissionByUser(authUser, [1, 5, 8, 11, 17]) || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])) {
            $form.put(route('convocatorias.proyectos.finish', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => {
                    sending = false
                    $form.password = ''

                    if (!errors.password) {
                        dialogFinalizarProyecto = false
                    }
                },
                preserveScroll: true,
            })
            $form.password = ''
        }
    }

    function sendProject() {
        if (isSuperAdmin || (checkRole(authUser, [4, 21]) && proyecto.finalizado == true)) {
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
        if (isSuperAdmin || (checkRole(authUser, [4, 21]) && proyecto.finalizado == true)) {
            $comentarioForm.put(route('convocatorias.proyectos.return-project', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => {
                    sending = false
                },
                preserveScroll: true,
            })
        }
    }

    let minimoEmpresas = 0
    if (proyecto.codigo_linea_programatica == 68) {
        if (proyecto.precio_proyecto >= 199999999) {
            minimoEmpresas = 16
        } else if (proyecto.precio_proyecto >= 200000000 && proyecto.precio_proyecto <= 499999999) {
            minimoEmpresas = 35
        } else if (proyecto.precio_proyecto >= 500000000) {
            minimoEmpresas = 50
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <div class="mt-20">
        {#if proyecto.codigo_linea_programatica == 68}
            <InfoMessage class="mb-2">
                <h1><strong>Información importante</strong></h1>
                <p>De acuerdo con el presupuesto solicitado el proyecto se compromete a cumplir adicionalmente las siguientes metas, las cuales están alineadas al cumplimento del Plan Nacional de Desarrollo y de los CONPES:</p>
                <ul>
                    {#if minimoEmpresas > 0}
                        <li>- Empresas atendidas por producción de centro: mínimo {minimoEmpresas}</li>
                    {/if}
                    <li>- Aprendices atendidos (visitas presenciales/virtuales, transferencia de conocimiento): 50 mínimo</li>
                    <li>- Emprendedores y empresarios atendidos (visitas presenciales/virtuales, transferencia de conocimiento): 50 mínimo</li>
                </ul>
                <p>Al momento de <strong>finalizar el proyecto</strong> está aceptando los compromisos establecidos para el cumplimiento de las metas la vigencia 2022</p>
            </InfoMessage>
        {/if}

        {#if convocatoria.tipo_convocatoria == 1 || convocatoria.tipo_convocatoria == 3}
            <InfoMessage class="mb-2">
                <h1 class="text-3xl"><strong>¡Tenga en cuenta!</strong></h1>
                <p>
                    Esta es una opción para notificar al Dinamizador que ha finalizado de diligenciar/subsanar el proyecto y de esta manera haga una revisión con el objetivo de confirmar o hacer un comentario de ajuste. Si el Dinamizador por alguna razón NO confirma el proyecto
                    <strong>la plataforma lo hará automáticamente al finalizar cada fase ya sea de formulación o de subsanación.</strong>
                </p>
            </InfoMessage>
        {/if}
        <hr class="mt-10 mb-10" />
        {#if (isSuperAdmin && proyecto.finalizado == true && proyecto.habilitado_para_evaluar == false) || (checkRole(authUser, [4, 21]) && proyecto.finalizado == true && proyecto.habilitado_para_evaluar == false)}
            <InfoMessage>
                <p>¿El proyecto está completo?</p>
                <Switch bind:checked={proyectoCompleto} />
                {#if proyectoCompleto}
                    <br />
                    <Button on:click={(event) => (sendProjectDialogOpen = true)} variant="raised" class="mt-10">Confirmar proyecto</Button>
                    <br />
                    <small class="mb-2 mt-8">Si desea confirmar el proyecto de clic en <strong>Confirmar proyecto</strong> y a continuación, escriba la contraseña de su usuario.</small>
                {:else if proyectoCompleto == false}
                    <form on:submit|preventDefault={submitComentario}>
                        <fieldset disabled={isSuperAdmin || (checkRole(authUser, [4, 21]) && proyecto.finalizado == true) ? undefined : true}>
                            <div class="mt-8">
                                <p class="mb-2">Si considera que el proyecto está incompleto por favor haga un comentario al proponente detallando que información o ítems debe completar.</p>
                                <Textarea label="Comentario" maxlength="40000" id="comentario" error={errors.comentario} bind:value={$comentarioForm.comentario} required />
                            </div>
                        </fieldset>
                        <div class="mt-10 flex items-center">
                            {#if isSuperAdmin || (checkRole(authUser, [4, 21]) && proyecto.finalizado == true)}
                                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Enviar comentario</LoadingButton>
                            {/if}
                        </div>
                    </form>
                {/if}
            </InfoMessage>
        {:else if isSuperAdmin || checkPermissionByUser(authUser, [1, 5, 8, 11, 17]) || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])}
            {#if proyecto.finalizado == false && proyecto.modificable == true && generalidades && problemaCentral && efectosDirectos && efectosIndirectos && causasDirectas && causasIndirectas && objetivoGeneral && resultados && objetivosEspecificos && actividades && impactos && metodologia && propuestaSostenibilidad && productosActividades && resultadoProducto && analisisRiesgo && anexos && soportesEstudioMercado && estudiosMercadoArchivo}
                {#if convocatoria.tipo_convocatoria == 1 || convocatoria.tipo_convocatoria == 3}
                    <InfoMessage class="mb-2" message="Si desea finalizar el proyecto de clic en <strong>Finalizar proyecto</strong> y a continuación, escriba la contraseña de su usuario. Se le notificará al dinamizador SENNOVA de su centro de formación para que haga la respectiva revisión y radicación del proyecto." />
                    <Button on:click={(event) => (dialogFinalizarProyecto = true)} variant="raised">Finalizar proyecto</Button>
                {:else}
                    <InfoMessage class="mb-2" message="El proyecto está completo." />
                {/if}
            {:else if proyecto.finalizado == false}
                <InfoMessage class="mb-2" alertMsg={true}>
                    <p><strong>La información del proyecto está incompleta. Para poder finalizar el proyecto debe completar los siguientes ítems:</strong></p>
                    <ul class="list-disc p-4">
                        {#if !generalidades}
                            <li>Generalidades</li>
                        {/if}
                        {#if !articulacionSennova && proyecto.codigo_linea_programatica == 70}
                            <li>Articulación SENNOVA</li>
                        {/if}
                        {#if !problemaCentral}
                            <li>Problema central</li>
                        {/if}
                        {#if !efectosDirectos}
                            <li>Efectos directos</li>
                        {/if}
                        {#if !efectosIndirectos}
                            <li>Efectos indirectos</li>
                        {/if}
                        {#if !causasDirectas}
                            <li>Causas directas</li>
                        {/if}
                        {#if !causasIndirectas}
                            <li>Causas indirectas</li>
                        {/if}
                        {#if !objetivoGeneral}
                            <li>Objetivo general</li>
                        {/if}
                        {#if !resultados}
                            <li>Resultados (árbol de objetivos)</li>
                        {/if}
                        {#if !objetivosEspecificos}
                            <li>Objetivos específicos (árbol de objetivos)</li>
                        {/if}
                        {#if !actividades}
                            <li>Actividades (árbol de objetivos)</li>
                        {/if}
                        {#if !impactos}
                            <li>Impactos</li>
                        {/if}
                        {#if !metodologia}
                            <li>Metodología (Metodología y actividades)</li>
                        {/if}
                        {#if !propuestaSostenibilidad}
                            <li>Propuesta de sostenibilidad (Cadena de valor)</li>
                        {/if}
                        {#if proyecto.codigo_linea_programatica == 70}
                            {#if !edt}
                                <li>Tiene un rubro presupuestal 'Servicios de organización y asistencia de convenciones y ferias' y le debe asociar al menos un EDT</li>
                            {/if}
                            {#if !maxValorRoles}
                                <li>El valor máximo de los roles es: ${new Intl.NumberFormat('de-DE').format(proyecto.max_valor_roles)}</li>
                            {/if}
                            {#if !maxValorTAPresupuesto}
                                <li>El valor máximo del presupuesto total es: ${new Intl.NumberFormat('de-DE').format(proyecto.max_valor_presupuesto)}</li>
                            {/if}
                        {/if}
                        {#if !productosActividades}
                            <li>Hay productos sin actividades relacionadas</li>
                        {/if}
                        {#if !resultadoProducto}
                            <li>Hay resultados sin productos relacionados</li>
                        {/if}
                        {#if !analisisRiesgo}
                            <li>Faltan análisis de riesgos</li>
                        {/if}
                        {#if !anexos}
                            <li>No se han cargado todos los anexos</li>
                        {/if}
                        {#if !soportesEstudioMercado}
                            <li>Hay estudios de mercado con menos de dos soportes</li>
                        {/if}
                        {#if !estudiosMercadoArchivo}
                            <li>Hay rubros presupuestales que no tienen el estudio de mercado cargado</li>
                        {/if}
                    </ul>
                </InfoMessage>
            {/if}
        {/if}
    </div>
    <hr class="mt-10 mb-10" />
    {#if proyecto.finalizado == true && proyecto.habilitado_para_evaluar == false && !checkRole(authUser, [1, 4])}
        <InfoMessage class="mb-2" message="El proyecto se ha finalizado con éxito. Espere la respuesta del dinamizador SENNOVA." />
    {:else if proyecto.habilitado_para_evaluar == true}
        <InfoMessage class="mb-2" message="El dinamizador SENNOVA ha confirmado el proyecto." />
    {/if}
    <hr class="mt-10 mb-10" />
    <div>
        <InfoMessage>
            <h1><strong>Historial de acciones</strong></h1>
            {#if proyecto.logs}
                <ul>
                    {#each proyecto.logs as log}
                        <li>{log.created_at} - {JSON.parse(log.data).subject}</li>
                    {/each}
                </ul>
            {:else}
                <p>No se ha generado un historial aún</p>
            {/if}
        </InfoMessage>
    </div>
    <hr class="mt-10 mb-10" />
    <div>
        <InfoMessage>
            <h1><strong>Versiones del proyecto</strong></h1>
            El PDF se generará una vez finalice la fase de formulación.
            {#if versiones}
                <ul>
                    {#each versiones as version}
                        <li>
                            {version.version}.pdf -
                            {#if version.estado == 1}
                                <a href={route('convocatorias.proyectos.version', [convocatoria.id, proyecto.id, version.version])}>Descargar</a>
                            {:else}
                                Generando una nueva versión, regrese pronto.
                            {/if}
                        </li>
                    {/each}
                </ul>
            {:else}
                <p>No se ha generado un historial aún</p>
            {/if}
        </InfoMessage>
    </div>

    <Dialog bind:open={dialogFinalizarProyecto}>
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
                <Button on:click={(event) => (dialogFinalizarProyecto = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="finalizar-proyecto">Finalizar proyecto</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={sendProjectDialogOpen}>
        <div slot="titulo" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Confirmar proyecto
        </div>
        <div slot="content">
            <InfoMessage class="mb-2" message="¿Está seguro (a) que desea confirmar el proyecto del proyecto?<br />Una vez confirmado el proyecto no se podrá modificar." />

            <form on:submit|preventDefault={sendProject} id="confirmar-proyecto" class="mt-10 mb-28" on:load={($form.password = '')}>
                <Label labelFor="password" value="Ingrese su contraseña para confirmar el proyecto" class="mb-4" />
                <Password id="password" class="w-full" bind:value={$form.password} error={errors.password} required autocomplete="current-password" />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (sendProjectDialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="confirmar-proyecto">Confirmar proyecto</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
