<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Switch from '@/Shared/Switch'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyectoPresupuestoEvaluacion
    export let proyecto
    export let proyectoPresupuesto
    export let tiposLicencia
    export let tiposSoftware
    export let opcionesServiciosEdicion
    export let otrasEvaluaciones

    $: $title = proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal ? proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let presupuestoInfo = {
        codigo_uso_presupuestal: '',

        segundo_grupo_presupuestal_id: proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal_id,
        tercer_grupo_presupuestal_id: proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal_id,
        convocatoria_presupuesto_id: proyectoPresupuesto.convocatoria_presupuesto_id,

        descripcion: proyectoPresupuesto.descripcion,
        justificacion: proyectoPresupuesto.justificacion,
        valor_total: proyectoPresupuesto.valor_total,
        numero_items: proyectoPresupuesto.numero_items,
        tipo_software: proyectoPresupuesto.software_info?.tipo_software,
        tipo_licencia: proyectoPresupuesto.software_info?.tipo_licencia,
        fecha_inicio: proyectoPresupuesto.software_info?.fecha_inicio,
        fecha_finalizacion: proyectoPresupuesto.software_info?.fecha_finalizacion,
        servicio_edicion_info: {
            value: opcionesServiciosEdicion.find((item) => item.label == proyectoPresupuesto.servicio_edicion_info?.info)?.value,
            label: opcionesServiciosEdicion.find((item) => item.label == proyectoPresupuesto.servicio_edicion_info?.info)?.label,
        },
        formato_estudio_mercado: '',
    }

    let dialogEvaluacion = proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.requiere_estudio_mercado || presupuestoInfo.codigo_uso_presupuestal == '020202008005096'

    let sending = false
    let form = useForm({
        comentario: proyectoPresupuestoEvaluacion ? proyectoPresupuestoEvaluacion.comentario : '',
        correcto: proyectoPresupuestoEvaluacion?.correcto == undefined || proyectoPresupuestoEvaluacion?.correcto == true ? true : false,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $form.put(route('convocatorias.evaluaciones.presupuesto.update', [convocatoria.id, evaluacion.id, proyectoPresupuesto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let presupuestoSennova
    let prevSegundoGrupoPresupuestal
    $: {
        if (presupuestoInfo.segundo_grupo_presupuestal_id != prevSegundoGrupoPresupuestal) {
            presupuestoSennova = null
        }

        presupuestoInfo.codigo_uso_presupuestal = presupuestoSennova?.codigo
        prevSegundoGrupoPresupuestal = presupuestoInfo.segundo_grupo_presupuestal_id
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkRole(authUser, [11, 5])}
                        <a use:inertia href={route('convocatorias.evaluaciones.presupuesto.index', [convocatoria.id, evaluacion.id])} class="text-indigo-400 hover:text-indigo-600"> Presupuesto </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion}
                </h1>
            </div>
        </div>
    </header>

    <div class="flex">
        <div class="bg-white rounded shadow max-w-3xl flex-1">
            <form on:submit|preventDefault={submit} id="form-proyecto-presupuesto">
                <div class="p-8">
                    <div class="mt-4">
                        <Label labelFor="segundo_grupo_presupuestal_id" value="Homologable 2018" />
                        <DynamicList classes="evaluacion-select" disabled={true} id="segundo_grupo_presupuestal_id" value={presupuestoInfo.segundo_grupo_presupuestal_id} routeWebApi={route('web-api.segundo-grupo-presupuestal', proyecto.linea_programatica)} placeholder="Busque por el homologable 2018" message={errors.segundo_grupo_presupuestal_id} />
                    </div>

                    {#if presupuestoInfo.segundo_grupo_presupuestal_id}
                        <div class="mt-4">
                            <Label labelFor="tercer_grupo_presupuestal_id" value="Rubro 2019" />
                            <DynamicList classes="evaluacion-select" disabled={true} id="tercer_grupo_presupuestal_id" value={presupuestoInfo.tercer_grupo_presupuestal_id} routeWebApi={route('web-api.tercer-grupo-presupuestal', presupuestoInfo.segundo_grupo_presupuestal_id)} placeholder="Busque por el nombre del rubro 2019" message={errors.tercer_grupo_presupuestal_id} />
                        </div>
                    {/if}

                    {#if presupuestoInfo.segundo_grupo_presupuestal_id && presupuestoInfo.tercer_grupo_presupuestal_id}
                        <div class="mt-4">
                            <Label labelFor="convocatoria_presupuesto_id" value="Uso presupuestal" />
                            <DynamicList
                                classes="evaluacion-select"
                                disabled={true}
                                id="convocatoria_presupuesto_id"
                                value={presupuestoInfo.convocatoria_presupuesto_id}
                                routeWebApi={route('web-api.usos-presupuestales', [convocatoria, proyecto.linea_programatica, presupuestoInfo.segundo_grupo_presupuestal_id, presupuestoInfo.tercer_grupo_presupuestal_id])}
                                placeholder="Busque por el nombre del uso presupuestal"
                                message={errors.convocatoria_presupuesto_id}
                                bind:recurso={presupuestoSennova}
                            />
                        </div>

                        {#if presupuestoSennova?.mensaje}
                            <InfoMessage message={presupuestoSennova.mensaje} />
                        {/if}
                    {/if}

                    <hr class="mt-10 mb-10" />

                    <div class="mt-4">
                        <Textarea disabled label="Describa el bien o servicio a adquirir. Sea específico" maxlength="40000" id="descripcion" value={presupuestoInfo.descripcion} />
                    </div>

                    <div class="mt-4">
                        <Textarea disabled label="Justificación de la necesidad: ¿por qué se requiere este producto o servicio?" maxlength="40000" id="justificacion" value={presupuestoInfo.justificacion} />
                    </div>

                    <div class="mt-4">
                        <Input disabled label="Valor total" id="valor_total" type="number" input$min="0" class="mt-1" value={presupuestoInfo.valor_total} error={errors.valor_total} />
                    </div>

                    {#if presupuestoInfo.codigo_uso_presupuestal == '2010100600203101'}
                        <div class="mt-4">
                            <Label class="mb-4" labelFor="tipo_licencia" value="Tipo de licencia" />
                            <select disabled id="tipo_licencia" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 p-4" value={presupuestoInfo.tipo_licencia}>
                                <option value="">Seleccione el tipo de licencia </option>
                                {#each tiposLicencia as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-4">
                            <Label class="mb-4" labelFor="tipo_software" value="Tipo de software" />
                            <select disabled id="tipo_software" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 p-4" value={presupuestoInfo.tipo_software}>
                                <option value="">Seleccione el tipo de software </option>
                                {#each tiposSoftware as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-8">
                            <p>Periodo de uso</p>
                            <div class="mt-4">
                                <Label class="mb-4" labelFor="fecha_inicio" value="Fecha de inicio" />
                                <input disabled label="Fecha de inicio" id="fecha_inicio" type="date" class="mt-1 p-4" value={presupuestoInfo.fecha_inicio} />
                            </div>
                            <div class="mt-4">
                                <Label class="mb-4" labelFor="fecha_finalizacion" value="Fecha de finalización (Cuando aplique)" />
                                <input disabled label="Fecha de finalización" id="fecha_finalizacion" type="date" class="mt-1 p-4" value={presupuestoInfo.fecha_finalizacion} />
                            </div>
                        </div>
                    {:else if presupuestoInfo.codigo_uso_presupuestal == '2020200800901'}
                        <div class="mt-4">
                            <Label class="mb-4" labelFor="servicio_edicion_info" value="Nodo editorial" />
                            <Select disabled={true} id="servicio_edicion_info" items={opcionesServiciosEdicion} bind:selectedValue={presupuestoInfo.servicio_edicion_info} error={errors.servicio_edicion_info} autocomplete="off" placeholder="Seleccione una opción" />
                        </div>
                    {/if}

                    {#if proyectoPresupuesto.actividades?.length > 0}
                        <h6 class="mt-20 mb-12 text-2xl">Actividades</h6>
                        <div class="bg-white rounded shadow overflow-hidden">
                            <div class="p-4" />

                            <div class="p-2">
                                <ul class="list-disc p-4">
                                    {#each proyectoPresupuesto.actividades as { id, descripcion }, i}
                                        <li class="first-letter-uppercase mb-4">{descripcion}</li>
                                    {/each}
                                </ul>
                            </div>
                        </div>
                    {/if}
                </div>

                <InfoMessage>
                    <div class="mt-4">
                        {#if checkRole(authUser, [5]) && evaluacion.evaluacion_final}
                            {#each otrasEvaluaciones as evaluacion}
                                <div class="mb-8">
                                    <h4>Evaluador(a): <span class="font-black capitalize">{evaluacion.evaluacion.evaluador.nombre}</span></h4>
                                    {evaluacion.comentario ? evaluacion.comentario : 'Estado: El evaluador(a) da cumplimiento al rubro presupuestal'}
                                    <br />
                                </div>
                            {/each}
                        {/if}
                        <p>¿El rubro presupuestal es correcto? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$form.correcto} />
                        {#if $form.correcto == false}
                            <Textarea disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="comentario" bind:value={$form.comentario} error={errors.comentario} required />
                        {/if}
                    </div>
                </InfoMessage>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                        {#if presupuestoInfo.convocatoria_presupuesto_id != '' || presupuestoInfo.convocatoria_presupuesto_id != ''}
                            <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Evaluar</LoadingButton>
                        {/if}
                    {/if}
                </div>
            </form>
        </div>
    </div>

    <Dialog bind:open={dialogEvaluacion} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">Archivos</div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Por favor descargue el anexo y los soportes/cotizaciones</h1>
                <a target="_blank" class="flex text-white underline mb-4" download href={proyectoPresupuesto.formato_estudio_mercado}>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Descargar estudio de mercado
                </a>
                <hr class="mb-4" />
                <div>
                    {#each proyectoPresupuesto.soportes_estudio_mercado as soporte}
                        {soporte.empresa}
                        <a target="_blank" class="flex text-white underline mb-4" download href={soporte.soporte}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Descargar soporte/cotización
                        </a>
                        <hr class="mb-4" />
                    {/each}
                </div>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogEvaluacion = false)} variant={null}>Aceptar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
