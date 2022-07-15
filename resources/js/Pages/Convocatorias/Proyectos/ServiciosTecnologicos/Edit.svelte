<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, checkPermissionByUser, monthDiff } from '@/Utils'
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
    import RecomendacionEvaluador from '@/Shared/RecomendacionEvaluador'

    export let errors
    export let convocatoria
    export let servicioTecnologico
    export let tiposProyectoSt
    export let sectoresProductivos
    export let proyectoProgramasFormacion

    let programasFormacion

    $: $title = servicioTecnologico ? servicioTecnologico.titulo : null

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let proyectoDialogOpen = true

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
            if (servicioTecnologico.proyecto.allowed.to_update) {
                //guardar
                Inertia.put(
                    route('convocatorias.servicios-tecnologicos.updateLongColumn', [convocatoria.id, servicioTecnologico.id, column]),
                    { [column]: form[column] },
                    {
                        onError: (resp) => resolve(resp),
                        onFinish: () => resolve({}),
                        preserveScroll: true,
                    },
                )
            } else {
                resolve({})
            }
        })
    }

    function submit() {
        if (servicioTecnologico.proyecto.allowed.to_update) {
            $form.put(route('convocatorias.servicios-tecnologicos.update', [convocatoria.id, servicioTecnologico.id]), {
                preserveScroll: true,
            })
        }
    }

    let deleteForm = useForm({
        password: '',
    })

    function destroy() {
        if (isSuperAdmin || canUserDeleteProyectoST || canDeleteProyectoST) {
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
    <header slot="header">
        <Stepper {convocatoria} proyecto={servicioTecnologico} />
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8 divide-y" disabled={servicioTecnologico.proyecto.allowed.to_update ? undefined : true}>
            <div class="py-24">
                <Label
                    required
                    labelFor="titulo"
                    class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full"
                    value="Debe corresponder al contenido del proyecto y responder a los siguientes interrogantes: ¿Qué se va a hacer?, ¿Sobre qué o quiénes se hará?, ¿Cómo?, ¿Dónde se llevará a cabo? Tiene que estar escrito de manera breve y concisa. Un buen título describe con exactitud y usando el menor número posible de palabras el tema central del proyecto. Nota: las respuestas a las preguntas anteriormente formuladas no necesariamente deben responderse en mismo orden en el que aparecen. (Máximo 40 palabras)"
                />
                <Textarea label="Título" sinContador={true} id="titulo" error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />

                {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                    <RecomendacionEvaluador class="mt-8">
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                    <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                    <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.titulo_comentario ? evaluacion.servicio_tecnologico_evaluacion.titulo_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    </RecomendacionEvaluador>
                {/if}
            </div>

            <div class="py-24">
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
                    <RecomendacionEvaluador class="mt-8">
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                    <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                    <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.fecha_ejecucion_comentario ? evaluacion.servicio_tecnologico_evaluacion.fecha_ejecucion_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    </RecomendacionEvaluador>
                {/if}
            </div>

            <fieldset class="py-24" disabled>
                <div class="grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tipo_proyecto_st_id" value="Centro de formación" />
                    </div>
                    <div>
                        <Select id="tipo_proyecto_st_id" items={tiposProyectoSt} bind:selectedValue={$form.tipo_proyecto_st_id} error={errors.tipo_proyecto_st_id} autocomplete="off" placeholder="Seleccione una tipología de ST" required />
                    </div>
                </div>

                {#if $form.tipo_proyecto_st_id}
                    <div class="grid grid-cols-2">
                        <div>
                            <Label required class="mb-4" labelFor="estado_sistema_gestion_id" value="Estado del sistema de gestión" />
                        </div>
                        <div>
                            <DynamicList id="estado_sistema_gestion_id" bind:value={$form.estado_sistema_gestion_id} routeWebApi={route('web-api.estados-sistema-gestion', $form.tipo_proyecto_st_id['value'])} classes="min-h" placeholder="Seleccione un estado" message={errors.estado_sistema_gestion_id} required />
                        </div>
                    </div>
                {/if}

                <div class="grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="sector_productivo" value="Sector priorizado de Colombia Productiva" />
                    </div>
                    <div>
                        <Select id="sector_productivo" items={sectoresProductivos} bind:selectedValue={$form.sector_productivo} error={errors.sector_productivo} autocomplete="off" placeholder="Seleccione una sector" required />
                    </div>
                </div>

                <div class="grid grid-cols-2">
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

            <div class="py-24">
                <div class="grid grid-cols-1">
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
                    </div>
                </div>
                <div>
                    {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                        <RecomendacionEvaluador class="mt-8">
                            {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                                {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                    <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                        <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                        <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.resumen_comentario ? evaluacion.servicio_tecnologico_evaluacion.resumen_comentario : 'Sin recomendación'}</p>
                                    </div>
                                {/if}
                            {/each}
                        </RecomendacionEvaluador>
                    {/if}
                </div>
            </div>

            <div class="py-24">
                <div class="grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                        <InfoMessage class="mb-2">
                            <p>
                                Se debe evidenciar la identificación y caracterización del mercado potencial/objetivo, nicho de mercado al cual se busca atender o la necesidad que se busca satisfacer tomando como referencia el estudio del sector, identificando si existen el(los) mismo(s) alcance(s) o similar(es) en la empresa privada o pública u otros centros de formación de tal forma que el
                                proyecto no se convierta en una competencia frente a un servicio/producto ofertado. Se debe registrar el análisis de las tendencias del mercado, en relación con clientes potenciales, competidores y proveedores. En este ítem es necesario valorar las necesidades de los clientes actuales o potenciales y precisar la segmentación del mercado, las tendencias de los
                                precios y las gestiones comerciales a realizadas.
                                <br />
                                <strong>Nota:</strong> La información debe ser de fuentes primarias, ejemplo: Secretarías, DANE, Artículos científicos, entre otros y citarla utilizando normas APA séptima edición. (Máximo 10000 caracteres).
                            </p>
                        </InfoMessage>
                    </div>
                    <div>
                        <Textarea maxlength="10000" id="antecedentes" error={errors.antecedentes} bind:value={$formAntecedentes.antecedentes} on:input={() => syncColumnLong('antecedentes', $formAntecedentes)} required />
                    </div>
                </div>
                {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                    <RecomendacionEvaluador class="mt-8">
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                    <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                    <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.antecedentes_comentario ? evaluacion.servicio_tecnologico_evaluacion.antecedentes_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    </RecomendacionEvaluador>
                {/if}
            </div>

            <div class="py-24">
                <div class="grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="identificacion_problema" value="Identificación y descripción del problema" />
                        <InfoMessage
                            class="mb-2"
                            message="1. Descripción de la necesidad, problema u oportunidad identificada del plan tecnológico y/o agendas departamentales de innovación y competitividad.<br>2. Descripción del problema que se atiende con el proyecto, sustentado en el contexto, la caracterización, los datos, las estadísticas, de la regional, entre otros, citar toda la información consignada utilizando normas APA última edición. La información debe ser de fuentes primarias de información, ejemplo: Secretarías, DANE, Artículos científicos, entre otros."
                        />
                    </div>

                    <div>
                        <Textarea label="Identificación y descripción del problema" maxlength="5000" id="identificacion_problema" error={errors.identificacion_problema} bind:value={$formIdentificacionProblema.identificacion_problema} on:input={() => syncColumnLong('identificacion_problema', $formIdentificacionProblema)} required />
                    </div>
                </div>
                {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                    <RecomendacionEvaluador class="mt-8">
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                    <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                    <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.identificacion_problema_comentario ? evaluacion.servicio_tecnologico_evaluacion.identificacion_problema_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    </RecomendacionEvaluador>
                {/if}
            </div>

            <div class="py-24">
                <div class="grid grid-cols-1">
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
                    </div>
                </div>
                {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                    <RecomendacionEvaluador class="mt-8">
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                    <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                    <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.pregunta_formulacion_problema_comentario ? evaluacion.servicio_tecnologico_evaluacion.pregunta_formulacion_problema_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    </RecomendacionEvaluador>
                {/if}
            </div>

            <div class="py-24">
                <div class="grid grid-cols-1">
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
                    </div>
                </div>
                {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                    <RecomendacionEvaluador class="mt-8">
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                    <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                    <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.justificacion_problema_comentario ? evaluacion.servicio_tecnologico_evaluacion.justificacion_problema_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    </RecomendacionEvaluador>
                {/if}
            </div>

            <div class="py-24">
                <div class="grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="programas_formacion" value="Nombre de los programas de formación con los que se relaciona el proyecto" />
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
            </div>

            <div class="py-24">
                <div class="grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="zona_influencia" value="Zona de influencia" />
                    </div>
                    <div>
                        <Input label="Zona de influencia" id="zona_influencia" type="text" class="mt-1" error={errors.zona_influencia} placeholder="Escriba el número de aprendices que se beneficiarán en la ejecución del proyecto" bind:value={$formZonaInfluencia.zona_influencia} on:input={() => syncColumnLong('zona_influencia', $formZonaInfluencia)} required />
                    </div>
                </div>
            </div>

            <div class="py-24">
                <div class="grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                        <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Última edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                    </div>
                    <div>
                        <Textarea sinContador={true} id="bibliografia" error={errors.bibliografia} bind:value={$formBibliografia.bibliografia} on:input={() => syncColumnLong('bibliografia', $formBibliografia)} required />
                    </div>
                </div>
                {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                    <RecomendacionEvaluador class="mt-8">
                        {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                    <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                    <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.bibliografia_comentario ? evaluacion.servicio_tecnologico_evaluacion.bibliografia_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    </RecomendacionEvaluador>
                {/if}
            </div>

            {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                <div class="py-24">
                    <h1>Ortografía</h1>
                    {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                        {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                            <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.ortografia_comentario ? evaluacion.servicio_tecnologico_evaluacion.ortografia_comentario : 'Sin recomendación'}</p>
                            </div>
                        {/if}
                    {/each}
                </div>
            {/if}

            {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                <div class="py-24">
                    <h1>Redacción</h1>
                    {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                        {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                            <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.redaccion_comentario ? evaluacion.servicio_tecnologico_evaluacion.redaccion_comentario : 'Sin recomendación'}</p>
                            </div>
                        {/if}
                    {/each}
                </div>
            {/if}

            {#if isSuperAdmin || servicioTecnologico.proyecto.mostrar_recomendaciones}
                <div class="py-24">
                    <h1>Normas APA</h1>
                    {#each servicioTecnologico.proyecto.evaluaciones as evaluacion, i}
                        {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                            <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                                <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                                <p class="whitespace-pre-line text-xs">{evaluacion.servicio_tecnologico_evaluacion.normas_apa_comentario ? evaluacion.servicio_tecnologico_evaluacion.normas_apa_comentario : 'Sin recomendación'}</p>
                            </div>
                        {/if}
                    {/each}
                </div>
            {/if}
        </fieldset>
        <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
            <small class="flex items-center text-violet-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {servicioTecnologico.updated_at}
            </small>

            {#if servicioTecnologico.proyecto.allowed.to_update}
                <LoadingButton loading={$form.processing} type="submit">Guardar</LoadingButton>
            {:else}
                <span class="inline-block ml-1.5"> El proyecto no se puede modificar </span>
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
                {#if (JSON.parse(servicioTecnologico.proyecto.estado_cord_sennova)?.requiereSubsanar == true && servicioTecnologico.proyecto.mostrar_recomendaciones == true && servicioTecnologico.proyecto.mostrar_requiere_subsanacion == true) || (JSON.parse(servicioTecnologico.proyecto.estado)?.requiereSubsanar == true && servicioTecnologico.proyecto.mostrar_recomendaciones == true && servicioTecnologico.proyecto.mostrar_requiere_subsanacion == true)}
                    <h1 class="text-center mb-4 font-black text-2xl">Este proyecto requiere ser subsanado</h1>
                    <p>Por favor revise las observaciones de los evaluadores en cada uno de los campos y secciones.</p>
                    <p>Importante: Se ha agregado una sección de <strong>Comentarios generales</strong>, revise si hay comentarios de los evaluadores y por favor escriba la respectiva respuesta.</p>
                {:else if (JSON.parse(servicioTecnologico.proyecto.estado_cord_sennova)?.requiereSubsanar == false && servicioTecnologico.proyecto.mostrar_recomendaciones == true && servicioTecnologico.proyecto.mostrar_requiere_subsanacion == true) || (JSON.parse(servicioTecnologico.proyecto.estado)?.requiereSubsanar == false && servicioTecnologico.proyecto.mostrar_recomendaciones == true && servicioTecnologico.proyecto.mostrar_requiere_subsanacion == true)}
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
                <Button on:click={() => (proyectoDialogOpen = false)} variant={null}>Omitir</Button>
                {#if servicioTecnologico.proyecto.allowed.to_update}
                    <Button variant="raised" on:click={() => (proyectoDialogOpen = false)} on:click={() => Inertia.visit('#estructura-proyecto')}>Continuar diligenciando</Button>
                {/if}
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
