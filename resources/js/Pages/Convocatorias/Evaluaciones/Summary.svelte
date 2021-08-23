<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import InfoMessage from '@/Shared/InfoMessage'
    import Label from '@/Shared/Label'
    import Password from '@/Shared/Password'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyecto
    export let versiones

    $: $title = 'Finalizar evaluación'

    let finishEvaluacionDialogOpen = errors.password != undefined ? true : false
    let sending = false

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        password: '',
    })

    function finishEvaluacion() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false && evaluacion.habilitado == true)) {
            $form.put(route('convocatorias.evaluaciones.finish', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => {
                    sending = false
                    $form.password = ''

                    if (!errors.password) {
                        finishEvaluacionDialogOpen = false
                    }
                },
                preserveScroll: true,
            })
            $form.password = ''
        }
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    {#if evaluacion.finalizado == true}
        <InfoMessage class="mt-20 mb-2" message="La evaluación ha sido finalizada con éxito." />
    {/if}
    <div class="mt-20">
        <InfoMessage class="mb-2">
            <h1 class="text-2xl font-black text-center">Evaluación</h1>
            <div class="flex">
                <ul class="list-disc flex-1 pl-4">
                    <li><strong>Título:</strong> {evaluacion.titulo_puntaje ? evaluacion.titulo_puntaje : 0}</li>
                    {#if proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
                        <li><strong>Video:</strong> {evaluacion.video_puntaje ? evaluacion.video_puntaje : 0}</li>
                    {/if}
                    <li><strong>Resumen:</strong> {evaluacion.resumen_puntaje ? evaluacion.resumen_puntaje : 0}</li>
                    <li><strong>Problema central:</strong> {evaluacion.problema_central_puntaje ? evaluacion.problema_central_puntaje : 0}</li>
                    <li><strong>Objetivos:</strong> {evaluacion.objetivos_puntaje ? evaluacion.objetivos_puntaje : 0}</li>
                    <li><strong>Metodología:</strong> {evaluacion.metodologia_puntaje ? evaluacion.metodologia_puntaje : 0}</li>
                    {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
                        <li><strong>Entidad aliada:</strong> {evaluacion.entidad_aliada_puntaje ? evaluacion.entidad_aliada_puntaje : 0}</li>
                    {/if}
                    <li><strong>Resultados:</strong> {evaluacion.resultados_puntaje ? evaluacion.resultados_puntaje : 0}</li>
                    <li><strong>Productos:</strong> {evaluacion.productos_puntaje ? evaluacion.productos_puntaje : 0}</li>
                    <li><strong>Cadena de valor:</strong> {evaluacion.cadena_valor_puntaje ? evaluacion.cadena_valor_puntaje : 0}</li>
                    <li><strong>Análisis de riesgos:</strong> {evaluacion.analisis_riesgos_puntaje ? evaluacion.analisis_riesgos_puntaje : 0}</li>
                    <li><strong>Ortografía:</strong> {evaluacion.ortografia_puntaje ? evaluacion.ortografia_puntaje : 0}</li>
                    <li><strong>Redacción:</strong> {evaluacion.redaccion_puntaje ? evaluacion.redaccion_puntaje : 0}</li>
                    <li><strong>Normas APA:</strong> {evaluacion.normas_apa_puntaje ? evaluacion.normas_apa_puntaje : 0}</li>
                </ul>
                <div class="flex flex-1 items-center justify-center border-l-2 border-indigo-400 pl-10">
                    <h1 class="text-2xl">
                        <strong>Puntaje total:</strong>
                        {evaluacion.total_evaluacion}
                    </h1>
                </div>
            </div>
        </InfoMessage>

        {#if evaluacion.validar_evaluacion.length > 0}
            <InfoMessage class="mb-2" alertMsg={true}>
                <p><strong>Aún faltan ítems por evaluar. Para poder finalizar la evaluación debe completar los siguientes ítems:</strong></p>
                <ul class="list-disc p-4">
                    {#each evaluacion.validar_evaluacion as item}
                        <li>{item}</li>
                    {/each}
                </ul>
            </InfoMessage>
        {:else if evaluacion.finalizado == false && evaluacion.validar_evaluacion.length == 0}
            <InfoMessage class="mb-2" message="Si desea finalizar la evaluación de clic en <strong>Finalizar evaluación</strong> y a continuación, escriba la contraseña de su usuario. Se le notificará al dinamizador SENNOVA de su centro de formación para que haga la respectiva revisión y radicación del proyecto." />
            <Button on:click={(event) => (finishEvaluacionDialogOpen = true)} variant="raised">Finalizar evaluación</Button>
        {/if}
    </div>
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
            {#if versiones}
                <ul>
                    {#each versiones as version}
                        <li>{version.version}.pdf - 
                            {#if version.estado==1}
                            <a href="{route('convocatorias.proyectos.version', [convocatoria.id, proyecto.id, version.version])}">Descargar</a>
                            {:else}
                            Generando, regrese pronto.
                            {/if}
                        </li>
                    {/each}
                </ul>
            {:else}
                <p>No se ha generado un historial aún</p>
            {/if}
        </InfoMessage>
    </div>

    <Dialog bind:open={finishEvaluacionDialogOpen}>
        <div slot="titulo" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Finalizar evaluación
        </div>
        <div slot="content">
            <InfoMessage class="mb-2" message="¿Está seguro (a) que desea finalizar la evaluación?<br />Una vez finalizada no se podrá modificar." />

            <form on:submit|preventDefault={finishEvaluacion} id="finalizar-evaluacion" class="mt-10 mb-28" on:load={($form.password = '')}>
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea finalizar esta evaluación" class="mb-4" />
                <Password id="password" class="w-full" bind:value={$form.password} error={errors.password} required autocomplete="current-password" />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (finishEvaluacionDialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="finalizar-evaluacion">Finalizar evaluación</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
