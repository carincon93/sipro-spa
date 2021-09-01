<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { onMount } from 'svelte'
    import { _ } from 'svelte-i18n'
    import { createPopper } from '@popperjs/core'

    import Label from '@/Shared/Label'
    import Input from '@/Shared/Input'
    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import InfoMessage from '@/Shared/InfoMessage'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Switch from '@/Shared/Switch'
    import EvaluationStepper from '@/Shared/EvaluationStepper'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyecto
    export let efectosDirectos
    export let causasDirectas
    export let tiposImpacto
    export let tipoProyectoA
    export let segundaEvaluacion

    let cantidadCeldasActividades = 3
    if (proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82) {
        cantidadCeldasActividades = 3
    } else if (proyecto.codigo_linea_programatica == 68) {
        cantidadCeldasActividades = 14
    } else if (proyecto.codigo_linea_programatica == 70) {
        cantidadCeldasActividades = 10
    }

    let sending = false
    let dialogOpen = false
    let dialogTitle
    let codigo

    $: $title = 'Árbol de objetivos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    /**
     * Mensaje para ítems bloqueados
     */
    let generalInfoType = 0
    let showGeneralInfo = false
    function showGeneralInfoDialog(tipo) {
        dialogTitle = 'Información general'
        dialogOpen = true

        generalInfoType = tipo
        showGeneralInfo = true
    }

    /**
     * Impactos
     */
    let impactoInfo = {
        id: 0,
        efecto_indirecto_id: 0,
        descripcion: '',
        tipo: '',
        resultado_id: '',
    }

    let showImpactoForm = false
    let impactoEfectoIndirecto
    function showImpactDialog(efectoIndirecto, efectoIndirectoId, resultadoId) {
        codigo = efectoIndirecto.impacto.id != null ? 'RES-' + resultadoId + '-IMP-' + efectoIndirecto.impacto.id : ''
        dialogTitle = 'Impacto'
        dialogOpen = true
        showImpactoForm = true
        impactoEfectoIndirecto = efectoIndirecto.descripcion

        if (efectoIndirecto.impacto != null) {
            impactoInfo.id = efectoIndirecto.impacto.id
            impactoInfo.descripcion = efectoIndirecto.impacto.descripcion
            impactoInfo.tipo = {
                value: efectoIndirecto.impacto.tipo,
                label: tiposImpacto.find((item) => item.value == efectoIndirecto.impacto.tipo)?.label,
            }
            impactoInfo.efecto_indirecto_id = efectoIndirecto.impacto.efectoIndirectoId
            impactoInfo.resultado_id = resultadoId
        } else {
            impactoInfo.id = null
            impactoInfo.efecto_indirecto_id = efectoIndirectoId
            impactoInfo.resultado_id = ''
        }
    }

    /**
     * Resultados
     */
    let resultadoInfo = {
        descripcion: '',
        trl: '',
    }

    let showResultadoForm = false
    let descripcionObjetivoEspecifico = []
    let resultadoEfectoDirecto
    $: causasDirectas
    function showResultadoDialog(efectoDirecto, resultado) {
        let objetivoEspecifico = causasDirectas.find((causaDirecta) => causaDirecta.objetivo_especifico.id == resultado.objetivo_especifico_id)
        descripcionObjetivoEspecifico = {
            descripcion: objetivoEspecifico.objetivo_especifico?.descripcion ? objetivoEspecifico.objetivo_especifico?.descripcion : 'Sin información registrada',
            numero: objetivoEspecifico.objetivo_especifico?.numero,
        }
        codigo = 'RES-' + resultado.id
        dialogTitle = 'Resultado'
        dialogOpen = true
        showResultadoForm = true
        resultadoInfo.id = resultado.id
        resultadoInfo.descripcion = resultado.descripcion
        resultadoInfo.trl = resultado.trl
        resultadoEfectoDirecto = efectoDirecto.descripcion ?? 'Sin información registrada'
    }

    /**
     * Objetivo general
     */
    let objetivoGeneralInfo = {
        objetivo_general: proyecto.objetivo_general,
    }

    let showObjetivoGeneralForm = false
    let problemaCentral
    function showObjetivoGeneralDialog() {
        dialogTitle = 'Objetivo general'
        dialogOpen = true
        showObjetivoGeneralForm = true
        problemaCentral = proyecto.problema_central
        objetivoGeneralInfo.objetivo_general = proyecto.objetivo_general
    }

    /**
     * Objetivos específicos
     */
    let objetivoEspecificoInfo = {
        id: 0,
        descripcion: '',
        numero: 0,
    }

    let showObjetivoEspecificoForm = false
    let causaDirectaObjetivoEspecifico
    function showObjetivoEspecificoDialog(causaDirecta, numero) {
        codigo = 'OBJ-ESP-' + causaDirecta.objetivo_especifico.id
        dialogTitle = causaDirecta.objetivo_especifico.numero
        dialogOpen = true
        showObjetivoEspecificoForm = true
        objetivoEspecificoInfo.id = causaDirecta.objetivo_especifico.id
        objetivoEspecificoInfo.descripcion = causaDirecta.objetivo_especifico.descripcion
        objetivoEspecificoInfo.numero = numero
        causaDirectaObjetivoEspecifico = causaDirecta.descripcion ?? 'Sin información registrada'
    }

    /**
     * Actividades
     */
    let actividadInfo = {
        id: 0,
        causa_indirecta_id: 0,
        objetivo_especifico_id: 0,
        descripcion: '',
    }

    let showActividadForm = false
    let actividadCausaIndirecta
    function showActivityDialog(causaIndirecta, objetivoEspecifico) {
        codigo = causaIndirecta.actividad.id != null ? 'OBJ-ESP-' + objetivoEspecifico + '-ACT-' + causaIndirecta.actividad.id : ''
        dialogTitle = 'Actividad'
        dialogOpen = true
        showActividadForm = true
        actividadInfo.id = causaIndirecta.actividad.id
        actividadInfo.causa_indirecta_id = causaIndirecta.actividad.causa_indirecta_id
        actividadInfo.objetivo_especifico_id = objetivoEspecifico
        actividadInfo.descripcion = causaIndirecta.actividad.descripcion
        actividadCausaIndirecta = causaIndirecta.descripcion ?? 'Sin información registrada'
    }

    function closeDialog() {
        dialogOpen = false
    }

    let formEstrategiaRegionalEvaluacion = useForm({
        objetivos_puntaje: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.objetivos_puntaje : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.objetivos_puntaje : null,
        objetivos_comentario: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.objetivos_comentario : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.objetivos_comentario : null,
        objetivos_requiere_comentario: evaluacion.idi_evaluacion ? (evaluacion.idi_evaluacion.objetivos_comentario == null ? true : false) : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.objetivos_comentario : null,

        resultados_puntaje: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.resultados_puntaje : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.resultados_puntaje : null,
        resultados_comentario: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.resultados_comentario : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.resultados_comentario : null,
        resultados_requiere_comentario: evaluacion.idi_evaluacion ? (evaluacion.idi_evaluacion.resultados_comentario == null ? true : false) : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.resultados_comentario : null,
    })
    function submitEstrategiaRegionalEvaluacion() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $formEstrategiaRegionalEvaluacion.put(route('convocatorias.evaluaciones.arbol-objetivos.guardar-evaluacion', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    onMount(() => {
        const impacto = document.querySelector('#impacto-tooltip-placement')
        const impactoTooltip = document.querySelector('#impacto-tooltip')
        const arrowImpact = document.querySelector('#arrow-impacto')

        const resultado = document.querySelector('#resultado-tooltip-placement')
        const resultadoTooltip = document.querySelector('#resultado-tooltip')
        const arrowResultado = document.querySelector('#arrow-resultado')

        const objetivoGeneral = document.querySelector('#objetivo-general-tooltip-placement')
        const objetivoGeneralTooltip = document.querySelector('#objetivo-general-tooltip')
        const arrowObjetivoGeneral = document.querySelector('#arrow-objetivo-general')

        const objetivoEspecifico = document.querySelector('#objetivo-especifico-tooltip-placement')
        const objetivoEspecificoTooltip = document.querySelector('#objetivo-especifico-tooltip')
        const arrowObjetivoEspecifico = document.querySelector('#arrow-objetivo-especifico')

        const actividad = document.querySelector('#actividad-tooltip-placement')
        const actividadTooltip = document.querySelector('#actividad-tooltip')
        const arrowActividad = document.querySelector('#arrow-actividad')

        let tooltips = [
            {
                element: {
                    target: impacto,
                    tooltip: impactoTooltip,
                    arrow: arrowImpact,
                },
            },
            {
                element: {
                    target: resultado,
                    tooltip: resultadoTooltip,
                    arrow: arrowResultado,
                },
            },
            {
                element: {
                    target: objetivoGeneral,
                    tooltip: objetivoGeneralTooltip,
                    arrow: arrowObjetivoGeneral,
                },
            },
            {
                element: {
                    target: objetivoEspecifico,
                    tooltip: objetivoEspecificoTooltip,
                    arrow: arrowObjetivoEspecifico,
                },
            },
            {
                element: {
                    target: actividad,
                    tooltip: actividadTooltip,
                    arrow: arrowActividad,
                },
            },
        ]

        tooltips.map(function (tooltip) {
            createPopper(tooltip.element.target, tooltip.element.tooltip, {
                placement: 'left',
                modifiers: [
                    {
                        name: 'arrow',
                        options: {
                            element: tooltip.element.arrow,
                        },
                    },
                ],
            })
        })
    })
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    {#if proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
        <a class="flex bg-orangered-900 bottom-0 fixed hover:bg-orangered-600 mb-4 px-6 py-2 rounded-3xl shadow-2xl text-center text-white z-50" href="#evaluacion">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Ir a la evaluación
        </a>
    {/if}

    <h1 class="text-3xl mt-24 mb-8 text-center">Árbol de objetivos</h1>
    <p class="text-center">El árbol de objetivos se obtiene al transformar en positivo el árbol de problemas manteniendo la misma estructura y niveles de jerarquía.</p>

    <div class="mt-16">
        <div class="flex mb-14">
            {#each efectosDirectos as efectoDirecto, i}
                {#if (proyecto.codigo_linea_programatica == 68 && tipoProyectoA && i < 3) || (proyecto.codigo_linea_programatica == 68 && !tipoProyectoA) || proyecto.codigo_linea_programatica != 68}
                    <div class="flex-1{proyecto.codigo_linea_programatica == 70 && efectoDirecto.efectos_indirectos.length == 0 ? ' flex items-end' : ''}">
                        <!-- Impactos -->
                        {#if i == 0}
                            <div id="impacto-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                                <small>Impactos</small>
                                <div id="arrow-impacto" class="arrow" data-popper-arrow />
                            </div>
                        {/if}
                        <div class="flex mb-14" id={i == 0 ? 'impacto-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                            {#each efectoDirecto.efectos_indirectos as efectoIndirecto}
                                {#if (proyecto.codigo_linea_programatica == 70 && efectoIndirecto.descripcion != ' ') || proyecto.codigo_linea_programatica != 70}
                                    <div class="flex-1 resultados relative">
                                        <div
                                            on:click={showImpactDialog(efectoIndirecto, efectoIndirecto.id, efectoDirecto.resultados[0].id)}
                                            class="{efectoIndirecto.descripcion != null && i % 2 == 0
                                                ? 'bg-orangered-400 hover:bg-orangered-500'
                                                : efectoIndirecto.descripcion == null && i % 2 == 0
                                                ? 'bg-gray-300 hover:bg-gray-400'
                                                : efectoIndirecto.descripcion != null && i % 2 != 0
                                                ? 'bg-orangered-500 hover:bg-orangered-600'
                                                : 'bg-gray-400 hover:bg-gray-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5"
                                        >
                                            <p class="paragraph-ellipsis text-xs node text-white line-height-1-24">
                                                {#if efectoIndirecto.impacto}
                                                    <small class="title block font-bold mb-2">
                                                        RES{#each efectoDirecto.resultados as { id }}
                                                            -{id}
                                                        {/each}
                                                        -IMP-
                                                        {efectoIndirecto.impacto.id}
                                                    </small>
                                                    {#if efectoIndirecto.impacto.descripcion != null && efectoIndirecto.impacto.descripcion.length > 0}
                                                        {efectoIndirecto.impacto.descripcion}
                                                    {/if}
                                                {/if}
                                            </p>
                                        </div>
                                    </div>
                                {/if}
                            {/each}
                            {#if proyecto.codigo_linea_programatica != 70}
                                {#each { length: 3 - efectoDirecto.efectos_indirectos.length } as _empty}
                                    <div on:click={() => showGeneralInfoDialog(1)} class="flex-1 resultados relative">
                                        <div class="h-36 bg-gray-300 rounded shadow-lg hover:bg-gray-400 cursor-pointer mr-1.5 p-2.5">
                                            <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                        </div>
                                    </div>
                                {/each}
                            {/if}
                        </div>
                        <!-- Resultado -->
                        {#if i == 0}
                            <div id="resultado-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                                <small>Resultados</small>
                                <div id="arrow-resultado" class="arrow" data-popper-arrow />
                            </div>
                        {/if}
                        <div
                            class="{i == 0 ? 'resultados' : (proyecto.codigo_linea_programatica == 68 && i == 1) || (proyecto.codigo_linea_programatica == 68 && i == 2) ? 'resultados-line' : proyecto.codigo_linea_programatica == 68 && i == 3 ? 'resultados-line-4' : 'resultados'} relative flex-1 flex flex-wrap"
                            id={i == 0 ? 'resultado-tooltip-placement' : ''}
                            aria-describedby={i == 0 ? 'tooltip' : ''}
                        >
                            {#each efectoDirecto.resultados as resultado, j}
                                <div
                                    on:click={showResultadoDialog(efectoDirecto, resultado)}
                                    class="{efectoDirecto.descripcion != null && i % 2 == 0
                                        ? 'bg-orangered-400 hover:bg-orangered-500'
                                        : efectoDirecto.descripcion == null && i % 2 == 0
                                        ? 'bg-gray-300 hover:bg-gray-400'
                                        : efectoDirecto.descripcion != null && i % 2 != 0
                                        ? 'bg-orangered-500 hover:bg-orangered-600'
                                        : 'bg-gray-400 hover:bg-gray-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5{proyecto.codigo_linea_programatica == 68 ? ' mb-4' : ''}"
                                    style="flex: 1 0 33.333%"
                                >
                                    <p class="paragraph-ellipsis text-white text-sm line-height-1-24">
                                        <small class="title block font-bold mb-2">RES-{resultado.id}</small>
                                        {#if resultado.descripcion != null && resultado.descripcion.length > 0}
                                            {resultado.descripcion}
                                        {/if}
                                    </p>
                                </div>
                            {/each}
                        </div>
                    </div>
                {/if}
            {/each}
        </div>

        <!-- Objetivo general -->
        <div id="objetivo-general-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
            <small>Objetivo general</small>
            <div id="arrow-objetivo-general" class="arrow" data-popper-arrow />
        </div>
        <div class="objetivo-general relative" id="objetivo-general-tooltip-placement" aria-describedby="tooltip">
            <div on:click={showObjetivoGeneralDialog} class="{proyecto.objetivo_general != null ? 'bg-orangered-400 hover:bg-orangered-500' : 'bg-gray-300 hover:bg-gray-400'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                {#if proyecto.objetivo_general != null && proyecto.objetivo_general.length > 0}
                    <p class="paragraph-ellipsis text-white text-sm line-height-1-24">
                        {proyecto.objetivo_general}
                    </p>
                {/if}
            </div>
        </div>

        <div class="flex mt-14">
            {#each causasDirectas as causaDirecta, i}
                {#if (proyecto.codigo_linea_programatica == 68 && tipoProyectoA && i < 3) || (proyecto.codigo_linea_programatica == 68 && !tipoProyectoA) || proyecto.codigo_linea_programatica != 68}
                    <div class="flex-1">
                        <!-- Objetivo específico -->
                        {#if i == 0}
                            <div id="objetivo-especifico-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                                <small class="block line-height-1-24">
                                    Objetivos <br /> específicos
                                </small>
                                <div id="arrow-objetivo-especifico" class="arrow" data-popper-arrow />
                            </div>
                        {/if}
                        <div class="objetivo-especificos relative flex-1" id={i == 0 ? 'objetivo-especifico-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                            <div
                                on:click={showObjetivoEspecificoDialog(causaDirecta, i + 1)}
                                class="{causaDirecta.descripcion != null && i % 2 == 0
                                    ? 'bg-orangered-400 hover:bg-orangered-500'
                                    : causaDirecta.descripcion == null && i % 2 == 0
                                    ? 'bg-gray-300 hover:bg-gray-400'
                                    : causaDirecta.descripcion != null && i % 2 != 0
                                    ? 'bg-orangered-500 hover:bg-orangered-600'
                                    : 'bg-gray-400 hover:bg-gray-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5"
                            >
                                <p class="paragraph-ellipsis text-white text-sm line-height-1-24">
                                    <small class="title block font-bold mb-2">
                                        OBJ-ESP-{causaDirecta.objetivo_especifico.id}
                                    </small>
                                    {#if causaDirecta.objetivo_especifico.descripcion != null && causaDirecta.objetivo_especifico.descripcion.length > 0}
                                        {causaDirecta.objetivo_especifico.descripcion}
                                    {/if}
                                </p>
                            </div>
                        </div>
                        <!-- Actividades -->
                        {#if i == 0}
                            <div id="actividad-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                                <small>Actividades</small>
                                <div id="arrow-actividad" class="arrow" data-popper-arrow />
                            </div>
                        {/if}
                        <div class="flex flex-wrap objetivo-especificos relative mt-14" id={i == 0 ? 'actividad-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                            {#each causaDirecta.causas_indirectas as causaIndirecta}
                                {#if (proyecto.codigo_linea_programatica == 70 && causaIndirecta.actividad.descripcion != ' ') || proyecto.codigo_linea_programatica != 70}
                                    <div class="mb-4" style="flex: 1 0 33.333%">
                                        <div
                                            on:click={showActivityDialog(causaIndirecta, causaDirecta.objetivo_especifico.id)}
                                            class="{causaIndirecta.descripcion != null && i % 2 == 0
                                                ? 'bg-orangered-400 hover:bg-orangered-500'
                                                : causaIndirecta.descripcion == null && i % 2 == 0
                                                ? 'bg-gray-300 hover:bg-gray-400'
                                                : causaIndirecta.descripcion != null && i % 2 != 0
                                                ? 'bg-orangered-500 hover:bg-orangered-600'
                                                : 'bg-gray-400 hover:bg-gray-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5"
                                        >
                                            <p class="paragraph-ellipsis text-xs node text-white line-height-1-24">
                                                {#if causaIndirecta.actividad}
                                                    <small class="title block font-bold mb-2">
                                                        OBJ-ESP-{causaDirecta.objetivo_especifico.id}-ACT-{causaIndirecta.actividad.id}
                                                    </small>
                                                    {#if causaIndirecta.actividad.descripcion != null && causaIndirecta.actividad.descripcion.length > 0}
                                                        {causaIndirecta.actividad.descripcion}
                                                    {/if}
                                                {/if}
                                            </p>
                                        </div>
                                    </div>
                                {/if}
                            {/each}
                            {#if proyecto.codigo_linea_programatica != 68 && proyecto.codigo_linea_programatica != 70}
                                {#each { length: cantidadCeldasActividades - causaDirecta.causas_indirectas.length } as _empty, j}
                                    <div id="{j}_empty_actividad" on:click={() => showGeneralInfoDialog(2)} class="mb-4" style="flex: 1 0 33.333%">
                                        <div class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                            <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                        </div>
                                    </div>
                                {/each}
                            {:else if proyecto.codigo_linea_programatica == 68 && i == 0}
                                {#each { length: 14 - causaDirecta.causas_indirectas.length } as _empty, j}
                                    <div id="{j}_empty_actividad" on:click={() => showGeneralInfoDialog(2)} class="mb-4" style="flex: 1 0 33.333%">
                                        <div class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                            <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                        </div>
                                    </div>
                                {/each}
                            {:else if proyecto.codigo_linea_programatica == 68 && i == 1}
                                {#each { length: 3 - causaDirecta.causas_indirectas.length } as _empty, j}
                                    <div id="{j}_empty_actividad" on:click={() => showGeneralInfoDialog(2)} class="mb-4" style="flex: 1 0 33.333%">
                                        <div class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                            <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                        </div>
                                    </div>
                                {/each}
                            {:else if proyecto.codigo_linea_programatica == 68 && i == 2}
                                {#each { length: 3 - causaDirecta.causas_indirectas.length } as _empty, j}
                                    <div id="{j}_empty_actividad" on:click={() => showGeneralInfoDialog(2)} class="mb-4" style="flex: 1 0 33.333%">
                                        <div class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                            <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                        </div>
                                    </div>
                                {/each}
                            {:else if proyecto.codigo_linea_programatica == 68 && i == 3}
                                {#each { length: 2 - causaDirecta.causas_indirectas.length } as _empty, j}
                                    <div id="{j}_empty_actividad" on:click={() => showGeneralInfoDialog(2)} class="mb-4" style="flex: 1 0 33.333%">
                                        <div class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                            <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                        </div>
                                    </div>
                                {/each}
                            {/if}
                        </div>
                    </div>
                {/if}
            {/each}
        </div>
    </div>

    {#if proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
        <hr class="mt-10 mb-10" />

        <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>

        <div class="mt-16">
            <form on:submit|preventDefault={submitEstrategiaRegionalEvaluacion}>
                <InfoMessage>
                    <h1 class="text-2xl text-center mb-10">Resultados</h1>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li><strong>Puntaje: 0 a 4</strong> No son claros los beneficios y ventajas de los resultados en el marco del proyecto, no se generan por el desarrollo de las actividades y tampoco evidencian la materialización de la solución propuesta para resolver el problema del proyecto.</li>
                        <li><strong>Puntaje: 5 a 7</strong> Los resultados se generan por el desarrollo de las actividades y se identifican sus ventajas y beneficios para dar solución al problema identificado. Son susceptibles de mejora para evidenciar de forma clara la materialización de la solución propuesta.</li>
                        <li><strong>Puntaje: 8 a 9</strong> Los resultados se generan por el desarrollo de las actividades, sus beneficios y ventajas sobresalen en pro de dar una solución contundente al problema identificado y evidencian de forma clara la materialización de la solución propuesta.</li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="resultados_puntaje" value="Puntaje (Máximo 9)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="resultados_puntaje"
                        type="number"
                        input$step="1"
                        input$min="0"
                        input$max="9"
                        class="mt-1"
                        bind:value={$formEstrategiaRegionalEvaluacion.resultados_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.resultados_puntaje}
                    />

                    <div class="mt-4">
                        <p>¿Los resultados son correctos? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formEstrategiaRegionalEvaluacion.resultados_requiere_comentario} />
                        {#if $formEstrategiaRegionalEvaluacion.resultados_requiere_comentario == false}
                            <Textarea disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="resultados_comentario" bind:value={$formEstrategiaRegionalEvaluacion.resultados_comentario} error={errors.resultados_comentario} required />
                        {/if}
                    </div>

                    <hr class="mt-10 mb-10" />
                    <h1 class="text-2xl text-center mb-10">Árbol de objetivos / Objetivo general / Objetivos específicos</h1>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li><strong>Puntaje: 0 a 7</strong> El objetivo general y los objetivos específicos (solución identificada) no dan respuesta al problema, tampo estan relacionados entre ellos, y los objetivos específicos no presentan una secuencia lógica para alcanzar el objetivo general.</li>
                        <li><strong>Puntaje: 8 a 13</strong> El objetivo general y los objetivos específicos (solución identificada) dan respuesta parcial al problema; hay relación entre ellos y los objetivos específicos están formulados como una secuencia lógica para alcanzar el objetivo general, pero susceptibles de ajustes y mejoras.</li>
                        <li><strong>Puntaje: 14 a 15</strong> El objetivo general y los objetivos específicos (solución identificada) dan respuesta integral al problema; hay relación entre ellos y los objetivos específicos están formulados como una secuencia lógica para alcanzar el objetivo general.</li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="objetivos_puntaje" value="Puntaje (Máximo 15)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="objetivos_puntaje"
                        type="number"
                        input$step="1"
                        input$min="0"
                        input$max="15"
                        class="mt-1"
                        bind:value={$formEstrategiaRegionalEvaluacion.objetivos_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.objetivos_puntaje}
                    />

                    {#if segundaEvaluacion?.objetivos_comentario}
                        <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{segundaEvaluacion?.objetivos_comentario}</p>
                    {/if}
                    <div class="mt-4">
                        <p>¿El árbol de objetivos, objetivo general o los objetivos específicos son correctos? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formEstrategiaRegionalEvaluacion.objetivos_requiere_comentario} />
                        {#if $formEstrategiaRegionalEvaluacion.objetivos_requiere_comentario == false}
                            <Textarea disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="objetivos_comentario" bind:value={$formEstrategiaRegionalEvaluacion.objetivos_comentario} error={errors.objetivos_comentario} required />
                        {/if}
                    </div>
                </InfoMessage>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>
    {/if}

    <!-- Dialog -->
    <Dialog bind:open={dialogOpen} id="arbol-objetivos">
        <div slot="title">
            <div class="mb-10 text-center">
                <div class="text-primary">
                    {dialogTitle}
                </div>
                {#if codigo}
                    <small class="block text-primary-light">
                        Código: {codigo}
                    </small>
                {/if}
            </div>
        </div>
        <div slot="content">
            {#if showActividadForm}
                <InfoMessage class="mb-4">
                    Se debe evidenciar que la descripción de las actividades se realice de manera secuencial y de forma coherente con los productos a las cuales están asociadas para alcanzar el logro de cada uno de los objetivos específicos.
                    <br />
                    Las actividades deben redactarse en verbos en modo infinitivo, es decir, en palabras que expresen acciones y terminen en “ar”, “er” o “ir”, estos no deben hacer referencia a objetivos específicos o generales. Algunos ejemplos de verbos inadecuados para describir actividades son: apropiar, asegurar, colaborar, consolidar, desarrollar, fomentar, fortalecer, garantizar, implementar,
                    impulsar, mejorar, movilizar, proponer, promover, entre otros.
                </InfoMessage>
                <p class="block font-medium mb-2 text-gray-700 text-sm">Causa indirecta</p>
                <p class="mb-10 whitespace-pre-line">
                    {actividadCausaIndirecta}
                </p>
                <form id="actividad-form">
                    <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                        <div>
                            <Textarea disabled label="Descripción" maxlength="15000" id="descripcion-actividad" value={actividadInfo.descripcion} />
                        </div>
                    </fieldset>
                </form>
            {:else if showObjetivoEspecificoForm}
                {#if causaDirectaObjetivoEspecifico != 'Sin información registrada'}
                    {#if proyecto.codigo_linea_programatica == 68}
                        <InfoMessage class="mb-4">
                            <p>
                                Los objetivos específicos son los medios cuantificables que llevarán al cumplimiento del objetivo general. Estos surgen de pasar a positivo las causas directas identificadas en el árbol de problemas.
                                <br />
                                La redacción de los objetivos específicos deberá iniciar con un verbo en modo infinitivo, es decir, con una palabra terminada en "ar", "er" o "ir". La estructura del objetivo debe contener al menos tres componentes: (1) la acción que se espera realizar, (2) el objeto sobre el cual recae la acción y (3) elementos adicionales de contexto o descriptivos.
                            </p>
                        </InfoMessage>
                    {/if}
                    <form id="objetivo-especifico-form">
                        <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                            <p class="block font-medium mb-2 text-gray-700 text-sm">Causa directa</p>

                            <p class="mb-20 whitespace-pre-line">
                                {causaDirectaObjetivoEspecifico}
                            </p>
                            <div>
                                <Textarea disabled label="Descripción" maxlength="40000" id="descripcion-objetivo-especifico" value={objetivoEspecificoInfo.descripcion} />
                            </div>
                        </fieldset>
                    </form>
                {:else}
                    <InfoMessage class="mb-4" message="Debe generar primero la causa directa en el árbol de problemas" />
                {/if}
            {:else if showObjetivoGeneralForm}
                <form id="objetivo-general-form">
                    <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                        {#if proyecto.codigo_linea_programatica == 68}
                            <InfoMessage class="mb-4">
                                <p>
                                    El objetivo general se origina al convertir en positivo el problema principal (tronco) identificado en el árbol de problemas.
                                    <br />
                                    La redacción deberá iniciar con un verbo en modo infinitivo, es decir, con una palabra terminada en "ar", "er" o "ir". La estructura del objetivo debe contener al menos tres componentes: (1) la acción que se espera realizar, (2) el objeto sobre el cual recae la acción y (3) elementos adicionales de contexto o descriptivos.
                                    <br />
                                    El objetivo general debe expresar el fin concreto del proyecto en correspondencia directa con el título del proyecto y la pregunta de la formulación del problema, el cual debe ser claro, medible, alcanzable y consistente con el proyecto que está formulando. Debe responde al ¿Qué?, ¿Cómo? y el ¿Para qué?
                                </p>
                            </InfoMessage>
                        {:else}
                            <InfoMessage class="mb-2" message="Establece que pretende alcanzar la investigación. Se inicia con un verbo en modo infinitivo, es medible y alcanzable. Responde al Qué, Cómo y el Para qué" />
                        {/if}
                        <p class="block font-medium mb-2 text-gray-700 text-sm">Problema central</p>

                        <p class="mb-20 whitespace-pre-line">
                            {problemaCentral ? problemaCentral : 'Sin información registrada'}
                        </p>
                        <div>
                            <Label class="mb-4" labelFor="objetivo-general" value="Objetivo general" />
                            <Textarea disabled label="Descripción" maxlength="10000" id="objetivo-general" value={objetivoGeneralInfo.objetivo_general} />
                        </div>
                    </fieldset>
                </form>
            {:else if showResultadoForm}
                {#if resultadoEfectoDirecto != 'Sin información registrada'}
                    <InfoMessage class="mb-4">Se debe evidenciar que los resultados son directos, medibles y cuantificables que se alcanzarán con el desarrollo de cada uno de los objetivos específicos del proyecto.</InfoMessage>
                    <p class="block font-medium mb-2 text-gray-700 text-sm">Efecto directo</p>
                    <p class="mb-20 whitespace-pre-line">
                        {resultadoEfectoDirecto}
                    </p>

                    <p class="block font-medium mb-2 text-gray-700 text-sm">
                        {descripcionObjetivoEspecifico.numero}
                    </p>
                    <p class="mb-20 whitespace-pre-line">
                        {descripcionObjetivoEspecifico.descripcion}
                    </p>

                    <form id="resultado-form">
                        <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                            {#if proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
                                <div class="mb-10">
                                    <Input disabled label="TRL" id="trl" type="number" input$max="9" input$min="1" class="block w-full" value={resultadoInfo.trl} />
                                </div>
                            {/if}
                            <div class="mb-20">
                                <Textarea disabled label="Descripción" maxlength="1000" id="descripcion-resultado" value={resultadoInfo.descripcion} />
                            </div>
                        </fieldset>
                    </form>
                {:else}
                    <InfoMessage class="mb-4" message="Debe generar primero el efecto directo en el árbol de problemas" />
                {/if}
            {:else if showImpactoForm}
                <InfoMessage class="mb-4">Se busca medir la contribución potencial que genera el proyecto en los siguientes ámbitos: tecnológico, económico, ambiental, social, centro de formación, sector productivo</InfoMessage>

                <p class="block font-medium mb-2 text-gray-700 text-sm">Efecto indirecto</p>

                <p class="mt-4 whitespace-pre-line">
                    {impactoEfectoIndirecto}
                </p>

                <form id="impacto-form">
                    <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                        <div class="mt-4">
                            <Label labelFor="tipo-impacto" value="Tipo" />
                            <Select disabled={true} id="tipo-impacto" items={tiposImpacto} bind:selectedValue={impactoInfo.tipo} autocomplete="off" placeholder="Seleccione un tipo" />
                            {#if impactoInfo.tipo?.value == 4}
                                <InfoMessage
                                    message="Se busca minimizar y/o evitar los impactos negativos sobre el medio ambiente, tales como contaminación del aire, contaminación de corrientes de agua naturales, ruido, destrucción del paisaje, separación de comunidades que operan como unidades, etc. Por otro lado, se busca identificar diversas acciones de impacto ambiental positivo, tales como: producción limpia y sustentable, protección medioambiental, uso de residuos y reciclaje."
                                />
                            {:else if impactoInfo.tipo?.value == 2}
                                <InfoMessage
                                    message="Se busca medir la contribución potencial del proyecto en cualquiera de los siguientes ámbitos: generación y aplicación de nuevos conocimientos y tecnologías, desarrollo de infraestructura científico- tecnológica, articulación de diferentes proyectos para lograr un objetivo común, mejoramiento de la infraestructura, desarrollo de capacidades de gestión tecnológica."
                                />
                            {:else if impactoInfo.tipo?.value == 5}
                                <InfoMessage message="Se busca medir la contribución potencial del proyecto al desarrollo de la comunidad Sena (Aprendices, instructores y a la formación)" />
                            {:else if impactoInfo.tipo?.value == 6}
                                <InfoMessage message="Se busca medir la contribución potencial del proyecto al desarrollo del sector productivo en concordancia con el sector priorizado de Colombia Productiva y a la mesa técnica a la que pertenece el proyecto." />
                            {/if}
                        </div>
                        <div class="mt-4">
                            <Textarea disabled label="Descripción" maxlength="10000" id="descripcion-impacto" value={impactoInfo.descripcion} />
                        </div>
                    </fieldset>
                </form>
            {:else if showGeneralInfo}
                {#if generalInfoType == 1}
                    <p>Para poder editar este impacto, primero debe generar el efecto indirecto en el árbol de problemas.</p>
                {/if}
                {#if generalInfoType == 2}
                    <p>Para poder editar esta actividad, primero debe generar la causa indirecta en el árbol de problemas.</p>
                {/if}
            {/if}
        </div>
        <div slot="actions" class="block flex w-full">
            <Button on:click={closeDialog} type="button" variant={null}>Cancelar</Button>
        </div>
    </Dialog>
</AuthenticatedLayout>

<style>
    .resultados.relative.flex-1:before {
        content: '';
        bottom: -57px;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 57px;
        background: #ff906e;
    }

    .resultados-line:before {
        content: '';
        bottom: -536px;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 552px;
        background: #ff906e;
    }

    .resultados-line-4:before {
        content: '';
        bottom: -696px;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 710px;
        background: #ff906e;
    }

    .objetivo-especificos.relative:before {
        content: '';
        top: -55px;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 55px;
        background: #ff906e;
    }

    .line-height-1-24 {
        line-height: 1.2;
    }

    .tooltip {
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 13px;
        position: relative;
        z-index: 9999;
    }

    .arrow,
    .arrow::before {
        position: absolute;
        width: 8px;
        height: 8px;
        background: inherit;
    }

    .arrow {
        visibility: hidden;
    }

    .arrow::before {
        visibility: visible;
        content: '';
        transform: rotate(45deg);
    }

    .tooltip[data-popper-placement^='left'] > .arrow {
        right: -4px;
    }

    .node {
        line-height: 1.24;
    }

    :global(#arbol-objetivos-dialog .mdc-dialog__surface) {
        width: 750px;
        max-width: calc(100vw - 32px) !important;
    }

    :global(#arbol-objetivos-dialog .mdc-dialog__content) {
        padding-top: 40px !important;
    }

    :global(#arbol-objetivos-dialog .mdc-dialog__title) {
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        margin-bottom: 0;
    }
</style>
