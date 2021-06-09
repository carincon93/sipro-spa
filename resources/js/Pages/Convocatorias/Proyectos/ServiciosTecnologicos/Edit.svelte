<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Button from '@/Shared/Button'
    import Input from '@/Shared/Input'
    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Stepper from '@/Shared/Stepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import Select from '@/Shared/Select'
    import Switch from '@/Shared/Switch'
    import Dialog from '@/Shared/Dialog'

    export let errors
    export let convocatoria
    export let servicioTecnologico
    export let mesasTecnicas

    $: $title = servicioTecnologico ? servicioTecnologico.titulo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let municipios
    let dialogOpen = errors.password != undefined ? true : false
    let sending = false

    let tieneVideo = servicioTecnologico.video != null
    let requiereJustificacionIndustria4 = servicioTecnologico.justificacion_industria_4 != null
    let requiereJustificacionEconomiaNaranja = servicioTecnologico.justificacion_economia_naranja != null
    let requiereJustificacionPoliticaDiscapacidad = servicioTecnologico.justificacion_politica_discapacidad != null

    let form = useForm({
        centro_formacion_id: servicioTecnologico.proyecto?.centro_formacion_id,
        tipo_proyecto_id: servicioTecnologico.proyecto?.tipo_proyecto_id,
        disciplina_subarea_conocimiento_id: servicioTecnologico.disciplina_subarea_conocimiento_id,
        tematica_estrategica_id: servicioTecnologico.tematica_estrategica_id,
        red_conocimiento_id: servicioTecnologico.red_conocimiento_id,
        actividad_economica_id: servicioTecnologico.actividad_economica_id,
        titulo: servicioTecnologico.titulo,
        titulo_proyecto_articulado: servicioTecnologico.titulo_proyecto_articulado,
        fecha_inicio: servicioTecnologico.fecha_inicio,
        fecha_finalizacion: servicioTecnologico.fecha_finalizacion,
        video: servicioTecnologico.video,
        justificacion_industria_4: servicioTecnologico.justificacion_industria_4,
        justificacion_economia_naranja: servicioTecnologico.justificacion_economia_naranja,
        justificacion_politica_discapacidad: servicioTecnologico.justificacion_politica_discapacidad,
        resumen: servicioTecnologico.resumen,
        antecedentes: servicioTecnologico.antecedentes,
        pregunta_formulacion_problema: servicioTecnologico.pregunta_formulacion_problema,
        metodologia: servicioTecnologico.metodologia,
        propuesta_sostenibilidad: servicioTecnologico.propuesta_sostenibilidad,
        bibliografia: servicioTecnologico.bibliografia,
        numero_aprendices: servicioTecnologico.numero_aprendices,
        impacto_centro_formacion: servicioTecnologico.impacto_centro_formacion,
        infraestructura_adecuada: servicioTecnologico.infraestructura_adecuada ? true : false,
        especificaciones_area: servicioTecnologico.especificaciones_area,
        linea_programatica_id: servicioTecnologico.linea_programatica_id,
        mesa_tecnica_id: {
            value: servicioTecnologico.tema_priorizado.mesa_tecnica.id,
            label: mesasTecnicas.find((item) => item.value == servicioTecnologico.tema_priorizado.mesa_tecnica.id)?.label,
        },
        sector_productivo_id: servicioTecnologico.tema_priorizado.sector_productivo.id,
        tema_priorizado_id: servicioTecnologico.tema_priorizado_id,
    })

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [9, 10])) {
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
        if (isSuperAdmin || checkPermission(authUser, [10])) {
            $deleteForm.delete(route('convocatorias.servicios-tecnologicos.destroy', [convocatoria.id, servicioTecnologico.id]), {
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} proyecto={servicioTecnologico} />

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
            <div class="mt-28">
                <Textarea label="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué." maxlength="40000" id="titulo" error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label required labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos} max={convocatoria.max_fecha_finalizacion_proyectos} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label required labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos} max={convocatoria.max_fecha_finalizacion_proyectos} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion}
                    <div class="mb-20 flex">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                    </div>
                {/if}
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                        <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                    </div>
                    <div>
                        <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tipo_proyecto_id" value="Código dependencia presupuestal (SIIF)" />
                    </div>
                    <div>
                        <DynamicList id="tipo_proyecto_id" bind:value={$form.tipo_proyecto_id} routeWebApi={route('web-api.tipos-proyecto', 3)} classes="min-h" placeholder="Busque por el nombre de la dependencia presupuestal, línea programática" message={errors.tipo_proyecto_id} required />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 text-center">
                <h1 class="text-2xl">Articulación con otras estrategias SENNOVA</h1>
                <p>Si el proyecto necesita articularse con otra línea programática para su desarrollo, Por favor registre la siguiente información:</p>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="linea_programatica_id" value="Línea programática" />
                </div>
                <div>
                    <DynamicList id="linea_programatica_id" bind:value={$form.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas')} classes="min-h" placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="titulo_proyecto_articulado" value="Título del proyecto de la línea programática con la que se realiza articulación" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="titulo_proyecto_articulado" bind:value={$form.titulo_proyecto_articulado} error={errors.titulo_proyecto_articulado} />
                </div>
            </div>

            <hr class="mt-32" />

            <div class="mt-10">
                <h1 class="text-2xl text-center">Mesa sectorial a la que se encuentra articulado el proyecto</h1>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="mesa_tecnica_id" value="Mesa técnica ST" />
                </div>
                <div>
                    <Select id="mesa_tecnica_id" items={mesasTecnicas} bind:selectedValue={$form.mesa_tecnica_id} error={errors.mesa_tecnica_id} autocomplete="off" placeholder="Seleccione una mesa técnica" required />
                </div>
            </div>

            {#if $form.mesa_tecnica_id?.value}
                <div class="mt-10 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="sector_productivo_id" value="Sector productivo" />
                    </div>
                    <div>
                        <DynamicList id="sector_productivo_id" bind:value={$form.sector_productivo_id} routeWebApi={route('web-api.sectores-productivos', [$form.mesa_tecnica_id?.value])} classes="min-h" placeholder="Busque por el nombre del sector productivo" message={errors.sector_productivo_id} required />
                    </div>
                </div>
            {/if}

            {#if $form.mesa_tecnica_id?.value && $form.sector_productivo_id}
                <div class="mt-10 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tema_priorizado_id" value="Tema priorizado" />
                    </div>

                    <div>
                        <DynamicList id="tema_priorizado_id" bind:value={$form.tema_priorizado_id} routeWebApi={route('web-api.temas-priorizados', [$form.mesa_tecnica_id.value, $form.sector_productivo_id])} classes="min-h" placeholder="Busque por el nombre del tema priorizado" message={errors.tema_priorizado_id} required />
                    </div>
                </div>
            {/if}

            <hr class="mt-32" />

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="red_conocimiento_id" value="Red de conocimiento sectorial" />
                </div>
                <div>
                    <DynamicList id="red_conocimiento_id" bind:value={$form.red_conocimiento_id} routeWebApi={route('web-api.redes-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la red de conocimiento sectorial" message={errors.red_conocimiento_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="disciplina_subarea_conocimiento_id" value="Disciplina de la subárea de conocimiento" />
                </div>
                <div>
                    <DynamicList id="disciplina_subarea_conocimiento_id" bind:value={$form.disciplina_subarea_conocimiento_id} routeWebApi={route('web-api.disciplinas-subarea-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento" message={errors.disciplina_subarea_conocimiento_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="actividad_economica_id" value="¿En cuál de estas actividades económicas se puede aplicar el proyecto de investigación?" />
                </div>
                <div>
                    <DynamicList id="actividad_economica_id" bind:value={$form.actividad_economica_id} routeWebApi={route('web-api.actividades-economicas')} placeholder="Busque por el nombre de la actividad económica" classes="min-h" message={errors.actividad_economica_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tematica_estrategica_id" value="Temática estratégica SENA" />
                </div>
                <div>
                    <DynamicList id="tematica_estrategica_id" bind:value={$form.tematica_estrategica_id} routeWebApi={route('web-api.tematicas-estrategicas')} placeholder="Busque por el nombre de la temática estrategica SENA" message={errors.tematica_estrategica_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label labelFor="video" value="Video" />
                </div>
                <div>
                    <InfoMessage message="Video de las instalaciones donde se desarrollan las actividades de la línea servicios tecnológicos. (Youtube, Vídeo en Google Drive con visualización pública)" />
                    <Input id="video" type="url" class="mt-1" error={errors.video} placeholder="Link del video del proyecto https://www.youtube.com/watch?v=gmc4tk" bind:value={$form.video} required={!tieneVideo ? undefined : 'required'} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label id="justificacion_industria_4" value="¿El proyecto está relacionado con la industria 4.0?" />
                </div>
                <div>
                    <div class="flex items-center mb-14">
                        <Switch bind:checked={requiereJustificacionIndustria4} />
                    </div>

                    {#if requiereJustificacionIndustria4}
                        <InfoMessage message="Si el proyecto está relacionado con la industria 4.0 por favor realice la justificación." />
                        <Textarea maxlength="40000" id="justificacion_industria_4" error={errors.justificacion_industria_4} bind:value={$form.justificacion_industria_4} required={!requiereJustificacionIndustria4 ? undefined : 'required'} />
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label labelFor="justificacion_economia_naranja" value="¿El proyecto está relacionado con la economía naranja?" />
                </div>
                <div>
                    <div class="flex items-center mb-14">
                        <Switch bind:checked={requiereJustificacionEconomiaNaranja} />
                    </div>
                    {#if requiereJustificacionEconomiaNaranja}
                        <InfoMessage message="Si el proyecto está relacionado con la economía naranja por favor realice la justificación. (Ver documento de apoyo: Guía Rápida SENA es NARANJA.)" />
                        <Textarea maxlength="40000" id="justificacion_economia_naranja" error={errors.justificacion_economia_naranja} bind:value={$form.justificacion_economia_naranja} required={!requiereJustificacionEconomiaNaranja ? undefined : 'required'} />
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label labelFor="justificacion_politica_discapacidad" value="¿El proyecto aporta a la Política Institucional para Atención de las Personas con discapacidad?" />
                </div>
                <div>
                    <div class="flex items-center mb-14">
                        <Switch bind:checked={requiereJustificacionPoliticaDiscapacidad} />
                    </div>
                    {#if requiereJustificacionPoliticaDiscapacidad}
                        <InfoMessage message="Si el proyecto aporta a la Política Institucional para Atención de las Personas con discapacidad por favor realice la justificación. RESOLUCIÓN 01726 DE 2014 - Por la cual se adopta la Política Institucional para Atención de las Personas con discapacidad." />
                        <Textarea maxlength="40000" id="justificacion_politica_discapacidad" error={errors.justificacion_politica_discapacidad} bind:value={$form.justificacion_politica_discapacidad} required={!requiereJustificacionPoliticaDiscapacidad ? undefined : 'required'} />
                    {/if}
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                    <InfoMessage message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="resumen" error={errors.resumen} bind:value={$form.resumen} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                    <InfoMessage
                        message="Identificar y caracterizar el mercado potencial, objetivo, nicho de mercado al cual se busca atender o la necesidad que se busca satisfacer tomando como referencia el estudio del sector. Registrar el análisis de las tendencias del mercado, en relación con clientes potenciales, competidores y proveedores. En este ítem es necesario valorar las necesidades de los clientes actuales o potenciales, y precisar la segmentación del mercado, las tendencias de los precios y las gestiones comerciales a realizar. "
                    />
                </div>
                <div>
                    <Textarea maxlength="40000" id="antecedentes" error={errors.antecedentes} bind:value={$form.antecedentes} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="pregunta_formulacion_problema" value="Pregunta de la formulación del problema" />
                    <InfoMessage
                        message="Se debe plantear el problema en forma de pregunta donde se define exactamente ¿cuál es el problema a resolver, investigar o intervenir?. La pregunta debe cumplir las siguientes condiciones: • Debe guardar una estrecha correspondencia con el título del proyecto. • Evitar adjetivos que impliquen juicios de valor tales como: bueno, malo, mejor, peor. • La pregunta no debe dar origen a respuestas tales como si o no. De ser así, debe ser reformulada."
                    />
                </div>
                <div>
                    <Textarea maxlength="40000" id="pregunta_formulacion_problema" error={errors.pregunta_formulacion_problema} bind:value={$form.pregunta_formulacion_problema} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="metodologia" value="Metodología" />
                    <InfoMessage message="Describir la (s) metodología (s) a utilizar en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="metodologia" error={errors.metodologia} bind:value={$form.metodologia} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="propuesta_sostenibilidad" value="Propuesta de sostenibilidad" />
                    <InfoMessage message="Identificar los efectos que tiene el desarrollo del proyecto de investigación ya sea positivos o negativos. Se recomienda establecer las acciones pertinentes para mitigar los impactos negativos ambientales identificados y anexar el respectivo permiso ambiental cuando aplique. Tener en cuenta si aplica el decreto 1376 de 2013." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="propuesta_sostenibilidad" error={errors.propuesta_sostenibilidad} bind:value={$form.propuesta_sostenibilidad} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Sexta edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="bibliografia" error={errors.bibliografia} bind:value={$form.bibliografia} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="numero_aprendices" value="Número de los aprendices que se beneficiarán en la ejecución del proyecto" />
                </div>
                <div>
                    <Input id="numero_aprendices" type="number" input$tmin="0" input$max="9999" class="mt-1" error={errors.numero_aprendices} placeholder="Escriba el número de aprendices que se beneficiarán en la ejecución del proyecto" bind:value={$form.numero_aprendices} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="impacto_centro_formacion" value="Impacto en el centro de formación" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="impacto_centro_formacion" error={errors.impacto_centro_formacion} bind:value={$form.impacto_centro_formacion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required labelFor="justificacion_politica_discapacidad" value="Cuenta con infraestructura adecuada y propia para el funcionamiento de la línea servicios tecnológicos en el centro de formación?" />
                </div>
                <div>
                    <div class="flex items-center mb-14">
                        <Switch bind:checked={$form.infraestructura_adecuada} />
                    </div>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="especificaciones_area" value="Relacione las especificaciones del área donde se desarrollan las actividades de servicios tecnológicos en el centro de formación" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="especificaciones_area" error={errors.especificaciones_area} bind:value={$form.especificaciones_area} required />
                </div>
            </div>
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || checkPermission(authUser, [10])}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin || checkPermission(authUser, [9, 10])}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={dialogOpen}>
        <div slot="titulo" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <InfoMessage message="¿Está seguro (a) que desea eliminar este proyecto?<br />Una vez eliminado el proyecto, todos sus recursos y datos se eliminarán de forma permanente." />

            <form on:submit|preventDefault={destroy} id="delete-tatp" class="mt-10 mb-28">
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente este proyecto" />
                <Input label="Ingrese su contraseña" id="password" type="password" class="mt-4" error={errors.password} bind:value={$deleteForm.password} required />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="delete-tatp">Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
