<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'
    import { onMount } from 'svelte'

    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import SelectMulti from '@/Shared/SelectMulti'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import Tags from '@/Shared/Tags'
    import Input from '@/Shared/Input'
    import Switch from '@/Shared/Switch'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import InfoMessage from '@/Shared/InfoMessage'
    import File from '@/Shared/File'

    export let errors
    export let proyectoIdiTecnoacademia
    export let tecnoacademias
    export let programasSennova
    export let semillerosInvestigacion
    export let estadosProyectoIdiTecnoacademia
    export let beneficiados
    export let lineasRelacionadas
    export let programasRelacionados
    export let beneficiadosRelacionados
    export let municipiosRelacionados
    export let roles
    export let autorPrincipal
    export let proyectos
    export let regionales

    let hayEntidadesVinculadas = proyectoIdiTecnoacademia.entidades_vinculadas ? true : false
    let existenDocumentos = proyectoIdiTecnoacademia.documentos_resultados ? true : false
    let municipios = []

    $: $title = 'Editar proyecto I+D+i TecnoAcademia'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        _method: 'put',
        tecnoacademia_id: {
            value: proyectoIdiTecnoacademia.tecnoacademia_id,
            label: tecnoacademias.find((item) => item.value == proyectoIdiTecnoacademia.tecnoacademia_id)?.label,
        },
        semillero_investigacion_id: {
            value: proyectoIdiTecnoacademia.semillero_investigacion_id,
            label: semillerosInvestigacion.find((item) => item.value == proyectoIdiTecnoacademia.semillero_investigacion_id)?.label,
        },
        proyecto_id: {
            value: proyectoIdiTecnoacademia.proyecto_id,
            label: proyectos.find((item) => item.value == proyectoIdiTecnoacademia.proyecto_id)?.label,
        },
        tecnoacademia_linea_tecnoacademia_id: lineasRelacionadas,
        titulo: proyectoIdiTecnoacademia.titulo,
        fecha_inicio: proyectoIdiTecnoacademia.fecha_inicio,
        fecha_finalizacion: proyectoIdiTecnoacademia.fecha_finalizacion,
        resumen: proyectoIdiTecnoacademia.resumen,
        palabras_clave: proyectoIdiTecnoacademia.palabras_clave,
        especies: proyectoIdiTecnoacademia.especies,
        tiene_linea_investigacion: proyectoIdiTecnoacademia.tiene_linea_investigacion,
        lineas_investigacion: proyectoIdiTecnoacademia.lineas_investigacion,
        proyecto_nuevo: proyectoIdiTecnoacademia.proyecto_nuevo,
        proyecto_con_continuidad: proyectoIdiTecnoacademia.proyecto_con_continuidad,
        productos_premios: proyectoIdiTecnoacademia.productos_premios,
        texto_exposicion: proyectoIdiTecnoacademia.texto_exposicion,
        pdf_proyecto: null,
        resultados_obtenidos: proyectoIdiTecnoacademia.resultados_obtenidos,
        documentos_resultados: null,
        observaciones_resultados: proyectoIdiTecnoacademia.observaciones_resultados,
        nombre_aprendices_vinculados: proyectoIdiTecnoacademia.nombre_aprendices_vinculados,
        nombre_instituciones_educativas: proyectoIdiTecnoacademia.nombre_instituciones_educativas,
        nuevas_instituciones_educativas: proyectoIdiTecnoacademia.nuevas_instituciones_educativas,
        programa_sennova_participante: programasRelacionados,
        programa_formacion_articulado_media: proyectoIdiTecnoacademia.programa_formacion_articulado_media,
        entidades_vinculadas: proyectoIdiTecnoacademia.entidades_vinculadas,
        fuente_recursos: proyectoIdiTecnoacademia.fuente_recursos,
        presupuesto: proyectoIdiTecnoacademia.presupuesto,
        hace_parte_de_semillero: proyectoIdiTecnoacademia.hace_parte_de_semillero,
        estado_proyecto: {
            value: proyectoIdiTecnoacademia.estado_proyecto,
            label: estadosProyectoIdiTecnoacademia.find((item) => item.value == proyectoIdiTecnoacademia.estado_proyecto)?.label,
        },
        poblacion_beneficiada: proyectoIdiTecnoacademia.poblacion_beneficiada,
        otra_poblacion_beneficiada: proyectoIdiTecnoacademia.otra_poblacion_beneficiada,
        nombre_centro_programa: proyectoIdiTecnoacademia.nombre_centro_programa,
        beneficiados: beneficiadosRelacionados,
        municipios: municipiosRelacionados,
        rol_sennova: {
            value: roles.find((item) => item.value == autorPrincipal.pivot.rol_sennova)?.value,
            label: roles.find((item) => item.value == autorPrincipal.pivot.rol_sennova)?.label,
        },
        cantidad_meses: autorPrincipal.pivot.cantidad_meses,
        cantidad_horas: autorPrincipal.pivot.cantidad_horas,
    })

    function submit() {
        if (isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])) {
            $form.post(route('proyectos-idi-tecnoacademia.update', proyectoIdiTecnoacademia.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])) {
            $form.delete(route('proyectos-idi-tecnoacademia.destroy', [proyectoIdiTecnoacademia.id]))
        }
    }

    onMount(() => {
        getMunicipios()
    })

    let lineasTecnoaAcademia
    let oldLineaTecnoacademiaValue = null

    $: if ($form.tecnoacademia_id?.value) {
        if (oldLineaTecnoacademiaValue != $form.tecnoacademia_id?.value) {
            getLineasTecnoacademia($form.tecnoacademia_id?.value)
        }
    }
    async function getLineasTecnoacademia(lineaTecnoacademiaId) {
        let res = await axios.get(route('web-api.tecnoacademias.lineas-tecnoacademia', [lineaTecnoacademiaId]))
        if (res.status == '200') {
            lineasTecnoaAcademia = res.data
            oldLineaTecnoacademiaValue = $form.tecnoacademia_id?.value
        }
    }

    async function getMunicipios() {
        let res = await axios.get(route('web-api.municipios'))
        if (res.status == '200') {
            municipios = res.data
        }
    }

    let departamentoIE
    $: whitelistInstitucionesEducativas = []
    $: if (departamentoIE) {
        axios
            .get('https://www.datos.gov.co/resource/cfw5-qzt5.json?cod_dane_departamento=' + departamentoIE?.codigo)
            .then(function (response) {
                // handle success
                response.data.map((item) => {
                    whitelistInstitucionesEducativas.push(item.nombre_establecimiento)
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
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.index')} class="text-cyan-400 hover:text-cyan-600"> Proyectos I+D+i TecnoAcademia </a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.edit', proyectoIdiTecnoacademia.id)} class="text-cyan-400 hover:text-cyan-600 font-extrabold underline"> Información básica </a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.participantes.index', proyectoIdiTecnoacademia.id)} class="text-cyan-400 hover:text-cyan-600"> Participantes </a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.productos.index', proyectoIdiTecnoacademia.id)} class="text-cyan-400 hover:text-cyan-600">Productos</a>
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || checkRole(authUser, [5, 10, 12, 22]) ? undefined : true}>
            <div class="mt-28">
                <Label required labelFor="titulo" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué." />
                <Textarea label="Título" id="titulo" sinContador={true} error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion || errors.max_meses_ejecucion}
                    <div class="mb-20 flex justify-center mt-4">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                    </div>
                {/if}
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tecnoacademia_id" value="TecnoAcademia" />
                </div>
                <div>
                    <Select id="tecnoacademia_id" items={tecnoacademias} bind:selectedValue={$form.tecnoacademia_id} error={errors.tecnoacademia_id} autocomplete="off" placeholder="Busque por el nombre de la TecnoAcademia" required />
                </div>
            </div>

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

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="resumen" error={errors.resumen} bind:value={$form.resumen} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="palabras_clave" value="Palabras claves relacionadas con el proyecto (Separados por coma)" />
                </div>
                <div>
                    <Tags id="palabras_clave" class="mt-4" enforceWhitelist={false} bind:tags={$form.palabras_clave} placeholder="Palabras clave" error={errors.palabras_clave} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="especies" value="En caso de aplicar, por favor incluir las especies (nombre científico) que hacen parte del proyecto" />
                </div>
                <div>
                    <Tags id="especies" class="mt-4" enforceWhitelist={false} bind:tags={$form.especies} placeholder="Especies" error={errors.especies} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" value="¿El proyecto hace parte de una línea de investigación de la TecnoAcademia?" />
                </div>
                <div>
                    <Switch bind:checked={$form.tiene_linea_investigacion} />
                </div>
            </div>

            {#if $form.tiene_linea_investigacion}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="lineas_investigacion" value="¿Cuáles líneas de investigación hacen parte del proyecto? (Separar por coma)" />
                    </div>
                    <div>
                        <Tags id="lineas_investigacion" class="mt-4" enforceWhitelist={false} bind:tags={$form.lineas_investigacion} placeholder="Líneas de investigación" error={errors.lineas_investigacion} required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" value="¿Es un proyecto nuevo?" />
                </div>
                <div>
                    <Switch bind:checked={$form.proyecto_nuevo} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" value="¿Es un proyecto que genera continuidad de uno anterior?" />
                </div>
                <div>
                    <Switch bind:checked={$form.proyecto_con_continuidad} />
                </div>
            </div>

            {#if $form.proyecto_con_continuidad}
                <div class="mt-44 grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="productos_premios" value="Si es un proyecto que ha tenido continuidad, por favor relacione los productos obtenidos previamente y los reconocimientos o premios" />
                    </div>
                    <div>
                        <Textarea maxlength="40000" id="productos_premios" error={errors.productos_premios} bind:value={$form.productos_premios} required />
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="proyecto_id" value="Seleccione el código SGPS del proyecto" />
                    </div>
                    <div>
                        <Select id="proyecto_id" items={proyectos} bind:selectedValue={$form.proyecto_id} error={errors.proyecto_id} autocomplete="off" placeholder="Busque por el código SGPS" required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="texto_exposicion" value="Texto para exposición: Un texto corto que resuma la experiencia y que pueda ser publicado en espacios como el MUSEO SENA para introducir la iniciativa y los resultados." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="texto_exposicion" error={errors.texto_exposicion} bind:value={$form.texto_exposicion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="pdf_proyecto" value="Proyecto en documento pdf" />
                </div>
                <div>
                    <File id="pdf_proyecto" accept="application/pdf" showInput={false} maxSize="10000" bind:value={$form.pdf_proyecto} error={errors.pdf_proyecto} route={route('proyectos-idi-tecnoacademia.download-pdf', [proyectoIdiTecnoacademia, 'pdf_proyecto'])} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="nombre_aprendices_vinculados" value="Nombre de los aprendices vinculados al proyecto (Por favor separar los nombres por coma- ,)" />
                </div>
                <div>
                    <Tags id="nombre_aprendices_vinculados" class="mt-4" enforceWhitelist={false} bind:tags={$form.nombre_aprendices_vinculados} placeholder="Aprendices vinculados" error={errors.nombre_aprendices_vinculados} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required={$form.nombre_instituciones_educativas ? undefined : true} class="mb-4" labelFor="nombre_instituciones_educativas" value="Nombre de las instituciones educativas de los aprendices vinculados al proyecto" />
                </div>
                <div>
                    <Select id="departamento" bind:selectedValue={departamentoIE} items={regionales} placeholder="Seleccione un departamento" />

                    <Tags id="nombre_instituciones_educativas" class="mt-4" whitelist={whitelistInstitucionesEducativas} bind:tags={$form.nombre_instituciones_educativas} placeholder="Nombre(s) de la(s) IE" error={errors.nombre_instituciones_educativas} required={$form.nuevas_instituciones_educativas ? undefined : true} />
                    <div class="mt-10">
                        <InfoMessage>Si no encuentra alguna institución educativa en la anterior lista por favor escriba el nombre en el siguiente campo de texto (Separadas por coma)</InfoMessage>
                        <Tags id="nuevas_instituciones_educativas" class="mt-4" enforceWhitelist={false} bind:tags={$form.nuevas_instituciones_educativas} placeholder="Instituciones educativas (Separadas por coma)" error={errors.nuevas_instituciones_educativas} />
                    </div>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="programa_sennova_participante" value="¿El proyecto vincula otro programa sennova?" />
                </div>
                <div>
                    <SelectMulti id="programa_sennova_participante" bind:selectedValue={$form.programa_sennova_participante} items={programasSennova} isMulti={true} error={errors.programa_sennova_participante} placeholder="Seleccione un programa" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="nombre_centro_programa" value="Por favor incluya el nombre y centro del programa sennova que participa en el proyecto" />
                </div>
                <div>
                    <Input label="Nombre" id="nombre_centro_programa" type="text" class="mt-1" error={errors.nombre_centro_programa} bind:value={$form.nombre_centro_programa} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="programa_formacion_articulado_media" value="El proyecto está vinculado a un programa de formación de articulación con la media? por favor indicar cual y la institución educativa" />
                </div>
                <div>
                    <Input label="Nombre" id="programa_formacion_articulado_media" type="text" class="mt-1" error={errors.programa_formacion_articulado_media} bind:value={$form.programa_formacion_articulado_media} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" value="¿Otras organizaciones externas vinculadas al proyecto?" />
                </div>
                <div>
                    <Switch bind:checked={hayEntidadesVinculadas} />
                </div>
            </div>

            {#if hayEntidadesVinculadas}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="entidades_vinculadas" value="Nombre de las entidades vinculadas al proyecto (Por favor separar los nombres por coma- ,)" />
                    </div>
                    <div>
                        <Tags id="entidades_vinculadas" class="mt-4" enforceWhitelist={false} bind:tags={$form.entidades_vinculadas} placeholder="Entidades vinculadas" />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="fuente_recursos" value="Fuente de recursos para el proyecto" />
                </div>
                <div>
                    <Input label="Fuente" id="fuente_recursos" type="text" class="mt-1" error={errors.fuente_recursos} bind:value={$form.fuente_recursos} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="presupuesto" value="Presupuesto del proyecto por año (o por la duración del proyecto si es menor a un año)" />
                </div>
                <div>
                    <Input label="Presupuesto" id="presupuesto" type="number" input$min="0" class="mt-1" error={errors.presupuesto} bind:value={$form.presupuesto} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="municipios" value="Nombre los municipios en los que se desarrolla" />
                </div>
                <div>
                    <SelectMulti id="municipios" bind:selectedValue={$form.municipios} items={municipios} isMulti={true} error={errors.municipios} placeholder="Buscar municipios" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="semillero_investigacion_id" value="¿Hace parte de un semillero?" />
                </div>
                <div>
                    <Switch bind:checked={$form.hace_parte_de_semillero} />
                </div>
            </div>

            {#if $form.hace_parte_de_semillero}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="semillero_investigacion_id" value="Semillero de investigación" />
                    </div>
                    <div>
                        <Select id="semillero_investigacion_id" items={semillerosInvestigacion} bind:selectedValue={$form.semillero_investigacion_id} error={errors.semillero_investigacion_id} autocomplete="off" placeholder="Busque por el nombre del centro de formación" required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="estado_proyecto" value="Estado del proyecto" />
                </div>
                <div>
                    <Select id="estado_proyecto" items={estadosProyectoIdiTecnoacademia} bind:selectedValue={$form.estado_proyecto} error={errors.centro_formacion_id} autocomplete="off" placeholder="Seleccione un estado" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="beneficiados" value="El proyecto beneficiará a" />
                </div>
                <div>
                    <SelectMulti id="beneficiados" bind:selectedValue={$form.beneficiados} items={beneficiados} isMulti={true} error={errors.beneficiados} placeholder="Selecciona los beneficiados" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="poblacion_beneficiada" value="Por favor describa la población que beneficia la iniciativa" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="poblacion_beneficiada" error={errors.poblacion_beneficiada} bind:value={$form.poblacion_beneficiada} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label class="mb-4" labelFor="otra_poblacion_beneficiada" value="Si es OTRA, por favor aclarar cuál" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="otra_poblacion_beneficiada" error={errors.otra_poblacion_beneficiada} bind:value={$form.otra_poblacion_beneficiada} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resultados_obtenidos" value="Resultados obtenidos" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="resultados_obtenidos" error={errors.resultados_obtenidos} bind:value={$form.resultados_obtenidos} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <p>¿Existen documentos tipo contrato, convenio o acuerdos que hagan parte del proyecto?</p>
                </div>
                <div>
                    <Switch bind:checked={existenDocumentos} />
                </div>
            </div>

            {#if existenDocumentos}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="documentos_resultados" value="Si, sí, por favor cargar el documento con los resultados. (Si son varios comprímalos en un archivo .zip)" />
                    </div>
                    <div>
                        <File id="documentos_resultados" showInput={false} maxSize="10000" bind:value={$form.documentos_resultados} error={errors.documentos_resultados} route={route('proyectos-idi-tecnoacademia.download-pdf', [proyectoIdiTecnoacademia, 'documentos_resultados'])} />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="observaciones_resultados" value="Observaciones" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="observaciones_resultados" error={errors.observaciones_resultados} bind:value={$form.observaciones_resultados} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="rol_sennova" value="Rol" />
                </div>

                <div>
                    <Select id="rol_sennova" items={roles} bind:selectedValue={$form.rol_sennova} error={errors.rol_sennova} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="cantidad_meses" value="Número de meses de vinculación al proyecto" />
                </div>
                <div>
                    <Input label="Número de meses de vinculación" id="cantidad_meses" type="number" input$step="0.1" input$min="1" input$max={monthDiff($form.fecha_inicio, $form.fecha_finalizacion)} class="mt-1" bind:value={$form.cantidad_meses} placeholder="Número de meses de vinculación" autocomplete="off" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="cantidad_horas" value="Número de horas semanales dedicadas para el desarrollo del proyecto" />
                </div>
                <div>
                    <Input label="Número de horas semanales dedicadas para el desarrollo del proyecto" id="cantidad_horas" type="number" input$step="1" input$min="1" input$max={$form.rol_sennova?.maxHoras} class="mt-1" bind:value={$form.cantidad_horas} placeholder="Número de horas semanales dedicadas para el desarrollo del proyecto" autocomplete="off" required />
                    {#if $form.rol_sennova?.maxHoras}
                        <InfoMessage>Horas máximas permitidas para este rol: {$form.rol_sennova?.maxHoras}.</InfoMessage>
                    {/if}
                </div>
            </div>

            {#if $form.progress}
                <progress value={$form.progress.percentage} max="100" class="mt-4">
                    {$form.progress.percentage}%
                </progress>
            {/if}
        </fieldset>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar proyecto I+D+i TecnoAcademia </button>
            {/if}
            {#if isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar y continuar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={dialogOpen}>
        <div slot="title" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro(a) que desea eliminar este recurso?
                <br />
                Todos los datos se eliminarán de forma permanente.
                <br />
                Está acción no se puede deshacer.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
