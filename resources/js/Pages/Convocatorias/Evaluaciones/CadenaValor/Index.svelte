<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { onMount } from 'svelte'
    import { _ } from 'svelte-i18n'
    import { checkRole } from '@/Utils'

    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import InfoMessage from '@/Shared/InfoMessage'
    import Label from '@/Shared/Label'
    import Textarea from '@/Shared/Textarea'
    import LoadingButton from '@/Shared/LoadingButton'
    import Input from '@/Shared/Input'
    import Switch from '@/Shared/Switch'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyecto
    export let productos
    export let objetivos
    export let impactos

    $title = 'Cadena de valor'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false

    let propuestaSostenibilidadInfo = {
        propuesta_sostenibilidad: proyecto.propuesta_sostenibilidad,
        propuesta_sostenibilidad_social: proyecto.propuesta_sostenibilidad_social,
        propuesta_sostenibilidad_ambiental: proyecto.propuesta_sostenibilidad_ambiental,
        propuesta_sostenibilidad_financiera: proyecto.propuesta_sostenibilidad_financiera,
    }

    onMount(() => {
        google.charts.setOnLoadCallback(drawChart)
    })

    function drawChart() {
        var data = new google.visualization.DataTable()
        data.addColumn('string', 'Name')
        data.addColumn('string', 'Manager')
        data.addColumn('string', 'ToolTip')

        var options = {
            nodeClass: 'bg-indigo-500 text-white shadow',
            selectedNodeClass: 'bg-indigo-700',
            allowHtml: true,
            size: 'small',
        }

        // For each orgchart box, provide the name, manager, and tooltip to show.

        // [[{ v: 'Key', f: 'Key<div>{Descritpion}</div>' }, 'Foreign key', 'Tooltip']]

        data.addRows([[{ v: 'Objetivo general', f: '<strong>Objetivo general</strong><div>' + objetivos['Objetivo general'] + '</div>' }, '', 'Objetivo general']])
        data.addRows([[{ v: 'Primer objetivo específico', f: '<strong>Primer objetivo específico</strong><div>' + objetivos['Primer objetivo específico'] + '</div>' }, 'Objetivo general', 'Primer objetivo específico']])
        data.addRows([[{ v: 'Segundo objetivo específico', f: '<strong>Segundo objetivo específico</strong><div>' + objetivos['Segundo objetivo específico'] + '</div>' }, 'Objetivo general', 'Segundo objetivo específico']])
        data.addRows([[{ v: 'Tercer objetivo específico', f: '<strong>Tercer objetivo específico</strong><div>' + objetivos['Tercer objetivo específico'] + '</div>' }, 'Objetivo general', 'Tercer objetivo específico']])
        data.addRows([[{ v: 'Cuarto objetivo específico', f: '<strong>Cuarto objetivo específico</strong><div>' + objetivos['Cuarto objetivo específico'] + '</div>' }, 'Objetivo general', 'Cuarto objetivo específico']])
        if (objetivos['Quinto objetivo específico']) {
            data.addRows([[{ v: 'Quinto objetivo específico', f: '<strong>Quinto objetivo específico</strong><div>' + objetivos['Quinto objetivo específico'] + '</div>' }, 'Objetivo general', 'Quinto objetivo específico']])
        }
        if (objetivos['Sexto objetivo específico']) {
            data.addRows([[{ v: 'Sexto objetivo específico', f: '<strong>Sexto objetivo específico</strong><div>' + objetivos['Sexto objetivo específico'] + '</div>' }, 'Objetivo general', 'Sexto objetivo específico']])
        }
        productos.map((producto) => {
            data.addRows([[{ v: producto.v, f: '<strong>Producto</strong><div>' + producto.f + '<div><strong>Meta</strong></div>' + producto.meta + '</div>' }, producto.fkey, producto.tooltip]])
            producto.actividades.map((actividad) => {
                data.addRows([[{ v: 'act' + producto.v + actividad.id, f: '<strong>Actividad</strong><div>' + actividad.descripcion + '</div>' }, producto.v, actividad.descripcion]])

                data.addRows([[{ v: 'cost' + producto.v + actividad.id, f: '<strong>Costo</strong><div>$ ' + new Intl.NumberFormat('de-DE').format(!isNaN(actividad.costo_actividad) ? actividad.costo_actividad : 0) + ' COP</div>' }, 'act' + producto.v + actividad.id, new Intl.NumberFormat('de-DE').format(!isNaN(actividad.costo_actividad) ? actividad.costo_actividad : 0)]])
            })
        })

        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById('orgchart_div'))
        if (typeof chart.draw === 'function') {
            // Draw the chart, setting the allowHtml option to true for the tooltips.
            chart.draw(data, options)
        }
    }

    let formEstrategiaRegionalEvaluacion = useForm({
        cadena_valor_puntaje: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion?.cadena_valor_puntaje : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.cadena_valor_puntaje : null,
        cadena_valor_comentario: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion?.cadena_valor_comentario : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.cadena_valor_comentario : null,
        cadena_valor_requiere_comentario: evaluacion.idi_evaluacion ? (evaluacion.idi_evaluacion?.cadena_valor_comentario == null ? true : false) : evaluacion.cultura_innovacion_evaluacion ? (evaluacion.cultura_innovacion_evaluacion.cadena_valor_comentario == null ? true : false) : null,
    })
    function submitEstrategiaRegionalEvaluacion() {
        if (isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $formEstrategiaRegionalEvaluacion.put(route('convocatorias.evaluaciones.cadena-valor.guardar-evaluacion', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let formTaEvaluacion = useForm({
        cadena_valor_comentario: evaluacion.ta_evaluacion?.cadena_valor_comentario,
        cadena_valor_requiere_comentario: evaluacion.ta_evaluacion?.cadena_valor_comentario == null ? true : false,
    })
    function submitTaEvaluacion() {
        if (isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $formTaEvaluacion.put(route('convocatorias.evaluaciones.cadena-valor.guardar-evaluacion', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let formTpEvaluacion = useForm({
        cadena_valor_comentario: evaluacion.tp_evaluacion?.cadena_valor_comentario,
        cadena_valor_requiere_comentario: evaluacion.tp_evaluacion?.cadena_valor_comentario == null ? true : false,
    })
    function submitTpEvaluacion() {
        if (isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $formTpEvaluacion.put(route('convocatorias.evaluaciones.cadena-valor.guardar-evaluacion', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let formServicioTecnologicoEvaluacion = useForm({
        propuesta_sostenibilidad_puntaje: evaluacion.servicio_tecnologico_evaluacion?.propuesta_sostenibilidad_puntaje,
        propuesta_sostenibilidad_comentario: evaluacion.servicio_tecnologico_evaluacion?.propuesta_sostenibilidad_comentario,
        propuesta_sostenibilidad_requiere_comentario: evaluacion.servicio_tecnologico_evaluacion?.propuesta_sostenibilidad_comentario == null ? true : false,

        impacto_ambiental_puntaje: evaluacion.servicio_tecnologico_evaluacion?.impacto_ambiental_puntaje,
        impacto_ambiental_comentario: evaluacion.servicio_tecnologico_evaluacion?.impacto_ambiental_comentario,
        impacto_ambiental_requiere_comentario: evaluacion.servicio_tecnologico_evaluacion?.impacto_ambiental_comentario == null ? true : false,

        impacto_social_centro_puntaje: evaluacion.servicio_tecnologico_evaluacion?.impacto_social_centro_puntaje,
        impacto_social_centro_comentario: evaluacion.servicio_tecnologico_evaluacion?.impacto_social_centro_comentario,
        impacto_social_centro_requiere_comentario: evaluacion.servicio_tecnologico_evaluacion?.impacto_social_centro_comentario == null ? true : false,

        impacto_social_productivo_puntaje: evaluacion.servicio_tecnologico_evaluacion?.impacto_social_productivo_puntaje,
        impacto_social_productivo_comentario: evaluacion.servicio_tecnologico_evaluacion?.impacto_social_productivo_comentario,
        impacto_social_productivo_requiere_comentario: evaluacion.servicio_tecnologico_evaluacion?.impacto_social_productivo_comentario == null ? true : false,

        impacto_tecnologico_puntaje: evaluacion.servicio_tecnologico_evaluacion?.impacto_tecnologico_puntaje,
        impacto_tecnologico_comentario: evaluacion.servicio_tecnologico_evaluacion?.impacto_tecnologico_comentario,
        impacto_tecnologico_requiere_comentario: evaluacion.servicio_tecnologico_evaluacion?.impacto_tecnologico_comentario == null ? true : false,
    })
    function submitServicioTecnologicoEvaluacion() {
        if (isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $formServicioTecnologicoEvaluacion.put(route('convocatorias.evaluaciones.cadena-valor.guardar-evaluacion', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    <a class="flex bg-orangered-900 bottom-0 fixed hover:bg-orangered-600 mb-4 px-6 py-2 rounded-3xl shadow-2xl text-center text-white z-50" href="#evaluacion">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        Ir a la evaluación
    </a>

    <h1 class="text-3xl mt-24 mb-10 text-center">Impactos</h1>

    <form>
        <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11, 5]) && proyecto.modificable == true) ? undefined : true}>
            {#each impactos as impacto}
                <div class="mt-4">
                    {#if impacto.tipo == 1}
                        <strong>Impacto social</strong>
                        <Textarea disabled label="" maxlength="40000" value={impacto.descripcion} />
                    {:else if impacto.tipo == 2}
                        <strong>Impacto tecnológico</strong>
                        <Textarea disabled label="" maxlength="40000" value={impacto.descripcion} />
                    {:else if impacto.tipo == 3}
                        <strong>Impacto económico</strong>
                        <Textarea disabled label="" maxlength="40000" value={impacto.descripcion} />
                    {:else if impacto.tipo == 4}
                        <strong>Impacto ambiental</strong>
                        <Textarea disabled label="" maxlength="40000" value={impacto.descripcion} />
                    {:else if impacto.tipo == 5}
                        <strong>Impacto social en el centro de formación</strong>
                        <Textarea disabled label="" maxlength="40000" value={impacto.descripcion} />
                    {:else if impacto.tipo == 6}
                        <strong>Impacto social en el sector productivo</strong>
                        <Textarea disabled label="" maxlength="40000" value={impacto.descripcion} />
                    {/if}
                </div>
            {/each}

            {#if proyecto.codigo_linea_programatica != 70}
                <div class="mt-4">
                    <h1 class="text-3xl mt-24 mb-10 text-center">Propuesta de sostenibilidad</h1>
                    {#if proyecto.codigo_linea_programatica == 68}
                        <InfoMessage class="mb-2">
                            Se deben mencionar aquellos factores que pueden comprometer la viabilidad, desarrollo de los objetivos y resultados del proyecto a través del tiempo.
                            <br />
                            Para definir la propuesta de sostenibilidad se deben tener en cuenta los impactos definidos en el árbol de objetivos (ambiental, social - en el centro de formación, social - en el sector productivo, tecnológico)
                        </InfoMessage>
                    {:else}
                        <InfoMessage class="mb-2" message="Identificar los efectos que tiene el desarrollo del proyecto de investigación ya sea positivos o negativos. Se recomienda establecer las acciones pertinentes para mitigar los impactos negativos ambientales identificados y anexar el respectivo permiso ambiental cuando aplique. Tener en cuenta si aplica el decreto 1376 de 2013." />
                    {/if}
                    <Textarea disabled label="Propuesta de sostenibilidad" maxlength="40000" id="propuesta_sostenibilidad" value={propuestaSostenibilidadInfo.propuesta_sostenibilidad} />
                </div>
            {:else if proyecto.codigo_linea_programatica == 70}
                <div class="mt-4">
                    <Textarea disabled label="Propuesta de sostenibilidad social" maxlength="40000" id="propuesta_sostenibilidad_social" value={propuestaSostenibilidadInfo.propuesta_sostenibilidad_social} />
                </div>
                <div class="mt-4">
                    <Textarea disabled label="Propuesta de sostenibilidad ambiental" maxlength="40000" id="propuesta_sostenibilidad_ambiental" value={propuestaSostenibilidadInfo.propuesta_sostenibilidad_ambiental} />
                </div>
                <div class="mt-4">
                    <Textarea disabled label="Propuesta de sostenibilidad financiera" maxlength="40000" id="propuesta_sostenibilidad_financiera" value={propuestaSostenibilidadInfo.propuesta_sostenibilidad_financiera} />
                </div>
            {/if}
        </fieldset>
    </form>

    <hr class="mb-20 mt-20" />

    <h1 class="text-3xl m-24 text-center">Cadena de valor</h1>

    {#if productos.length == 0}
        <InfoMessage message="No ha generado productos por lo tanto tiene la cadena de valor incompleta.<br />Por favor realice los siguientes pasos:<div>1. Diríjase a <strong>Productos</strong> y genere los productos correspondientes</div><div>2. Luego diríjase a <strong>Actividades</strong> y asocie los productos y rubros correspondientes. De esta manera completa la cadena de valor.</div>" />
    {/if}

    <div class="mt-10">
        <div id="orgchart_div" class="overflow-x-scroll" style="margin: 0 -100px;" />
    </div>

    {#if proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
        <hr class="mt-10 mb-10" />

        <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>

        <div class="mt-16">
            <form on:submit|preventDefault={submitEstrategiaRegionalEvaluacion}>
                <InfoMessage>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li>
                            <strong>{proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 ? '0 a 12' : proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 ? '0 a 9' : ''}</strong> El presupuesto esta sobre o subdimensionado y / o no está directamente relacionado con el desarrollo de las actividades para el logro de los objetivos propuestos.
                            Los soportes que evidencian el costo del bien a adquirir no son pertinentes y tampoco confiables
                        </li>
                        <li>
                            <strong>{proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 ? '13 a 23' : proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 ? '10 a 18' : ''}</strong> El presupuesto es adecuado, pero es susceptible de ajustes frente a las las actividades a desarrollar que darán cumplimiento a los objetivos propuestos.
                            Los soportes que evidencian el costo del bien a adquirir son pertinentes y confiables.
                        </li>
                        <li>
                            <strong>{proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 ? '24 a 25' : proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 ? '19 a 20' : ''}</strong> El presupuesto está bien definido y se relaciona directamente con el desarrollo de las actividades y los entregables del proyecto. Los soportes que evidencian
                            el costo del bien a adquirir son pertinentes y confiables.
                        </li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="cadena_valor_puntaje" value={proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 ? 'Puntaje (Máximo 25)' : proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 ? 'Puntaje (Máximo 20)' : 'Puntaje (Máximo 0)'} />
                    <Input
                        disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="cadena_valor_puntaje"
                        type="number"
                        input$step="1"
                        input$min="0"
                        input$max={proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 ? 25 : proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 ? 20 : 0}
                        class="mt-1"
                        bind:value={$formEstrategiaRegionalEvaluacion.cadena_valor_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.cadena_valor_puntaje}
                    />

                    <div class="mt-4">
                        <p>¿La cadena de valor, propuesta de sostenibilidad, impacto social, impacto tecnológico o impacto en el centro de formación son correctos? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formEstrategiaRegionalEvaluacion.cadena_valor_requiere_comentario} />
                        {#if $formEstrategiaRegionalEvaluacion.cadena_valor_requiere_comentario == false}
                            <Textarea disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="cadena_valor_comentario" bind:value={$formEstrategiaRegionalEvaluacion.cadena_valor_comentario} error={errors.cadena_valor_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>
    {:else if proyecto.codigo_linea_programatica == 68}
        <hr class="mt-10 mb-10" />

        <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>

        <div class="mt-16">
            <form on:submit|preventDefault={submitServicioTecnologicoEvaluacion}>
                <InfoMessage>
                    <h1 class="font-black">Propuesta de sostenibilidad</h1>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li>
                            Se deben mencionar aquellos factores que pueden comprometer la viabilidad, desarrollo de los objetivos y resultados del proyecto a través del tiempo.
                            <br />
                            Para definir la propuesta de sostenibilidad se deben tener en cuenta los impactos definidos en el árbol de objetivos (ambiental, social - en el centro de formación, social - en el sector productivo, tecnológico)
                        </li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="propuesta_sostenibilidad_puntaje" value="Puntaje (Máximo 3)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="propuesta_sostenibilidad_puntaje"
                        type="number"
                        input$step="1"
                        input$min="0"
                        input$max="3"
                        class="mt-1"
                        bind:value={$formServicioTecnologicoEvaluacion.propuesta_sostenibilidad_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.propuesta_sostenibilidad_puntaje}
                    />

                    <div class="mt-4">
                        <p>¿La propuesta de sostenibilidad es correcto? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formServicioTecnologicoEvaluacion.propuesta_sostenibilidad_requiere_comentario} />
                        {#if $formServicioTecnologicoEvaluacion.propuesta_sostenibilidad_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="propuesta_sostenibilidad_comentario"
                                bind:value={$formServicioTecnologicoEvaluacion.propuesta_sostenibilidad_comentario}
                                error={errors.propuesta_sostenibilidad_comentario}
                                required
                            />
                        {/if}
                    </div>

                    <hr class="mt-10 mb-10 border-indigo-300" />
                    <h1 class="font-black">Impacto ambiental</h1>
                    <h1>Criterios de evaluacion</h1>

                    <ul class="list-disc p-4">
                        <li>
                            Se deben mencionar aquellos factores que pueden comprometer la viabilidad, desarrollo de los objetivos y resultados del proyecto a través del tiempo.
                            <br />
                            Para definir la propuesta de sostenibilidad se deben tener en cuenta los impactos definidos en el árbol de objetivos (ambiental, social - en el centro de formación, social - en el sector productivo, tecnológico)
                        </li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="impacto_ambiental_puntaje" value="Puntaje (Máximo 1)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="impacto_ambiental_puntaje"
                        type="number"
                        input$step="1"
                        input$min="0"
                        input$max="1"
                        class="mt-1"
                        bind:value={$formServicioTecnologicoEvaluacion.impacto_ambiental_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.impacto_ambiental_puntaje}
                    />

                    <div class="mt-4">
                        <p>¿El impacto ambiental es correcto? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formServicioTecnologicoEvaluacion.impacto_ambiental_requiere_comentario} />
                        {#if $formServicioTecnologicoEvaluacion.impacto_ambiental_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="impacto_ambiental_comentario"
                                bind:value={$formServicioTecnologicoEvaluacion.impacto_ambiental_comentario}
                                error={errors.impacto_ambiental_comentario}
                                required
                            />
                        {/if}
                    </div>

                    <hr class="mt-10 mb-10 border-indigo-300" />
                    <h1 class="font-black">Impacto social en el centro de formación</h1>
                    <h1>Criterios de evaluacion</h1>

                    <ul class="list-disc p-4">
                        <li>Se busca medir la contribución potencial del proyecto al desarrollo de la comunidad Sena (Aprendices, instructores y a la formación)</li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="impacto_social_centro_puntaje" value="Puntaje (Máximo 1)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="impacto_social_centro_puntaje"
                        type="number"
                        input$step="1"
                        input$min="0"
                        input$max="1"
                        class="mt-1"
                        bind:value={$formServicioTecnologicoEvaluacion.impacto_social_centro_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.impacto_social_centro_puntaje}
                    />

                    <div class="mt-4">
                        <p>¿El impacto social en el centro de formación es correcto? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formServicioTecnologicoEvaluacion.impacto_social_centro_requiere_comentario} />
                        {#if $formServicioTecnologicoEvaluacion.impacto_social_centro_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="impacto_social_centro_comentario"
                                bind:value={$formServicioTecnologicoEvaluacion.impacto_social_centro_comentario}
                                error={errors.impacto_social_centro_comentario}
                                required
                            />
                        {/if}
                    </div>

                    <hr class="mt-10 mb-10 border-indigo-300" />
                    <h1 class="font-black">Impacto social en el sector productivo</h1>
                    <h1>Criterios de evaluacion</h1>

                    <ul class="list-disc p-4">
                        <li>Se busca medir la contribución potencial del proyecto al desarrollo del sector productivo en concordancia con el sector priorizado de Colombia Productiva y a la mesa técnica a la que pertenece el proyecto.</li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="impacto_social_productivo_puntaje" value="Puntaje (Máximo 1)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="impacto_social_productivo_puntaje"
                        type="number"
                        input$step="1"
                        input$min="0"
                        input$max="1"
                        class="mt-1"
                        bind:value={$formServicioTecnologicoEvaluacion.impacto_social_productivo_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.impacto_social_productivo_puntaje}
                    />

                    <div class="mt-4">
                        <p>¿El impacto social en el sector productivo es correcto? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formServicioTecnologicoEvaluacion.impacto_social_productivo_requiere_comentario} />
                        {#if $formServicioTecnologicoEvaluacion.impacto_social_productivo_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="impacto_social_productivo_comentario"
                                bind:value={$formServicioTecnologicoEvaluacion.impacto_social_productivo_comentario}
                                error={errors.impacto_social_productivo_comentario}
                                required
                            />
                        {/if}
                    </div>

                    <hr class="mt-10 mb-10 border-indigo-300" />
                    <h1 class="font-black">Impacto tecnológico</h1>
                    <h1>Criterios de evaluacion</h1>

                    <ul class="list-disc p-4">
                        <li>Se busca medir la contribución potencial del proyecto al desarrollo de la comunidad Sena (Aprendices, instructores y a la formación)</li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="impacto_tecnologico_puntaje" value="Puntaje (Máximo 1)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="impacto_tecnologico_puntaje"
                        type="number"
                        input$step="1"
                        input$min="0"
                        input$max="1"
                        class="mt-1"
                        bind:value={$formServicioTecnologicoEvaluacion.impacto_tecnologico_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.impacto_tecnologico_puntaje}
                    />

                    <div class="mt-4">
                        <p>¿El impacto tecnológico es correcto? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formServicioTecnologicoEvaluacion.impacto_tecnologico_requiere_comentario} />
                        {#if $formServicioTecnologicoEvaluacion.impacto_tecnologico_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="impacto_tecnologico_comentario"
                                bind:value={$formServicioTecnologicoEvaluacion.impacto_tecnologico_comentario}
                                error={errors.impacto_tecnologico_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>
    {:else if proyecto.codigo_linea_programatica == 70}
        <hr class="mt-10 mb-10" />

        <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>

        <div class="mt-16">
            <form on:submit|preventDefault={submitTaEvaluacion}>
                <InfoMessage>
                    <div class="mt-4">
                        <p>¿La cadena de valor, propuesta de sostenibilidad, impacto social, impacto tecnológico o impacto en el centro de formación son correctos? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formTaEvaluacion.cadena_valor_requiere_comentario} />
                        {#if $formTaEvaluacion.cadena_valor_requiere_comentario == false}
                            <Textarea disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="cadena_valor_comentario" bind:value={$formTaEvaluacion.cadena_valor_comentario} error={errors.cadena_valor_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>
    {:else if proyecto.codigo_linea_programatica == 69}
        <hr class="mt-10 mb-10" />

        <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>

        <div class="mt-16">
            <form on:submit|preventDefault={submitTpEvaluacion}>
                <InfoMessage>
                    <div class="mt-4">
                        <p>¿La cadena de valor, propuesta de sostenibilidad, impacto social, impacto tecnológico o impacto en el centro de formación son correctos? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formTpEvaluacion.cadena_valor_requiere_comentario} />
                        {#if $formTpEvaluacion.cadena_valor_requiere_comentario == false}
                            <Textarea disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="cadena_valor_comentario" bind:value={$formTpEvaluacion.cadena_valor_comentario} error={errors.cadena_valor_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkRole(authUser, [11, 5]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>
    {/if}
</AuthenticatedLayout>

<style>
    :global(#orgchart_div table) {
        border-collapse: unset;
    }

    :global(#orgchart_div table td.google-visualization-orgchart-node-small > div) {
        margin: auto;
        width: 150px;
    }
</style>
