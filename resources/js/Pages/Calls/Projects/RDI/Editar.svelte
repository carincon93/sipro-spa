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
    export let call
    export let rdi
    export let sectorBasedCommittees
    export let relatedSectorBasedCommittees
    export let relatedTechnologicalLines
    export let technoAcademies
    export let technoAcademy
    export let rdiDropdownOptions
    export let projectCities
    export let cities

    $: $title = rdi ? rdi.title : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexRDI =
        authUser.can.find((element) => element == 'rdi.index') == 'rdi.index'
    let canCreateRDI =
        authUser.can.find((element) => element == 'rdi.create') == 'rdi.create'
    let canShowRDI =
        authUser.can.find((element) => element == 'rdi.show') == 'rdi.show'
    let canEditRDI =
        authUser.can.find((element) => element == 'rdi.edit') == 'rdi.edit'
    let canDestroyRDI =
        authUser.can.find((element) => element == 'rdi.destroy') == 'rdi.delete'

    let dialog_open = errors.password != undefined ? true : false
    let sending = false

    let video = rdi.video != null
    let justificacion_industria_4 = rdi.justificacion_industria_4 != null
    let justificacion_economica_naranja =
        rdi.justificacion_economica_naranja != null
    let justificacion_politica_discapacidad =
        rdi.justificacion_politica_discapacidad != null

    let technologicalLines = []

    let form = useForm({
        academic_centre_id: rdi.project.academic_centre_id,
        linea_investigacion_id: rdi.linea_investigacion_id,
        disciplina_subarea_conocimiento_id:
            rdi.disciplina_subarea_conocimiento_id,
        tematica_estrategica_id: rdi.tematica_estrategica_id,
        red_conocimiento_id: rdi.red_conocimiento_id,
        project_type_id: rdi.project.project_type_id,
        actividad_economica_id: rdi.actividad_economica_id,
        title: rdi.title,
        fecha_incio: rdi.fecha_incio,
        fecha_finalizacion: rdi.fecha_finalizacion,
        video: rdi.video,
        justificacion_industria_4: rdi.justificacion_industria_4,
        justificacion_economica_naranja: rdi.justificacion_economica_naranja,
        justificacion_politica_discapacidad:
            rdi.justificacion_politica_discapacidad,
        abstract: rdi.abstract,
        antecedentes: rdi.antecedentes,
        marco_conceptual: rdi.marco_conceptual,
        metodologia: rdi.metodologia,
        propuesta_sostenibilidad: rdi.propuesta_sostenibilidad,
        bibliografia: rdi.bibliografia,
        numero_aprendices: rdi.numero_aprendices,
        cities: projectCities.length > 0 ? projectCities : null,
        impacto_ciudades: rdi.impacto_ciudades,
        impacto_centro_formacion: rdi.impacto_centro_formacion,
        muestreo: rdi.muestreo,
        actividades_muestreo: rdi.actividades_muestreo,
        muestreo_objective: rdi.muestreo_objective,

        relacionado_plan_tecnologico: {
            value: rdi.relacionado_plan_tecnologico,
            label: rdiDropdownOptions.find(
                (item) => item.value == rdi.relacionado_plan_tecnologico,
            )?.label,
        },
        relacionado_agendas_competitividad: {
            value: rdi.relacionado_agendas_competitividad,
            label: rdiDropdownOptions.find(
                (item) => item.value == rdi.relacionado_agendas_competitividad,
            )?.label,
        },
        relacionado_mesas_sectoriales: {
            value: rdi.relacionado_mesas_sectoriales,
            label: rdiDropdownOptions.find(
                (item) => item.value == rdi.relacionado_mesas_sectoriales,
            )?.label,
        },
        relacionado_tecnoacademia: {
            value: rdi.relacionado_tecnoacademia,
            label: rdiDropdownOptions.find(
                (item) => item.value == rdi.relacionado_tecnoacademia,
            )?.label,
        },

        techno_academy_id: {
            value: technoAcademy?.id,
            label: technoAcademies.find(
                (item) => item.value == technoAcademy?.id,
            )?.label,
        },

        technological_line_id: relatedTechnologicalLines,
        sector_based_committee_id: relatedSectorBasedCommittees,
    })

    onMount(() => {
        if (technoAcademy) {
            getTechnologicalLines(technoAcademy)
        }
        getCities()
    })

    function submit() {
        if (canEditRDI || isSuperAdmin) {
            if ($form.relacionado_tecnoacademia?.value != 1) {
                $form.techno_academy_id = {}
                technologicalLines = []
            }

            $form.put(route('calls.rdi.update', [call.id, rdi.id]), {
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
        if (canDestroyRDI || isSuperAdmin) {
            $deleteForm.delete(route('calls.rdi.destroy', [call.id, rdi.id]), {
                preserveScroll: true,
            })
        }
    }

    $: selectedTechnoAcademy = $form.techno_academy_id?.value

    $: if (selectedTechnoAcademy) {
        getTechnologicalLines(selectedTechnoAcademy)
    }

    async function getTechnologicalLines(technoAcademy) {
        let res = await axios.get(
            route('web-api.techno-academies.technological-lines', [
                technoAcademy,
            ]),
        )
        res.status == '200'
            ? ($form.technological_line_id = relatedTechnologicalLines)
            : null
        technologicalLines = res.data
    }

    async function getCities() {
        let res = await axios.get(route('web-api.ciudades'))
        if (res.status == '200') {
            cities = res.data
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {call} project={rdi.project} />

    <form on:submit|preventDefault={submit}>
        <fieldset
            class="p-8"
            disabled={canEditRDI || isSuperAdmin ? undefined : true}
        >
            <div class="mt-28">
                <Label
                    required
                    labelFor="title"
                    class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full"
                    value="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué."
                />
                <Textarea
                    rows="4"
                    id="title"
                    error={errors.title}
                    bind:value={$form.title}
                    classes="bg-transparent block border-0 {errors.title
                        ? ''
                        : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full"
                    required
                />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <div class="mt-4 flex items-start justify-around">
                    <div
                        class="mt-4 flex {errors.fecha_incio
                            ? ''
                            : 'items-center'}"
                    >
                        <Label
                            required
                            labelFor="fecha_incio"
                            class={errors.fecha_incio ? 'top-3.5 relative' : ''}
                            value="Del"
                        />
                        <div class="ml-4">
                            <Input
                                id="fecha_incio"
                                type="date"
                                class="mt-1 block w-full"
                                bind:value={$form.fecha_incio}
                                required
                            />
                        </div>
                    </div>
                    <div
                        class="mt-4 flex {errors.fecha_finalizacion
                            ? ''
                            : 'items-center'}"
                    >
                        <Label
                            required
                            labelFor="fecha_finalizacion"
                            class={errors.fecha_finalizacion
                                ? 'top-3.5 relative'
                                : ''}
                            value="hasta"
                        />
                        <div class="ml-4">
                            <Input
                                id="fecha_finalizacion"
                                type="date"
                                class="mt-1 block w-full"
                                bind:value={$form.fecha_finalizacion}
                                required
                            />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_incio || errors.fecha_finalizacion}
                    <div class="mb-20">
                        <InputError
                            classes="text-center"
                            message={errors.fecha_incio}
                        />
                        <InputError
                            classes="text-center"
                            message={errors.fecha_finalizacion}
                        />
                    </div>
                {/if}
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="academic_centre_id"
                        value="Centro de formación"
                    />
                    <small
                        >Nota: El Centro de Formación relacionado es el ejecutor
                        del proyecto</small
                    >
                </div>
                <div>
                    <DynamicList
                        id="academic_centre_id"
                        bind:value={$form.academic_centre_id}
                        routeWebApi={route('web-api.centros-formacion')}
                        placeholder="Busque por el nombre del centro de formación"
                        message={errors.academic_centre_id}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="linea_investigacion_id"
                        value="Línea de investigación"
                    />
                </div>
                <div>
                    <DynamicList
                        id="linea_investigacion_id"
                        bind:value={$form.linea_investigacion_id}
                        routeWebApi={route('web-api.lineas-investigacion')}
                        classes="min-h"
                        placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional"
                        message={errors.linea_investigacion_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="project_type_id"
                        value="Código dependencia presupuestal (SIIF)"
                    />
                </div>
                <div>
                    <DynamicList
                        id="project_type_id"
                        bind:value={$form.project_type_id}
                        routeWebApi={route('web-api.project-types', ['I+D+i'])}
                        classes="min-h"
                        placeholder="Busque por el nombre de la dependencia presupuestal, línea programática"
                        message={errors.project_type_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="red_conocimiento_id"
                        value="Red de conocimiento sectorial"
                    />
                </div>
                <div>
                    <DynamicList
                        id="red_conocimiento_id"
                        bind:value={$form.red_conocimiento_id}
                        routeWebApi={route('web-api.knowledge-networks')}
                        classes="min-h"
                        placeholder="Busque por el nombre de la red de conocimiento sectorial"
                        message={errors.red_conocimiento_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="disciplina_subarea_conocimiento_id"
                        value="Disciplina de la subárea de conocimiento"
                    />
                </div>
                <div>
                    <DynamicList
                        id="disciplina_subarea_conocimiento_id"
                        bind:value={$form.disciplina_subarea_conocimiento_id}
                        routeWebApi={route(
                            'web-api.knowledge-subarea-disciplines',
                        )}
                        classes="min-h"
                        placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento"
                        message={errors.disciplina_subarea_conocimiento_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="actividad_economica_id"
                        value="¿En cuál de estas actividades económicas se puede aplicar el proyecto de investigación?"
                    />
                </div>
                <div>
                    <DynamicList
                        id="actividad_economica_id"
                        bind:value={$form.actividad_economica_id}
                        routeWebApi={route('web-api.economic-activities')}
                        placeholder="Busque por el nombre de la actividad económica"
                        classes="min-h"
                        message={errors.actividad_economica_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="tematica_estrategica_id"
                        value="Temática estratégica SENA"
                    />
                </div>
                <div>
                    <DynamicList
                        id="tematica_estrategica_id"
                        bind:value={$form.tematica_estrategica_id}
                        routeWebApi={route('web-api.strategic-thematics')}
                        placeholder="Busque por el nombre de la temática estrategica SENA"
                        message={errors.tematica_estrategica_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label labelFor="video" value="¿El proyecto tiene video?" />
                </div>
                <div>
                    <Switch bind:checked={video} />
                    {#if video}
                        <Input
                            id="video"
                            type="url"
                            class="mt-1 block w-full"
                            error={errors.video}
                            placeholder="Link del video del proyecto https://www.youtube.com/watch?v=gmc4tk"
                            bind:value={$form.video}
                            required={!video ? undefined : 'required'}
                        />
                        <InfoMessage
                            message="Video de 3 minutos, en donde se presente de manera sencilla y dinámica la justificación del proyecto, la problemática, el objetivo general, los objetivos específicos, las actividades, los productos y su impacto en el marco del mecanismo de participación seleccionado como regional."
                        />
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        id="justificacion_industria_4"
                        value="¿El proyecto está relacionado con la industria 4.0?"
                    />
                </div>
                <div>
                    <div class="flex items-center mb-14">
                        <Switch bind:checked={justificacion_industria_4} />
                    </div>
                    {#if justificacion_industria_4}
                        <Textarea
                            rows="4"
                            id="justificacion_industria_4"
                            error={errors.justificacion_industria_4}
                            bind:value={$form.justificacion_industria_4}
                            required={!justificacion_industria_4
                                ? undefined
                                : 'required'}
                        />
                        <InfoMessage
                            message="Si el proyecto está relacionado con la industria 4.0 por favor realice la justificación."
                        />
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        labelFor="justificacion_economica_naranja"
                        value="¿El proyecto está relacionado con la economía naranja?"
                    />
                </div>
                <div>
                    <div class="flex items-center mb-14">
                        <Switch
                            bind:checked={justificacion_economica_naranja}
                        />
                    </div>
                    {#if justificacion_economica_naranja}
                        <Textarea
                            rows="4"
                            id="justificacion_economica_naranja"
                            error={errors.justificacion_economica_naranja}
                            bind:value={$form.justificacion_economica_naranja}
                            required={!justificacion_economica_naranja
                                ? undefined
                                : 'required'}
                        />
                        <InfoMessage
                            message="Si el proyecto está relacionado con la economía naranja por favor realice la justificación. (Ver documento de apoyo: Guía Rápida SENA es NARANJA.)"
                        />
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        labelFor="justificacion_politica_discapacidad"
                        value="¿El proyecto aporta a la Política Institucional para Atención de las Personas con discapacidad?"
                    />
                </div>
                <div>
                    <div class="flex items-center mb-14">
                        <Switch
                            bind:checked={justificacion_politica_discapacidad}
                        />
                    </div>
                    {#if justificacion_politica_discapacidad}
                        <Textarea
                            rows="4"
                            id="justificacion_politica_discapacidad"
                            error={errors.justificacion_politica_discapacidad}
                            bind:value={$form.justificacion_politica_discapacidad}
                            required={!justificacion_politica_discapacidad
                                ? undefined
                                : 'required'}
                        />
                        <InfoMessage
                            message="Si el proyecto aporta a la Política Institucional para Atención de las Personas con discapacidad por favor realice la justificación. RESOLUCIÓN 01726 DE 2014 - Por la cual se adopta la Política Institucional para Atención de las Personas con discapacidad."
                        />
                    {/if}
                </div>
            </div>

            <div>
                <p class="text-center mt-36 mb-20">
                    ¿Cuál es el origen de las muestras con las que se realizarán
                    las actividades de investigación, bioprospección y/o
                    aprovechamiento comercial o industrial?
                </p>
                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="1" />
                        <span slot="label">
                            Especies Nativas. (es la especie o subespecie
                            taxonómica o variedad de animales cuya área de
                            disposición geográfica se extiende al territorio
                            nacional o a aguas jurisdiccionales colombianas o
                            forma parte de los mismos comprendidas las especies
                            o subespecies que migran temporalmente a ellos,
                            siempre y cuando no se encuentren en el país o
                            migren a él como resultado voluntario o involuntario
                            de la actividad humana. Pueden ser silvestre,
                            domesticada o escapada de domesticación incluyendo
                            virus, viroides y similares)
                        </span>
                    </FormField>
                </div>

                <!-- Si seleccionan Especies nativas -->
                {#if $form.muestreo == 1}
                    <InfoMessage
                        classes="mt-10"
                        message="Ha seleccionado Especies Nativas. Por favor responda las siguientes preguntas:"
                    />
                    <div class="flex mb-20">
                        <div class="bg-gray-200 flex-1 p-8">
                            <div class="flex items-center">
                                <Label
                                    required
                                    class="mb-4"
                                    id="1.1"
                                    value="¿Qué actividad pretende realizar con la especie nativa?"
                                />
                            </div>

                            <p class="bg-indigo-100 mt-10 p-4 text-indigo-600">
                                Seleccione una opción
                            </p>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio
                                        bind:group={$form.actividades_muestreo}
                                        value="1.1.1"
                                    />
                                    <span slot="label">
                                        Separación de las unidades funcionales y
                                        no funcionales del ADN y el ARN, en
                                        todas las formas que se encuentran en la
                                        naturaleza.
                                    </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio
                                        bind:group={$form.actividades_muestreo}
                                        value="1.1.2"
                                    />
                                    <span slot="label">
                                        Aislamiento de una o varias moléculas,
                                        entendidas estas como micro y
                                        macromoléculas, producidas por el
                                        metabolismo de un organismo.
                                    </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio
                                        bind:group={$form.actividades_muestreo}
                                        value="1.1.3"
                                    />
                                    <span slot="label">
                                        Solicitar patente sobre una función o
                                        propiedad identificada de una molécula,
                                        que se ha aislado y purificado.
                                    </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio
                                        bind:group={$form.actividades_muestreo}
                                        value="1.1.4"
                                    />
                                    <span slot="label">
                                        No logro identificar la actividad a
                                        desarrollar con la especie nativa
                                    </span>
                                </FormField>
                            </div>
                        </div>

                        <div class="bg-gray-300 flex-1 p-8">
                            <div class="flex items-center">
                                <Label
                                    required
                                    class="mb-4"
                                    id="1.2"
                                    value="¿Cuál es la finalidad de las actividades a realizar con la especie nativa?"
                                />
                            </div>

                            <p class="bg-indigo-100 mt-10 p-4 text-indigo-600">
                                Seleccione una opción
                            </p>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio
                                        bind:group={$form.muestreo_objective}
                                        value="1.2.1"
                                    />
                                    <span slot="label">
                                        Investigación básica sin fines
                                        comerciales
                                    </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio
                                        bind:group={$form.muestreo_objective}
                                        value="1.2.2"
                                    />
                                    <span slot="label">
                                        Bioprospección en cualquiera de sus
                                        fases
                                    </span>
                                </FormField>
                            </div>
                            <div class="flex mt-4 items-center">
                                <FormField>
                                    <Radio
                                        bind:group={$form.muestreo_objective}
                                        value="1.2.3"
                                    />
                                    <span slot="label">
                                        Comercial o Industrial
                                    </span>
                                </FormField>
                            </div>
                        </div>
                    </div>
                {/if}

                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="2" />
                        <span slot="label">
                            Especies Introducidas. (son aquellas que no son
                            nativas de Colombia y que ingresaron al país por
                            intervención humana)
                        </span>
                    </FormField>
                </div>
                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="3" />
                        <span slot="label">
                            Recursos genéticos humanos y sus productos derivados
                        </span>
                    </FormField>
                </div>
                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="4" />
                        <span slot="label">
                            Intercambio de recursos genéticos y sus productos
                            derivados, recursos biológicos que los contienen o
                            los componentes asociados a estos. (son aquellas que
                            realizan las comunidades indígenas, afroamericanas y
                            locales de los Países Miembros de la Comunidad
                            Andina entre sí y para su propio consumo, basadas en
                            sus prácticas consuetudinarias)
                        </span>
                    </FormField>
                </div>
                <div class="flex mt-4 items-center">
                    <FormField>
                        <Radio bind:group={$form.muestreo} value="5" />
                        <span slot="label">
                            Recurso biológico que involucren actividades de
                            sistemática molecular, ecología molecular, evolución
                            y biogeografía molecular (siempre que el recurso
                            biológico se haya colectado en el marco de un
                            permiso de recolección de especímenes de especies
                            silvestres de la diversidad biológica con fines de
                            investigación científica no comercial o provenga de
                            una colección registrada ante el Instituto Alexander
                            van Humboldt)
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
                    <Label
                        required
                        class="mb-4"
                        labelFor="relacionado_plan_tecnologico"
                        value="¿El proyecto se alinea con el plan tecnológico desarrollado por el centro de formación?"
                    />
                </div>
                <div>
                    <Select
                        items={rdiDropdownOptions}
                        id="relacionado_plan_tecnologico"
                        bind:selectedValue={$form.relacionado_plan_tecnologico}
                        error={errors.relacionado_plan_tecnologico}
                        autocomplete="off"
                        placeholder="Seleccione una opción"
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="relacionado_agendas_competitividad"
                        value="¿El proyecto se alinea con las Agendas Departamentales de Competitividad e Innovación?"
                    />
                </div>
                <div>
                    <Select
                        items={rdiDropdownOptions}
                        id="relacionado_agendas_competitividad"
                        bind:selectedValue={$form.relacionado_agendas_competitividad}
                        error={errors.relacionado_agendas_competitividad}
                        autocomplete="off"
                        placeholder="Seleccione una opción"
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="relacionado_mesas_sectoriales"
                        value="¿El proyecto se alinea con las Mesas Sectoriales?"
                    />
                </div>
                <div>
                    <Select
                        items={rdiDropdownOptions}
                        id="relacionado_mesas_sectoriales"
                        bind:selectedValue={$form.relacionado_mesas_sectoriales}
                        error={errors.relacionado_mesas_sectoriales}
                        autocomplete="off"
                        placeholder="Seleccione una opción"
                        required
                    />
                </div>
            </div>
            {#if $form.relacionado_mesas_sectoriales?.value == 1}
                <div class="bg-indigo-100 p-5 mt-10">
                    <InputError message={errors.sector_based_committee_id} />
                    <div class="grid grid-cols-2">
                        <div>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                class="w-5"
                                style="transform: translateX(-50px);"
                                ><path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                /></svg
                            >
                            <p class="text-indigo-600">
                                Por favor seleccione la o las mesas sectoriales
                                con la cual o las cuales se alinea el proyecto
                            </p>
                        </div>
                        <div
                            class="bg-white grid grid-cols-2 max-w-xl overflow-y-scroll shadow-2xl mt-4 h-80"
                        >
                            {#each sectorBasedCommittees as { id, name }, i}
                                <FormField>
                                    <Checkbox
                                        bind:group={$form.sector_based_committee_id}
                                        value={id}
                                    />
                                    <span slot="label">{name}</span>
                                </FormField>
                            {/each}
                        </div>
                    </div>
                </div>
            {/if}

            <div class="mt-40 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="relacionado_tecnoacademia"
                        value="¿El proyecto se formuló en conjunto con la tecnoacademia?"
                    />
                </div>
                <div>
                    <Select
                        items={rdiDropdownOptions}
                        id="relacionado_tecnoacademia"
                        bind:selectedValue={$form.relacionado_tecnoacademia}
                        error={errors.relacionado_tecnoacademia}
                        autocomplete="off"
                        placeholder="Seleccione una opción"
                        required
                    />
                </div>
            </div>

            {#if $form.relacionado_tecnoacademia?.value == 1}
                <div class="bg-indigo-100 p-5 mt-10">
                    <InputError message={errors.technological_line_id} />
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        class="w-5"
                        style="transform: translateX(-50px);"
                        ><path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        /></svg
                    >
                    <div class="grid grid-cols-2">
                        <div>
                            <p class="text-indigo-600">
                                Por favor seleccione la Tecnoacademia con la
                                cual articuló el proyecto
                            </p>
                        </div>
                        <div>
                            <Select
                                items={technoAcademies}
                                id="techno_academy_id"
                                bind:selectedValue={$form.techno_academy_id}
                                error={errors.techno_academy_id}
                                autocomplete="off"
                                placeholder="Seleccione una opción"
                                required
                            />
                            {#if technologicalLines.length > 0}
                                <div
                                    class="bg-white grid grid-cols-2 max-w-xl overflow-y-scroll shadow-2xl mt-4 h-80"
                                >
                                    {#each technologicalLines as { id, name }, i}
                                        <Label
                                            class="p-3 border-t border-b flex items-center text-sm"
                                            {id}
                                            value={name}
                                        />

                                        <div
                                            class="border-b border-t flex items-center justify-center"
                                        >
                                            <input
                                                type="checkbox"
                                                bind:group={$form.technological_line_id}
                                                {id}
                                                value={id}
                                                class="rounded text-indigo-500"
                                            />
                                        </div>
                                    {/each}
                                </div>
                            {/if}
                        </div>
                    </div>
                </div>
            {/if}

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="abstract"
                        value="Resumen del proyecto"
                    />
                    <InfoMessage
                        message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto."
                    />
                </div>
                <div>
                    <Textarea
                        rows="4"
                        id="abstract"
                        error={errors.abstract}
                        bind:value={$form.abstract}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="antecedentes"
                        value="Antecedentes"
                    />
                    <InfoMessage
                        message="Presenta las investigaciones, innovaciones o desarrollos tecnológicos que se han realizado a nivel internacional, nacional, departamental o municipal en el marco de la temática de la propuesta del proyecto; que muestran la pertinencia del proyecto, citar toda la información consignada utilizando normas APA sexta edición."
                    />
                </div>
                <div>
                    <Textarea
                        rows="4"
                        id="antecedentes"
                        error={errors.antecedentes}
                        bind:value={$form.antecedentes}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="marco_conceptual"
                        value="Marco conceptual"
                    />
                    <InfoMessage
                        message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones."
                    />
                </div>
                <div>
                    <Textarea
                        rows="4"
                        id="marco_conceptual"
                        error={errors.marco_conceptual}
                        bind:value={$form.marco_conceptual}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="metodologia"
                        value="Metodología"
                    />
                    <InfoMessage
                        message="Describir la (s) metodología (s) a utilizar en el desarrollo del proyecto."
                    />
                </div>
                <div>
                    <Textarea
                        rows="4"
                        id="metodologia"
                        error={errors.metodologia}
                        bind:value={$form.metodologia}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="propuesta_sostenibilidad"
                        value="Propuesta de sostenibilidad"
                    />
                    <InfoMessage
                        message="Identificar los efectos que tiene el desarrollo del proyecto de investigación ya sea positivos o negativos. Se recomienda establecer las acciones pertinentes para mitigar los impactos negativos ambientales identificados y anexar el respectivo permiso ambiental cuando aplique. Tener en cuenta si aplica el decreto 1376 de 2013."
                    />
                </div>
                <div>
                    <Textarea
                        rows="4"
                        id="propuesta_sostenibilidad"
                        error={errors.propuesta_sostenibilidad}
                        bind:value={$form.propuesta_sostenibilidad}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="bibliografia"
                        value="Bibliografía"
                    />
                    <InfoMessage
                        message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Sexta edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)."
                    />
                </div>
                <div>
                    <Textarea
                        rows="4"
                        id="bibliografia"
                        error={errors.bibliografia}
                        bind:value={$form.bibliografia}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="numero_aprendices"
                        value="Número de los aprendices que se beneficiarán en la ejecución del proyecto"
                    />
                </div>
                <div>
                    <Input
                        id="numero_aprendices"
                        type="number"
                        min="0"
                        max="9999"
                        class="mt-1 block w-full"
                        error={errors.numero_aprendices}
                        placeholder="Escriba el número de aprendices que se beneficiarán en la ejecución del proyecto"
                        bind:value={$form.numero_aprendices}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        for="cities"
                        value="Nombre de los municipios beneficiados"
                    />
                </div>
                <div>
                    <SelectMulti
                        id="cities"
                        bind:selectedValue={$form.cities}
                        items={cities}
                        isMulti={true}
                        error={errors.cities}
                        placeholder="Buscar municipios"
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="impacto_ciudades"
                        value="Descripción del beneficio en los municipios"
                    />
                </div>
                <div>
                    <Textarea
                        rows="4"
                        id="impacto_ciudades"
                        error={errors.impacto_ciudades}
                        bind:value={$form.impacto_ciudades}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="impacto_centro_formacion"
                        value="Impacto en el centro de formación"
                    />
                </div>
                <div>
                    <Textarea
                        rows="4"
                        id="impacto_centro_formacion"
                        error={errors.impacto_centro_formacion}
                        bind:value={$form.impacto_centro_formacion}
                        required
                    />
                </div>
            </div>
        </fieldset>
        <div
            class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
        >
            {#if canDestroyRDI || isSuperAdmin}
                <button
                    class="text-red-600 hover:underline text-left"
                    tabindex="-1"
                    type="button"
                    on:click={(event) => (dialog_open = true)}
                >
                    Eliminar
                </button>
            {/if}
            {#if canEditRDI || isSuperAdmin}
                <LoadingButton
                    loading={sending}
                    class="btn-indigo ml-auto"
                    type="submit"
                >
                    {$_('Save')}
                </LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={dialog_open}>
        <div slot="title" class="flex items-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 mr-2 text-red-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro (a) que desea eliminar este proyecto?
                <br />
                Una vez eliminado el proyecto, todos sus recursos y datos se eliminarán
                de forma permanente.
            </p>

            <form
                on:submit|preventDefault={destroy}
                id="delete-rdi"
                class="mt-20 mb-28"
            >
                <Label
                    for="password"
                    value="Ingrese su contraseña para confirmar que desea eliminar permanentemente este proyecto."
                />
                <Input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    error={errors.password}
                    placeholder="Escriba su contraseña"
                    bind:value={$deleteForm.password}
                    required
                />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button
                    on:click={(event) => (dialog_open = false)}
                    variant={null}>Cancelar</Button
                >
                <Button variant="raised" form="delete-rdi">Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
