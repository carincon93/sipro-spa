<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'
    import { onMount } from 'svelte'

    import Button from '@/Shared/Button'
    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import SelectMulti from '@/Shared/SelectMulti'
    import InfoMessage from '@/Shared/InfoMessage'
    import Switch from '@/Shared/Switch'
    import FormField from '@smui/form-field'
    import Radio from '@smui/radio'
    import Dialog from '@/Shared/Dialog'

    export let errors
    export let convocatoria
    export let culturaInnovacion
    export let culturaInnovacionEvaluacion
    export let mesasSectoriales
    export let mesasSectorialesRelacionadas
    export let lineasTecnoacademiaRelacionadas
    export let tecnoacademias
    export let tecnoacademia
    export let opcionesAplicaNoAplica
    export let proyectoMunicipios
    export let proyectoProgramasFormacion
    export let proyectoProgramasFormacionArticulados

    $: $title = culturaInnovacion ? culturaInnovacion.titulo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let municipios
    let programasFormacion
    let programasFormacionArticular
    let dialogSegundaEvaluacion = convocatoria.fase == 4 ? true : false
    let proyectoDialogOpen = culturaInnovacionEvaluacion.evaluacion.clausula_confidencialidad == false ? true : false
    let sending = false
    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]

    let lineasTecnologicas = []
    let tieneVideo = culturaInnovacion.video != null
    let requiereJustificacionIndustria4 = culturaInnovacion.justificacion_industria_4 != null
    let requiereJustificacionEconomiaNaranja = culturaInnovacion.justificacion_economia_naranja != null
    let requiereJustificacionPoliticaDiscapacidad = culturaInnovacion.justificacion_politica_discapacidad != null

    let culturaInnovacionInfo = {
        centro_formacion_id: culturaInnovacion.proyecto?.centro_formacion_id,
        linea_investigacion_id: culturaInnovacion.linea_investigacion_id,
        area_conocimiento_id: culturaInnovacion.area_conocimiento_id,
        tematica_estrategica_id: culturaInnovacion.tematica_estrategica_id,
        linea_programatica_id: culturaInnovacion.proyecto?.linea_programatica_id,
        actividad_economica_id: culturaInnovacion.actividad_economica_id,
        titulo: culturaInnovacion.titulo,
        fecha_inicio: culturaInnovacion.fecha_inicio,
        fecha_finalizacion: culturaInnovacion.fecha_finalizacion,
        max_meses_ejecucion: culturaInnovacion.max_meses_ejecucion,
        video: culturaInnovacion.video,
        justificacion_industria_4: culturaInnovacion.justificacion_industria_4,
        justificacion_economia_naranja: culturaInnovacion.justificacion_economia_naranja,
        justificacion_politica_discapacidad: culturaInnovacion.justificacion_politica_discapacidad,
        resumen: culturaInnovacion.resumen,
        antecedentes: culturaInnovacion.antecedentes,
        identificacion_problema: culturaInnovacion.identificacion_problema,
        justificacion_problema: culturaInnovacion.justificacion_problema,
        marco_conceptual: culturaInnovacion.marco_conceptual,
        bibliografia: culturaInnovacion.bibliografia,
        numero_aprendices: culturaInnovacion.numero_aprendices,
        municipios: proyectoMunicipios.length > 0 ? proyectoMunicipios : null,
        programas_formacion: proyectoProgramasFormacion.length > 0 ? proyectoProgramasFormacion : null,
        programas_formacion_articulados: proyectoProgramasFormacionArticulados.length > 0 ? proyectoProgramasFormacionArticulados : null,
        impacto_municipios: culturaInnovacion.impacto_municipios,
        impacto_centro_formacion: culturaInnovacion.impacto_centro_formacion,
        muestreo: culturaInnovacion.muestreo,
        actividades_muestreo: culturaInnovacion.actividades_muestreo,
        objetivo_muestreo: culturaInnovacion.objetivo_muestreo,
        recoleccion_especimenes: {
            value: culturaInnovacion.recoleccion_especimenes,
            label: opcionesSiNo.find((item) => item.value == culturaInnovacion.recoleccion_especimenes)?.label,
        },
        relacionado_plan_tecnologico: {
            value: culturaInnovacion.relacionado_plan_tecnologico,
            label: opcionesAplicaNoAplica.find((item) => item.value == culturaInnovacion.relacionado_plan_tecnologico)?.label,
        },
        relacionado_agendas_competitividad: {
            value: culturaInnovacion.relacionado_agendas_competitividad,
            label: opcionesAplicaNoAplica.find((item) => item.value == culturaInnovacion.relacionado_agendas_competitividad)?.label,
        },
        relacionado_mesas_sectoriales: {
            value: culturaInnovacion.relacionado_mesas_sectoriales,
            label: opcionesAplicaNoAplica.find((item) => item.value == culturaInnovacion.relacionado_mesas_sectoriales)?.label,
        },
        relacionado_tecnoacademia: {
            value: culturaInnovacion.relacionado_tecnoacademia,
            label: opcionesAplicaNoAplica.find((item) => item.value == culturaInnovacion.relacionado_tecnoacademia)?.label,
        },
        tecnoacademia_id: {
            value: tecnoacademia?.id,
            label: tecnoacademias.find((item) => item.value == tecnoacademia?.id)?.label,
        },

        linea_tecnologica_id: lineasTecnoacademiaRelacionadas,
        mesa_sectorial_id: mesasSectorialesRelacionadas,
    }

    onMount(() => {
        if (tecnoacademia) {
            getLineasTecnologicas(tecnoacademia)
        }
        getMunicipios()
        getProgramasFormacion()
        getProgramasFormacionArticular()
    })

    $: if (culturaInnovacionInfo.fecha_inicio && culturaInnovacionInfo.fecha_finalizacion) {
        culturaInnovacionInfo.max_meses_ejecucion = monthDiff(culturaInnovacionInfo.fecha_inicio, culturaInnovacionInfo.fecha_finalizacion)
    }

    let form = useForm({
        clausula_confidencialidad: culturaInnovacionEvaluacion.evaluacion.clausula_confidencialidad,
        titulo_puntaje: culturaInnovacionEvaluacion.titulo_puntaje,
        titulo_comentario: culturaInnovacionEvaluacion.titulo_comentario,
        titulo_requiere_comentario: culturaInnovacionEvaluacion.titulo_comentario == null ? true : false,
        video_puntaje: culturaInnovacionEvaluacion.video_puntaje,
        video_comentario: culturaInnovacionEvaluacion.video_comentario,
        video_requiere_comentario: culturaInnovacionEvaluacion.video_comentario == null ? true : false,
        resumen_puntaje: culturaInnovacionEvaluacion.resumen_puntaje,
        resumen_comentario: culturaInnovacionEvaluacion.resumen_comentario,
        resumen_requiere_comentario: culturaInnovacionEvaluacion.resumen_comentario == null ? true : false,
        ortografia_puntaje: culturaInnovacionEvaluacion.ortografia_puntaje,
        ortografia_comentario: culturaInnovacionEvaluacion.ortografia_comentario,
        ortografia_requiere_comentario: culturaInnovacionEvaluacion.ortografia_comentario == null ? true : false,
        redaccion_puntaje: culturaInnovacionEvaluacion.redaccion_puntaje,
        redaccion_comentario: culturaInnovacionEvaluacion.redaccion_comentario,
        redaccion_requiere_comentario: culturaInnovacionEvaluacion.redaccion_comentario == null ? true : false,
        normas_apa_puntaje: culturaInnovacionEvaluacion.normas_apa_puntaje,
        normas_apa_comentario: culturaInnovacionEvaluacion.normas_apa_comentario,
        normas_apa_requiere_comentario: culturaInnovacionEvaluacion.normas_apa_comentario == null ? true : false,

        justificacion_economia_naranja_requiere_comentario: culturaInnovacionEvaluacion.justificacion_economia_naranja_comentario == null ? true : false,
        justificacion_economia_naranja_comentario: culturaInnovacionEvaluacion.justificacion_economia_naranja_comentario,

        justificacion_industria_4_requiere_comentario: culturaInnovacionEvaluacion.justificacion_industria_4_comentario == null ? true : false,
        justificacion_industria_4_comentario: culturaInnovacionEvaluacion.justificacion_industria_4_comentario,

        bibliografia_requiere_comentario: culturaInnovacionEvaluacion.bibliografia_comentario == null ? true : false,
        bibliografia_comentario: culturaInnovacionEvaluacion.bibliografia_comentario,

        fechas_requiere_comentario: culturaInnovacionEvaluacion.fechas_comentario == null ? true : false,
        fechas_comentario: culturaInnovacionEvaluacion.fechas_comentario,

        justificacion_politica_discapacidad_requiere_comentario: culturaInnovacionEvaluacion.justificacion_politica_discapacidad_comentario == null ? true : false,
        justificacion_politica_discapacidad_comentario: culturaInnovacionEvaluacion.justificacion_politica_discapacidad_comentario,

        actividad_economica_requiere_comentario: culturaInnovacionEvaluacion.actividad_economica_comentario == null ? true : false,
        actividad_economica_comentario: culturaInnovacionEvaluacion.actividad_economica_comentario,

        area_conocimiento_requiere_comentario: culturaInnovacionEvaluacion.area_conocimiento_comentario == null ? true : false,
        area_conocimiento_comentario: culturaInnovacionEvaluacion.area_conocimiento_comentario,

        tematica_estrategica_requiere_comentario: culturaInnovacionEvaluacion.tematica_estrategica_comentario == null ? true : false,
        tematica_estrategica_comentario: culturaInnovacionEvaluacion.tematica_estrategica_comentario,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == true && culturaInnovacionEvaluacion.evaluacion.modificable == true)) {
            $form.put(route('convocatorias.cultura-innovacion-evaluaciones.update', [convocatoria.id, culturaInnovacionEvaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    $: selectedTecnoacademia = culturaInnovacionInfo.tecnoacademia_id?.value

    $: if (selectedTecnoacademia) {
        getLineasTecnologicas(selectedTecnoacademia)
    }

    async function getLineasTecnologicas(tecnoacademia) {
        let res = await axios.get(route('web-api.tecnoacademias.lineas-tecnoacademia', [tecnoacademia]))
        res.status == '200' ? (culturaInnovacionInfo.linea_tecnologica_id = lineasTecnoacademiaRelacionadas) : null
        lineasTecnologicas = res.data
    }

    async function getMunicipios() {
        let res = await axios.get(route('web-api.municipios'))
        if (res.status == '200') {
            municipios = res.data
        }
    }

    async function getProgramasFormacion() {
        let res = await axios.get(route('web-api.programas-formacion', culturaInnovacionInfo.centro_formacion_id))
        if (res.status == '200') {
            programasFormacion = res.data
        }
    }

    async function getProgramasFormacionArticular() {
        let res = await axios.get(route('web-api.programas-formacion-articulados'))
        if (res.status == '200') {
            programasFormacionArticular = res.data
        }
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} evaluacion={culturaInnovacionEvaluacion.evaluacion} proyecto={culturaInnovacion.proyecto} />

    <form on:submit|preventDefault={submit}>
        <div class="mt-28">
            <p class="mb-4">Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué. (Máximo 20 palabras)</p>
            <Textarea disabled label="Título" id="titulo" sinContador={true} bind:value={culturaInnovacionInfo.titulo} classes="bg-transparent block border-0" />

            <InfoMessage>
                <h1>Criterios de evaluacion</h1>
                <ul class="list-disc p-4">
                    <li><strong>Puntaje: 0,0 a 0,5</strong> El título orienta el enfoque del proyecto</li>
                    <li><strong>Puntaje: 0,6 a 1,0</strong> El título orienta el enfoque del proyecto e indica el cómo y el para qué</li>
                </ul>
                <Label class="mt-4 mb-4" labelFor="titulo_puntaje" value="Puntaje (Máximo 1)" />
                <Input disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Puntaje" id="titulo_puntaje" type="number" input$step="0.1" input$min="0" input$max="1" class="mt-1" bind:value={$form.titulo_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.titulo_puntaje} />

                <div class="mt-4">
                    <p>¿El título es correcto? Por favor seleccione si Cumple o No cumple.</p>
                    <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.titulo_requiere_comentario} />
                    {#if $form.titulo_requiere_comentario == false}
                        <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="titulo_comentario" bind:value={$form.titulo_comentario} required />
                    {/if}
                </div>
            </InfoMessage>
        </div>

        <div class="mt-44">
            <p class="text-center">Fecha de ejecución</p>
            <div class="mt-4 flex items-start justify-around">
                <div class="mt-4 flex items-end">
                    <p class="mb-4">Del</p>
                    <div class="ml-4">
                        <input disabled id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_cultura} max={convocatoria.max_fecha_finalizacion_proyectos_cultura} bind:value={culturaInnovacionInfo.fecha_inicio} />
                    </div>
                </div>
                <div class="mt-4 flex items-end">
                    <p class="mb-4">hasta</p>
                    <div class="ml-4">
                        <input disabled id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_cultura} max={convocatoria.max_fecha_finalizacion_proyectos_cultura} bind:value={culturaInnovacionInfo.fecha_finalizacion} />
                    </div>
                </div>
            </div>

            <InfoMessage>
                <div class="mt-4">
                    <p>¿Las fechas son correctas? Por favor seleccione si Cumple o No cumple.</p>
                    <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.fechas_requiere_comentario} />
                    {#if $form.fechas_requiere_comentario == false}
                        <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="fechas_comentario" bind:value={$form.fechas_comentario} error={errors.fechas_comentario} required />
                    {/if}
                </div>
            </InfoMessage>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Código dependencia presupuestal (SIIF)</p>
            </div>
            <div>
                <DynamicList disabled={true} id="linea_programatica_id" bind:value={culturaInnovacionInfo.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas', 5)} classes="evaluacion-select min-h" placeholder="Busque por el nombre de la línea programática" />
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Área de conocimiento</p>
            </div>
            <div>
                <DynamicList disabled={true} id="area_conocimiento_id" bind:value={culturaInnovacionInfo.area_conocimiento_id} routeWebApi={route('web-api.areas-conocimiento')} classes="evaluacion-select min-h" placeholder="Busque por el nombre de la área de conocimiento" />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿La área de conocimiento es correcto? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.area_conocimiento_requiere_comentario} />
                        {#if $form.area_conocimiento_requiere_comentario == false}
                            <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="area_conocimiento_comentario" bind:value={$form.area_conocimiento_comentario} error={errors.area_conocimiento_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿En cuál de estas actividades económicas se puede aplicar el proyecto de investigación?</p>
            </div>
            <div>
                <DynamicList disabled={true} id="actividad_economica_id" bind:value={culturaInnovacionInfo.actividad_economica_id} routeWebApi={route('web-api.actividades-economicas')} placeholder="Busque por el nombre de la actividad económica" classes="evaluacion-select min-h" />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿La actividad económica es correcta? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.actividad_economica_requiere_comentario} />
                        {#if $form.actividad_economica_requiere_comentario == false}
                            <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="actividad_economica_comentario" bind:value={$form.actividad_economica_comentario} error={errors.actividad_economica_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Temática estratégica SENA</p>
            </div>
            <div>
                <DynamicList classes="evaluacion-select" disabled={true} id="tematica_estrategica_id" bind:value={culturaInnovacionInfo.tematica_estrategica_id} routeWebApi={route('web-api.tematicas-estrategicas')} placeholder="Busque por el nombre de la temática estrategica SENA" />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿La temática estratégica es correcta? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.tematica_estrategica_requiere_comentario} />
                        {#if $form.tematica_estrategica_requiere_comentario == false}
                            <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="tematica_estrategica_comentario" bind:value={$form.tematica_estrategica_comentario} error={errors.tematica_estrategica_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto tiene video?</p>
            </div>
            <div>
                <Switch disabled bind:checked={tieneVideo} />
                {#if tieneVideo}
                    <Input disabled label="Link del video" id="video" type="url" class="mt-1" placeholder="Link del video del proyecto https://www.youtube.com/watch?v=gmc4tk" bind:value={culturaInnovacionInfo.video} />

                    <InfoMessage>
                        <h1>Criterios de evaluacion</h1>
                        <ul class="list-disc p-4">
                            <li><strong>Puntaje: 0,0 a 0,5</strong> El video no cumple los 3 minutos establecidos y no presenta de forma clara la justificación, la problemática, el objetivo general, los objetivos específicos, las actividades, los productos y/o su impacto de acuerdo con los lineamientos de la convocatoria</li>
                            <li><strong>Puntaje: 0,6 a 1,0</strong> El video cumple los 3 minutos establecidos y presenta la justificación, la problemática, el objetivo general, los objetivos específicos, las actividades, los productos y su impacto de acuerdo con los lineamientos de la convocatoria</li>
                        </ul>

                        <Label class="mt-4 mb-4" labelFor="video_puntaje" value="Puntaje (Máximo 1)" />
                        <Input disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Puntaje" id="video_puntaje" type="number" input$step="0.1" input$min="0" input$max="1" class="mt-1" bind:value={$form.video_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.video_puntaje} />

                        <div class="mt-4">
                            <p>¿El video es correcto? Por favor seleccione si Cumple o No cumple.</p>
                            <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.video_requiere_comentario} />
                            {#if $form.video_requiere_comentario == false}
                                <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="video_comentario" bind:value={$form.video_comentario} required />
                            {/if}
                        </div>
                    </InfoMessage>
                {/if}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto está relacionado con la industria 4.0?</p>
            </div>
            <div>
                <div class="flex items-center mb-14">
                    <Switch disabled bind:checked={requiereJustificacionIndustria4} />
                </div>

                {#if requiereJustificacionIndustria4}
                    <Textarea disabled label="Justificación" maxlength="40000" id="justificacion_industria_4" bind:value={culturaInnovacionInfo.justificacion_industria_4} />

                    <InfoMessage>
                        <div class="mt-4">
                            <p>¿El ítem es correcto? Por favor seleccione si Cumple o No cumple.</p>
                            <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.justificacion_industria_4_requiere_comentario} />
                            {#if $form.justificacion_industria_4_requiere_comentario == false}
                                <Textarea
                                    disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined}
                                    label="Comentario"
                                    class="mt-4"
                                    maxlength="40000"
                                    id="justificacion_industria_4_comentario"
                                    bind:value={$form.justificacion_industria_4_comentario}
                                    error={errors.justificacion_industria_4_comentario}
                                    required
                                />
                            {/if}
                        </div>
                    </InfoMessage>
                {/if}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto está relacionado con la economía naranja?</p>
            </div>
            <div>
                <div class="flex items-center mb-14">
                    <Switch disabled bind:checked={requiereJustificacionEconomiaNaranja} />
                </div>
                {#if requiereJustificacionEconomiaNaranja}
                    <Textarea disabled label="Justificación" maxlength="40000" id="justificacion_economia_naranja" bind:value={culturaInnovacionInfo.justificacion_economia_naranja} />

                    <InfoMessage>
                        <div class="mt-4">
                            <p>¿El ítem es correcto? Por favor seleccione si Cumple o No cumple.</p>
                            <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.justificacion_economia_naranja_requiere_comentario} />
                            {#if $form.justificacion_economia_naranja_requiere_comentario == false}
                                <Textarea
                                    disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined}
                                    label="Comentario"
                                    class="mt-4"
                                    maxlength="40000"
                                    id="justificacion_economia_naranja_comentario"
                                    bind:value={$form.justificacion_economia_naranja_comentario}
                                    error={errors.justificacion_economia_naranja_comentario}
                                    required
                                />
                            {/if}
                        </div>
                    </InfoMessage>
                {/if}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto aporta a la Política Institucional para Atención de las Personas con discapacidad?</p>
            </div>
            <div>
                <div class="flex items-center mb-14">
                    <Switch disabled bind:checked={requiereJustificacionPoliticaDiscapacidad} />
                </div>
                {#if requiereJustificacionPoliticaDiscapacidad}
                    <Textarea disabled label="Justificación" maxlength="40000" id="justificacion_politica_discapacidad" bind:value={culturaInnovacionInfo.justificacion_politica_discapacidad} />

                    <InfoMessage>
                        <div class="mt-4">
                            <p>¿El ítem es correcto? Por favor seleccione si Cumple o No cumple.</p>
                            <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.justificacion_politica_discapacidad_requiere_comentario} />
                            {#if $form.justificacion_politica_discapacidad_requiere_comentario == false}
                                <Textarea
                                    disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined}
                                    label="Comentario"
                                    class="mt-4"
                                    maxlength="40000"
                                    id="justificacion_politica_discapacidad_comentario"
                                    bind:value={$form.justificacion_politica_discapacidad_comentario}
                                    error={errors.justificacion_politica_discapacidad_comentario}
                                    required
                                />
                            {/if}
                        </div>
                    </InfoMessage>
                {/if}
            </div>
        </div>

        <hr class="mt-5 mb-5" />

        <div>
            <p class="text-center mt-36 mb-8">¿Cuál es el origen de las muestras con las que se realizarán las actividades de investigación, bioprospección y/o aprovechamiento comercial o industrial?</p>
            <div class="flex mt-20 items-center">
                <FormField>
                    <Radio disabled bind:group={culturaInnovacionInfo.muestreo} value="1" />
                    <span slot="label">
                        Especies Nativas. (es la especie o subespecie taxonómica o variedad de animales cuya área de disposición geográfica se extiende al territorio nacional o a aguas jurisdiccionales colombianas o forma parte de los mismos comprendidas las especies o subespecies que migran temporalmente a ellos, siempre y cuando no se encuentren en el país o migren a él como resultado voluntario
                        o involuntario de la actividad humana. Pueden ser silvestre, domesticada o escapada de domesticación incluyendo virus, viroides y similares)
                    </span>
                </FormField>
            </div>

            <!-- Si seleccionan Especies nativas -->
            {#if culturaInnovacionInfo.muestreo == 1}
                <div class="flex mb-20">
                    <div class="bg-gray-200 flex-1 p-8">
                        <div class="flex items-center">
                            <p class="mb-4">¿Qué actividad pretende realizar con la especie nativa?</p>
                        </div>

                        <p class="bg-indigo-100 mt-10 p-4 text-indigo-600">Seleccione una opción</p>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={culturaInnovacionInfo.actividades_muestreo} value="1.1.1" />
                                <span slot="label"> Separación de las unidades funcionales y no funcionales del ADN y el ARN, en todas las formas que se encuentran en la naturaleza. </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={culturaInnovacionInfo.actividades_muestreo} value="1.1.2" />
                                <span slot="label"> Aislamiento de una o varias moléculas, entendidas estas como micro y macromoléculas, producidas por el metabolismo de un organismo. </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={culturaInnovacionInfo.actividades_muestreo} value="1.1.3" />
                                <span slot="label"> Solicitar patente sobre una función o propiedad identificada de una molécula, que se ha aislado y purificado. </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={culturaInnovacionInfo.actividades_muestreo} value="1.1.4" />
                                <span slot="label"> No logro identificar la actividad a desarrollar con la especie nativa </span>
                            </FormField>
                        </div>
                    </div>

                    <div class="bg-gray-300 flex-1 p-8">
                        <div class="flex items-center">
                            <p class="mb-4">¿Cuál es la finalidad de las actividades a realizar con la especie nativa/endémica?</p>
                        </div>

                        <p class="bg-indigo-100 mt-10 p-4 text-indigo-600">Seleccione una opción</p>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={culturaInnovacionInfo.objetivo_muestreo} value="1.2.1" />
                                <span slot="label"> Investigación básica sin fines comerciales </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={culturaInnovacionInfo.objetivo_muestreo} value="1.2.2" />
                                <span slot="label"> Bioprospección en cualquiera de sus fases </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={culturaInnovacionInfo.objetivo_muestreo} value="1.2.3" />
                                <span slot="label"> Comercial o Industrial </span>
                            </FormField>
                        </div>
                    </div>
                </div>
            {/if}

            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={culturaInnovacionInfo.muestreo} value="2" />
                    <span slot="label"> Especies Introducidas. (son aquellas que no son nativas de Colombia y que ingresaron al país por intervención humana) </span>
                </FormField>
            </div>
            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={culturaInnovacionInfo.muestreo} value="3" />
                    <span slot="label"> Recursos genéticos humanos y sus productos derivados </span>
                </FormField>
            </div>
            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={culturaInnovacionInfo.muestreo} value="4" />
                    <span slot="label"> Intercambio de recursos genéticos y sus productos derivados, recursos biológicos que los contienen o los componentes asociados a estos. (son aquellas que realizan las comunidades indígenas, afroamericanas y locales de los Países Miembros de la Comunidad Andina entre sí y para su propio consumo, basadas en sus prácticas consuetudinarias) </span>
                </FormField>
            </div>
            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={culturaInnovacionInfo.muestreo} value="5" />
                    <span slot="label">
                        Recurso biológico que involucren actividades de sistemática molecular, ecología molecular, evolución y biogeografía molecular (siempre que el recurso biológico se haya colectado en el marco de un permiso de recolección de especímenes de especies silvestres de la diversidad biológica con fines de investigación científica no comercial o provenga de una colección registrada
                        ante el Instituto Alexander van Humboldt)
                    </span>
                </FormField>
            </div>
            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={culturaInnovacionInfo.muestreo} value="6" />
                    <span slot="label"> No aplica </span>
                </FormField>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">
                    En la ejecución del proyecto se requiere la recolección de especímenes de especies silvestres de la diversidad biológica con fines de elaboración de estudios ambientales (entendiendo como recolección los procesos de remoción o extracción temporal o definitiva de una especie ya sea vegetal o animal del medio natural) Nota: este permiso no se requiere cuando las actividades de
                    recolección se limiten a investigaciones científicas o con fines industriales, comerciales o de prospección biológica.
                </p>
            </div>
            <div>
                <Select disabled={true} items={opcionesSiNo} id="recoleccion_especimenes" bind:selectedValue={culturaInnovacionInfo.recoleccion_especimenes} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
            <div />
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto se alinea con el plan tecnológico desarrollado por el centro de formación?</p>
            </div>
            <div>
                <Select disabled={true} items={opcionesAplicaNoAplica} id="relacionado_plan_tecnologico" bind:selectedValue={culturaInnovacionInfo.relacionado_plan_tecnologico} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto se alinea con las Agendas Departamentales de Competitividad e Innovación?</p>
            </div>
            <div>
                <Select disabled={true} items={opcionesAplicaNoAplica} id="relacionado_agendas_competitividad" bind:selectedValue={culturaInnovacionInfo.relacionado_agendas_competitividad} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto se alinea con las Mesas Sectoriales?</p>
            </div>
            <div>
                <Select disabled={true} items={opcionesAplicaNoAplica} id="relacionado_mesas_sectoriales" bind:selectedValue={culturaInnovacionInfo.relacionado_mesas_sectoriales} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
        </div>
        {#if culturaInnovacionInfo.relacionado_mesas_sectoriales?.value == 1}
            <div class="grid grid-cols-2">
                <div>Mesas sectoriales relacionadas:</div>
                <div>
                    <ul class="list-disc p-4">
                        {#each mesasSectoriales as { id, nombre }, i}
                            {#each culturaInnovacionInfo.mesa_sectorial_id as mesaSectorialRelacionada}
                                {#if id == mesaSectorialRelacionada}
                                    <li>{nombre}</li>
                                {/if}
                            {/each}
                        {/each}
                    </ul>
                </div>
            </div>
        {/if}

        <div class="mt-40 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto se formuló en conjunto con la tecnoacademia?</p>
            </div>
            <div>
                <Select disabled={true} items={opcionesAplicaNoAplica} id="relacionado_tecnoacademia" bind:selectedValue={culturaInnovacionInfo.relacionado_tecnoacademia} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
        </div>

        {#if culturaInnovacionInfo.relacionado_tecnoacademia?.value == 1}
            <div class="grid grid-cols-2">
                <div>Tecnoacademia relacionada</div>
                <div>
                    <Select disabled={true} items={tecnoacademias} id="tecnoacademia_id" bind:selectedValue={culturaInnovacionInfo.tecnoacademia_id} autocomplete="off" placeholder="Seleccione una opción" />
                </div>
            </div>
            <div class="grid grid-cols-2">
                <div>Líneas tecnológicas relacionadas:</div>
                <div>
                    <ul class="list-disc p-4">
                        {#each lineasTecnologicas as { value, label }, i}
                            {#each culturaInnovacionInfo.linea_tecnologica_id as lineaTecnologica}
                                {#if value == lineaTecnologica}
                                    <li>{label}</li>
                                {/if}
                            {/each}
                        {/each}
                    </ul>
                </div>
            </div>
        {/if}

        <div class="mt-40 grid grid-cols-1">
            <div>
                <p class="mb-4">Resumen del proyecto</p>
            </div>
            <div>
                <Textarea disabled label="Resumen" maxlength="40000" id="resumen" bind:value={culturaInnovacionInfo.resumen} />

                <InfoMessage>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li><strong>Puntaje: 0,0 a 1,0</strong> El resumen no presenta de forma clara la pertinencia y calidad del proyecto, en términos de cuál es el problema central, cómo se le dará solución y cuáles serán las herramientas que se utilizarán para ello, entre otros.</li>
                        <li><strong>Puntaje: 1,1 a 2,0</strong> El resumen presenta de forma clara la pertinencia y calidad del proyecto e incluye todos los elementos en términos de cuál es el problema central, cómo se le dará solución y cuáles serán las herramientas que se utilizarán para ello, entre otros.</li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="resumen_puntaje" value="Puntaje (Máximo 2)" />
                    <Input disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Puntaje" id="resumen_puntaje" type="number" input$step="0.1" input$min="0" input$max="2" class="mt-1" bind:value={$form.resumen_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.resumen_puntaje} />

                    <div class="mt-4">
                        <p>¿El resumen es correcto? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.resumen_requiere_comentario} />
                        {#if $form.resumen_requiere_comentario == false}
                            <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="resumen_comentario" bind:value={$form.resumen_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                <InfoMessage class="mb-2" message="Presenta las investigaciones, innovaciones o desarrollos tecnológicos que se han realizado a nivel internacional, nacional, departamental o municipal en el marco de la temática de la propuesta del proyecto; que muestran la pertinencia del proyecto, citar toda la información consignada utilizando normas APA última edición." />
            </div>
            <div>
                <Textarea label="Antecedentes" maxlength="40000" id="antecedentes" error={errors.antecedentes} bind:value={culturaInnovacionInfo.antecedentes} required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="marco_conceptual" value="Marco conceptual" />
                <InfoMessage class="mb-2" message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones." />
            </div>
            <div>
                <Textarea label="Marco conceptual" maxlength="20000" id="marco_conceptual" error={errors.marco_conceptual} bind:value={culturaInnovacionInfo.marco_conceptual} required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Número de los aprendices que se beneficiarán en la ejecución del proyecto</p>
            </div>
            <div>
                <Input disabled label="Número de aprendices" id="numero_aprendices" type="number" input$min="0" input$max="9999" class="mt-1" placeholder="Escriba el número de aprendices que se beneficiarán en la ejecución del proyecto" bind:value={culturaInnovacionInfo.numero_aprendices} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Nombre de los municipios beneficiados</p>
            </div>
            <div>
                <SelectMulti classes="evaluacion-select-multi" disabled={true} id="municipios" bind:selectedValue={culturaInnovacionInfo.municipios} items={municipios} isMulti={true} placeholder="Buscar municipios" />
                {#if municipios?.length == 0}
                    <div>
                        <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                        <button on:click={getMunicipios} type="button" class="flex underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refrescar
                        </button>
                    </div>
                {/if}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <p class="mb-4">Descripción del beneficio en los municipios</p>
            </div>
            <div>
                <Textarea disabled label="Descripción" maxlength="40000" id="impacto_municipios" bind:value={culturaInnovacionInfo.impacto_municipios} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <p class="mb-4">Impacto en el centro de formación</p>
            </div>
            <div>
                <Textarea disabled label="Descripción" maxlength="40000" id="impacto_centro_formacion" bind:value={culturaInnovacionInfo.impacto_centro_formacion} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Nombre de los programas de formación con registro calificado a impactar</p>
            </div>
            <div>
                <SelectMulti classes="evaluacion-select-multi" disabled={true} id="programas_formacion" bind:selectedValue={culturaInnovacionInfo.programas_formacion} items={programasFormacion} isMulti={true} placeholder="Buscar por el nombre del programa de formación" />
                {#if programasFormacion?.length == 0}
                    <div>
                        <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                        <button on:click={getProgramasFormacion} type="button" class="flex underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refrescar
                        </button>
                    </div>
                {/if}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Nombre de los programas de formación articulados</p>
            </div>
            <div>
                <SelectMulti classes="evaluacion-select-multi" disabled={true} id="programas_formacion_articulados" bind:selectedValue={culturaInnovacionInfo.programas_formacion_articulados} items={programasFormacionArticular} isMulti={true} placeholder="Buscar por el nombre del programa de formación" />
                {#if programasFormacionArticular?.length == 0}
                    <div>
                        <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                        <button on:click={getProgramasFormacion} type="button" class="flex underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refrescar
                        </button>
                    </div>
                {/if}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <p class="mb-4">Bibliografía</p>
            </div>
            <div>
                <Textarea disabled label="Bibliografía" maxlength="40000" id="bibliografia" bind:value={culturaInnovacionInfo.bibliografia} />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿La bibliografía es correcta? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.bibliografia_requiere_comentario} />
                        {#if $form.bibliografia_requiere_comentario == false}
                            <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="bibliografia_comentario" bind:value={$form.bibliografia_comentario} error={errors.bibliografia_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <hr class="mt-10 mb-10" />
        <h1>Ortografía</h1>
        <InfoMessage>
            <h1>Criterios de evaluacion</h1>
            <ul class="list-disc p-4">
                <li><strong>Puntaje: 1</strong> Todo el documento respeta y aplica las reglas ortográficas</li>
            </ul>

            <Label class="mt-4 mb-4" labelFor="ortografia_puntaje" value="Puntaje (Máximo 1)" />
            <Input disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Puntaje" id="ortografia_puntaje" type="number" input$step="1" input$min="0" input$max="1" class="mt-1" bind:value={$form.ortografia_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.ortografia_puntaje} />

            <div class="mt-4">
                <p>¿La ortografía es correcta? Por favor seleccione si Cumple o No cumple.</p>
                <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.ortografia_requiere_comentario} />
                {#if $form.ortografia_requiere_comentario == false}
                    <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="ortografia_comentario" bind:value={$form.ortografia_comentario} required />
                {/if}
            </div>
        </InfoMessage>

        <hr class="mt-10 mb-10" />
        <h1>Redacción</h1>
        <InfoMessage>
            <h1>Criterios de evaluacion</h1>
            <ul class="list-disc p-4">
                <li><strong>Puntaje: 1</strong> Todo el documento respeta y aplica las reglas gramaticales</li>
            </ul>

            <Label class="mt-4 mb-4" labelFor="redaccion_puntaje" value="Puntaje (Máximo 1)" />
            <Input disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Puntaje" id="redaccion_puntaje" type="number" input$step="1" input$min="0" input$max="1" class="mt-1" bind:value={$form.redaccion_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.redaccion_puntaje} />

            <div class="mt-4">
                <p>¿La redacción es correcto? Por favor seleccione si Cumple o No cumple.</p>
                <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.redaccion_requiere_comentario} />
                {#if $form.redaccion_requiere_comentario == false}
                    <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="redaccioncomentario" bind:value={$form.redaccion_comentario} required />
                {/if}
            </div>
        </InfoMessage>

        <hr class="mt-10 mb-10" />
        <h1>Normas APA</h1>
        <InfoMessage>
            <h1>Criterios de evaluacion</h1>
            <ul class="list-disc p-4">
                <li><strong>Puntaje: 1</strong> Las normas APA han sido aplicadas en todo el documento para referenciar y citar otros autores</li>
            </ul>

            <Label class="mt-4 mb-4" labelFor="normas_apa_puntaje" value="Puntaje (Máximo 1)" />
            <Input disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Puntaje" id="normas_apa_puntaje" type="number" input$step="1" input$min="0" input$max="1" class="mt-1" bind:value={$form.normas_apa_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.normas_apa_puntaje} />

            <div class="mt-4">
                <p>¿Las normas APA son correctas? Por favor seleccione si Cumple o No cumple.</p>
                <Switch onMessage="Cumple" offMessage="No cumple" disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={$form.normas_apa_requiere_comentario} />
                {#if $form.normas_apa_requiere_comentario == false}
                    <Textarea disabled={culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="normas_apa_comentario" bind:value={$form.normas_apa_comentario} required />
                {/if}
            </div>
        </InfoMessage>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkRole(authUser, [11]) && culturaInnovacionEvaluacion.evaluacion.finalizado == false && culturaInnovacionEvaluacion.evaluacion.habilitado == true && culturaInnovacionEvaluacion.evaluacion.modificable == true)}
                {#if $form.clausula_confidencialidad}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-green-500">Ha aceptado la cláusula de confidencialidad</span>
                {:else}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-red-500">No ha aceptado la cláusula de confidencialidad</span>
                {/if}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={proyectoDialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/feedback.png'} alt="Evaluación" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {culturaInnovacion.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Cláusula de confidencialidad</h1>
                <p class="mb-4">
                    Al hacer clic en el botón Aceptar, dejo constancia del tratamiento confidencial que daré a la información relacionada con el proceso de evaluación que incluye, sin limitarse a esta, la información sobre proyectos, centros de formación, formuladores, autores y coautores de proyectos, resultados del proceso de evaluación en sus dos etapas. Por tanto, declaro que me abstendré de
                    usar o divulgar para cualquier fin y por cualquier medio la información enunciada.
                </p>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (($form.clausula_confidencialidad = true), (proyectoDialogOpen = false))} variant={null}>Aceptar</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={dialogSegundaEvaluacion} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/feedback.png'} alt="Evaluación" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {culturaInnovacion.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Importante</h1>

                <p>Antes de iniciar a la segunda evaluación por favor diríjase a la sección <strong>Comentarios generales</strong> y verifique si el proponente hizo alguna aclaración sobre algún ítem.</p>

                {#if culturaInnovacion.proyecto.pdf_versiones}
                    <hr class="mx-4" />
                    <p class="mt-4">También revise la versión del proyecto en .pdf para ir verificando los cambios realizados en los diferentes campos.</p>
                    <h1 class="text-center mt-4 mb-4">Version del proyecto (.pdf)</h1>
                    <ul>
                        {#each culturaInnovacion.proyecto.pdf_versiones as version}
                            <li>
                                {#if version.estado == 1}
                                    <a class="text-indigo-500 underline" href={route('convocatorias.proyectos.version', [convocatoria.id, culturaInnovacion.id, version.version])}> {version.version}.pdf - Descargar</a>
                                    <small class="block">{version.created_at}</small>
                                {/if}
                            </li>
                        {/each}
                    </ul>
                {/if}
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogSegundaEvaluacion = false)} variant={null}>Aceptar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
