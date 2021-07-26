<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { Inertia } from '@inertiajs/inertia'
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
    import Switch from '@/Shared/Switch'
    import FormField from '@smui/form-field'
    import Radio from '@smui/radio'
    import Dialog from '@/Shared/Dialog'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let convocatoria
    export let idi
    export let idiEvaluacion
    export let mesasSectoriales
    export let mesasSectorialesRelacionadas
    export let lineasTecnoacademiaRelacionadas
    export let tecnoacademias
    export let tecnoacademia
    export let opcionesIDiDropdown
    export let proyectoMunicipios
    export let proyectoProgramasFormacion
    export let proyectoProgramasFormacionArticulados

    $: $title = idi ? idi.titulo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let municipios
    let programasFormacion
    let programasFormacionArticular
    let proyectoDialogOpen = true
    let sending = false
    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]

    let lineasTecnologicas = []
    let tieneVideo = idi.video != null
    let requiereJustificacionIndustria4 = idi.justificacion_industria_4 != null
    let requiereJustificacionEconomiaNaranja = idi.justificacion_economia_naranja != null
    let requiereJustificacionPoliticaDiscapacidad = idi.justificacion_politica_discapacidad != null

    let idiInfo = {
        centro_formacion_id: idi.proyecto?.centro_formacion_id,
        linea_investigacion_id: idi.linea_investigacion_id,
        area_conocimiento_id: idi.disciplina_subarea_conocimiento.subarea_conocimiento.area_conocimiento_id,
        subarea_conocimiento_id: idi.disciplina_subarea_conocimiento.subarea_conocimiento_id,
        disciplina_subarea_conocimiento_id: idi.disciplina_subarea_conocimiento_id,
        tematica_estrategica_id: idi.tematica_estrategica_id,
        red_conocimiento_id: idi.red_conocimiento_id,
        linea_programatica_id: idi.proyecto?.linea_programatica_id,
        actividad_economica_id: idi.actividad_economica_id,
        titulo: idi.titulo,
        fecha_inicio: idi.fecha_inicio,
        fecha_finalizacion: idi.fecha_finalizacion,
        max_meses_ejecucion: idi.max_meses_ejecucion,
        video: idi.video,
        justificacion_industria_4: idi.justificacion_industria_4,
        justificacion_economia_naranja: idi.justificacion_economia_naranja,
        justificacion_politica_discapacidad: idi.justificacion_politica_discapacidad,
        resumen: idi.resumen,
        antecedentes: idi.antecedentes,
        identificacion_problema: idi.identificacion_problema,
        justificacion_problema: idi.justificacion_problema,
        marco_conceptual: idi.marco_conceptual,
        bibliografia: idi.bibliografia,
        numero_aprendices: idi.numero_aprendices,
        municipios: proyectoMunicipios.length > 0 ? proyectoMunicipios : null,
        programas_formacion: proyectoProgramasFormacion.length > 0 ? proyectoProgramasFormacion : null,
        programas_formacion_articulados: proyectoProgramasFormacionArticulados.length > 0 ? proyectoProgramasFormacionArticulados : null,
        impacto_municipios: idi.impacto_municipios,
        impacto_centro_formacion: idi.impacto_centro_formacion,
        muestreo: idi.muestreo,
        actividades_muestreo: idi.actividades_muestreo,
        objetivo_muestreo: idi.objetivo_muestreo,
        recoleccion_especimenes: {
            value: idi.recoleccion_especimenes,
            label: opcionesSiNo.find((item) => item.value == idi.recoleccion_especimenes)?.label,
        },
        relacionado_plan_tecnologico: {
            value: idi.relacionado_plan_tecnologico,
            label: opcionesIDiDropdown.find((item) => item.value == idi.relacionado_plan_tecnologico)?.label,
        },
        relacionado_agendas_competitividad: {
            value: idi.relacionado_agendas_competitividad,
            label: opcionesIDiDropdown.find((item) => item.value == idi.relacionado_agendas_competitividad)?.label,
        },
        relacionado_mesas_sectoriales: {
            value: idi.relacionado_mesas_sectoriales,
            label: opcionesIDiDropdown.find((item) => item.value == idi.relacionado_mesas_sectoriales)?.label,
        },
        relacionado_tecnoacademia: {
            value: idi.relacionado_tecnoacademia,
            label: opcionesIDiDropdown.find((item) => item.value == idi.relacionado_tecnoacademia)?.label,
        },
        tecnoacademia_id: {
            value: tecnoacademia?.id,
            label: tecnoacademias.find((item) => item.value == tecnoacademia?.id)?.label,
        },

        linea_tecnologica_id: lineasTecnoacademiaRelacionadas,
        mesa_sectorial_id: mesasSectorialesRelacionadas,
    }

    $: if (idiInfo.fecha_inicio && idiInfo.fecha_finalizacion) {
        idiInfo.max_meses_ejecucion = monthDiff(idiInfo.fecha_inicio, idiInfo.fecha_finalizacion)
    }

    onMount(() => {
        if (tecnoacademia) {
            getLineasTecnologicas(tecnoacademia)
        }
        getMunicipios()
        getProgramasFormacion()
        getProgramasFormacionArticular()
    })

    let form = useForm({
        titulo_puntaje: idiEvaluacion.titulo_puntaje,
        titulo_comentario: idiEvaluacion.titulo_comentario,
        titulo_requiere_comentario: idiEvaluacion.titulo_comentario == null ? false : true,
        video_puntaje: idiEvaluacion.video_puntaje,
        video_comentario: idiEvaluacion.video_comentario,
        video_requiere_comentario: idiEvaluacion.video_comentario == null ? false : true,
        resumen_puntaje: idiEvaluacion.resumen_puntaje,
        resumen_comentario: idiEvaluacion.resumen_comentario,
        resumen_requiere_comentario: idiEvaluacion.resumen_comentario == null ? false : true,
        problema_central_puntaje: idiEvaluacion.problema_central_puntaje,
        problema_central_comentario: idiEvaluacion.problema_central_comentario,
        problema_central_requiere_comentario: idiEvaluacion.problema_central_comentario == null ? false : true,
        ortografia_puntaje: idiEvaluacion.ortografia_puntaje,
        ortografia_comentario: idiEvaluacion.ortografia_comentario,
        ortografia_requiere_comentario: idiEvaluacion.ortografia_comentario == null ? false : true,
        redaccion_puntaje: idiEvaluacion.redaccion_puntaje,
        redaccion_comentario: idiEvaluacion.redaccion_comentario,
        redaccion_requiere_comentario: idiEvaluacion.redaccion_comentario == null ? false : true,
        normas_apa_puntaje: idiEvaluacion.normas_apa_puntaje,
        normas_apa_comentario: idiEvaluacion.normas_apa_comentario,
        normas_apa_requiere_comentario: idiEvaluacion.normas_apa_comentario == null ? false : true,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && idi.proyecto.finalizado == true)) {
            $form.put(route('convocatorias.idi-evaluaciones.update', [convocatoria.id, idiEvaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    $: selectedTecnoacademia = idiInfo.tecnoacademia_id?.value

    $: if (selectedTecnoacademia) {
        getLineasTecnologicas(selectedTecnoacademia)
    }

    async function getLineasTecnologicas(tecnoacademia) {
        let res = await axios.get(route('web-api.tecnoacademias.lineas-tecnoacademia', [tecnoacademia]))
        res.status == '200' ? (idiInfo.linea_tecnologica_id = lineasTecnoacademiaRelacionadas) : null
        lineasTecnologicas = res.data
    }

    async function getMunicipios() {
        let res = await axios.get(route('web-api.municipios'))
        if (res.status == '200') {
            municipios = res.data
        }
    }

    async function getProgramasFormacion() {
        let res = await axios.get(route('web-api.programas-formacion', idiInfo.centro_formacion_id))
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
    <EvaluationStepper {convocatoria} evaluacion={idiEvaluacion.evaluacion} proyecto={idi.proyecto} />

    <form on:submit|preventDefault={submit}>
        <div class="mt-28">
            <p class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full">Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué. (Máximo 20 palabras)</p>
            <Textarea disabled label="Título" id="titulo" sinContador={true} bind:value={idiInfo.titulo} classes="bg-transparent block border-0 mt-1 outline-none text-4xl text-center w-full" />

            <InfoMessage>
                <h1>Criterios de evaluacion</h1>
                <ul class="list-disc p-4">
                    <li><strong>Puntaje: 0,0 a 0,5</strong> El título orienta el enfoque del proyecto</li>
                    <li><strong>Puntaje: 0,6 a 1,0</strong> El título orienta el enfoque del proyecto e indica el cómo y el para qué</li>
                </ul>
                <Label class="mt-4 mb-4" labelFor="titulo_puntaje" value="Puntaje (Máximo 1)" />
                <Input label="Puntaje" id="titulo_puntaje" type="number" input$step="0.1" input$min="0" input$max="1" class="mt-1" bind:value={$form.titulo_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.titulo_puntaje} />

                <div class="mt-4">
                    <p>¿El título requiere de una recomendación?</p>
                    <Switch bind:checked={$form.titulo_requiere_comentario} />
                    {#if $form.titulo_requiere_comentario}
                        <Textarea label="Comentario" class="mt-4" maxlength="40000" id="titulo_comentario" bind:value={$form.titulo_comentario} />
                    {/if}
                </div>
            </InfoMessage>
        </div>

        <div class="mt-44">
            <p class="text-center">Fecha de ejecución</p>
            <div class="mt-4 flex items-start justify-around">
                <div class="mt-4 flex items-center">
                    <p>Del</p>
                    <div class="ml-4">
                        <input disabled id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_idi} max={convocatoria.max_fecha_finalizacion_proyectos_idi} bind:value={idiInfo.fecha_inicio} />
                    </div>
                </div>
                <div class="mt-4 flex">
                    <p>hasta</p>
                    <div class="ml-4">
                        <input disabled id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_idi} max={convocatoria.max_fecha_finalizacion_proyectos_idi} bind:value={idiInfo.fecha_finalizacion} />
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Centro de formación</p>
            </div>
            <div>
                {idi.proyecto.centro_formacion.nombre}
            </div>
        </div>

        {#if idiInfo.centro_formacion_id}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <p class="mb-4">Línea de investigación</p>
                </div>
                <div>
                    <DynamicList disabled={true} id="linea_investigacion_id" bind:value={idiInfo.linea_investigacion_id} routeWebApi={route('web-api.lineas-investigacion', idiInfo.centro_formacion_id)} classes="min-h" placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional" />
                </div>
            </div>
        {/if}
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Código dependencia presupuestal (SIIF)</p>
            </div>
            <div>
                <DynamicList disabled={true} id="linea_programatica_id" bind:value={idiInfo.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas', 2)} classes="min-h" placeholder="Busque por el nombre de la línea programática" />
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Red de conocimiento sectorial</p>
            </div>
            <div>
                <DynamicList disabled={true} id="red_conocimiento_id" bind:value={idiInfo.red_conocimiento_id} routeWebApi={route('web-api.redes-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la red de conocimiento sectorial" />
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Área de conocimiento</p>
            </div>
            <div>
                <DynamicList disabled={true} id="area_conocimiento_id" bind:value={idiInfo.area_conocimiento_id} routeWebApi={route('web-api.areas-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la área de conocimiento" />
            </div>
        </div>
        {#if idiInfo.area_conocimiento_id}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <p class="mb-4">Subárea de conocimiento</p>
                </div>
                <div>
                    <DynamicList disabled={true} id="subarea_conocimiento_id" bind:value={idiInfo.subarea_conocimiento_id} routeWebApi={route('web-api.subareas-conocimiento', idiInfo.area_conocimiento_id)} classes="min-h" placeholder="Busque por el nombre de la subárea de conocimiento" />
                </div>
            </div>
        {/if}
        {#if idiInfo.subarea_conocimiento_id}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <p class="mb-4">Disciplina de la subárea de conocimiento</p>
                </div>
                <div>
                    <DynamicList disabled={true} id="disciplina_subarea_conocimiento_id" bind:value={idiInfo.disciplina_subarea_conocimiento_id} routeWebApi={route('web-api.disciplinas-subarea-conocimiento', idiInfo.subarea_conocimiento_id)} classes="min-h" placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento" />
                </div>
            </div>
        {/if}
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿En cuál de estas actividades económicas se puede aplicar el proyecto?</p>
            </div>
            <div>
                <DynamicList disabled={true} id="actividad_economica_id" bind:value={idiInfo.actividad_economica_id} routeWebApi={route('web-api.actividades-economicas')} placeholder="Busque por el nombre de la actividad económica" classes="min-h" />
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Temática estratégica SENA</p>
            </div>
            <div>
                <DynamicList disabled={true} id="tematica_estrategica_id" bind:value={idiInfo.tematica_estrategica_id} routeWebApi={route('web-api.tematicas-estrategicas')} placeholder="Busque por el nombre de la temática estrategica SENA" />
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <p>¿El proyecto tiene video?</p>
            </div>
            <div>
                <Switch disabled bind:checked={tieneVideo} />
                {#if tieneVideo}
                    <Input disabled label="Link del video" id="video" type="url" class="mt-1" placeholder="Link del video del proyecto https://www.youtube.com/watch?v=gmc4tk" bind:value={idiInfo.video} />

                    <InfoMessage>
                        <h1>Criterios de evaluacion</h1>
                        <ul class="list-disc p-4">
                            <li><strong>Puntaje: 0,0 a 0,5</strong> El video no cumple los 3 minutos establecidos y no presenta de forma clara la justificación, la problemática, el objetivo general, los objetivos específicos, las actividades, los productos y/o su impacto de acuerdo con los lineamientos de la convocatoria</li>
                            <li><strong>Puntaje: 0,6 a 1,0</strong> El video cumple los 3 minutos establecidos y presenta la justificación, la problemática, el objetivo general, los objetivos específicos, las actividades, los productos y su impacto de acuerdo con los lineamientos de la convocatoria</li>
                        </ul>

                        <Label class="mt-4 mb-4" labelFor="video_puntaje" value="Puntaje (Máximo 1)" />
                        <Input label="Puntaje" id="video_puntaje" type="number" input$step="0.1" input$min="0" input$max="1" class="mt-1" bind:value={$form.video_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.video_puntaje} />

                        <div class="mt-4">
                            <p>¿El video requiere de una recomendación?</p>
                            <Switch bind:checked={$form.video_requiere_comentario} />
                            {#if $form.video_requiere_comentario}
                                <Textarea label="Comentario" class="mt-4" maxlength="40000" id="video_comentario" bind:value={$form.video_comentario} />
                            {/if}
                        </div>
                    </InfoMessage>
                {/if}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p>¿El proyecto está relacionado con la industria 4.0?</p>
            </div>
            <div>
                <div class="flex items-center mb-14">
                    <Switch disabled bind:checked={requiereJustificacionIndustria4} />
                </div>

                {#if requiereJustificacionIndustria4}
                    <Textarea disabled label="Justificación" maxlength="40000" id="justificacion_industria_4" bind:value={idiInfo.justificacion_industria_4} />
                {/if}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p>¿El proyecto está relacionado con la economía naranja?</p>
            </div>
            <div>
                <div class="flex items-center mb-14">
                    <Switch disabled bind:checked={requiereJustificacionEconomiaNaranja} />
                </div>
                {#if requiereJustificacionEconomiaNaranja}
                    <Textarea disabled label="Justificación" maxlength="40000" id="justificacion_economia_naranja" bind:value={idiInfo.justificacion_economia_naranja} />
                {/if}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p>¿El proyecto aporta a la Política Institucional para Atención de las Personas con discapacidad?</p>
            </div>
            <div>
                <div class="flex items-center mb-14">
                    <Switch disabled bind:checked={requiereJustificacionPoliticaDiscapacidad} />
                </div>
                {#if requiereJustificacionPoliticaDiscapacidad}
                    <Textarea disabled label="Justificación" maxlength="40000" id="justificacion_politica_discapacidad" bind:value={idiInfo.justificacion_politica_discapacidad} />
                {/if}
            </div>
        </div>

        <hr class="mt-5 mb-5" />

        <div>
            <p class="text-center mt-36 mb-8">¿Cuál es el origen de las muestras con las que se realizarán las actividades de investigación, bioprospección y/o aprovechamiento comercial o industrial?</p>
            <div class="flex mt-20 items-center">
                <FormField>
                    <Radio disabled bind:group={idiInfo.muestreo} value="1" />
                    <span slot="label">
                        Especies Nativas. (es la especie o subespecie taxonómica o variedad de animales cuya área de disposición geográfica se extiende al territorio nacional o a aguas jurisdiccionales colombianas o forma parte de los mismos comprendidas las especies o subespecies que migran temporalmente a ellos, siempre y cuando no se encuentren en el país o migren a él como resultado voluntario
                        o involuntario de la actividad humana. Pueden ser silvestre, domesticada o escapada de domesticación incluyendo virus, viroides y similares)
                    </span>
                </FormField>
            </div>

            <!-- Si seleccionan Especies nativas -->
            {#if idiInfo.muestreo == 1}
                <div class="flex mb-20">
                    <div class="bg-gray-200 flex-1 p-8">
                        <div class="flex items-center">
                            <p class="mb-4" id="1.1">¿Qué actividad pretende realizar con la especie nativa?</p>
                        </div>

                        <p class="bg-indigo-100 mt-10 p-4 text-indigo-600">Seleccione una opción</p>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={idiInfo.actividades_muestreo} value="1.1.1" />
                                <span slot="label"> Separación de las unidades funcionales y no funcionales del ADN y el ARN, en todas las formas que se encuentran en la naturaleza. </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={idiInfo.actividades_muestreo} value="1.1.2" />
                                <span slot="label"> Aislamiento de una o varias moléculas, entendidas estas como micro y macromoléculas, producidas por el metabolismo de un organismo. </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={idiInfo.actividades_muestreo} value="1.1.3" />
                                <span slot="label"> Solicitar patente sobre una función o propiedad identificada de una molécula, que se ha aislado y purificado. </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={idiInfo.actividades_muestreo} value="1.1.4" />
                                <span slot="label"> No logro identificar la actividad a desarrollar con la especie nativa </span>
                            </FormField>
                        </div>
                    </div>

                    <div class="bg-gray-300 flex-1 p-8">
                        <div class="flex items-center">
                            <p class="mb-4" id="1.2">¿Cuál es la finalidad de las actividades a realizar con la especie nativa/endémica?</p>
                        </div>

                        <p class="bg-indigo-100 mt-10 p-4 text-indigo-600">Seleccione una opción</p>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={idiInfo.objetivo_muestreo} value="1.2.1" />
                                <span slot="label"> Investigación básica sin fines comerciales </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={idiInfo.objetivo_muestreo} value="1.2.2" />
                                <span slot="label"> Bioprospección en cualquiera de sus fases </span>
                            </FormField>
                        </div>
                        <div class="flex mt-4 items-center">
                            <FormField>
                                <Radio disabled bind:group={idiInfo.objetivo_muestreo} value="1.2.3" />
                                <span slot="label"> Comercial o Industrial </span>
                            </FormField>
                        </div>
                    </div>
                </div>
            {/if}

            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={idiInfo.muestreo} value="2" />
                    <span slot="label"> Especies Introducidas. (son aquellas que no son nativas de Colombia y que ingresaron al país por intervención humana) </span>
                </FormField>
            </div>
            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={idiInfo.muestreo} value="3" />
                    <span slot="label"> Recursos genéticos humanos y sus productos derivados </span>
                </FormField>
            </div>
            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={idiInfo.muestreo} value="4" />
                    <span slot="label"> Intercambio de recursos genéticos y sus productos derivados, recursos biológicos que los contienen o los componentes asociados a estos. (son aquellas que realizan las comunidades indígenas, afroamericanas y locales de los Países Miembros de la Comunidad Andina entre sí y para su propio consumo, basadas en sus prácticas consuetudinarias) </span>
                </FormField>
            </div>
            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={idiInfo.muestreo} value="5" />
                    <span slot="label">
                        Recurso biológico que involucren actividades de sistemática molecular, ecología molecular, evolución y biogeografía molecular (siempre que el recurso biológico se haya colectado en el marco de un permiso de recolección de especímenes de especies silvestres de la diversidad biológica con fines de investigación científica no comercial o provenga de una colección registrada
                        ante el Instituto Alexander van Humboldt)
                    </span>
                </FormField>
            </div>
            <div class="flex mt-4 items-center">
                <FormField>
                    <Radio disabled bind:group={idiInfo.muestreo} value="6" />
                    <span slot="label"> No aplica </span>
                </FormField>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">
                    En la ejecución del proyecto se requiere la recolección de especímenes de especies silvestres de la diversidad biológica con fines de elaboración de estudios ambientales (entendiendo como recolección los procesos de remoción o extracción temporal o definitiva de una especie ya sea vegetal o animal del medio natural) Nota: este permiso no se requiere cuando las actividades de
                    recolección se limiten a investigaciones científicas o con fines industriales, comerciales o de prospección biológica."
                </p>
            </div>
            <div>
                <Select disabled={true} items={opcionesSiNo} id="recoleccion_especimenes" bind:selectedValue={idiInfo.recoleccion_especimenes} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
            <div />
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto se alinea con el plan tecnológico desarrollado por el centro de formación?</p>
            </div>
            <div>
                <Select disabled={true} items={opcionesIDiDropdown} id="relacionado_plan_tecnologico" bind:selectedValue={idiInfo.relacionado_plan_tecnologico} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto se alinea con las Agendas Departamentales de Competitividad e Innovación?</p>
            </div>
            <div>
                <Select disabled={true} items={opcionesIDiDropdown} id="relacionado_agendas_competitividad" bind:selectedValue={idiInfo.relacionado_agendas_competitividad} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">¿El proyecto se alinea con las Mesas Sectoriales?</p>
            </div>
            <div>
                <Select disabled={true} items={opcionesIDiDropdown} id="relacionado_mesas_sectoriales" bind:selectedValue={idiInfo.relacionado_mesas_sectoriales} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
        </div>
        {#if idiInfo.relacionado_mesas_sectoriales?.value == 1}
            <div class="grid grid-cols-2">
                <div>Mesas sectoriales relacionadas:</div>
                <div>
                    <ul class="list-disc p-4">
                        {#each mesasSectoriales as { id, nombre }, i}
                            {#each idiInfo.mesa_sectorial_id as mesaSectorialRelacionada}
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
                <Select disabled={true} items={opcionesIDiDropdown} id="relacionado_tecnoacademia" bind:selectedValue={idiInfo.relacionado_tecnoacademia} autocomplete="off" placeholder="Seleccione una opción" />
            </div>
        </div>

        {#if idiInfo.relacionado_tecnoacademia?.value == 1}
            {#if isSuperAdmin}
                <div class="bg-indigo-100 p-5 mt-10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5" style="transform: translateX(-50px);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <div class="grid grid-cols-2">
                        <div>
                            <p class="text-indigo-600">Por favor seleccione la Tecnoacademia con la cual articuló el proyecto</p>
                        </div>
                        <div>
                            <Select disabled={true} items={tecnoacademias} id="tecnoacademia_id" bind:selectedValue={idiInfo.tecnoacademia_id} autocomplete="off" placeholder="Seleccione una opción" />
                            {#if lineasTecnologicas?.length > 0}
                                <div class="bg-white grid grid-cols-2 max-w-xl overflow-y-scroll shadow-2xl mt-4 h-80">
                                    {#each lineasTecnologicas as { value, label }, i}
                                        <Label class="p-3 border-t border-b flex items-center text-sm" labelFor={'linea-tecnologica-' + value} value={label} />

                                        <div class="border-b border-t flex items-center justify-center">
                                            <input disabled type="checkbox" bind:group={idiInfo.linea_tecnologica_id} id={'linea-tecnologica-' + value} {value} class="rounded text-indigo-500" />
                                        </div>
                                    {/each}
                                </div>
                            {:else}
                                <div>
                                    <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                                    <button on:click={getLineasTecnologicas} type="button" class="flex underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Refrescar
                                    </button>
                                </div>
                            {/if}
                        </div>
                    </div>
                    <Input disabledError />
                </div>
            {:else}
                <div class="grid grid-cols-2">
                    <div>Tecnoacademia relacionada</div>
                    <div>
                        <Select disabled={true} items={tecnoacademias} id="tecnoacademia_id" bind:selectedValue={idiInfo.tecnoacademia_id} autocomplete="off" placeholder="Seleccione una opción" />
                    </div>
                </div>
                <div class="grid grid-cols-2">
                    <div>Líneas tecnológicas relacionadas:</div>
                    <div>
                        <ul class="list-disc p-4">
                            {#each lineasTecnologicas as { value, label }, i}
                                {#each idiInfo.linea_tecnologica_id as lineaTecnologica}
                                    {#if value == lineaTecnologica}
                                        <li>{label}</li>
                                    {/if}
                                {/each}
                            {/each}
                        </ul>
                    </div>
                </div>
            {/if}
        {/if}

        <div class="mt-40 grid grid-cols-1">
            <div>
                <p class="mb-4">Resumen del proyecto</p>
            </div>
            <div>
                <Textarea disabled label="Resumen" maxlength="40000" id="resumen" bind:value={idiInfo.resumen} />

                <InfoMessage>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li><strong>Puntaje: 0,0 a 1,0</strong> El resumen no presenta de forma clara la pertinencia y calidad del proyecto, en términos de cuál es el problema central, cómo se le dará solución y cuáles serán las herramientas que se utilizarán para ello, entre otros.</li>
                        <li><strong>Puntaje: 1,1 a 2,0</strong> El resumen presenta de forma clara la pertinencia y calidad del proyecto e incluye todos los elementos en términos de cuál es el problema central, cómo se le dará solución y cuáles serán las herramientas que se utilizarán para ello, entre otros.</li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="resumen_puntaje" value="Puntaje (Máximo 2)" />
                    <Input label="Puntaje" id="resumen_puntaje" type="number" input$step="0.1" input$min="0" input$max="2" class="mt-1" bind:value={$form.resumen_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.resumen_puntaje} />

                    <div class="mt-4">
                        <p>¿El resumen requiere de una recomendación?</p>
                        <Switch bind:checked={$form.resumen_requiere_comentario} />
                        {#if $form.resumen_requiere_comentario}
                            <Textarea label="Comentario" class="mt-4" maxlength="40000" id="resumen_comentario" bind:value={$form.resumen_comentario} />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Número de los aprendices que se beneficiarán en la ejecución del proyecto</p>
            </div>
            <div>
                <Input disabled label="Número de aprendices" id="numero_aprendices" type="number" input$min="0" input$max="9999" class="mt-1" placeholder="Escriba el número de aprendices que se beneficiarán en la ejecución del proyecto" bind:value={idiInfo.numero_aprendices} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Nombre de los municipios beneficiados</p>
            </div>
            <div>
                <SelectMulti disabled={true} id="municipios" bind:selectedValue={idiInfo.municipios} items={municipios} isMulti={true} placeholder="Buscar municipios" />
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

        <div class="mt-44 grid grid-cols-2">
            <div>
                <p class="mb-4">Nombre de los programas de formación con registro calificado a impactar</p>
            </div>
            <div>
                <SelectMulti disabled={true} id="programas_formacion" bind:selectedValue={idiInfo.programas_formacion} items={programasFormacion} isMulti={true} placeholder="Buscar por el nombre del programa de formación" />
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
                <p class="mb-4">Nombre de los programas de formación articulados"</p>
            </div>
            <div>
                <SelectMulti disabled={true} id="programas_formacion_articulados" bind:selectedValue={idiInfo.programas_formacion_articulados} items={programasFormacionArticular} isMulti={true} placeholder="Buscar por el nombre del programa de formación" />
                {#if programasFormacionArticular?.length == 0}
                    <div>
                        <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                        <button on:click={getProgramasFormacionArticular} type="button" class="flex underline">
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
                <Textarea disabled label="Descripción" maxlength="40000" id="impacto_municipios" bind:value={idiInfo.impacto_municipios} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <p class="mb-4">Impacto en el centro de formación</p>
            </div>
            <div>
                <Textarea disabled label="Descripción" maxlength="40000" id="impacto_centro_formacion" bind:value={idiInfo.impacto_centro_formacion} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <p class="mb-4">Bibliografía</p>
            </div>
            <div>
                <Textarea disabled label="Bibliografía" maxlength="40000" id="bibliografia" bind:value={idiInfo.bibliografia} />
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
            <Input label="Puntaje" id="ortografia_puntaje" type="number" input$step="1" input$min="0" input$max="1" class="mt-1" bind:value={$form.ortografia_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.ortografia_puntaje} />

            <div class="mt-4">
                <p>¿La ortografía requiere de una recomendación?</p>
                <Switch bind:checked={$form.ortografia_requiere_comentario} />
                {#if $form.ortografia_requiere_comentario}
                    <Textarea label="Comentario" class="mt-4" maxlength="40000" id="ortografia_comentario" bind:value={$form.ortografia_comentario} />
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
            <Input label="Puntaje" id="redaccion_puntaje" type="number" input$step="1" input$min="0" input$max="1" class="mt-1" bind:value={$form.redaccion_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.redaccion_puntaje} />

            <div class="mt-4">
                <p>¿La redacción requiere de una recomendación?</p>
                <Switch bind:checked={$form.redaccion_requiere_comentario} />
                {#if $form.redaccion_requiere_comentario}
                    <Textarea label="Comentario" class="mt-4" maxlength="40000" id="redaccioncomentario" bind:value={$form.redaccion_comentario} />
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
            <Input label="Puntaje" id="normas_apa_puntaje" type="number" input$step="1" input$min="0" input$max="1" class="mt-1" bind:value={$form.normas_apa_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.normas_apa_puntaje} />

            <div class="mt-4">
                <p>¿Las normas APA requieren de una recomendación?</p>
                <Switch bind:checked={$form.normas_apa_requiere_comentario} />
                {#if $form.normas_apa_requiere_comentario}
                    <Textarea label="Comentario" class="mt-4" maxlength="40000" id="normas_apa_comentario" bind:value={$form.normas_apa_comentario} />
                {/if}
            </div>
        </InfoMessage>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkRole(authUser, [11]) && idi.proyecto.finalizado == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={proyectoDialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/feedback.png'} alt="Evaluación" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {idi.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Evaluación</h1>
                <p class="text-center mb-4">Realice la evaluacion cuantitativa de los siguientes ítems:</p>
                <ul class="list-disc">
                    <li>Título</li>
                    <li>Video</li>
                    <li>Resumen</li>
                    <li>Antecedentes, árbol de problemas, identificación y descripción del problema, justificación y marco conceptual</li>
                    <li>Árbol de objetivos, objetivo genral y objetivos específicos</li>
                    <li>Metodología y actividades</li>
                    <li>Resultados esperados</li>
                    <li>Productos esperados</li>
                    <li>Impactos, propuesta de sostenibilidad y la cadena de valor</li>
                    <li>Análisis de riesgos</li>
                    <li>Ortografía</li>
                    <li>Redacción</li>
                    <li>Normas APA</li>
                </ul>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (proyectoDialogOpen = false)} variant={null}>Omitir</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
