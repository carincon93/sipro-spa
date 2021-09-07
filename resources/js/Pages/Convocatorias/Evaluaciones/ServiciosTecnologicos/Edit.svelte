<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { onMount } from 'svelte'
    import axios from 'axios'

    import Button from '@/Shared/Button'
    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'
    import Switch from '@/Shared/Switch'
    import SelectMulti from '@/Shared/SelectMulti'

    export let errors
    export let convocatoria
    export let servicioTecnologico
    export let servicioTecnologicoEvaluacion
    export let tiposProyectoSt
    export let servicioTecnologicoSegundaEvaluacion
    export let sectoresProductivos
    export let proyectoProgramasFormacion

    let programasFormacion

    $: $title = servicioTecnologico ? servicioTecnologico.titulo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let proyectoDialogOpen = servicioTecnologicoEvaluacion.evaluacion.clausula_confidencialidad == false ? true : false
    let sending = false

    let servicioTecnologicoInfo = {
        tipo_proyecto_st_id: {
            value: servicioTecnologico.tipo_proyecto_st_id,
            label: tiposProyectoSt.find((item) => item.value == servicioTecnologico.tipo_proyecto_st_id)?.label,
        },
        linea_programatica_id: servicioTecnologico.proyecto?.linea_programatica_id,
        titulo: servicioTecnologico.titulo,
        fecha_inicio: servicioTecnologico.fecha_inicio,
        fecha_finalizacion: servicioTecnologico.fecha_finalizacion,
        max_meses_ejecucion: servicioTecnologico.max_meses_ejecucion,
        resumen: servicioTecnologico.resumen,
        antecedentes: servicioTecnologico.antecedentes,
        bibliografia: servicioTecnologico.bibliografia,
        zona_influencia: servicioTecnologico.zona_influencia,

        identificacion_problema: servicioTecnologico.identificacion_problema,
        pregunta_formulacion_problema: servicioTecnologico.pregunta_formulacion_problema,
        justificacion_problema: servicioTecnologico.justificacion_problema,

        programas_formacion: proyectoProgramasFormacion.length > 0 ? proyectoProgramasFormacion : null,

        estado_sistema_gestion_id: servicioTecnologico.estado_sistema_gestion_id,
        sector_productivo: {
            value: servicioTecnologico.sector_productivo,
            label: sectoresProductivos.find((item) => item.value == servicioTecnologico.sector_productivo)?.label,
        },
    }

    let form = useForm({
        clausula_confidencialidad: servicioTecnologicoEvaluacion.evaluacion.clausula_confidencialidad,

        fechas_requiere_comentario: servicioTecnologicoEvaluacion.fecha_ejecucion_comentario == null ? true : false,
        fecha_ejecucion_comentario: servicioTecnologicoEvaluacion.fecha_ejecucion_comentario,

        titulo_puntaje: servicioTecnologicoEvaluacion.titulo_puntaje,
        titulo_comentario: servicioTecnologicoEvaluacion.titulo_comentario,
        titulo_requiere_comentario: servicioTecnologicoEvaluacion.titulo_comentario == null ? true : false,

        resumen_puntaje: servicioTecnologicoEvaluacion.resumen_puntaje,
        resumen_comentario: servicioTecnologicoEvaluacion.resumen_comentario,
        resumen_requiere_comentario: servicioTecnologicoEvaluacion.resumen_comentario == null ? true : false,

        antecedentes_puntaje: servicioTecnologicoEvaluacion.antecedentes_puntaje,
        antecedentes_comentario: servicioTecnologicoEvaluacion.antecedentes_comentario,
        antecedentes_requiere_comentario: servicioTecnologicoEvaluacion.antecedentes_comentario == null ? true : false,

        identificacion_problema_puntaje: servicioTecnologicoEvaluacion.identificacion_problema_puntaje,
        identificacion_problema_comentario: servicioTecnologicoEvaluacion.identificacion_problema_comentario,
        identificacion_problema_requiere_comentario: servicioTecnologicoEvaluacion.identificacion_problema_comentario == null ? true : false,

        pregunta_formulacion_problema_puntaje: servicioTecnologicoEvaluacion.pregunta_formulacion_problema_puntaje,
        pregunta_formulacion_problema_comentario: servicioTecnologicoEvaluacion.pregunta_formulacion_problema_comentario,
        pregunta_formulacion_problema_requiere_comentario: servicioTecnologicoEvaluacion.pregunta_formulacion_problema_comentario == null ? true : false,

        justificacion_problema_puntaje: servicioTecnologicoEvaluacion.justificacion_problema_puntaje,
        justificacion_problema_comentario: servicioTecnologicoEvaluacion.justificacion_problema_comentario,
        justificacion_problema_requiere_comentario: servicioTecnologicoEvaluacion.justificacion_problema_comentario == null ? true : false,

        bibliografia_requiere_comentario: servicioTecnologicoEvaluacion.bibliografia_comentario == null ? true : false,
        bibliografia_comentario: servicioTecnologicoEvaluacion.bibliografia_comentario,

        ortografia_comentario: servicioTecnologicoEvaluacion.ortografia_comentario,
        ortografia_requiere_comentario: servicioTecnologicoEvaluacion.ortografia_comentario == null ? true : false,
        redaccion_comentario: servicioTecnologicoEvaluacion.redaccion_comentario,
        redaccion_requiere_comentario: servicioTecnologicoEvaluacion.redaccion_comentario == null ? true : false,
        normas_apa_comentario: servicioTecnologicoEvaluacion.normas_apa_comentario,
        normas_apa_requiere_comentario: servicioTecnologicoEvaluacion.normas_apa_comentario == null ? true : false,
    })

    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && servicioTecnologico.proyecto.finalizado == true && servicioTecnologicoEvaluacion.evaluacion.finalizado == false && servicioTecnologicoEvaluacion.evaluacion.habilitado == true && servicioTecnologicoEvaluacion.evaluacion.modificable == true)) {
            $form.put(route('convocatorias.servicios-tecnologicos-evaluaciones.update', [convocatoria.id, servicioTecnologicoEvaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    $: if (servicioTecnologicoInfo.fecha_inicio && servicioTecnologicoInfo.fecha_finalizacion) {
        servicioTecnologicoInfo.max_meses_ejecucion = monthDiff(servicioTecnologicoInfo.fecha_inicio, servicioTecnologicoInfo.fecha_finalizacion)
    }

    onMount(() => {
        getProgramasFormacion()
    })

    async function getProgramasFormacion() {
        let res = await axios.get(route('web-api.programas-formacion', servicioTecnologico.proyecto.centro_formacion_id))
        if (res.status == '200') {
            programasFormacion = res.data
        }
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} evaluacion={servicioTecnologicoEvaluacion.evaluacion} proyecto={servicioTecnologico.proyecto} />

    <form on:submit|preventDefault={submit}>
        <div class="mt-28">
            <Label labelFor="titulo" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="Título" />
            <Textarea label="Título" sinContador={true} id="titulo" bind:value={servicioTecnologicoInfo.titulo} classes="bg-transparent block border-0 outline-none-important mt-1 outline-none text-4xl text-center w-full" />

            <InfoMessage>
                <h1>Criterios de evaluacion</h1>
                <ul class="list-disc p-4">
                    <li>
                        <strong>Puntaje: 0 a 4</strong> Debe corresponder al contenido del proyecto y responder a los siguientes interrogantes: ¿Qué se va a hacer?, ¿Sobre qué o quiénes se hará?, ¿Cómo?, ¿Dónde se llevará a cabo? Tiene que estar escrito de manera breve y concisa. Un buen título describe con exactitud y usando el menor número posible de palabras el tema central del proyecto.

                        <br />
                        Nota: las respuestas a las preguntas anteriormente formuladas no necesariamente deben responderse en mismo orden en el que aparecen. (Máximo 40 palabras)
                    </li>
                </ul>
                <Label class="mt-4 mb-4" labelFor="titulo_puntaje" value="Puntaje (Máximo 4)" />
                <Input
                    disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                    label="Puntaje"
                    id="titulo_puntaje"
                    type="number"
                    input$min="0"
                    input$max="4"
                    class="mt-1"
                    bind:value={$form.titulo_puntaje}
                    placeholder="Puntaje"
                    autocomplete="off"
                    error={errors.titulo_puntaje}
                />

                {#if servicioTecnologicoSegundaEvaluacion?.titulo_comentario}
                    <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.titulo_comentario}</p>
                {/if}

                <div class="mt-4">
                    <p>¿El título es correcto? Por favor seleccione si Cumple o No cumple.</p>
                    <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.titulo_requiere_comentario} />
                    {#if $form.titulo_requiere_comentario == false}
                        <Textarea
                            disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                            label="Comentario"
                            class="mt-4"
                            maxlength="40000"
                            id="titulo_comentario"
                            bind:value={$form.titulo_comentario}
                            error={errors.titulo_comentario}
                            required
                        />
                    {/if}
                </div>
            </InfoMessage>
        </div>

        <div class="mt-44">
            <p class="text-center">Fecha de ejecución</p>
            <div class="mt-4 flex items-start justify-around">
                <div class="mt-4 flex items-center">
                    <Label labelFor="fecha_inicio" value="Del" />
                    <div class="ml-4">
                        <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_st} max={convocatoria.max_fecha_finalizacion_proyectos_st} bind:value={servicioTecnologicoInfo.fecha_inicio} />
                    </div>
                </div>
                <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                    <Label labelFor="fecha_finalizacion" value="hasta" />
                    <div class="ml-4">
                        <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_st} max={convocatoria.max_fecha_finalizacion_proyectos_st} bind:value={servicioTecnologicoInfo.fecha_finalizacion} />
                    </div>
                </div>
            </div>

            <InfoMessage>
                {#if servicioTecnologicoSegundaEvaluacion?.fecha_ejecucion_comentario}
                    <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.fecha_ejecucion_comentario}</p>
                {/if}
                <div class="mt-4">
                    <p>¿Las fechas son correctas? Por favor seleccione si Cumple o No cumple</p>
                    <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.fechas_requiere_comentario} />
                    {#if $form.fechas_requiere_comentario == false}
                        <Textarea
                            disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                            label="Comentario"
                            class="mt-4"
                            maxlength="40000"
                            id="fecha_ejecucion_comentario"
                            bind:value={$form.fecha_ejecucion_comentario}
                            error={errors.fecha_ejecucion_comentario}
                            required
                        />
                    {/if}
                </div>
            </InfoMessage>
        </div>

        <fieldset disabled>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipo_proyecto_st_id" value="Centro de formación" />
                </div>
                <div>
                    <Select id="tipo_proyecto_st_id" items={tiposProyectoSt} bind:selectedValue={servicioTecnologicoInfo.tipo_proyecto_st_id} error={errors.tipo_proyecto_st_id} autocomplete="off" placeholder="Seleccione una tipología de ST" required />
                </div>
            </div>

            {#if servicioTecnologicoInfo.tipo_proyecto_st_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="estado_sistema_gestion_id" value="Estado del sistema de gestión" />
                    </div>
                    <div>
                        <DynamicList id="estado_sistema_gestion_id" bind:value={servicioTecnologicoInfo.estado_sistema_gestion_id} routeWebApi={route('web-api.estados-sistema-gestion', servicioTecnologicoInfo.tipo_proyecto_st_id['value'])} classes="min-h" placeholder="Seleccione un estado" message={errors.estado_sistema_gestion_id} required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="sector_productivo" value="Sector priorizado de Colombia Productiva" />
                </div>
                <div>
                    <Select id="sector_productivo" items={sectoresProductivos} bind:selectedValue={servicioTecnologicoInfo.sector_productivo} error={errors.sector_productivo} autocomplete="off" placeholder="Seleccione una sector" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="linea_programatica_id" value="Código dependencia presupuestal (SIIF)" />
                </div>
                <div>
                    <DynamicList id="linea_programatica_id" bind:value={servicioTecnologicoInfo.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas', 3)} classes="min-h" placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} required />
                </div>
            </div>
        </fieldset>
        <hr class="mt-32 mb-32" />

        <h1 class="text-2xl text-center" id="estructura-proyecto">Estructura del proyecto</h1>

        <div class="mt-40 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="resumen" value="Resumen ejecutivo" />
            </div>
            <div>
                <Textarea maxlength="1000" id="resumen" bind:value={servicioTecnologicoInfo.resumen} />

                <InfoMessage>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li>
                            <strong>Puntaje: 0 a 3</strong> Información necesaria para darle al lector una idea precisa de la pertinencia y calidad proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto. Nota: El resumen por lo general se construye al
                            final de la contextualización con el fin de tener claros todos los puntos que intervinieron en la misma y poder dar a conocer de forma más pertinente los por menores del proyecto. (Máximo 1000 caracteres).
                        </li>
                    </ul>
                    <Label class="mt-4 mb-4" labelFor="resumen_puntaje" value="Puntaje (Máximo 3)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="resumen_puntaje"
                        type="number"
                        input$min="0"
                        input$max="3"
                        class="mt-1"
                        bind:value={$form.resumen_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.resumen_puntaje}
                    />

                    {#if servicioTecnologicoSegundaEvaluacion?.resumen_comentario}
                        <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.resumen_comentario}</p>
                    {/if}

                    <div class="mt-4">
                        <p>¿El resumen es correcto? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.resumen_requiere_comentario} />
                        {#if $form.resumen_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="resumen_comentario"
                                bind:value={$form.resumen_comentario}
                                error={errors.resumen_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="antecedentes" value="Antecedentes" />
            </div>
            <div>
                <Textarea maxlength="10000" id="antecedentes" bind:value={servicioTecnologicoInfo.antecedentes} />

                <InfoMessage>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li>
                            <strong>Puntaje: 0 a 3</strong> Se debe evidenciar la identificación y caracterización del mercado potencial/objetivo, nicho de mercado al cual se busca atender o la necesidad que se busca satisfacer tomando como referencia el estudio del sector, identificando si existen el(los) mismo(s) alcance(s) o similar(es) en la empresa privada o pública u otros centros de formación
                            de tal forma que el proyecto no se convierta en una competencia frente a un servicio/producto ofertado. Se debe registrar el análisis de las tendencias del mercado, en relación con clientes potenciales, competidores y proveedores. En este ítem es necesario valorar las necesidades de los clientes actuales o potenciales y precisar la segmentación del mercado, las tendencias
                            de los precios y las gestiones comerciales a realizadas Nota: La información debe ser de fuentes primarias, ejemplo: Secretarías, DANE, Artículos científicos, entre otros y citarla utilizando normas APA séptima edición. (Máximo 10000 caracteres).
                        </li>
                    </ul>
                    <Label class="mt-4 mb-4" labelFor="antecedentes_puntaje" value="Puntaje (Máximo 3)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="antecedentes_puntaje"
                        type="number"
                        input$min="0"
                        input$max="3"
                        class="mt-1"
                        bind:value={$form.antecedentes_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.antecedentes_puntaje}
                    />

                    {#if servicioTecnologicoSegundaEvaluacion?.antecedentes_comentario}
                        <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.antecedentes_comentario}</p>
                    {/if}

                    <div class="mt-4">
                        <p>¿Los antecedentes son correctos? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.antecedentes_requiere_comentario} />
                        {#if $form.antecedentes_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="antecedentes_comentario"
                                bind:value={$form.antecedentes_comentario}
                                error={errors.antecedentes_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="identificacion_problema" value="Identificación y descripción del problema" />
            </div>

            <div>
                <Textarea label="Identificación y descripción del problema" maxlength="5000" id="identificacion_problema" bind:value={servicioTecnologicoInfo.identificacion_problema} />

                <InfoMessage>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li>
                            <strong>Puntaje: 0 a 4</strong> Se debe evidenciar la descripción de la realidad objeto de estudio. Luego de identificar el problema central y de haber separado las causas que lo generan de los efectos que produce, se procede a describirlo concretamente, partiendo de lo general a lo específico (a nivel mundial, a nivel nacional, a nivel regional) alineados con los objetivos
                            de desarrollo sostenible, los documentos CONPES (Consejo Nacional de Política económica y social) que le aplique(n), las agendas departamentales de competitividad e innovación y considerando la situación actual. Se deben referenciar los datos estadísticos entre otros datos relevantes. Nota: La información debe ser de fuentes primarias, ejemplo: Secretarías, DANE, Artículos
                            científicos, entre otros, y citarla utilizando normas APA sexta edición. Evite adjetivos que impliquen juicios de valor tales como: bueno, malo, mejor, peor. (Máximo 5000 caracteres)
                        </li>
                    </ul>
                    <Label class="mt-4 mb-4" labelFor="identificacion_problema_puntaje" value="Puntaje (Máximo 4)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="identificacion_problema_puntaje"
                        type="number"
                        input$min="0"
                        input$max="4"
                        class="mt-1"
                        bind:value={$form.identificacion_problema_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.identificacion_problema_puntaje}
                    />

                    {#if servicioTecnologicoSegundaEvaluacion?.identificacion_problema_comentario}
                        <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.identificacion_problema_comentario}</p>
                    {/if}

                    <div class="mt-4">
                        <p>¿La identificación del problema es correcta? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.identificacion_problema_requiere_comentario} />
                        {#if $form.identificacion_problema_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="identificacion_problema_comentario"
                                bind:value={$form.identificacion_problema_comentario}
                                error={errors.identificacion_problema_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="pregunta_formulacion_problema" value="Pregunta de formulación del problema" />
            </div>
            <div>
                <Textarea label="Pregunta de formulación del problema" sinContador={true} id="pregunta_formulacion_problema" bind:value={servicioTecnologicoInfo.pregunta_formulacion_problema} />

                <InfoMessage>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li>
                            <p><strong>Puntaje: 0 a 2</strong> Se debe verificar que la pregunta del problema defina con exactitud ¿cuál es el problema para resolver, investigar o intervenir?</p>
                            La pregunta debe cumplir las siguientes condiciones:
                            <ul>
                                <li>• Guardar estrecha correspondencia con el título del proyecto.</li>
                                <li>• Evitar adjetivos que impliquen juicios de valor tales como: bueno, malo, mejor, peor.</li>
                                <li>• No debe dar origen a respuestas tales como si o no.</li>
                            </ul>
                            <br />
                            <strong>Máximo 50 palabras</strong>
                        </li>
                    </ul>
                    <Label class="mt-4 mb-4" labelFor="pregunta_formulacion_problema_puntaje" value="Puntaje (Máximo 2)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="pregunta_formulacion_problema_puntaje"
                        type="number"
                        input$min="0"
                        input$max="2"
                        class="mt-1"
                        bind:value={$form.pregunta_formulacion_problema_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.pregunta_formulacion_problema_puntaje}
                    />

                    {#if servicioTecnologicoSegundaEvaluacion?.pregunta_formulacion_problema_comentario}
                        <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.pregunta_formulacion_problema_comentario}</p>
                    {/if}

                    <div class="mt-4">
                        <p>¿La pregunta de formulación del problema es correcta? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.pregunta_formulacion_problema_requiere_comentario} />
                        {#if $form.pregunta_formulacion_problema_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="pregunta_formulacion_problema_comentario"
                                bind:value={$form.pregunta_formulacion_problema_comentario}
                                error={errors.pregunta_formulacion_problema_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>
        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="justificacion_problema" value="Justificación" />
            </div>
            <div>
                <Textarea label="Justificación" maxlength="5000" id="justificacion_problema" bind:value={servicioTecnologicoInfo.justificacion_problema} />

                <InfoMessage>
                    <h1>Criterios de evaluacion</h1>
                    <p><strong>Puntaje: 0 a 4</strong></p>
                    <ul class="list-disc p-4">
                        <p>La justificación debe describir la solución del problema y debe responder a las siguientes preguntas:</p>
                        <li>• ¿Cómo se relaciona el proyecto con las prioridades de la región y del país?</li>
                        <li>• ¿Qué resultados se lograrán?</li>
                        <li>• ¿Cuál es la finalidad con los resultados esperados?</li>
                        <li>• ¿Cómo se utilizarán los resultados y quiénes serán los beneficiarios?</li>
                        <li>• Debe incluir el impacto a la formación, al sector productivo y a la política nacional de ciencia, tecnología e innovación.</li>
                        <li>
                            <strong>Nota:</strong> La justificación debe brindar un argumento convincente de los resultados del proyecto generado y de su aplicabilidad.
                        </li>
                    </ul>
                    <Label class="mt-4 mb-4" labelFor="justificacion_problema_puntaje" value="Puntaje (Máximo 4)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="justificacion_problema_puntaje"
                        type="number"
                        input$min="0"
                        input$max="4"
                        class="mt-1"
                        bind:value={$form.justificacion_problema_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.justificacion_problema_puntaje}
                    />

                    {#if servicioTecnologicoSegundaEvaluacion?.justificacion_problema_comentario}
                        <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.justificacion_problema_comentario}</p>
                    {/if}

                    <div class="mt-4">
                        <p>¿La justificación del problema es correcta? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.justificacion_problema_requiere_comentario} />
                        {#if $form.justificacion_problema_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="justificacion_problema_comentario"
                                bind:value={$form.justificacion_problema_comentario}
                                error={errors.justificacion_problema_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" for="programas_formacion" value="Nombre de los programas de formación con los que se relaciona el proyecto" />
            </div>
            <div>
                <SelectMulti id="programas_formacion" bind:selectedValue={servicioTecnologicoInfo.programas_formacion} items={programasFormacion} isMulti={true} placeholder="Buscar por el nombre del programa de formación" required />
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
                <Label required class="mb-4" labelFor="zona_influencia" value="Zona de influencia" />
            </div>
            <div>
                <Input label="Zona de influencia" id="zona_influencia" type="text" class="mt-1" placeholder="Escriba el número de aprendices que se beneficiarán en la ejecución del proyecto" bind:value={servicioTecnologicoInfo.zona_influencia} required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Última edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
            </div>
            <div>
                <Textarea sinContador={true} id="bibliografia" bind:value={servicioTecnologicoInfo.bibliografia} />

                <InfoMessage>
                    {#if servicioTecnologicoSegundaEvaluacion?.bibliografia_comentario}
                        <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.bibliografia_comentario}</p>
                    {/if}
                    <div class="mt-4">
                        <p>¿La bibliografía es correcta? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.bibliografia_requiere_comentario} />
                        {#if $form.bibliografia_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="bibliografia_comentario"
                                bind:value={$form.bibliografia_comentario}
                                error={errors.bibliografia_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <hr class="mt-10 mb-10" />
        <h1>Ortografía</h1>
        <InfoMessage>
            {#if servicioTecnologicoSegundaEvaluacion?.ortografia_comentario}
                <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.ortografia_comentario}</p>
            {/if}
            <div class="mt-4">
                <p>¿La ortografía es correcta? Por favor seleccione si Cumple o No cumple.</p>
                <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.ortografia_requiere_comentario} />
                {#if $form.ortografia_requiere_comentario == false}
                    <Textarea
                        disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                        label="Comentario"
                        class="mt-4"
                        maxlength="40000"
                        id="ortografia_comentario"
                        bind:value={$form.ortografia_comentario}
                        error={errors.ortografia_comentario}
                        required
                    />
                {/if}
            </div>
        </InfoMessage>

        <hr class="mt-10 mb-10" />
        <h1>Redacción</h1>
        <InfoMessage>
            {#if servicioTecnologicoSegundaEvaluacion?.redaccion_comentario}
                <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.redaccion_comentario}</p>
            {/if}
            <div class="mt-4">
                <p>¿La redacción es correcta? Por favor seleccione si Cumple o No cumple.</p>
                <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.redaccion_requiere_comentario} />
                {#if $form.redaccion_requiere_comentario == false}
                    <Textarea
                        disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                        label="Comentario"
                        class="mt-4"
                        maxlength="40000"
                        id="redaccioncomentario"
                        bind:value={$form.redaccion_comentario}
                        error={errors.redaccion_comentario}
                    />
                {/if}
            </div>
        </InfoMessage>

        <hr class="mt-10 mb-10" />
        <h1>Normas APA</h1>
        <InfoMessage>
            {#if servicioTecnologicoSegundaEvaluacion?.normas_apa_comentario}
                <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{servicioTecnologicoSegundaEvaluacion?.normas_apa_comentario}</p>
            {/if}
            <div class="mt-4">
                <p>¿Las normas APA son correctas? Por favor seleccione si Cumple o No cumple.</p>
                <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.normas_apa_requiere_comentario} />
                {#if $form.normas_apa_requiere_comentario == false}
                    <Textarea
                        disabled={isSuperAdmin ? undefined : servicioTecnologicoEvaluacion.evaluacion.finalizado == true || servicioTecnologicoEvaluacion.evaluacion.habilitado == false || servicioTecnologicoEvaluacion.evaluacion.modificable == false ? true : undefined}
                        label="Comentario"
                        class="mt-4"
                        maxlength="40000"
                        id="normas_apa_comentario"
                        bind:value={$form.normas_apa_comentario}
                        error={errors.normas_apa_comentario}
                        required
                    />
                {/if}
            </div>
        </InfoMessage>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center justify-between sticky bottom-0">
            {#if isSuperAdmin || (checkRole(authUser, [11]) && servicioTecnologico.proyecto.finalizado == true && servicioTecnologicoEvaluacion.evaluacion.finalizado == false && servicioTecnologicoEvaluacion.evaluacion.habilitado == true && servicioTecnologicoEvaluacion.evaluacion.modificable == true)}
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
            Código del proyecto: {servicioTecnologico.proyecto.codigo}
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
</AuthenticatedLayout>
