<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'
    import { onMount } from 'svelte'

    import Button from '@/Shared/Button'
    import Input from '@/Shared/Input'
    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Stepper from '@/Shared/Stepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import Password from '@/Shared/Password'
    import SelectMulti from '@/Shared/SelectMulti'
    import Dialog from '@/Shared/Dialog'
    import Tags from '@/Shared/Tags'
    import Select from '@/Shared/Select'
    import { Inertia } from '@inertiajs/inertia'

    export let errors
    export let convocatoria
    export let ta
    export let regionales
    export let lineasTecnoacademiaRelacionadas
    export let tecnoacademiaRelacionada
    export let proyectoMunicipios
    export let proyectoMunicipiosImpactar
    export let proyectoProgramasFormacionArticulados
    export let proyectoDisCurriculares
    export let disCurriculares
    export let programasFormacion
    export let tecnoAcademias
    export let modalidades
    export let nivelesFormacion

    $: $title = ta ? ta.titulo : null

    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]
    let sending = false
    let dialogOpen = errors.password != undefined ? true : false
    let proyectoDialogOpen = true

    let municipios = []
    let codigoLineaProgramatica

    $: if (codigoLineaProgramatica) {
        $form.codigo_linea_programatica = codigoLineaProgramatica.codigo
    } else {
        $form.codigo_linea_programatica = ta.codigo_linea_programatica
    }

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let resumenForm = useForm({
        resumen: ta.resumen,
    })
    let formResumenRegional = useForm({
        resumen_regional: ta.resumen_regional,
    })
    let formAntecedentes = useForm({
        antecedentes: ta.antecedentes,
    })
    let formAntecedentesTA = useForm({
        antecedentes_tecnoacademia: ta.antecedentes_tecnoacademia,
    })
    let formJustificacionProblema = useForm({
        justificacion_problema: ta.justificacion_problema,
    })
    let formMarcoConceptual = useForm({
        marco_conceptual: ta.marco_conceptual,
    })
    let formBibliografia = useForm({
        bibliografia: ta.bibliografia,
    })
    let formImpactoMunicipios = useForm({
        impacto_municipios: ta.impacto_municipios,
    })
    let formArticulacionCentroFormacion = useForm({
        articulacion_centro_formacion: ta.articulacion_centro_formacion,
    })
    let formPertinenciaTerritorio = useForm({
        pertinencia_territorio: ta.pertinencia_territorio,
    })
    let formRetosOportunidades = useForm({
        retos_oportunidades: ta.retos_oportunidades,
    })
    let formProyectosMacro = useForm({
        proyectos_macro: ta.proyectos_macro,
    })
    let formLineasMedularesCentro = useForm({
        lineas_medulares_centro: ta.lineas_medulares_centro,
    })
    let formLineasTecnologicasCentro = useForm({
        lineas_tecnologicas_centro: ta.lineas_tecnologicas_centro,
    })

    let form = useForm({
        centro_formacion_id: ta.proyecto.centro_formacion_id,
        linea_programatica_id: ta.proyecto.linea_programatica_id,
        fecha_inicio: ta.fecha_inicio,
        fecha_finalizacion: ta.fecha_finalizacion,
        max_meses_ejecucion: ta.max_meses_ejecucion,
        municipios: proyectoMunicipios.length > 0 ? proyectoMunicipios : null,
        municipios_impactar: proyectoMunicipiosImpactar.length > 0 ? proyectoMunicipiosImpactar : null,
        nombre_instituciones: ta.nombre_instituciones,
        nombre_instituciones_programas: ta.nombre_instituciones_programas,
        nuevas_instituciones: ta.nuevas_instituciones,
        proyeccion_nuevas_instituciones: {
            value: ta.proyeccion_nuevas_instituciones,
            label: opcionesSiNo.find((item) => item.value == ta.proyeccion_nuevas_instituciones)?.label,
        },
        proyeccion_articulacion_media: {
            value: ta.proyeccion_articulacion_media,
            label: opcionesSiNo.find((item) => item.value == ta.proyeccion_articulacion_media)?.label,
        },
        tecnoacademia_id: {
            value: tecnoacademiaRelacionada,
            label: tecnoAcademias.find((item) => item.value == tecnoacademiaRelacionada)?.label,
        },
        tecnoacademia_linea_tecnoacademia_id: lineasTecnoacademiaRelacionadas,
        codigo_linea_programatica: null,
        programas_formacion_articulados: proyectoProgramasFormacionArticulados.length > 0 ? proyectoProgramasFormacionArticulados : null,
        dis_curricular_id: proyectoDisCurriculares.length > 0 ? proyectoDisCurriculares : null,

        otras_nuevas_instituciones: ta.otras_nuevas_instituciones,
        otras_nombre_instituciones_programas: ta.otras_nombre_instituciones_programas,
        otras_nombre_instituciones: ta.otras_nombre_instituciones,
    })

    let regionalIEArticulacion
    $: whitelistInstitucionesEducativasArticular = []
    $: if (regionalIEArticulacion) {
        axios
            .get('https://www.datos.gov.co/resource/cfw5-qzt5.json?cod_dane_departamento=' + regionalIEArticulacion?.codigo)
            .then(function (response) {
                // handle success
                response.data.map((item) => {
                    whitelistInstitucionesEducativasArticular.push(item.nombre_establecimiento)
                })
            })
            .catch(function (error) {
                // handle error
                console.log(error)
            })
            .then(function () {
                // always executed
            })
    }

    let regionalIEEjecucion
    $: whitelistInstitucionesEducativasEjecutar = []
    $: if (regionalIEEjecucion) {
        axios
            .get('https://www.datos.gov.co/resource/cfw5-qzt5.json?cod_dane_departamento=' + regionalIEEjecucion?.codigo)
            .then(function (response) {
                // handle success
                response.data.map((item) => {
                    whitelistInstitucionesEducativasEjecutar.push(item.nombre_establecimiento)
                })
            })
            .catch(function (error) {
                // handle error
                console.log(error)
            })
            .then(function () {
                // always executed
            })
    }

    onMount(() => {
        getMunicipios()
        getLineasTecnoacademia()
    })

    async function syncColumnLong(column, form) {
        return new Promise(resolve => {
            if (isSuperAdmin || (checkPermission(authUser, [9, 10]) && ta.proyecto.modificable == true)) {
                //guardar
                Inertia.put(route('convocatorias.ta.updateLongColumn', [convocatoria.id, ta.id, column]), {[column]:form[column]}, {
                    onStart: () => (sending = true),
                    onError: resp => ((sending = false), (resolve(resp))),
                    onFinish: () => ((sending = false), resolve({})),
                    preserveScroll: true,
                })
            }else{
                resolve({});
            }
        })
    }

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [9, 10]) && ta.proyecto.modificable == true)) {
            $form.put(route('convocatorias.ta.update', [convocatoria.id, ta.id]), {
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
        if (isSuperAdmin || (checkPermission(authUser, [10]) && ta.proyecto.modificable == true)) {
            $deleteForm.delete(route('convocatorias.ta.destroy', [convocatoria.id, ta.id]), {
                preserveScroll: true,
            })
        }
    }

    async function getMunicipios() {
        let res = await axios.get(route('web-api.municipios'))
        if (res.status == '200') {
            municipios = res.data
        }
    }

    $: if ($form.fecha_inicio && $form.fecha_finalizacion) {
        $form.max_meses_ejecucion = monthDiff($form.fecha_inicio, $form.fecha_finalizacion)
    }

    let lineasTecnoaAcademia
    async function getLineasTecnoacademia() {
        let res = await axios.get(route('web-api.tecnoacademias.lineas-tecnoacademia', [tecnoacademiaRelacionada]))
        if (res.status == '200') {
            lineasTecnoaAcademia = res.data
        }
    }

    let programasFormacionDialogOpen = false
    let formProgramasFormacion = useForm({
        nombre: '',
        codigo: '',
        modalidad: '',
        nivel_formacion: '',
        registro_calificado: true,
        centro_formacion_id: ta.proyecto.centro_formacion_id,
    })
    function submitProgramasFormacion() {
        if (isSuperAdmin || (checkPermission(authUser, [9, 10]) && ta.proyecto.modificable == true)) {
            $formProgramasFormacion.post(route('convocatorias.proyectos.programas-formacion.store', [convocatoria.id, ta.id]), {
                onStart: () => (sending = true),
                onFinish: () => ((sending = false), ((programasFormacionDialogOpen = false), $formProgramasFormacion.reset())),
                preserveScroll: true,
            })
        }
    }

    let disCurricularDialogOpen = false
    let formDisCurricular = useForm({
        nombre: '',
    })
    function submitDisCurricular() {
        if (isSuperAdmin || (checkPermission(authUser, [9, 10]) && ta.proyecto.modificable == true)) {
            $formDisCurricular.post(route('convocatorias.proyectos.dis-curriculares.store', [convocatoria.id, ta.id]), {
                onStart: () => (sending = true),
                onFinish: () => ((sending = false), (disCurricularDialogOpen = false)),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} proyecto={ta} />

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [9, 10]) && ta.proyecto.modificable == true) ? undefined : true}>
            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <InfoMessage message={convocatoria.fecha_maxima_ta} class="my-5" />

                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_ta} max={convocatoria.max_fecha_finalizacion_proyectos_ta} error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_ta} max={convocatoria.max_fecha_finalizacion_proyectos_ta} error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
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

                {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                    {#each ta.proyecto.evaluaciones as evaluacion, i}
                        {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                            <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                <div class="flex text-orangered-900 font-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Recomendación del evaluador COD-{evaluacion.id}:
                                </div>
                                <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.fecha_ejecucion_comentario ? evaluacion.ta_evaluacion.fecha_ejecucion_comentario : 'Sin recomendación'}</p>
                            </div>
                        {/if}
                    {/each}
                {/if}
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="linea_programatica_id" value="Código dependencia presupuestal (SIIF)" />
                    </div>
                    <div>
                        <DynamicList id="linea_programatica_id" bind:value={$form.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas', 1)} classes="min-h" placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} required />
                    </div>
                </div>
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                        <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                    </div>
                    <div>
                        {ta.proyecto.centro_formacion.nombre}
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tecnoacademia_id" value="TecnoAcademia" />
                    </div>
                    <div>
                        <Select id="tecnoacademia_id" items={tecnoAcademias} bind:selectedValue={$form.tecnoacademia_id} error={errors.tecnoacademia_id} autocomplete="off" placeholder="Busque por el nombre de la TecnoAcademia" required />
                    </div>
                </div>
            </fieldset>
            {#if $form.tecnoacademia_id && lineasTecnoaAcademia}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tecnoacademia_linea_tecnoacademia_id" value="Líneas temáticas a ejecutar en la vigencia del proyecto:" />
                    </div>
                    <div>
                        <SelectMulti id="tecnoacademia_linea_tecnoacademia_id" bind:selectedValue={$form.tecnoacademia_linea_tecnoacademia_id} items={lineasTecnoaAcademia} isMulti={true} error={errors.tecnoacademia_linea_tecnoacademia_id} placeholder="Buscar por el nombre de la línea" required />
                        {#if lineasTecnoaAcademia?.length == 0}
                            <div>
                                <p>Parece que no se han encontrado elementos, por favor haga clic en <strong>Refrescar</strong></p>
                                <button on:click={getLineasTecnoacademia} type="button" class="flex underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Refrescar
                                </button>
                            </div>
                        {/if}
                    </div>
                </div>
            {/if}

            <fieldset disabled>
                <div class="mt-40 grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                        <InfoMessage message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad del proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto." />
                    </div>
                    <div>
                        <Textarea label="Resumen del proyecto" maxlength="40000" id="resumen" error={errors.resumen} bind:value={$resumenForm.resumen} change={syncColumnLong('resumen', $resumenForm)} required />
                    </div>
                </div>
            </fieldset>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen_regional" value="Complemento - Resumen ejecutivo regional" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="resumen_regional" error={errors.resumen_regional} bind:value={$formResumenRegional.resumen_regional} change={syncColumnLong('resumen_regional', $formResumenRegional)} required />

                    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                        {#each ta.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.resumen_regional_comentario ? evaluacion.ta_evaluacion.resumen_regional_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                        <InfoMessage
                            message="Presenta las investigaciones, innovaciones o desarrollos tecnológicos que se han realizado a nivel internacional, nacional, departamental o municipal en el marco de la temática de la propuesta del proyecto; que muestran la pertinencia del proyecto, citar toda la información consignada utilizando normas APA última edición. De igual forma, relacionar los proyectos ejecutados en vigencias anteriores (incluir códigos SGPS), si el proyecto corresponde a la continuidad de proyectos SENNOVA."
                        />
                    </div>
                    <div>
                        <Textarea label="Antecedentes" maxlength="40000" id="antecedentes" error={errors.antecedentes} bind:value={$formAntecedentes.antecedentes} change={syncColumnLong('antecedentes', $formAntecedentes)} required />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes_tecnoacademia" value="Antecedentes de la Tecnoacademia y su impacto en la región" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="antecedentes_tecnoacademia" error={errors.antecedentes_tecnoacademia} bind:value={$formAntecedentesTA.antecedentes_tecnoacademia} change={syncColumnLong('antecedentes_tecnoacademia', $formAntecedentesTA)} required />
                    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                        {#each ta.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.antecedentes_tecnoacademia_comentario ? evaluacion.ta_evaluacion.antecedentes_tecnoacademia_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="justificacion_problema" value="Justificación" />
                    </div>
                    <div>
                        <Textarea label="Justificación" maxlength="40000" id="justificacion_problema" error={errors.justificacion_problema} bind:value={$formJustificacionProblema.justificacion_problema} change={syncColumnLong('justificacion_problema', $formJustificacionProblema)} required />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="retos_oportunidades" value="Descripción de retos y prioridades locales y regionales en los cuales la Tecnoacademia tiene impacto" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="retos_oportunidades" error={errors.retos_oportunidades} bind:value={$formRetosOportunidades.retos_oportunidades} change={syncColumnLong('retos_oportunidades', $formRetosOportunidades)} required />

                    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                        {#each ta.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.retos_oportunidades_comentario ? evaluacion.ta_evaluacion.retos_oportunidades_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="pertinencia_territorio" value="Justificación y pertinencia en el territorio" />
                </div>
                <div>
                    <Textarea label="Justificación y pertinencia en el territorio" maxlength="40000" id="pertinencia_territorio" error={errors.pertinencia_territorio} bind:value={$formPertinenciaTerritorio.pertinencia_territorio} change={syncColumnLong('pertinencia_territorio', $formPertinenciaTerritorio)} required />
                </div>
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="marco_conceptual" value="Marco conceptual" />
                        <InfoMessage message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones." />
                    </div>
                    <div>
                        <Textarea label="Marco conceptual" maxlength="40000" id="marco_conceptual" error={errors.marco_conceptual} bind:value={$formMarcoConceptual.marco_conceptual} change={syncColumnLong('marco_conceptual', $formMarcoConceptual)} required />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="municipios" value="Nombre los municipios impactados en la vigencia anterior por la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti id="municipios" bind:selectedValue={$form.municipios} items={municipios} isMulti={true} error={errors.municipios} placeholder="Buscar municipios" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="municipios_impactar" value="Defina los municipios a impactar en la vigencia el proyecto:" />
                </div>
                <div>
                    <SelectMulti id="municipios_impactar" bind:selectedValue={$form.municipios_impactar} items={municipios} isMulti={true} error={errors.municipios_impactar} placeholder="Buscar municipios" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="impacto_municipios" value="Descripción del beneficio o impacto generado por la TecnoAcademia en los municipios" />
                </div>
                <div>
                    <Textarea label="Descripción del beneficio o impacto generado por la TecnoAcademia en los municipios" maxlength="40000" id="impacto_municipios" error={errors.impacto_municipios} bind:value={$formImpactoMunicipios.impacto_municipios} change={syncColumnLong('impacto_municipios', $formImpactoMunicipios)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div />
                <div>
                    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                        {#each ta.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.municipios_comentario ? evaluacion.ta_evaluacion.municipios_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="nombre_instituciones_programas" value="Instituciones donde se están ejecutando los programas y que se espera continuar con el proyecto de TecnoAcademias" />
                </div>
                <div>
                    <Select id="departamento_instituciones_programas" bind:selectedValue={regionalIEEjecucion} items={regionales} placeholder="Seleccione un departamento" />

                    <Tags id="nombre_instituciones_programas" class="mt-4" whitelist={whitelistInstitucionesEducativasEjecutar} bind:tags={$form.nombre_instituciones_programas} placeholder="Nombre(s) de la(s) IE" error={errors.nombre_instituciones_programas} required />
                    <div class="mt-10">
                        <InfoMessage>Si no encuentra alguna institución educativa en la anterior lista por favor escriba el nombre en el siguiente campo de texto</InfoMessage>
                        <Textarea label="Instituciones" maxlength="40000" id="otras_nombre_instituciones_programas" error={errors.otras_nombre_instituciones_programas} bind:value={$form.otras_nombre_instituciones_programas} />
                    </div>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="proyeccion_nuevas_instituciones" value="¿Se proyecta incluir nuevas Instituciones Educativas en la nueva vigencia??" />
                </div>
                <div>
                    <Select items={opcionesSiNo} id="proyeccion_nuevas_instituciones" bind:selectedValue={$form.proyeccion_nuevas_instituciones} error={errors.proyeccion_nuevas_instituciones} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            {#if $form.proyeccion_nuevas_instituciones?.value == 1}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="nuevas_instituciones" value="Nuevas instituciones educativas que se vincularán con el proyecto de TecnoAcademia" />
                    </div>
                    <div>
                        <Select id="departamento_nuevas_instituciones" bind:selectedValue={regionalIEEjecucion} items={regionales} placeholder="Seleccione un departamento" />

                        <Tags id="nuevas_instituciones" class="mt-4" whitelist={whitelistInstitucionesEducativasEjecutar} bind:tags={$form.nuevas_instituciones} placeholder="Nombre(s) de la(s) IE" error={errors.nuevas_instituciones} required />
                        <div class="mt-10">
                            <InfoMessage>Si no encuentra alguna institución educativa en la anterior lista por favor escriba el nombre en el siguiente campo de texto</InfoMessage>
                            <Textarea label="Instituciones" maxlength="40000" id="otras_nuevas_instituciones" error={errors.otras_nuevas_instituciones} bind:value={$form.otras_nuevas_instituciones} />
                        </div>
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="proyeccion_articulacion_media" value="¿Se proyecta incluir Instituciones Educativas en articulación con la media??" />
                </div>
                <div>
                    <Select items={opcionesSiNo} id="proyeccion_articulacion_media" bind:selectedValue={$form.proyeccion_articulacion_media} error={errors.proyeccion_articulacion_media} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            {#if $form.proyeccion_articulacion_media?.value == 1}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="nombre_instituciones" value="Instituciones donde se implementará el programa que tienen <strong>articulación con la Media</strong>" />
                    </div>
                    <div>
                        <Select id="departamento_instituciones_media" bind:selectedValue={regionalIEArticulacion} items={regionales} placeholder="Seleccione un departamento" />

                        <Tags id="nombre_instituciones" class="mt-4" whitelist={whitelistInstitucionesEducativasArticular} bind:tags={$form.nombre_instituciones} placeholder="Nombre(s) de la(s) IE" error={errors.nombre_instituciones} required />
                        <div class="mt-10">
                            <InfoMessage>Si no encuentra alguna institución educativa en la anterior lista por favor escriba el nombre en el siguiente campo de texto</InfoMessage>
                            <Textarea label="Instituciones" maxlength="40000" id="otras_nombre_instituciones" error={errors.otras_nombre_instituciones} bind:value={$form.otras_nombre_instituciones} />
                        </div>
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-1">
                <div />
                <div>
                    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                        {#each ta.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.instituciones_comentario ? evaluacion.ta_evaluacion.instituciones_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="programas_formacion_articulados" value="Programas de articulación con la Media con los cuales se espera dar continuidad a la ruta de formación de los aprendices de la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti id="programas_formacion_articulados" bind:selectedValue={$form.programas_formacion_articulados} items={programasFormacion} isMulti={true} error={errors.programas_formacion_articulados} placeholder="Buscar por el nombre del programa de formación" required />
                    <InfoMessage>
                        Si no encuentra un programa por favor de clic en <strong>Añadir programa de formación</strong>. A continuación, se mostrará un campo de texto para que diligencie el nombre del programa y posterior de clic en <strong>Crear programa de formación</strong>.
                        <br />
                        Por último busque nuevamente en la lista y seleccione el programa recién creado.
                        <br />
                        <Button on:click={(event) => (programasFormacionDialogOpen = true)} variant="raised" type="button">Añadir programa de formación</Button>
                    </InfoMessage>
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="articulacion_centro_formacion" value="Articulación con el centro de formación" />
                </div>
                <div>
                    <Textarea label="Articulación con el centro de formación" maxlength="40000" id="articulacion_centro_formacion" error={errors.articulacion_centro_formacion} bind:value={$formArticulacionCentroFormacion.articulacion_centro_formacion} change={syncColumnLong('articulacion_centro_formacion', $formArticulacionCentroFormacion)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="dis_curricular_id" value="Programas a ejecutar en la vigencia del proyecto:" />
                </div>
                <div>
                    <SelectMulti id="dis_curricular_id" bind:selectedValue={$form.dis_curricular_id} items={disCurriculares} isMulti={true} error={errors.dis_curricular_id} placeholder="Buscar por el nombre del programa de formación" required />

                    <InfoMessage>
                        Si no encuentra un programa por favor de clic en <strong>Añadir programa</strong>. A continuación, se mostrará un campo de texto para que diligencie el nombre del programa y posterior de clic en <strong>Crear programa</strong>.
                        <br />
                        Por último busque nuevamente en la lista y seleccione el programa recién creado.
                        <br />
                        <Button on:click={(event) => (disCurricularDialogOpen = true)} variant="raised" type="button">Añadir programa</Button>
                    </InfoMessage>
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="proyectos_macro" value="Proyectos Macro o líneas de proyecto de investigación formativa y aplicada de la TecnoAcademia para la vigencia 2022" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="proyectos_macro" error={errors.proyectos_macro} bind:value={$formProyectosMacro.proyectos_macro} change={syncColumnLong('proyectos_macro', $formProyectosMacro)} required />

                    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                        {#each ta.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.proyectos_macro_comentario ? evaluacion.ta_evaluacion.proyectos_macro_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="lineas_medulares_centro" value="Líneas medulares del Centro con las que se articula la TecnoAcademia" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="lineas_medulares_centro" error={errors.lineas_medulares_centro} bind:value={$formLineasMedularesCentro.lineas_medulares_centro} change={syncColumnLong('lineas_medulares_centro', $formLineasMedularesCentro)} required />

                    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                        {#each ta.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.lineas_medulares_centro_comentario ? evaluacion.ta_evaluacion.lineas_medulares_centro_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="lineas_tecnologicas_centro" value="Líneas tecnológicas del Centro con las que se articula la TecnoAcademia" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="lineas_tecnologicas_centro" error={errors.lineas_tecnologicas_centro} bind:value={$formLineasTecnologicasCentro.lineas_tecnologicas_centro} change={syncColumnLong('lineas_tecnologicas_centro', $formLineasTecnologicasCentro)} required />

                    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                        {#each ta.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.lineas_tecnologicas_centro_comentario ? evaluacion.ta_evaluacion.lineas_tecnologicas_centro_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Última edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="bibliografia" error={errors.bibliografia} bind:value={$formBibliografia.bibliografia} change={syncColumnLong('bibliografia', $formBibliografia)} required />

                    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                        {#each ta.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.bibliografia ? evaluacion.ta_evaluacion.bibliografia : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                <hr class="mt-10 mb-10" />
                <h1>Ortografía</h1>
                {#each ta.proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.ortografia_comentario ? evaluacion.ta_evaluacion.ortografia_comentario : 'Sin recomendación'}</p>
                        </div>
                    {/if}
                {/each}
            {/if}

            {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                <hr class="mt-10 mb-10" />
                <h1>Redacción</h1>
                {#each ta.proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.redaccion_comentario ? evaluacion.ta_evaluacion.redaccion_comentario : 'Sin recomendación'}</p>
                        </div>
                    {/if}
                {/each}
            {/if}

            {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                <hr class="mt-10 mb-10" />
                <h1>Normas APA</h1>
                {#each ta.proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            <p class="whitespace-pre-line">{evaluacion.ta_evaluacion.normas_apa_comentario ? evaluacion.ta_evaluacion.normas_apa_comentario : 'Sin recomendación'}</p>
                        </div>
                    {/if}
                {/each}
            {/if}
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center justify-between sticky bottom-0">
            {#if isSuperAdmin || (checkPermission(authUser, [10]) && ta.proyecto.modificable == true)}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin || (checkPermission(authUser, [9, 10]) && ta.proyecto.modificable == true)}
                <small>{ta.updated_at}</small>

                <LoadingButton loading={sending} class="btn-indigo" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={programasFormacionDialogOpen} id="programas-formacion">
        <div slot="content">
            <form on:submit|preventDefault={submitProgramasFormacion} id="programas-formacion-form">
                <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [9, 10]) && ta.proyecto.modificable == true) ? undefined : true}>
                    <div class="mt-4">
                        <Input label="Nombre" id="nombre" type="text" class="mt-1" bind:value={$formProgramasFormacion.nombre} error={errors.nombre} required />
                    </div>

                    <div class="mt-4">
                        <Input label="Código" id="codigo" type="number" input$min="0" input$max="2147483647" class="mt-1" bind:value={$formProgramasFormacion.codigo} error={errors.codigo} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="modalidad" value="Modalidad de estudio" />
                        <Select id="modalidad" items={modalidades} bind:selectedValue={$formProgramasFormacion.modalidad} error={errors.modalidad} autocomplete="off" placeholder="Seleccione una modalidad de estudio" required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="nivel_formacion" value="Nivel de formación" />
                        <Select id="nivel_formacion" items={nivelesFormacion} bind:selectedValue={$formProgramasFormacion.nivel_formacion} error={errors.nivel_formacion} autocomplete="off" placeholder="Seleccione un nivel de formación" required />
                    </div>
                </fieldset>
            </form>
        </div>

        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (programasFormacionDialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="programas-formacion-form">Crear de formación</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={disCurricularDialogOpen} id="dis-curricular">
        <div slot="content">
            <form on:submit|preventDefault={submitDisCurricular} id="dis-curricular-form">
                <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [9, 10]) && ta.proyecto.modificable == true) ? undefined : true}>
                    <div>
                        <Label required class="mb-4" labelFor="nombre" value="Nombre del programa" />
                        <Textarea label="" maxlength="40000" id="nombre" error={errors.nombre} bind:value={$formDisCurricular.nombre} required />
                    </div>
                </fieldset>
            </form>
        </div>

        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (disCurricularDialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="dis-curricular-form">Crear programa</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={proyectoDialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/proyecto.png'} alt="Proyecto" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {ta.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Para terminar el numeral de <strong>Generalidades</strong> por favor continue diligenciando los siguientes campos:</h1>
                <p class="text-center mb-4">Si ya están completos omita esta información.</p>
                <ul class="list-disc">
                    <li>Resumen</li>
                    <li>Complemento - Resumen</li>
                    <li>Antecedentes</li>
                    <li>Antecedentes de la Tecnoacademia y su impacto en la región</li>
                    <li>Justificación</li>
                    <li>Retos y prioridades locales</li>
                    <li>Justificación y pertinencia en el territorio</li>
                    <li>Marco conceptual</li>
                    <li>Bibliografía</li>
                    <li>Número de aprendices beneficiados</li>
                    <li>Nombre de los municipios beneficiados</li>
                    <li>Articulación con la media</li>
                    <li>Articulación con el centro de formación</li>
                    <li>Articulación con programas de formación</li>
                </ul>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (proyectoDialogOpen = false)} variant={null}>Omitir</Button>
                <Button variant="raised" on:click={(event) => (proyectoDialogOpen = false)} on:click={() => Inertia.visit('#tecnoacademia_linea_tecnoacademia_id')}>Continuar diligenciando</Button>
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

            <form on:submit|preventDefault={destroy} id="delete-ta" class="mt-10 mb-28">
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente este proyecto" class="mb-4" />
                <Password id="password" class="w-full" bind:value={$deleteForm.password} error={errors.password} required autocomplete="current-password" />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="delete-ta">Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>

<style>
    :global(.tagify__tag) {
        background: #e5e5e5;
    }

    :global(.tagify__tag:focus div::before) {
        background: #d3e2e2;
    }

    :global(.tagify__tag:hover:not([readonly]) div:before) {
        background: #d3e2e2;
    }
</style>
