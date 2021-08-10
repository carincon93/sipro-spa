<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'
    import { onMount } from 'svelte'

    import Button from '@/Shared/Button'
    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Stepper from '@/Shared/Stepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import SelectMulti from '@/Shared/SelectMulti'
    import Dialog from '@/Shared/Dialog'
    import Tags from '@/Shared/Tags'
    import Select from '@/Shared/Select'
    import Switch from '@/Shared/Switch'

    export let errors
    export let convocatoria
    export let ta
    export let taEvaluacion
    export let regionales
    export let lineasTecnoacademiaRelacionadas
    export let tecnoacademiaRelacionada
    export let proyectoMunicipios
    export let proyectoMunicipiosImpactar
    export let proyectoProgramasFormacionArticulados
    export let proyectoDisCurriculares
    export let disCurriculares
    export let tecnoAcademias

    $: $title = ta ? ta.titulo : null

    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]
    let sending = false
    let proyectoDialogOpen = true

    let municipios = []
    let codigoLineaProgramatica

    $: if (codigoLineaProgramatica) {
        taInfo.codigo_linea_programatica = codigoLineaProgramatica.codigo
    } else {
        taInfo.codigo_linea_programatica = ta.codigo_linea_programatica
    }

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let taInfo = {
        centro_formacion_id: ta.proyecto.centro_formacion_id,
        linea_programatica_id: ta.proyecto.linea_programatica_id,
        fecha_inicio: ta.fecha_inicio,
        fecha_finalizacion: ta.fecha_finalizacion,
        max_meses_ejecucion: ta.max_meses_ejecucion,
        resumen: ta.resumen,
        resumen_regional: ta.resumen_regional,
        antecedentes: ta.antecedentes,
        justificacion: ta.justificacion,
        antecedentes_tecnoacademia: ta.antecedentes_tecnoacademia,
        retos_oportunidades: ta.retos_oportunidades,
        pertinencia_territorio: ta.pertinencia_territorio,
        marco_conceptual: ta.marco_conceptual,
        municipios: proyectoMunicipios.length > 0 ? proyectoMunicipios : null,
        municipios_impactar: proyectoMunicipiosImpactar.length > 0 ? proyectoMunicipiosImpactar : null,
        impacto_municipios: ta.impacto_municipios,
        nombre_instituciones: ta.nombre_instituciones,
        nombre_instituciones_programas: ta.nombre_instituciones_programas,
        nuevas_instituciones: ta.nuevas_instituciones,
        articulacion_centro_formacion: ta.articulacion_centro_formacion,
        proyectos_macro: ta.proyectos_macro,
        lineas_medulares_centro: ta.lineas_medulares_centro,
        lineas_tecnologicas_centro: ta.lineas_tecnologicas_centro,
        proyeccion_nuevas_instituciones: {
            value: ta.proyeccion_nuevas_instituciones,
            label: opcionesSiNo.find((item) => item.value == ta.proyeccion_nuevas_instituciones)?.label,
        },
        proyeccion_articulacion_media: {
            value: ta.proyeccion_articulacion_media,
            label: opcionesSiNo.find((item) => item.value == ta.proyeccion_articulacion_media)?.label,
        },
        bibliografia: ta.bibliografia,
        tecnoacademia_id: {
            value: tecnoacademiaRelacionada,
            label: tecnoAcademias.find((item) => item.value == tecnoacademiaRelacionada)?.label,
        },
        tecnoacademia_linea_tecnoacademia_id: lineasTecnoacademiaRelacionadas,
        codigo_linea_programatica: null,
        programas_formacion_articulados: proyectoProgramasFormacionArticulados.length > 0 ? proyectoProgramasFormacionArticulados : null,
        dis_curricular_id: proyectoDisCurriculares.length > 0 ? proyectoDisCurriculares : null,
    }

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
        getProgramasFormacionArticular()
        getLineasTecnoacademia()
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [11]) && ta.proyecto.finalizado == true)) {
            taInfo.put(route('convocatorias.ta-evaluaciones.update', [convocatoria.id, taEvaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
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

    $: if (taInfo.fecha_inicio && taInfo.fecha_finalizacion) {
        taInfo.max_meses_ejecucion = monthDiff(taInfo.fecha_inicio, taInfo.fecha_finalizacion)
    }

    let programasFormacionArticular
    async function getProgramasFormacionArticular() {
        let res = await axios.get(route('web-api.programas-formacion', ta.proyecto.centro_formacion_id))
        if (res.status == '200') {
            programasFormacionArticular = res.data
        }
    }

    let lineasTecnoaAcademia
    async function getLineasTecnoacademia() {
        let res = await axios.get(route('web-api.tecnoacademias.lineas-tecnoacademia', [tecnoacademiaRelacionada]))
        if (res.status == '200') {
            lineasTecnoaAcademia = res.data
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} proyecto={ta} />

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [11]) && ta.proyecto.finalizado == true) ? undefined : true}>
            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_ta} max={convocatoria.max_fecha_finalizacion_proyectos_ta} bind:value={taInfo.fecha_inicio} />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_ta} max={convocatoria.max_fecha_finalizacion_proyectos_ta} bind:value={taInfo.fecha_finalizacion} />
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
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label class="mb-4" labelFor="linea_programatica_id" value="Código dependencia presupuestal (SIIF)" />
                    </div>
                    <div>
                        <DynamicList disabled={true} id="linea_programatica_id" bind:value={taInfo.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas', 1)} classes="min-h" placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} />
                    </div>
                </div>
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                        <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                    </div>
                    <div>
                        {ta.proyecto.centro_formacion.nombre}
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label class="mb-4" labelFor="tecnoacademia_id" value="TecnoAcademia" />
                    </div>
                    <div>
                        <Select disabled={true} id="tecnoacademia_id" items={tecnoAcademias} bind:selectedValue={taInfo.tecnoacademia_id} autocomplete="off" placeholder="Busque por el nombre de la TecnoAcademia" />
                    </div>
                </div>
            </fieldset>
            {#if taInfo.tecnoacademia_id && lineasTecnoaAcademia}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label class="mb-4" labelFor="tecnoacademia_linea_tecnoacademia_id" value="Líneas temáticas a ejecutar en la vigencia del proyecto:" />
                    </div>
                    <div>
                        <SelectMulti disabled={true} id="tecnoacademia_linea_tecnoacademia_id" bind:selectedValue={taInfo.tecnoacademia_linea_tecnoacademia_id} items={lineasTecnoaAcademia} isMulti={true} placeholder="Buscar por el nombre de la línea" />
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
                        <Label class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                        <InfoMessage message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad del proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto." />
                    </div>
                    <div>
                        <Textarea disabled maxlength="40000" id="resumen" bind:value={taInfo.resumen} />
                    </div>
                </div>
            </fieldset>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="resumen_regional" value="Complemento - Resumen ejecutivo regional" />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="resumen_regional" bind:value={taInfo.resumen_regional} />

                    <InfoMessage>
                        <div class="mt-4">
                            <p>¿El ítem requiere de una recomendación?</p>
                            <Switch disabled={isSuperAdmin ? undefined : taEvaluacion.evaluacion.finalizado == true || taEvaluacion.evaluacion.habilitado == false ? true : undefined} bind:checked={taInfo.justificacion_economia_naranja_requiere_comentario} />
                            {#if taInfo.justificacion_economia_naranja_requiere_comentario}
                                <Textarea disabled={isSuperAdmin ? undefined : taEvaluacion.evaluacion.finalizado == true || taEvaluacion.evaluacion.habilitado == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="justificacion_economia_naranja_comentario" bind:value={taInfo.justificacion_economia_naranja_comentario} />
                            {/if}
                        </div>
                    </InfoMessage>
                </div>
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-1">
                    <div>
                        <Label class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                        <InfoMessage
                            message="Presenta las investigaciones, innovaciones o desarrollos tecnológicos que se han realizado a nivel internacional, nacional, departamental o municipal en el marco de la temática de la propuesta del proyecto; que muestran la pertinencia del proyecto, citar toda la información consignada utilizando normas APA última edición. De igual forma, relacionar los proyectos ejecutados en vigencias anteriores (incluir códigos SGPS), si el proyecto corresponde a la continuidad de proyectos SENNOVA."
                        />
                    </div>
                    <div>
                        <Textarea disabled maxlength="40000" id="antecedentes" bind:value={taInfo.antecedentes} />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="antecedentes_tecnoacademia" value="Antecedentes de la Tecnoacademia y su impacto en la región" />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="antecedentes_tecnoacademia" bind:value={taInfo.antecedentes_tecnoacademia} />
                </div>
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-1">
                    <div>
                        <Label class="mb-4" labelFor="justificacion" value="Justificación" />
                    </div>
                    <div>
                        <Textarea disabled maxlength="40000" id="justificacion" bind:value={taInfo.justificacion} />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="retos_oportunidades" value="Descripción de retos y prioridades locales y regionales en los cuales la Tecnoacademia tiene impacto" />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="retos_oportunidades" bind:value={taInfo.retos_oportunidades} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="pertinencia_territorio" value="Justificación y pertinencia en el territorio" />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="pertinencia_territorio" bind:value={taInfo.pertinencia_territorio} />
                </div>
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-1">
                    <div>
                        <Label class="mb-4" labelFor="marco_conceptual" value="Marco conceptual" />
                        <InfoMessage message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones." />
                    </div>
                    <div>
                        <Textarea disabled maxlength="40000" id="marco_conceptual" bind:value={taInfo.marco_conceptual} />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" for="municipios" value="Nombre los municipios impactados en la vigencia anterior por la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti disabled={true} id="municipios" bind:selectedValue={taInfo.municipios} items={municipios} isMulti={true} placeholder="Buscar municipios" />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" for="municipios_impactar" value="Defina los municipios a impactar en la vigencia el proyecto:" />
                </div>
                <div>
                    <SelectMulti disabled={true} id="municipios_impactar" bind:selectedValue={taInfo.municipios_impactar} items={municipios} isMulti={true} placeholder="Buscar municipios" />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="impacto_municipios" value="Descripción del beneficio o impacto generado por la TecnoAcademia en los municipios" />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="impacto_municipios" bind:value={taInfo.impacto_municipios} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="nombre_instituciones_programas" value="Instituciones donde se están ejecutando los programas y que se espera continuar con el proyecto de TecnoAcademias" />
                </div>
                <div>
                    <Select disabled={true} id="departamento_instituciones_programas" bind:selectedValue={regionalIEEjecucion} items={regionales} placeholder="Seleccione un departamento" />

                    <Tags disabled id="nombre_instituciones_programas" class="mt-4" whitelist={whitelistInstitucionesEducativasEjecutar} bind:tags={taInfo.nombre_instituciones_programas} placeholder="Nombre(s) de la(s) IE" />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="proyeccion_nuevas_instituciones" value="¿Se proyecta incluir nuevas TecnoAcademia?" />
                </div>
                <div>
                    <Select disabled={true} items={opcionesSiNo} id="proyeccion_nuevas_instituciones" bind:selectedValue={taInfo.proyeccion_nuevas_instituciones} autocomplete="off" placeholder="Seleccione una opción" />
                </div>
            </div>

            {#if taInfo.proyeccion_nuevas_instituciones?.value == 1}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label class="mb-4" labelFor="nuevas_instituciones" value="Nuevas instituciones educativas que se vincularán con el proyecto de TecnoAcademia" />
                    </div>
                    <div>
                        <Select disabled={true} id="departamento_nuevas_instituciones" bind:selectedValue={regionalIEEjecucion} items={regionales} placeholder="Seleccione un departamento" />

                        <Tags disabled id="nuevas_instituciones" class="mt-4" whitelist={whitelistInstitucionesEducativasEjecutar} bind:tags={taInfo.nuevas_instituciones} placeholder="Nombre(s) de la(s) IE" />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="proyeccion_articulacion_media" value="¿Se proyecta incluir Institucienes Educativas en articulación con la media?" />
                </div>
                <div>
                    <Select disabled={true} items={opcionesSiNo} id="proyeccion_articulacion_media" bind:selectedValue={taInfo.proyeccion_articulacion_media} autocomplete="off" placeholder="Seleccione una opción" />
                </div>
            </div>

            {#if taInfo.proyeccion_articulacion_media?.value == 1}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label class="mb-4" labelFor="nombre_instituciones" value="Instituciones donde se implementará el programa que tienen <strong>articulación con la Media</strong>" />
                    </div>
                    <div>
                        <Select disabled={true} id="departamento_instituciones_media" bind:selectedValue={regionalIEArticulacion} items={regionales} placeholder="Seleccione un departamento" />

                        <Tags disabled id="nombre_instituciones" class="mt-4" whitelist={whitelistInstitucionesEducativasArticular} bind:tags={taInfo.nombre_instituciones} placeholder="Nombre(s) de la(s) IE" />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" for="programas_formacion_articulados" value="Programas de articulación con la Media con los cuales se espera dar continuidad a la ruta de formación de los aprendices de la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti disabled={true} id="programas_formacion_articulados" bind:selectedValue={taInfo.programas_formacion_articulados} items={programasFormacionArticular} isMulti={true} placeholder="Buscar por el nombre del programa de formación" />
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

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="articulacion_centro_formacion" value="Articulación con el centro de formación" />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="articulacion_centro_formacion" bind:value={taInfo.articulacion_centro_formacion} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" for="dis_curricular_id" value="Programas a ejecutar en la vigencia del proyecto:" />
                </div>
                <div>
                    <SelectMulti disabled={true} id="dis_curricular_id" bind:selectedValue={taInfo.dis_curricular_id} items={disCurriculares} isMulti={true} placeholder="Buscar por el nombre del programa de formación" />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="proyectos_macro" value="Proyectos Macro de investigación formativa y aplicada de la TecnoAcademia para la vigencia 2022" />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="proyectos_macro" bind:value={taInfo.proyectos_macro} />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="lineas_medulares_centro" value="Líneas medulares del Centro con las que se articula la TecnoAcademia" />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="lineas_medulares_centro" bind:value={taInfo.lineas_medulares_centro} />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="lineas_tecnologicas_centro" value="Líneas tecnológicas del Centro con las que se articula la TecnoAcademia" />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="lineas_tecnologicas_centro" bind:value={taInfo.lineas_tecnologicas_centro} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Última edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea disabled maxlength="40000" id="bibliografia" bind:value={taInfo.bibliografia} />
                </div>
            </div>
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkRole(authUser, [11]) && ta.proyecto.finalizado == true && taEvaluacion.evaluacion.finalizado == false && taEvaluacion.evaluacion.habilitado == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

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
