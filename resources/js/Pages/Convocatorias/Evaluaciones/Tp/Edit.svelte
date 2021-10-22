<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'
    import { onMount } from 'svelte'

    import Button from '@/Shared/Button'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import SelectMulti from '@/Shared/SelectMulti'
    import Dialog from '@/Shared/Dialog'
    import Switch from '@/Shared/Switch'

    export let errors
    export let convocatoria
    export let tp
    export let tpEvaluacion
    export let proyectoMunicipios

    $: $title = tp ? tp.titulo : null

    let sending = false
    let dialogSegundaEvaluacion = convocatoria.fase == 4 ? true : false
    let proyectoDialogOpen = tpEvaluacion.evaluacion.clausula_confidencialidad == false ? true : false

    let municipios = []
    let codigoLineaProgramatica

    $: if (codigoLineaProgramatica) {
        tpInfo.codigo_linea_programatica = codigoLineaProgramatica.codigo
    } else {
        tpInfo.codigo_linea_programatica = tp.codigo_linea_programatica
    }

    const groupBy = (item) => item.group

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let tpInfo = {
        centro_formacion_id: tp.proyecto.centro_formacion_id,
        linea_programatica_id: tp.proyecto.linea_programatica_id,
        fecha_inicio: tp.fecha_inicio,
        fecha_finalizacion: tp.fecha_finalizacion,
        max_meses_ejecucion: tp.max_meses_ejecucion,
        resumen: tp.resumen,
        resumen_regional: tp.resumen_regional,
        antecedentes: tp.antecedentes,
        justificacion_problema: tp.justificacion_problema,
        antecedentes_regional: tp.antecedentes_regional,
        retos_oportunidades: tp.retos_oportunidades,
        pertinencia_territorio: tp.pertinencia_territorio,
        marco_conceptual: tp.marco_conceptual,
        municipios: proyectoMunicipios.length > 0 ? proyectoMunicipios : null,
        impacto_municipios: tp.impacto_municipios,
        impacto_centro_formacion: tp.impacto_centro_formacion,
        bibliografia: tp.bibliografia,
        codigo_linea_programatica: null,
        nodo_tecnoparque_id: tp.nodo_tecnoparque_id,
    }

    let regional
    $: whitelistInstitucionesEducativas = []
    $: if (regional) {
        axios
            .get('https://www.datos.gov.co/resource/cfw5-qzt5.json?cod_dane_departamento=' + regional?.codigo)
            .then(function (response) {
                // handle success
                response.datp.map((item) => {
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

    let form = useForm({
        clausula_confidencialidad: tpEvaluacion.evaluacion.clausula_confidencialidad,
        fecha_ejecucion_comentario: tpEvaluacion.fecha_ejecucion_comentario,
        fecha_ejecucion_requiere_comentario: tpEvaluacion.fecha_ejecucion_comentario == null ? true : false,
        resumen_regional_comentario: tpEvaluacion.resumen_regional_comentario,
        resumen_regional_requiere_comentario: tpEvaluacion.resumen_regional_comentario == null ? true : false,
        antecedentes_regional_comentario: tpEvaluacion.antecedentes_regional_comentario,
        antecedentes_regional_requiere_comentario: tpEvaluacion.antecedentes_regional_comentario == null ? true : false,
        municipios_comentario: tpEvaluacion.municipios_comentario,
        municipios_requiere_comentario: tpEvaluacion.municipios_comentario == null ? true : false,
        impacto_centro_formacion_comentario: tpEvaluacion.impacto_centro_formacion_comentario,
        impacto_centro_formacion_requiere_comentario: tpEvaluacion.impacto_centro_formacion_comentario == null ? true : false,
        retos_oportunidades_comentario: tpEvaluacion.retos_oportunidades_comentario,
        retos_oportunidades_requiere_comentario: tpEvaluacion.retos_oportunidades_comentario == null ? true : false,
        pertinencia_territorio_comentario: tpEvaluacion.pertinencia_territorio_comentario,
        pertinencia_territorio_requiere_comentario: tpEvaluacion.pertinencia_territorio_comentario == null ? true : false,
        instituciones_comentario: tpEvaluacion.instituciones_comentario,
        instituciones_requiere_comentario: tpEvaluacion.instituciones_comentario == null ? true : false,

        bibliografia_comentario: tpEvaluacion.bibliografia_comentario,
        bibliografia_requiere_comentario: tpEvaluacion.bibliografia_comentario == null ? true : false,

        ortografia_comentario: tpEvaluacion.ortografia_comentario,
        ortografia_requiere_comentario: tpEvaluacion.ortografia_comentario == null ? true : false,
        redaccion_comentario: tpEvaluacion.redaccion_comentario,
        redaccion_requiere_comentario: tpEvaluacion.redaccion_comentario == null ? true : false,
        normas_apa_comentario: tpEvaluacion.normas_apa_comentario,
        normas_apa_requiere_comentario: tpEvaluacion.normas_apa_comentario == null ? true : false,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && tpEvaluacion.evaluacion.finalizado == false && tpEvaluacion.evaluacion.habilitado == true && tpEvaluacion.evaluacion.modificable == true)) {
            $form.put(route('convocatorias.tp-evaluaciones.update', [convocatoria.id, tpEvaluacion.id]), {
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

    $: if (tpInfo.fecha_inicio && tpInfo.fecha_finalizacion) {
        tpInfo.max_meses_ejecucion = monthDiff(tpInfo.fecha_inicio, tpInfo.fecha_finalizacion)
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} evaluacion={tpEvaluacion.evaluacion} proyecto={tp.proyecto} />

    <form on:submit|preventDefault={submit}>
        <div class="mt-44">
            <p class="text-center">Fecha de ejecución</p>
            <small class="text-red-400 block text-center"> * Campo obligatorio </small>
            <div class="mt-4 flex items-start justify-around">
                <div class="mt-4 flex">
                    <Label labelFor="fecha_inicio" value="Del" />
                    <div class="ml-4">
                        <input disabled id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_tp} max={convocatoria.max_fecha_finalizacion_proyectos_tp} bind:value={tpInfo.fecha_inicio} />
                    </div>
                </div>
                <div class="mt-4 flex">
                    <Label labelFor="fecha_finalizacion" value="hasta" />
                    <div class="ml-4">
                        <input disabled id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_tp} max={convocatoria.max_fecha_finalizacion_proyectos_tp} bind:value={tpInfo.fecha_finalizacion} />
                    </div>
                </div>
            </div>

            <InfoMessage>
                <div class="mt-4">
                    <p>¿Las fechas son correctas? Por favor seleccione si Cumple o No cumple</p>
                    <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.fecha_ejecucion_requiere_comentario} />
                    {#if $form.fecha_ejecucion_requiere_comentario == false}
                        <Textarea
                            disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined}
                            label="Comentario"
                            class="mt-4"
                            maxlength="40000"
                            id="fecha_ejecucion_comentario"
                            bind:value={$form.fecha_ejecucion_comentario}
                            error={errors.fecha_ejecucion_comentario}
                            required
                        />
                    {/if}
                </div>
            </InfoMessage>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label class="mb-4" labelFor="linea_programatica_id" value="Código dependencia presupuestal (SIIF)" />
            </div>
            <div>
                <DynamicList disabled={true} id="linea_programatica_id" bind:value={tpInfo.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas', 1)} classes="evaluacion-select min-h" placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} />
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
            </div>
            <div>
                {tp.proyecto.centro_formacion.nombre}
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label class="mb-4" labelFor="nodo_tecnoparque_id" value="Nodo Tecnoparque" />
            </div>
            <div>
                <DynamicList classes="evaluacion-select" disabled={true} id="nodo_tecnoparque_id" bind:value={tpInfo.nodo_tecnoparque_id} placeholder="Seleccione un nodo Tecnoparque" routeWebApi={route('web-api.nodos-tecnoparque', tpInfo.centro_formacion_id)} message={errors.nodo_tecnoparque_id} />
            </div>
        </div>

        <div class="mt-40 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                <InfoMessage message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad del proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto." />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="resumen" bind:value={tpInfo.resumen} />
            </div>
        </div>

        <div class="mt-40 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="resumen_regional" value="Complemento - Resumen ejecutivo regional" />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="resumen_regional" bind:value={tpInfo.resumen_regional} />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿El resumen ejecutivo regional es correcto? Por favor seleccione si Cumple o No cumple</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.resumen_regional_requiere_comentario} />
                        {#if $form.resumen_regional_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="resumen_regional_comentario"
                                bind:value={$form.resumen_regional_comentario}
                                error={errors.resumen_regional_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                <InfoMessage
                    message="Presenta las investigaciones, innovaciones o desarrollos tecnológicos que se han realizado a nivel internacional, nacional, departamental o municipal en el marco de la temática de la propuesta del proyecto; que muestran la pertinencia del proyecto, citar toda la información consignada utilizando normas APA última edición. De igual forma, relacionar los proyectos ejecutados en vigencias anteriores (incluir códigos SGPS), si el proyecto corresponde a la continuidad de proyectos SENNOVA."
                />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="antecedentes" bind:value={tpInfo.antecedentes} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="antecedentes_regional" value="Complemento - Antecedentes regional" />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="antecedentes_regional" bind:value={tpInfo.antecedentes_regional} />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿Los antecedentes regionales son correctos? Por favor seleccione si Cumple o No cumple</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.antecedentes_regional_requiere_comentario} />
                        {#if $form.antecedentes_regional_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="antecedentes_regional_comentario"
                                bind:value={$form.antecedentes_regional_comentario}
                                error={errors.antecedentes_regional_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="justificacion_problema" value="Justificación" />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="justificacion_problema" bind:value={tpInfo.justificacion_problema} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="retos_oportunidades" value="Descripción de retos y prioridades locales y regionales en los cuales el Tecnoparque tiene impacto" />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="retos_oportunidades" bind:value={tpInfo.retos_oportunidades} />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿Los retos y prioridades locales son correctos? Por favor seleccione si Cumple o No cumple</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.retos_oportunidades_requiere_comentario} />
                        {#if $form.retos_oportunidades_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="retos_oportunidades_comentario"
                                bind:value={$form.retos_oportunidades_comentario}
                                error={errors.retos_oportunidades_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="pertinencia_territorio" value="Justificación y pertinencia en el territorio" />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="pertinencia_territorio" bind:value={tpInfo.pertinencia_territorio} />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿La justificación y pertinencia en el territorio es correcta? Por favor seleccione si Cumple o No cumple</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.pertinencia_territorio_requiere_comentario} />
                        {#if $form.pertinencia_territorio_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="pertinencia_territorio_comentario"
                                bind:value={$form.pertinencia_territorio_comentario}
                                error={errors.pertinencia_territorio_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="marco_conceptual" value="Marco conceptual" />
                <InfoMessage message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones." />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="marco_conceptual" bind:value={tpInfo.marco_conceptual} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label class="mb-4" for="municipios" value="Nombre de los municipios beneficiados" />
            </div>
            <div>
                <SelectMulti classes="evaluacion-select-multi" disabled={true} id="municipios" bind:selectedValue={tpInfo.municipios} items={municipios} isMulti={true} placeholder="Buscar municipios" />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="impacto_municipios" value="Descripción del beneficio en los municipios" />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="impacto_municipios" bind:value={tpInfo.impacto_municipios} />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div />
            <div>
                <InfoMessage>
                    <div class="mt-4">
                        <p>¿Los municipios y la descripción del beneficio son correctos? Por favor seleccione si Cumple o No cumple</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.municipios_requiere_comentario} />
                        {#if $form.municipios_requiere_comentario == false}
                            <Textarea disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="municipios_comentario" bind:value={$form.municipios_comentario} error={errors.municipios_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-40 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="impacto_centro_formacion" value="Impacto en el centro de formación" />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="impacto_centro_formacion" bind:value={tpInfo.impacto_centro_formacion} />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿El impacto en el centro de formación es correcto? Por favor seleccione si Cumple o No cumple</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.impacto_centro_formacion_requiere_comentario} />
                        {#if $form.impacto_centro_formacion_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="impacto_centro_formacion_comentario"
                                bind:value={$form.impacto_centro_formacion_comentario}
                                error={errors.impacto_centro_formacion_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Última edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
            </div>
            <div>
                <Textarea disabled maxlength="40000" id="bibliografia" bind:value={tpInfo.bibliografia} />

                <InfoMessage>
                    <div class="mt-4">
                        <p>¿La bibliografia es correcta? Por favor seleccione si Cumple o No cumple</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.bibliografia_requiere_comentario} />
                        {#if $form.bibliografia_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="bibliografia_comentario"
                                bind:value={$form.bibliografia_comentario}
                                error={errors.bibliografia_comentario}
                                required
                            />
                        {/if}
                    </div>
                </InfoMessage>
            </div>
        </div>

        <hr class="mt-10 mb-10" />

        <h1>Ortografía</h1>
        <InfoMessage>
            <div class="mt-4">
                <p>¿La ortografía es correcta? Por favor seleccione si Cumple o No cumple.</p>
                <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.ortografia_requiere_comentario} />
                {#if $form.ortografia_requiere_comentario == false}
                    <Textarea disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="ortografia_comentario" bind:value={$form.ortografia_comentario} error={errors.ortografia_comentario} required />
                {/if}
            </div>
        </InfoMessage>

        <hr class="mt-10 mb-10" />
        <h1>Redacción</h1>
        <InfoMessage>
            <div class="mt-4">
                <p>¿La redacción es correcta? Por favor seleccione si Cumple o No cumple.</p>
                <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.redaccion_requiere_comentario} />
                {#if $form.redaccion_requiere_comentario == false}
                    <Textarea disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="redaccioncomentario" bind:value={$form.redaccion_comentario} error={errors.redaccion_comentario} />
                {/if}
            </div>
        </InfoMessage>

        <hr class="mt-10 mb-10" />
        <h1>Normas APA</h1>
        <InfoMessage>
            <div class="mt-4">
                <p>¿Las normas APA son correctas? Por favor seleccione si Cumple o No cumple.</p>
                <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} bind:checked={$form.normas_apa_requiere_comentario} />
                {#if $form.normas_apa_requiere_comentario == false}
                    <Textarea disabled={isSuperAdmin ? undefined : tpEvaluacion.evaluacion.finalizado == true || tpEvaluacion.evaluacion.habilitado == false || tpEvaluacion.evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="normas_apa_comentario" bind:value={$form.normas_apa_comentario} error={errors.normas_apa_comentario} required />
                {/if}
            </div>
        </InfoMessage>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center justify-between sticky bottom-0">
            {#if isSuperAdmin || (checkRole(authUser, [11]) && tpEvaluacion.evaluacion.finalizado == false && tpEvaluacion.evaluacion.habilitado == true && tpEvaluacion.evaluacion.modificable == true)}
                {#if $form.clausula_confidencialidad}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-green-500">Ha aceptado la cláusula de confidencialidad</span>
                {:else}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-red-500">No ha aceptado la cláusula de confidencialidad</span>
                {/if}

                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={proyectoDialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/feedback.png'} alt="Evaluación" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {tp.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Cláusula de confidencialidad</h1>
                <p class="mb-4">
                    Al hacer clic en el botón Aceptar, dejo constancia del tratamiento confidencial que daré a la información relacionada con el proceso de evaluación que incluye, sin limitarse a esta, la información sobre proyectos, centros de formación, formuladores, autores y coautores de proyectos, resultados del proceso de evaluación en sus dos etapas. Por tanto, declaro que me abstendré de
                    usar o divulgar para cualquier fin y por cualquier medio la información enunciada.
                </p>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (($form.clausula_confidencialidad = true), (proyectoDialogOpen = false))} variant={null}>Aceptar</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={dialogSegundaEvaluacion} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/feedback.png'} alt="Evaluación" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {tp.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Importante</h1>

                <p>Antes de iniciar a la segunda evaluación por favor diríjase a la sección <strong>Comentarios generales</strong> y verifique si el proponente hizo alguna aclaración sobre algún ítem.</p>

                {#if tp.proyecto.pdf_versiones}
                    <hr class="mx-4 block" />
                    <p class="mt-4">También revise la versión del proyecto en .pdf para ir verificando los cambios realizados en los diferentes campos.</p>
                    <h1 class="text-center mt-4 mb-4">Version del proyecto (.pdf)</h1>
                    <ul>
                        {#each tp.proyecto.pdf_versiones as version}
                            <li>
                                {#if version.estado == 1}
                                    <a class="text-white underline" href={route('convocatorias.proyectos.version', [convocatoria.id, tp.id, version.version])}> {version.version}.pdf - Descargar</a>
                                    <small class="block">{version.created_at}</small>
                                {/if}
                            </li>
                        {/each}
                    </ul>
                {/if}
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogSegundaEvaluacion = false)} variant={null}>Aceptar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
