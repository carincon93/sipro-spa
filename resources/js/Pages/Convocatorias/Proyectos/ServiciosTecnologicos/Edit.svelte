<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'
    import { onMount } from 'svelte'
    import axios from 'axios'

    import Button from '@/Shared/Button'
    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Stepper from '@/Shared/Stepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import Password from '@/Shared/Password'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'
    import SelectMulti from '@/Shared/SelectMulti'
    import Input from '@/Shared/Input'

    export let errors
    export let convocatoria
    export let servicioTecnologico
    export let tiposProyectoSt
    export let sectoresProductivos
    export let proyectoProgramasFormacion

    let programasFormacion

    $: $title = servicioTecnologico ? servicioTecnologico.titulo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = errors.password != undefined ? true : false
    let proyectoDialogOpen = true
    let sending = false

    let resumenForm = useForm({
        resumen: servicioTecnologico.resumen,
    })
    let formAntecedentes = useForm({
        antecedentes: servicioTecnologico.antecedentes,
    })
    let formIdentificacionProblema = useForm({
        identificacion_problema: servicioTecnologico.identificacion_problema,
    })
    let formJustificacionProblema = useForm({
        justificacion_problema: servicioTecnologico.justificacion_problema,
    })
    let formZonaInfluencia = useForm({
        zona_influencia: servicioTecnologico.zona_influencia,
    })
    let formBibliografia = useForm({
        bibliografia: servicioTecnologico.bibliografia,
    })

    let form = useForm({
        tipo_proyecto_st_id: {
            value: servicioTecnologico.tipo_proyecto_st_id,
            label: tiposProyectoSt.find((item) => item.value == servicioTecnologico.tipo_proyecto_st_id)?.label,
        },
        linea_programatica_id: servicioTecnologico.proyecto?.linea_programatica_id,
        titulo: servicioTecnologico.titulo,
        fecha_inicio: servicioTecnologico.fecha_inicio,
        fecha_finalizacion: servicioTecnologico.fecha_finalizacion,
        max_meses_ejecucion: servicioTecnologico.max_meses_ejecucion,
        pregunta_formulacion_problema: servicioTecnologico.pregunta_formulacion_problema,

        programas_formacion: proyectoProgramasFormacion.length > 0 ? proyectoProgramasFormacion : null,

        estado_sistema_gestion_id: servicioTecnologico.estado_sistema_gestion_id,
        sector_productivo: {
            value: servicioTecnologico.sector_productivo,
            label: sectoresProductivos.find((item) => item.value == servicioTecnologico.sector_productivo)?.label,
        },
    })

    async function syncColumnLong(column, form) {
        return new Promise((resolve) => {
            if (isSuperAdmin || (checkPermission(authUser, [6, 7]) && servicioTecnologico.proyecto.modificable == true)) {
                //guardar
                Inertia.put(
                    route('convocatorias.servicios-tecnologicos.updateLongColumn', [convocatoria.id, servicioTecnologico.id, column]),
                    { [column]: form[column] },
                    {
                        onStart: () => (sending = true),
                        onError: (resp) => ((sending = false), resolve(resp)),
                        onFinish: () => ((sending = false), resolve({})),
                        preserveScroll: true,
                    },
                )
            } else {
                resolve({})
            }
        })
    }
    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [6, 7]) && servicioTecnologico.proyecto.modificable == true)) {
            $form.put(route('convocatorias.servicios-tecnologicos.update', [convocatoria.id, servicioTecnologico.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let deleteForm = useForm({
        password: '',
    })

    function destroy() {
        if (isSuperAdmin || (checkPermission(authUser, [7]) && servicioTecnologico.proyecto.modificable == true)) {
            $deleteForm.delete(route('convocatorias.servicios-tecnologicos.destroy', [convocatoria.id, servicioTecnologico.id]), {
                preserveScroll: true,
            })
        }
    }

    $: if ($form.fecha_inicio && $form.fecha_finalizacion) {
        $form.max_meses_ejecucion = monthDiff($form.fecha_inicio, $form.fecha_finalizacion)
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
    <Stepper {convocatoria} proyecto={servicioTecnologico} />

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [6, 7]) && servicioTecnologico.proyecto.modificable == true) ? undefined : true}>
            <div class="mt-28">
                <Label
                    required
                    labelFor="titulo"
                    class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full"
                    value="Debe corresponder al contenido del proyecto y responder a los siguientes interrogantes: ¿Qué se va a hacer?, ¿Sobre qué o quiénes se hará?, ¿Cómo?, ¿Dónde se llevará a cabo? Tiene que estar escrito de manera breve y concisa. Un buen título describe con exactitud y usando el menor número posible de palabras el tema central del proyecto. Nota: las respuestas a las preguntas anteriormente formuladas no necesariamente deben responderse en mismo orden en el que aparecen. (Máximo 40 palabras)"
                />
                <Textarea label="Título" sinContador={true} id="titulo" error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />

                {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                    {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                        {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                            <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                <div class="flex text-orangered-900 font-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Recomendación del evaluador COD-{evaluacion.id}:
                                </div>
                                <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.titulo_comentario ? evaluacion.servicio_tecnologico_evaluacion.titulo_comentario : 'Sin recomendación'}</p>
                            </div>
                        {/if}
                    {/each}
                {/if}
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <InfoMessage message={convocatoria.fecha_maxima_st} class="my-5" />

                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label required labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_st} max={convocatoria.max_fecha_finalizacion_proyectos_st} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label required labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_st} max={convocatoria.max_fecha_finalizacion_proyectos_st} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion || errors.max_meses_ejecucion}
                    <div class="mb-20 flex justify-center mt-4">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                        <InputError classes="text-center" message={errors.max_meses_ejecucion} />
                    </div>
                {/if}

                {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                    {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                        {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                            <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                <div class="flex text-orangered-900 font-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Recomendación del evaluador COD-{evaluacion.id}:
                                </div>
                                <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.fecha_ejecucion_comentario ? evaluacion.servicio_tecnologico_evaluacion.fecha_ejecucion_comentario : 'Sin recomendación'}</p>
                            </div>
                        {/if}
                    {/each}
                {/if}
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tipo_proyecto_st_id" value="Centro de formación" />
                    </div>
                    <div>
                        <Select id="tipo_proyecto_st_id" items={tiposProyectoSt} bind:selectedValue={$form.tipo_proyecto_st_id} error={errors.tipo_proyecto_st_id} autocomplete="off" placeholder="Seleccione una tipología de ST" required />
                    </div>
                </div>

                {#if $form.tipo_proyecto_st_id}
                    <div class="mt-44 grid grid-cols-2">
                        <div>
                            <Label required class="mb-4" labelFor="estado_sistema_gestion_id" value="Estado del sistema de gestión" />
                        </div>
                        <div>
                            <DynamicList id="estado_sistema_gestion_id" bind:value={$form.estado_sistema_gestion_id} routeWebApi={route('web-api.estados-sistema-gestion', $form.tipo_proyecto_st_id['value'])} classes="min-h" placeholder="Seleccione un estado" message={errors.estado_sistema_gestion_id} required />
                        </div>
                    </div>
                {/if}

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="sector_productivo" value="Sector priorizado de Colombia Productiva" />
                    </div>
                    <div>
                        <Select id="sector_productivo" items={sectoresProductivos} bind:selectedValue={$form.sector_productivo} error={errors.sector_productivo} autocomplete="off" placeholder="Seleccione una sector" required />
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="linea_programatica_id" value="Código dependencia presupuestal (SIIF)" />
                    </div>
                    <div>
                        <DynamicList id="linea_programatica_id" bind:value={$form.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas', 3)} classes="min-h" placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} required />
                    </div>
                </div>
            </fieldset>

            <hr class="mt-32 mb-32" />

            <h1 class="text-2xl text-center" id="estructura-proyecto">Estructura del proyecto</h1>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen" value="Resumen ejecutivo" />
                    <InfoMessage class="mb-2">
                        <p>
                            Información necesaria para darle al lector una idea precisa de la pertinencia y calidad del proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto.
                            <br />
                            <strong>Nota:</strong> El resumen por lo general se construye al final de la contextualización con el fin de tener claros todos los puntos que intervinieron en la misma y poder dar a conocer de forma más pertinente los por menores del proyecto. (Máximo 1000 caracteres).
                        </p>
                    </InfoMessage>
                </div>
                <div>
                    <Textarea maxlength="1000" id="resumen" error={errors.resumen} bind:value={$resumenForm.resumen} on:input={() => syncColumnLong('resumen', $resumenForm)} required />

                    {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.resumen_comentario ? evaluacion.servicio_tecnologico_evaluacion.resumen_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                    <InfoMessage class="mb-2">
                        <p>
                            Se debe evidenciar la identificación y caracterización del mercado potencial/objetivo, nicho de mercado al cual se busca atender o la necesidad que se busca satisfacer tomando como referencia el estudio del sector, identificando si existen el(los) mismo(s) alcance(s) o similar(es) en la empresa privada o pública u otros centros de formación de tal forma que el proyecto
                            no se convierta en una competencia frente a un servicio/producto ofertado. Se debe registrar el análisis de las tendencias del mercado, en relación con clientes potenciales, competidores y proveedores. En este ítem es necesario valorar las necesidades de los clientes actuales o potenciales y precisar la segmentación del mercado, las tendencias de los precios y las
                            gestiones comerciales a realizadas.
                            <br />
                            <strong>Nota:</strong> La información debe ser de fuentes primarias, ejemplo: Secretarías, DANE, Artículos científicos, entre otros y citarla utilizando normas APA séptima edición. (Máximo 10000 caracteres).
                        </p>
                    </InfoMessage>
                </div>
                <div>
                    <Textarea maxlength="10000" id="antecedentes" error={errors.antecedentes} bind:value={$formAntecedentes.antecedentes} on:input={() => syncColumnLong('antecedentes', $formAntecedentes)} required />

                    {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.antecedentes_comentario ? evaluacion.servicio_tecnologico_evaluacion.antecedentes_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="identificacion_problema" value="Identificación y descripción del problema" />
                    <InfoMessage
                        class="mb-2"
                        message="1. Descripción de la necesidad, problema u oportunidad identificada del plan tecnológico y/o agendas departamentales de innovación y competitividad.<br>2. Descripción del problema que se atiende con el proyecto, sustentado en el contexto, la caracterización, los datos, las estadísticas, de la regional, entre otros, citar toda la información consignada utilizando normas APA última edición. La información debe ser de fuentes primarias de información, ejemplo: Secretarías, DANE, Artículos científicos, entre otros."
                    />
                </div>

                <div>
                    <Textarea label="Identificación y descripción del problema" maxlength="5000" id="identificacion_problema" error={errors.identificacion_problema} bind:value={$formIdentificacionProblema.identificacion_problema} on:input={() => syncColumnLong('identificacion_problema', $formIdentificacionProblema)} required />

                    {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.identificacion_problema_comentario ? evaluacion.servicio_tecnologico_evaluacion.identificacion_problema_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="pregunta_formulacion_problema" value="Pregunta de formulación del problema" />
                    <InfoMessage class="mb-2">
                        <p>Se debe verificar que la pregunta del problema defina con exactitud ¿cuál es el problema para resolver, investigar o intervenir?</p>
                        La pregunta debe cumplir las siguientes condiciones:
                        <ul>
                            <li>• Guardar estrecha correspondencia con el título del proyecto.</li>
                            <li>• Evitar adjetivos que impliquen juicios de valor tales como: bueno, malo, mejor, peor.</li>
                            <li>• No debe dar origen a respuestas tales como si o no.</li>
                        </ul>
                        <br />
                        <strong>Nota:</strong> Se sugiere convertir el problema principal (tronco) identificado en el árbol de problemas en forma pregunta.
                        <br />
                        <strong>Máximo 50 palabras</strong>
                    </InfoMessage>
                </div>
                <div>
                    <Textarea label="Pregunta formulación del problema" sinContador={true} id="pregunta_formulacion_problema" error={errors.pregunta_formulacion_problema} bind:value={$form.pregunta_formulacion_problema} required />

                    {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.pregunta_formulacion_problema_comentario ? evaluacion.servicio_tecnologico_evaluacion.pregunta_formulacion_problema_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>
            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="justificacion_problema" value="Justificación" />
                    <InfoMessage class="mb-2">
                        <p>La justificación debe describir la solución del problema y debe responder a las siguientes preguntas:</p>
                        <ul>
                            <li>• ¿Cómo se relaciona el proyecto con las prioridades de la región y del país?</li>
                            <li>• ¿Qué resultados se lograrán?</li>
                            <li>• ¿Cuál es la finalidad con los resultados esperados?</li>
                            <li>• ¿Cómo se utilizarán los resultados y quiénes serán los beneficiarios?</li>
                            <li>• Debe incluir el impacto a la formación, al sector productivo y a la política nacional de ciencia, tecnología e innovación.</li>
                        </ul>
                        <strong>Nota:</strong> La justificación debe brindar un argumento convincente de los resultados del proyecto generado y de su aplicabilidad."
                    </InfoMessage>
                </div>
                <div>
                    <Textarea label="Justificación" maxlength="5000" id="justificacion_problema" error={errors.justificacion_problema} bind:value={$formJustificacionProblema.justificacion_problema} on:input={() => syncColumnLong('justificacion_problema', $formJustificacionProblema)} required />
                    {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.justificacion_problema_comentario ? evaluacion.servicio_tecnologico_evaluacion.justificacion_problema_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="programas_formacion" value="Nombre de los programas de formación con los que se relaciona el proyecto" />
                </div>
                <div>
                    <SelectMulti id="programas_formacion" bind:selectedValue={$form.programas_formacion} items={programasFormacion} isMulti={true} error={errors.programas_formacion} placeholder="Buscar por el nombre del programa de formación" required />
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
                    <Input label="Zona de influencia" id="zona_influencia" type="text" class="mt-1" error={errors.zona_influencia} placeholder="Escriba el número de aprendices que se beneficiarán en la ejecución del proyecto" bind:value={$formZonaInfluencia.zona_influencia} on:input={() => syncColumnLong('zona_influencia', $formZonaInfluencia)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Última edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea sinContador={true} id="bibliografia" error={errors.bibliografia} bind:value={$formBibliografia.bibliografia} on:input={() => syncColumnLong('bibliografia', $formBibliografia)} required />

                    {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.bibliografia_comentario ? evaluacion.servicio_tecnologico_evaluacion.bibliografia_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                <hr class="mt-10 mb-10" />
                <h1>Ortografía</h1>
                {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.ortografia_comentario ? evaluacion.servicio_tecnologico_evaluacion.ortografia_comentario : 'Sin recomendación'}</p>
                        </div>
                    {/if}
                {/each}
            {/if}

            {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                <hr class="mt-10 mb-10" />
                <h1>Redacción</h1>
                {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.redaccion_comentario ? evaluacion.servicio_tecnologico_evaluacion.redaccion_comentario : 'Sin recomendación'}</p>
                        </div>
                    {/if}
                {/each}
            {/if}

            {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                <hr class="mt-10 mb-10" />
                <h1>Normas APA</h1>
                {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            <p class="whitespace-pre-line">{evaluacion.servicio_tecnologico_evaluacion.normas_apa_comentario ? evaluacion.servicio_tecnologico_evaluacion.normas_apa_comentario : 'Sin recomendación'}</p>
                        </div>
                    {/if}
                {/each}
            {/if}
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center justify-between sticky bottom-0">
            {#if isSuperAdmin || (checkPermission(authUser, [7]) && servicioTecnologico.proyecto.modificable == true && convocatoria.fase == 1)}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin || (checkPermission(authUser, [6, 7]) && servicioTecnologico.proyecto.modificable == true)}
                <small>{servicioTecnologico.updated_at}</small>

                <LoadingButton loading={sending} class="btn-indigo" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={proyectoDialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/proyecto.png'} alt="Proyecto" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {servicioTecnologico.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                {#if JSON.parse(servicioTecnologico.proyecto.estado_cord_sennova)?.requiereSubsanar == true || JSON.parse(servicioTecnologico.proyecto.estado)?.requiereSubsanar == true}
                    <h1 class="text-center mb-4 font-black text-2xl">Este proyecto requiere ser subsanado</h1>
                    <p>Por favor revise las observaciones de los evaluadores en cada uno de los campos y secciones.</p>
                    <p>Importante: Se ha agregado una sección de <strong>Comentarios generales</strong>, revise si hay comentarios de los evaluadores y por favor escriba la respectiva respuesta.</p>
                {:else if JSON.parse(servicioTecnologico.proyecto.estado_cord_sennova)?.requiereSubsanar == false || JSON.parse(servicioTecnologico.proyecto.estado)?.requiereSubsanar == false}
                    <div>
                        <h1 class="text-center mb-4 font-black text-2xl">Este proyecto no requiere subsanación</h1>
                        <p><strong>Tenga en cuenta:</strong> El estado final de los proyectos se conocerá cuando finalice la etapa de segunda evaluación (Estado Rechazado, pre – aprobado con observaciones y Preaprobado). Fechas segunda evaluación: 22 de octubre (13:00 HH) al 3 de noviembre (23:59 HH).</p>
                    </div>
                {:else}
                    <h1 class="text-center mt-4 mb-4">Para terminar el numeral de <strong>Generalidades</strong> por favor continue diligenciando los siguientes campos:</h1>
                    <p class="text-center mb-4">Si ya están completos omita esta información.</p>
                    <ul class="list-disc">
                        <li>Resumen</li>
                        <li>Antecedentes</li>
                        <li>Identificación y descripción del problema</li>
                        <li>Pregunta de formulación del problema</li>
                        <li>Justificación</li>
                        <li>Bibliografía</li>
                    </ul>
                {/if}
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (proyectoDialogOpen = false)} variant={null}>Omitir</Button>
                {#if servicioTecnologico.proyecto.modificable}
                    <Button variant="raised" on:click={(event) => (proyectoDialogOpen = false)} on:click={() => Inertia.visit('#estructura-proyecto')}>Continuar diligenciando</Button>
                {/if}
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={dialogOpen}>
        <div slot="titulo" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <InfoMessage message="¿Está seguro (a) que desea eliminar este proyecto?<br />Una vez eliminado el proyecto, todos sus recursos y datos se eliminarán de forma permanente." />

            <form on:submit|preventDefault={destroy} id="delete-servicio-tecnologico" class="mt-10 mb-28">
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente este proyecto" class="mb-4" />
                <Password id="password" class="w-full" bind:value={$deleteForm.password} error={errors.password} required autocomplete="current-password" />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="delete-servicio-tecnologico">Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
