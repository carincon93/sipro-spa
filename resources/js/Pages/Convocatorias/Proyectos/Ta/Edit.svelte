<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
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
    export let lineaTecnologicaRelacionada
    export let tecnoacademiaRelacionada
    export let proyectoMunicipios
    export let proyectoProgramasFormacionArticulados

    $: $title = ta ? ta.titulo : null

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

    let form = useForm({
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
        impacto_municipios: ta.impacto_municipios,
        nombre_instituciones: ta.nombre_instituciones,
        nombre_instituciones_programas: ta.nombre_instituciones_programas,
        articulacion_centro_formacion: ta.articulacion_centro_formacion,
        bibliografia: ta.bibliografia,
        tecnoacademia_id: tecnoacademiaRelacionada,
        tecnoacademia_linea_tecnologica_id: lineaTecnologicaRelacionada,
        codigo_linea_programatica: null,
        nodo_tecnoparque_id: ta.nodo_tecnoparque_id,
        programas_formacion_articulados: proyectoProgramasFormacionArticulados.length > 0 ? proyectoProgramasFormacionArticulados : null,
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
        getProgramasFormacionArticular()
    })

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

    let programasFormacionArticular
    async function getProgramasFormacionArticular() {
        let res = await axios.get(route('web-api.programas-formacion-articulados'))
        if (res.status == '200') {
            programasFormacionArticular = res.data
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
                        <Label required class="mb-4" labelFor="tecnoacademia_id" value="Tecnoacademia" />
                    </div>
                    <div>
                        <DynamicList
                            id="tecnoacademia_id"
                            bind:value={$form.tecnoacademia_id}
                            noOptionsText="No hay tecnoacademias registradas para este centro de formación. Por favor seleccione un centro de formación diferente."
                            routeWebApi={route('web-api.centros-formacion.tecnoacademias', [$form.centro_formacion_id])}
                            placeholder="Busque por el nombre de la Tecnoacademia"
                            message={errors.tecnoacademia_id}
                            required
                        />
                    </div>
                </div>

                {#if $form.tecnoacademia_id}
                    <div class="mt-44 grid grid-cols-2">
                        <div>
                            <Label required class="mb-4" labelFor="tecnoacademia_linea_tecnologica_id" value="Línea tecnológica" />
                        </div>
                        <div>
                            <DynamicList
                                id="tecnoacademia_linea_tecnologica_id"
                                bind:value={$form.tecnoacademia_linea_tecnologica_id}
                                noOptionsText="No hay nodos tecnoparque registrados para este centro de formación. Por favor seleccione un centro de formación diferente."
                                routeWebApi={route('web-api.tecnoacademias.lineas-tecnologicas', [$form.tecnoacademia_id])}
                                placeholder="Busque por el nombre de la línea tecnológica"
                                message={errors.tecnoacademia_linea_tecnologica_id}
                                required
                            />
                        </div>
                    </div>
                {/if}
            </fieldset>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                    <InfoMessage message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="resumen" error={errors.resumen} bind:value={$form.resumen} required />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen_regional" value="Complemento - Resumen ejecutivo regional" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="resumen_regional" error={errors.resumen_regional} bind:value={$form.resumen_regional} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                    <InfoMessage
                        message="Presenta las investigaciones, innovaciones o desarrollos tecnológicos que se han realizado a nivel internacional, nacional, departamental o municipal en el marco de la temática de la propuesta del proyecto; que muestran la pertinencia del proyecto, citar toda la información consignada utilizando normas APA sexta edición. De igual forma, relacionar los proyectos ejecutados en vigencias anteriores (incluir códigos SGPS), si el proyecto corresponde a la continuidad de proyectos SENNOVA."
                    />
                </div>
                <div>
                    <Textarea maxlength="40000" id="antecedentes" error={errors.antecedentes} bind:value={$form.antecedentes} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes_tecnoacademia" value="Antecedentes de la Tecnoacademia y su impacto en la región" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="antecedentes_tecnoacademia" error={errors.antecedentes_tecnoacademia} bind:value={$form.antecedentes_tecnoacademia} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="justificacion" value="Justificación" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="justificacion" error={errors.justificacion} bind:value={$form.justificacion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="retos_oportunidades" value="Descripción de retos y prioridades locales y regionales en los cuales la Tecnoacademia tiene impacto" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="retos_oportunidades" error={errors.retos_oportunidades} bind:value={$form.retos_oportunidades} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="pertinencia_territorio" value="Justificación y pertinencia en el territorio" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="pertinencia_territorio" error={errors.pertinencia_territorio} bind:value={$form.pertinencia_territorio} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="marco_conceptual" value="Marco conceptual" />
                    <InfoMessage message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="marco_conceptual" error={errors.marco_conceptual} bind:value={$form.marco_conceptual} required />
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
                    <Textarea maxlength="40000" id="impacto_municipios" error={errors.impacto_municipios} bind:value={$form.impacto_municipios} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="nombre_instituciones" value="Instituciones donde se implementará el programa que tienen <strong>articulación con la Media</strong>" />
                </div>
                <div>
                    <Select id="departamento" bind:selectedValue={regionalIEArticulacion} items={regionales} placeholder="Seleccione un departamento" />

                    <Tags id="nombre_instituciones" class="mt-4" whitelist={whitelistInstitucionesEducativasArticular} bind:tags={$form.nombre_instituciones} placeholder="Nombre(s) de la(s) IE" error={errors.nombre_instituciones} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="nombre_instituciones_programas" value="Instituciones donde se están ejecutando los programas" />
                </div>
                <div>
                    <Select bind:selectedValue={regionalIEEjecucion} items={regionales} placeholder="Seleccione un departamento" />

                    <Tags id="nombre_instituciones_programas" class="mt-4" whitelist={whitelistInstitucionesEducativasEjecutar} bind:tags={$form.nombre_instituciones_programas} placeholder="Nombre(s) de la(s) IE" error={errors.nombre_instituciones_programas} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="programas_formacion_articulados" value="Nombre de los programas de formación articulados" />
                </div>
                <div>
                    <SelectMulti id="programas_formacion_articulados" bind:selectedValue={$form.programas_formacion_articulados} items={programasFormacionArticular} isMulti={true} error={errors.programas_formacion_articulados} placeholder="Buscar por el nombre del programa de formación" required />
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
                    <Label required class="mb-4" labelFor="articulacion_centro_formacion" value="Articulación con el centro de formación" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="articulacion_centro_formacion" error={errors.articulacion_centro_formacion} bind:value={$form.articulacion_centro_formacion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Séptima edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="bibliografia" error={errors.bibliografia} bind:value={$form.bibliografia} required />
                </div>
            </div>
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkPermission(authUser, [10]) && ta.proyecto.modificable == true)}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin || (checkPermission(authUser, [9, 10]) && ta.proyecto.modificable == true)}
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
                <Button variant="raised" on:click={(event) => (proyectoDialogOpen = false)} on:click={() => Inertia.visit('#tecnoacademia_linea_tecnologica_id')}>Continuar diligenciando</Button>
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

    :global(.tagify__tag:focus div::before, .tagify__tag:hover:not([readonly]) div::before) {
        background: #d3e2e2;
    }
</style>
