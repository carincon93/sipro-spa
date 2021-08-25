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

    let sending = false
    let form = useForm({
        comentario: proyectoPresupuestoEvaluacion ? proyectoPresupuestoEvaluacion.comentario : '',
        correcto: proyectoPresupuestoEvaluacion?.correcto,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true)) {
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
                    {#if isSuperAdmin || checkRole(authUser, [11])}
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
                <fieldset class="p-8" disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                    <div class="mt-4">
                        <Label labelFor="segundo_grupo_presupuestal_id" value="Homologable 2018" />
                        <DynamicList disabled={true} id="segundo_grupo_presupuestal_id" value={presupuestoInfo.segundo_grupo_presupuestal_id} routeWebApi={route('web-api.segundo-grupo-presupuestal', proyecto.linea_programatica)} placeholder="Busque por el homologable 2018" message={errors.segundo_grupo_presupuestal_id} />
                    </div>

                    {#if presupuestoInfo.segundo_grupo_presupuestal_id}
                        <div class="mt-4">
                            <Label labelFor="tercer_grupo_presupuestal_id" value="Rubro 2019" />
                            <DynamicList disabled={true} id="tercer_grupo_presupuestal_id" value={presupuestoInfo.tercer_grupo_presupuestal_id} routeWebApi={route('web-api.tercer-grupo-presupuestal', presupuestoInfo.segundo_grupo_presupuestal_id)} placeholder="Busque por el nombre del rubro 2019" message={errors.tercer_grupo_presupuestal_id} />
                        </div>
                    {/if}

                    {#if presupuestoInfo.segundo_grupo_presupuestal_id && presupuestoInfo.tercer_grupo_presupuestal_id}
                        <div class="mt-4">
                            <Label labelFor="convocatoria_presupuesto_id" value="Uso presupuestal" />
                            <DynamicList
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
                </fieldset>

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿El rubro presupuestal requiere de una recomendación?</p>
                        <Switch disabled={evaluacion.finalizado && evaluacion.habilitado ? true : undefined} bind:checked={$form.correcto} />
                        {#if $form.correcto}
                            <Textarea disabled={evaluacion.finalizado && evaluacion.habilitado ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="comentario" bind:value={$form.comentario} error={errors.comentario} required />
                        {/if}
                    </div>
                </InfoMessage>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false)}
                        {#if presupuestoInfo.convocatoria_presupuesto_id != '' || presupuestoInfo.convocatoria_presupuesto_id != ''}
                            <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Evaluar</LoadingButton>
                        {/if}
                    {/if}
                </div>
            </form>
        </div>
        {#if proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.requiere_estudio_mercado || presupuestoInfo.codigo_uso_presupuestal == '020202008005096'}
            <div class="px-4">
                <h1 class="mb-4 text-2xl">Enlaces de interés</h1>
                <ul>
                    <li>
                        <a class="bg-indigo-100 hover:bg-indigo-200 mb-4 px-6 py-2 rounded-3xl text-center text-indigo-400" use:inertia href={route('convocatorias.evaluaciones.presupuesto.soportes', [convocatoria.id, evaluacion.id, proyectoPresupuesto.id])}>Soportes / Cotizaciones</a>
                    </li>
                </ul>
            </div>
        {/if}
    </div>

    {#if proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.requiere_estudio_mercado || presupuestoInfo.codigo_uso_presupuestal == '020202008005096'}
        <h1 class="text-2xl mt-10">Archivos</h1>
        <div class="mt-4 bg-white rounded shadow">
            <table class="w-full whitespace-no-wrap table-fixed data-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre archivo</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Archivo</th>
                    </tr>
                </thead>

                <tbody>
                    {#if !proyectoPresupuesto.formato_estudio_mercado}
                        <tr>
                            <td class="border-t px-6 py-4" colspan="2">Sin información registrada</td>
                        </tr>
                    {:else}
                        <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t px-6 pt-6 pb-4"> Estudio de mercado </td>

                            <td class="border-t px-6 pt-6 pb-4">
                                <a target="_blank" class="flex text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.presupuesto.download', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar estudio de mercado
                                </a>
                            </td>
                        </tr>
                    {/if}
                </tbody>
            </table>
        </div>
    {/if}
</AuthenticatedLayout>
