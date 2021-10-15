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
    import { Inertia } from '@inertiajs/inertia'

    export let errors
    export let convocatoria
    export let tp
    export let proyectoMunicipios

    $: $title = tp ? tp.titulo : null

    let sending = false
    let dialogOpen = errors.password != undefined ? true : false
    let proyectoDialogOpen = true

    let municipios = []
    let codigoLineaProgramatica

    $: if (codigoLineaProgramatica) {
        $form.codigo_linea_programatica = codigoLineaProgramatica.codigo
    } else {
        $form.codigo_linea_programatica = tp.codigo_linea_programatica
    }

    const groupBy = (item) => item.group

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let resumenForm = useForm({
        resumen: tp.resumen,
    })
    let formResumenRegional = useForm({
        resumen_regional: tp.resumen_regional,
    })
    let formAntecedentes = useForm({
        antecedentes: tp.antecedentes,
    })
    let formAntecedentesRegional = useForm({
        antecedentes_regional: tp.antecedentes_regional,
    })
    let formMarcoConceptual = useForm({
        marco_conceptual: tp.marco_conceptual,
    })
    let formBibliografia = useForm({
        bibliografia: tp.bibliografia,
    })
    let formImpactoMunicipios = useForm({
        impacto_municipios: tp.impacto_municipios,
    })
    let formImpactoCentroFormacion = useForm({
        impacto_centro_formacion: tp.impacto_centro_formacion,
    })
    let formRetosOportunidades = useForm({
        retos_oportunidades: tp.retos_oportunidades,
    })
    let formPertinenciaTerritorio = useForm({
        pertinencia_territorio: tp.pertinencia_territorio,
    })

    let form = useForm({
        centro_formacion_id: tp.proyecto.centro_formacion_id,
        linea_programatica_id: tp.proyecto.linea_programatica_id,
        fecha_inicio: tp.fecha_inicio,
        fecha_finalizacion: tp.fecha_finalizacion,
        max_meses_ejecucion: tp.max_meses_ejecucion,
        municipios: proyectoMunicipios.length > 0 ? proyectoMunicipios : null,
        codigo_linea_programatica: null,
        nodo_tecnoparque_id: tp.nodo_tecnoparque_id,
    })

    let regional
    $: whitelistInstitucionesEducativas = []
    $: if (regional) {
        axios
            .get('https://www.datos.gov.co/resource/cfw5-qzt5.json?cod_dane_departamento=' + regional?.codigo)
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

    onMount(() => {
        getMunicipios()
    })

    async function syncColumnLong(column, form) {
        return new Promise((resolve) => {
            if (isSuperAdmin || (checkPermission(authUser, [18, 19]) && tp.proyecto.modificable == true)) {
                //guardar
                Inertia.put(
                    route('convocatorias.tp.updateLongColumn', [convocatoria.id, tp.id, column]),
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
        if (isSuperAdmin || (checkPermission(authUser, [18, 19]) && tp.proyecto.modificable == true)) {
            $form.put(route('convocatorias.tp.update', [convocatoria.id, tp.id]), {
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
        if (isSuperAdmin || (checkPermission(authUser, [19]) && tp.proyecto.modificable == true)) {
            $deleteForm.delete(route('convocatorias.tp.destroy', [convocatoria.id, tp.id]), {
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
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} proyecto={tp} />

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [18, 19]) && tp.proyecto.modificable == true) ? undefined : true}>
            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <InfoMessage message={convocatoria.fecha_maxima_tp} class="my-5" />

                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_tp} max={convocatoria.max_fecha_finalizacion_proyectos_tp} error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_tp} max={convocatoria.max_fecha_finalizacion_proyectos_tp} error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
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

                {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                    {#each tp.proyecto.evaluaciones as evaluacion, i}
                        {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                            <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                <div class="flex text-orangered-900 font-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Recomendación del evaluador COD-{evaluacion.id}:
                                </div>
                                <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.fecha_ejecucion_comentario ? evaluacion.tp_evaluacion.fecha_ejecucion_comentario : 'Sin recomendación'}</p>
                            </div>
                        {/if}
                    {/each}
                {/if}
            </div>

            <fieldset disabled={authUser.id == 122 ? undefined : true}>
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
                        {tp.proyecto.centro_formacion.nombre}
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="nodo_tecnoparque_id" value="Nodo Tecnoparque" />
                    </div>
                    <div>
                        <DynamicList id="nodo_tecnoparque_id" bind:value={$form.nodo_tecnoparque_id} placeholder="Seleccione un nodo Tecnoparque" routeWebApi={route('web-api.nodos-tecnoparque', $form.centro_formacion_id)} message={errors.nodo_tecnoparque_id} required />
                    </div>
                </div>
            </fieldset>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                    <InfoMessage message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad del proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea label="Resumen del proyecto" maxlength="40000" id="resumen" error={errors.resumen} bind:value={$resumenForm.resumen} on:input={() => syncColumnLong('resumen', $resumenForm)} required />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen_regional" value="Complemento - Resumen ejecutivo regional" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="resumen_regional" error={errors.resumen_regional} bind:value={$formResumenRegional.resumen_regional} on:input={() => syncColumnLong('resumen_regional', $formResumenRegional)} required />

                    {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                        {#each tp.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.resumen_regional_comentario ? evaluacion.tp_evaluacion.resumen_regional_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <fieldset disabled={authUser.id == 122 ? undefined : true}>
                <div class="mt-44 grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                        <InfoMessage
                            message="Presenta las investigaciones, innovaciones o desarrollos tecnológicos que se han realizado a nivel internacional, nacional, departamental o municipal en el marco de la temática de la propuesta del proyecto; que muestran la pertinencia del proyecto, citar toda la información consignada utilizando normas APA última edición. De igual forma, relacionar los proyectos ejecutados en vigencias anteriores (incluir códigos SGPS), si el proyecto corresponde a la continuidad de proyectos SENNOVA."
                        />
                    </div>
                    <div>
                        <Textarea label="Antecedentes" maxlength="40000" id="antecedentes" error={errors.antecedentes} bind:value={$formAntecedentes.antecedentes} on:input={() => syncColumnLong('antecedentes', $formAntecedentes)} required />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes_regional" value="Complemento - Antecedentes regional" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="antecedentes_regional" error={errors.antecedentes_regional} bind:value={$formAntecedentesRegional.antecedentes_regional} on:input={() => syncColumnLong('antecedentes_regional', $formAntecedentesRegional)} required />

                    {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                        {#each tp.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.antecedentes_regional_comentario ? evaluacion.tp_evaluacion.antecedentes_regional_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="retos_oportunidades" value="Descripción de retos y prioridades locales y regionales en los cuales el Tecnoparque tiene impacto" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="retos_oportunidades" error={errors.retos_oportunidades} bind:value={$formRetosOportunidades.retos_oportunidades} on:input={() => syncColumnLong('retos_oportunidades', $formRetosOportunidades)} required />

                    {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                        {#each tp.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.retos_oportunidades_comentario ? evaluacion.tp_evaluacion.retos_oportunidades_comentario : 'Sin recomendación'}</p>
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
                    <Textarea maxlength="40000" id="pertinencia_territorio" error={errors.pertinencia_territorio} bind:value={$formPertinenciaTerritorio.pertinencia_territorio} on:input={() => syncColumnLong('pertinencia_territorio', $formPertinenciaTerritorio)} required />

                    {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                        {#each tp.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.pertinencia_territorio_comentario ? evaluacion.tp_evaluacion.pertinencia_territorio_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <fieldset disabled={authUser.id == 122 ? undefined : true}>
                <div class="mt-44 grid grid-cols-1">
                    <div>
                        <Label required class="mb-4" labelFor="marco_conceptual" value="Marco conceptual" />
                        <InfoMessage message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones." />
                    </div>
                    <div>
                        <Textarea label="" maxlength="40000" id="marco_conceptual" error={errors.marco_conceptual} bind:value={$formMarcoConceptual.marco_conceptual} on:input={() => syncColumnLong('marco_conceptual', $formMarcoConceptual)} required />
                    </div>
                </div>
            </fieldset>

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
                    <Textarea label="Descripción del beneficio en los municipios" maxlength="40000" id="impacto_municipios" error={errors.impacto_municipios} bind:value={$formImpactoMunicipios.impacto_municipios} on:input={() => syncColumnLong('impacto_municipios', $formImpactoMunicipios)} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div />
                <div>
                    {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                        {#each tp.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.municipios_comentario ? evaluacion.tp_evaluacion.municipios_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="impacto_centro_formacion" value="Impacto en el centro de formación" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="impacto_centro_formacion" error={errors.impacto_centro_formacion} bind:value={$formImpactoCentroFormacion.impacto_centro_formacion} on:input={() => syncColumnLong('impacto_centro_formacion', $formImpactoCentroFormacion)} required />

                    {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                        {#each tp.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.impacto_centro_formacion_comentario ? evaluacion.tp_evaluacion.impacto_centro_formacion_comentario : 'Sin recomendación'}</p>
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
                    <Textarea maxlength="40000" id="bibliografia" error={errors.bibliografia} bind:value={$formBibliografia.bibliografia} on:input={() => syncColumnLong('bibliografia', $formBibliografia)} required />

                    {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                        {#each tp.proyecto.evaluaciones as evaluacion, i}
                            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                    <div class="flex text-orangered-900 font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Recomendación del evaluador COD-{evaluacion.id}:
                                    </div>
                                    <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.bibliografia_comentario ? evaluacion.tp_evaluacion.bibliografia_comentario : 'Sin recomendación'}</p>
                                </div>
                            {/if}
                        {/each}
                    {/if}
                </div>
            </div>

            {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                <hr class="mt-10 mb-10" />
                <h1>Ortografía</h1>
                {#each tp.proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.ortografia_comentario ? evaluacion.tp_evaluacion.ortografia_comentario : 'Sin recomendación'}</p>
                        </div>
                    {/if}
                {/each}
            {/if}

            {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                <hr class="mt-10 mb-10" />
                <h1>Redacción</h1>
                {#each tp.proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.redaccion_comentario ? evaluacion.tp_evaluacion.redaccion_comentario : 'Sin recomendación'}</p>
                        </div>
                    {/if}
                {/each}
            {/if}

            {#if isSuperAdmin || tp.proyecto.mostrar_recomendaciones}
                <hr class="mt-10 mb-10" />
                <h1>Normas APA</h1>
                {#each tp.proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            <p class="whitespace-pre-line">{evaluacion.tp_evaluacion.normas_apa_comentario ? evaluacion.tp_evaluacion.normas_apa_comentario : 'Sin recomendación'}</p>
                        </div>
                    {/if}
                {/each}
            {/if}
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center justify-between sticky bottom-0">
            {#if isSuperAdmin || (checkPermission(authUser, [19]) && tp.proyecto.modificable == true && convocatoria.fase == 1)}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin || (checkPermission(authUser, [18, 19]) && tp.proyecto.modificable == true)}
                <small>{tp.updated_at}</small>

                <LoadingButton loading={sending} class="btn-indigo" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={proyectoDialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/proyecto.png'} alt="Proyecto" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {tp.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                {#if JSON.parse(tp.proyecto.estado)?.requiereSubsanar == true && convocatoria.fase == 3}
                    <h1 class="text-center mb-4 font-black text-2xl">Este proyecto requiere ser subsanado</h1>
                    <p>Por favor revise las observaciones de los evaluadores en cada uno de los campos y secciones.</p>
                    <p>Importante: Se ha agregado una sección de <strong>Comentarios generales</strong>, revise si hay comentarios de los evaluadores y por favor escriba la respectiva respuesta.</p>
                {:else if JSON.parse(tp.proyecto.estado)?.requiereSubsanar == false && convocatoria.fase == 3}
                    <div>
                        <h1 class="text-center mb-4 font-black text-2xl">Este proyecto no requiere subsanación</h1>
                        <p><strong>Tenga en cuenta:</strong> El estado final de los proyectos se conocerá cuando finalice la etapa de segunda evaluación (Estado Rechazado, pre – aprobado con observaciones y Preaprobado). Fechas segunda evaluación: 22 de octubre (13:00 HH) al 3 de noviembre (23:59 HH).</p>
                    </div>
                {:else}
                    <h1 class="text-center mt-4 mb-4">Para terminar el numeral de <strong>Generalidades</strong> por favor continue diligenciando los siguientes campos:</h1>
                    <p class="text-center mb-4">Si ya están completos omita esta información.</p>
                    <ul class="list-disc">
                        <li>Resumen</li>
                        <li>Complemento - Resumen</li>
                        <li>Antecedentes</li>
                        <li>Complemento - Antecedentes regional</li>
                        <li>Justificación</li>
                        <li>Retos y prioridades locales</li>
                        <li>Justificación y pertinencia en el territorio</li>
                        <li>Marco conceptual</li>
                        <li>Bibliografía</li>
                        <li>Número de aprendices beneficiados</li>
                        <li>Nombre de los municipios beneficiados</li>
                        <li>Articulación con la media</li>
                        <li>Impacto en el centro de formación</li>
                    </ul>
                {/if}
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (proyectoDialogOpen = false)} variant={null}>Omitir</Button>
                {#if tp.proyecto.modificable}
                    <Button variant="raised" on:click={(event) => (proyectoDialogOpen = false)} on:click={() => Inertia.visit('#nodo_tecnoparque_id')}>Continuar diligenciando</Button>
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

            <form on:submit|preventDefault={destroy} id="delete-tp" class="mt-10 mb-28">
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente este proyecto" class="mb-4" />
                <Password id="password" class="w-full" bind:value={$deleteForm.password} error={errors.password} required autocomplete="current-password" />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="delete-tp">Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
