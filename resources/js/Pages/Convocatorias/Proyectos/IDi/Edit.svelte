<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'
    import { onMount } from 'svelte'

    import Button from '@/Components/Button'
    import Input from '@/Components/Input'
    import InputError from '@/Components/InputError'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Stepper from '@/Components/Stepper'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Textarea from '@/Components/Textarea'
    import InfoMessage from '@/Components/InfoMessage'
    import Select from '@/Components/Select'
    import SelectMulti from '@/Components/SelectMulti'
    import Switch from '@/Components/Switch'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import Radio from '@smui/radio'
    import Dialog from '@/Components/Dialog'

    export let errors
    export let convocatoria
    export let idi
    export let mesasSectoriales
    export let mesasSectorialesRelacionadas
    export let lineasTecnologicasRelacionadas
    export let tecnoacademias
    export let tecnoacademia
    export let opcionesIDiDropdown
    export let proyectoMunicipios

    $: $title = idi ? idi.titulo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

    let municipios
    let dialogOpen = errors.password != undefined ? true : false
    let sending = false

    let lineasTecnologicas = []
    let tieneVideo = idi.video != null
    let requiereJustificacionIndustria4 = idi.justificacion_industria_4 != null
    let requiereJustificacionEconomiaNaranja = idi.justificacion_economia_naranja != null
    let requiereJustificacionPoliticaDiscapacidad = idi.justificacion_politica_discapacidad != null

    let form = useForm({
        centro_formacion_id: idi.proyecto?.centro_formacion_id,
        linea_investigacion_id: idi.linea_investigacion_id,
        disciplina_subarea_conocimiento_id: idi.disciplina_subarea_conocimiento_id,
        tematica_estrategica_id: idi.tematica_estrategica_id,
        red_conocimiento_id: idi.red_conocimiento_id,
        tipo_proyecto_id: idi.proyecto?.tipo_proyecto_id,
        actividad_economica_id: idi.actividad_economica_id,
        titulo: idi.titulo,
        fecha_inicio: idi.fecha_inicio,
        fecha_finalizacion: idi.fecha_finalizacion,
        video: idi.video,
        justificacion_industria_4: idi.justificacion_industria_4,
        justificacion_economia_naranja: idi.justificacion_economia_naranja,
        justificacion_politica_discapacidad: idi.justificacion_politica_discapacidad,
        resumen: idi.resumen,
        antecedentes: idi.antecedentes,
        marco_conceptual: idi.marco_conceptual,
        metodologia: idi.metodologia,
        propuesta_sostenibilidad: idi.propuesta_sostenibilidad,
        bibliografia: idi.bibliografia,
        numero_aprendices: idi.numero_aprendices,
        municipios: proyectoMunicipios.length > 0 ? proyectoMunicipios : null,
        impacto_municipios: idi.impacto_municipios,
        impacto_centro_formacion: idi.impacto_centro_formacion,
        muestreo: idi.muestreo,
        actividades_muestreo: idi.actividades_muestreo,
        objetivo_muestreo: idi.objetivo_muestreo,

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

        linea_tecnologica_id: lineasTecnologicasRelacionadas,
        mesa_sectorial_id: mesasSectorialesRelacionadas,
    })

    onMount(() => {
        if (tecnoacademia) {
            getLineasTecnologicas(tecnoacademia)
        }
        getMunicipios()
    })

    function submit() {
        if (isSuperAdmin || checkRole(74)) {
            if ($form.relacionado_tecnoacademia?.value != 1) {
                $form.tecnoacademia_id = {}
                lineasTecnologicas = []
            }

            $form.put(route('convocatorias.idi.update', [convocatoria.id, idi.id]), {
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
        if (isSuperAdmin || checkRole(74)) {
            $deleteForm.delete(route('convocatorias.idi.destroy', [convocatoria.id, idi.id]), {
                preserveScroll: true,
            })
        }
    }

    $: selectedTecnoacademia = $form.tecnoacademia_id?.value

    $: if (selectedTecnoacademia) {
        getLineasTecnologicas(selectedTecnoacademia)
    }

    async function getLineasTecnologicas(tecnoacademia) {
        let res = await axios.get(route('web-api.tecnoacademias.lineas-tecnologicas', [tecnoacademia]))
        res.status == '200' ? ($form.linea_tecnologica_id = lineasTecnologicasRelacionadas) : null
        lineasTecnologicas = res.data
    }

    async function getMunicipios() {
        let res = await axios.get(route('web-api.municipios'))
        if (res.status == '200') {
            municipios = res.data
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} proyecto={idi} />

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
            <div class="mt-28">
                <Label required labelFor="titulo" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué." />
                <Textarea rows="4" id="titulo" error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label required labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <Input id="fecha_inicio" type="date" class="mt-1 block w-full" bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label required labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <Input id="fecha_finalizacion" type="date" class="mt-1 block w-full" bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion}
                    <div class="mb-20">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                    </div>
                {/if}
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                </div>
                <div>
                    <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                </div>
            </div>

            {#if $form.centro_formacion_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea de investigación" />
                    </div>
                    <div>
                        <DynamicList id="linea_investigacion_id" bind:value={$form.linea_investigacion_id} routeWebApi={route('web-api.lineas-investigacion', $form.centro_formacion_id)} classes="min-h" placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional" message={errors.linea_investigacion_id} required />
                    </div>
                </div>
            {/if}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipo_proyecto_id" value="Código dependencia presupuestal (SIIF)" />
                </div>
                <div>
                    <DynamicList id="tipo_proyecto_id" bind:value={$form.tipo_proyecto_id} routeWebApi={route('web-api.tipos-proyecto', 2)} classes="min-h" placeholder="Busque por el nombre de la dependencia presupuestal, línea programática" message={errors.tipo_proyecto_id} required />
                </div>
            </div>
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
                    <Label labelFor="video" value="¿El proyecto tiene video?" />
                </div>
                <div>
                    <Switch bind:checked={tieneVideo} />
                    {#if tieneVideo}
                        <InfoMessage message="Video de 3 minutos, en donde se presente de manera sencilla y dinámica la justificación del proyecto, la problemática, el objetivo general, los objetivos específicos, las actividades, los productos y su impacto en el marco del mecanismo de participación seleccionado como regional." />
                        <Input id="video" type="url" class="mt-1 block w-full" error={errors.video} placeholder="Link del video del proyecto https://www.youtube.com/watch?v=gmc4tk" bind:value={$form.video} required={!tieneVideo ? undefined : 'required'} />
                    {/if}
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
                        <Textarea rows="4" id="justificacion_industria_4" error={errors.justificacion_industria_4} bind:value={$form.justificacion_industria_4} required={!requiereJustificacionIndustria4 ? undefined : 'required'} />
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
                        <Textarea rows="4" id="justificacion_economia_naranja" error={errors.justificacion_economia_naranja} bind:value={$form.justificacion_economia_naranja} required={!requiereJustificacionEconomiaNaranja ? undefined : 'required'} />
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
                        <Textarea rows="4" id="justificacion_politica_discapacidad" error={errors.justificacion_politica_discapacidad} bind:value={$form.justificacion_politica_discapacidad} required={!requiereJustificacionPoliticaDiscapacidad ? undefined : 'required'} />
                    {/if}
                </div>
            </div>

            <hr />

            <div>
                <p class="text-center mt-36 mb-8">¿Cuál es el origen de las muestras con las que se realizarán las actividades de investigación, bioprospección y/o aprovechamiento comercial o industrial?</p>
                <InfoMessage message="Nota: Bioprospección se define como la exploración sistemática y sostenible de la biodiversidad para identificar y obtener nuevas fuentes de compuestos químicos, genes, proteínas, microorganismos y otros productos que tienen potencial de ser aprovechados comercialmente" />
                <InputError message={errors.muestreo} />
                <div class="flex mt-20 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="1" />
                        <span slot="label">
                            Especies Nativas. (es la especie o subespecie taxonómica o variedad de animales cuya área de disposición geográfica se extiende al territorio nacional o a aguas jurisdiccionales colombianas o forma parte de los mismos comprendidas las especies o subespecies que migran temporalmente a ellos, siempre y cuando no se encuentren en el país o migren a él como resultado
                            voluntario o involuntario de la actividad humana. Pueden ser silvestre, domesticada o escapada de domesticación incluyendo virus, viroides y similares)
                        </span>
                    </FormField>
                </div>

                <!-- Si seleccionan Especies nativas -->
                {#if $form.muestreo == 1}
                    <InfoMessage classes="mt-10" message="Ha seleccionado Especies Nativas. Por favor responda las siguientes preguntas:" />
                    <div class="flex mb-20">
                        <div class="bg-gray-200 flex-1 p-8">
                            <div class="flex items-center">
                                <Label required class="mb-4" id="1.1" value="¿Qué actividad pretende realizar con la especie nativa?" />
                            </div>

                            <p class="bg-indigo-100 mt-10 p-4 text-indigo-600">Seleccione una opción</p>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio bind:group={$form.actividades_muestreo} value="1.1.1" />
                                    <span slot="label"> Separación de las unidades funcionales y no funcionales del ADN y el ARN, en todas las formas que se encuentran en la naturaleza. </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio bind:group={$form.actividades_muestreo} value="1.1.2" />
                                    <span slot="label"> Aislamiento de una o varias moléculas, entendidas estas como micro y macromoléculas, producidas por el metabolismo de un organismo. </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio bind:group={$form.actividades_muestreo} value="1.1.3" />
                                    <span slot="label"> Solicitar patente sobre una función o propiedad identificada de una molécula, que se ha aislado y purificado. </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio bind:group={$form.actividades_muestreo} value="1.1.4" />
                                    <span slot="label"> No logro identificar la actividad a desarrollar con la especie nativa </span>
                                </FormField>
                            </div>
                        </div>

                        <div class="bg-gray-300 flex-1 p-8">
                            <div class="flex items-center">
                                <Label required class="mb-4" id="1.2" value="¿Cuál es la finalidad de las actividades a realizar con la especie nativa/endémica?" />
                            </div>

                            <p class="bg-indigo-100 mt-10 p-4 text-indigo-600">Seleccione una opción</p>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio bind:group={$form.objetivo_muestreo} value="1.2.1" />
                                    <span slot="label"> Investigación básica sin fines comerciales </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio bind:group={$form.objetivo_muestreo} value="1.2.2" />
                                    <span slot="label"> Bioprospección en cualquiera de sus fases </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio bind:group={$form.objetivo_muestreo} value="1.2.3" />
                                    <span slot="label"> Comercial o Industrial </span>
                                </FormField>
                            </div>
                        </div>
                    </div>
                {/if}

                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="2" />
                        <span slot="label"> Especies Introducidas. (son aquellas que no son nativas de Colombia y que ingresaron al país por intervención humana) </span>
                    </FormField>
                </div>
                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="3" />
                        <span slot="label"> Recursos genéticos humanos y sus productos derivados </span>
                    </FormField>
                </div>
                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="4" />
                        <span slot="label"> Intercambio de recursos genéticos y sus productos derivados, recursos biológicos que los contienen o los componentes asociados a estos. (son aquellas que realizan las comunidades indígenas, afroamericanas y locales de los Países Miembros de la Comunidad Andina entre sí y para su propio consumo, basadas en sus prácticas consuetudinarias) </span>
                    </FormField>
                </div>
                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="5" />
                        <span slot="label">
                            Recurso biológico que involucren actividades de sistemática molecular, ecología molecular, evolución y biogeografía molecular (siempre que el recurso biológico se haya colectado en el marco de un permiso de recolección de especímenes de especies silvestres de la diversidad biológica con fines de investigación científica no comercial o provenga de una colección
                            registrada ante el Instituto Alexander van Humboldt)
                        </span>
                    </FormField>
                </div>
                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="6" />
                        <span slot="label"> No aplica </span>
                    </FormField>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="relacionado_plan_tecnologico" value="¿El proyecto se alinea con el plan tecnológico desarrollado por el centro de formación?" />
                </div>
                <div>
                    <Select items={opcionesIDiDropdown} id="relacionado_plan_tecnologico" bind:selectedValue={$form.relacionado_plan_tecnologico} error={errors.relacionado_plan_tecnologico} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="relacionado_agendas_competitividad" value="¿El proyecto se alinea con las Agendas Departamentales de Competitividad e Innovación?" />
                </div>
                <div>
                    <Select items={opcionesIDiDropdown} id="relacionado_agendas_competitividad" bind:selectedValue={$form.relacionado_agendas_competitividad} error={errors.relacionado_agendas_competitividad} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="relacionado_mesas_sectoriales" value="¿El proyecto se alinea con las Mesas Sectoriales?" />
                </div>
                <div>
                    <Select items={opcionesIDiDropdown} id="relacionado_mesas_sectoriales" bind:selectedValue={$form.relacionado_mesas_sectoriales} error={errors.relacionado_mesas_sectoriales} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>
            {#if $form.relacionado_mesas_sectoriales?.value == 1}
                <div class="bg-indigo-100 p-5 mt-10">
                    <InputError message={errors.mesa_sectorial_id} />
                    <div class="grid grid-cols-2">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5" style="transform: translateX(-50px);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-indigo-600">Por favor seleccione la o las mesas sectoriales con la cual o las cuales se alinea el proyecto</p>
                        </div>
                        <div class="bg-white grid grid-cols-2 max-w-xl overflow-y-scroll shadow-2xl mt-4 h-80">
                            {#each mesasSectoriales as { id, nombre }, i}
                                <FormField>
                                    <Checkbox bind:group={$form.mesa_sectorial_id} value={id} />
                                    <span slot="label">{nombre}</span>
                                </FormField>
                            {/each}
                        </div>
                    </div>
                </div>
            {/if}

            <div class="mt-40 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="relacionado_tecnoacademia" value="¿El proyecto se formuló en conjunto con la tecnoacademia?" />
                </div>
                <div>
                    <Select items={opcionesIDiDropdown} id="relacionado_tecnoacademia" bind:selectedValue={$form.relacionado_tecnoacademia} error={errors.relacionado_tecnoacademia} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            {#if $form.relacionado_tecnoacademia?.value == 1}
                <div class="bg-indigo-100 p-5 mt-10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5" style="transform: translateX(-50px);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <div class="grid grid-cols-2">
                        <div>
                            <p class="text-indigo-600">Por favor seleccione la Tecnoacademia con la cual articuló el proyecto</p>
                        </div>
                        <div>
                            <Select items={tecnoacademias} id="tecnoacademia_id" bind:selectedValue={$form.tecnoacademia_id} error={errors.tecnoacademia_id} autocomplete="off" placeholder="Seleccione una opción" required />
                            {#if lineasTecnologicas.length > 0}
                                <div class="bg-white grid grid-cols-2 max-w-xl overflow-y-scroll shadow-2xl mt-4 h-80">
                                    {#each lineasTecnologicas as { value, label }, i}
                                        <Label class="p-3 border-t border-b flex items-center text-sm" labelFor={'linea-tecnologica-' + value} value={label} />

                                        <div class="border-b border-t flex items-center justify-center">
                                            <input type="checkbox" bind:group={$form.linea_tecnologica_id} id={'linea-tecnologica-' + value} {value} class="rounded text-indigo-500" />
                                        </div>
                                    {/each}
                                </div>
                            {/if}
                        </div>
                    </div>
                    <InputError message={errors.linea_tecnologica_id} />
                </div>
            {/if}

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                    <InfoMessage message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea rows="4" id="resumen" error={errors.resumen} bind:value={$form.resumen} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                    <InfoMessage message="Presenta las investigaciones, innovaciones o desarrollos tecnológicos que se han realizado a nivel internacional, nacional, departamental o municipal en el marco de la temática de la propuesta del proyecto; que muestran la pertinencia del proyecto, citar toda la información consignada utilizando normas APA sexta edición." />
                </div>
                <div>
                    <Textarea rows="4" id="antecedentes" error={errors.antecedentes} bind:value={$form.antecedentes} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="marco_conceptual" value="Marco conceptual" />
                    <InfoMessage message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones." />
                </div>
                <div>
                    <Textarea rows="4" id="marco_conceptual" error={errors.marco_conceptual} bind:value={$form.marco_conceptual} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="metodologia" value="Metodología" />
                    <InfoMessage message="Describir la (s) metodología (s) a utilizar en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea rows="4" id="metodologia" error={errors.metodologia} bind:value={$form.metodologia} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="propuesta_sostenibilidad" value="Propuesta de sostenibilidad" />
                    <InfoMessage message="Identificar los efectos que tiene el desarrollo del proyecto de investigación ya sea positivos o negativos. Se recomienda establecer las acciones pertinentes para mitigar los impactos negativos ambientales identificados y anexar el respectivo permiso ambiental cuando aplique. Tener en cuenta si aplica el decreto 1376 de 2013." />
                </div>
                <div>
                    <Textarea rows="4" id="propuesta_sostenibilidad" error={errors.propuesta_sostenibilidad} bind:value={$form.propuesta_sostenibilidad} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Sexta edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea rows="4" id="bibliografia" error={errors.bibliografia} bind:value={$form.bibliografia} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="numero_aprendices" value="Número de los aprendices que se beneficiarán en la ejecución del proyecto" />
                </div>
                <div>
                    <Input id="numero_aprendices" type="number" min="0" max="9999" class="mt-1 block w-full" error={errors.numero_aprendices} placeholder="Escriba el número de aprendices que se beneficiarán en la ejecución del proyecto" bind:value={$form.numero_aprendices} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="municipios" value="Nombre de los municipios beneficiados" />
                </div>
                <div>
                    <SelectMulti id="municipios" bind:selectedValue={$form.municipios} items={municipios} isMulti={true} error={errors.municipios} placeholder="Buscar municipios" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="impacto_municipios" value="Descripción del beneficio en los municipios" />
                </div>
                <div>
                    <Textarea rows="4" id="impacto_municipios" error={errors.impacto_municipios} bind:value={$form.impacto_municipios} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="impacto_centro_formacion" value="Impacto en el centro de formación" />
                </div>
                <div>
                    <Textarea rows="4" id="impacto_centro_formacion" error={errors.impacto_centro_formacion} bind:value={$form.impacto_centro_formacion} required />
                </div>
            </div>
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || checkRole(74)}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin || checkRole(74)}
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
                <Label labelFor="password" class="mb-6" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente este proyecto." />
                <Input id="password" type="password" class="mt-1 block w-full" error={errors.password} placeholder="Escriba su contraseña" bind:value={$deleteForm.password} required />
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
