<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { onMount } from 'svelte'
    import { _ } from 'svelte-i18n'

    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Input from '@/Shared/Input'
    import Switch from '@/Shared/Switch'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import EvaluationStepper from '@/Shared/EvaluationStepper'

    import { createPopper } from '@popperjs/core'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyecto
    export let efectosDirectos
    export let causasDirectas
    export let segundaEvaluacion

    let dialogTitle
    let codigo
    let sending = false
    let dialogOpen = false
    let cantidadCeldasCausasIndirectas = 3

    if (proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82) {
        cantidadCeldasCausasIndirectas = 3
    } else if (proyecto.codigo_linea_programatica == 68) {
        cantidadCeldasCausasIndirectas = 14
    } else if (proyecto.codigo_linea_programatica == 70) {
        cantidadCeldasCausasIndirectas = 10
    }

    $: $title = 'Árbol de problemas'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    /**
     * Efectos indirectos
     */
    let efectoIndirectoInfo = {
        id: 0,
        efecto_directo_id: 0,
        descripcion: '',
    }

    let showEfectoIndirectoForm = false
    function showEfectoIndirectoDialog(efectoIndirecto, efectoDirectoId) {
        codigo = efectoIndirecto?.id != null ? 'EFE-' + efectoIndirecto.efecto_directo_id + '-IND-' + efectoIndirecto.id : ''
        dialogTitle = 'Efecto indirecto'
        showEfectoIndirectoForm = true
        dialogOpen = true

        if (efectoIndirecto != null) {
            efectoIndirectoInfo.id = efectoIndirecto.id
            efectoIndirectoInfo.descripcion = efectoIndirecto.descripcion
            efectoIndirectoInfo.efecto_directo_id = efectoIndirecto.efecto_directo_id
        } else {
            efectoIndirectoInfo.id = null
            efectoIndirectoInfo.descripcion = null
            efectoIndirectoInfo.efecto_directo_id = efectoDirectoId
        }
    }

    /**
     * Efectos directos
     */
    let efectoDirectoInfo = {
        id: 0,
        descripcion: '',
    }

    let showEfectoDirectoForm = false
    function showEfectoDirectoDialog(efectoDirecto) {
        codigo = 'EFE-' + efectoDirecto.id
        dialogTitle = 'Efecto directo'
        showEfectoDirectoForm = true
        dialogOpen = true
        efectoDirectoInfo.descripcion = efectoDirecto.descripcion
        efectoDirectoInfo.id = efectoDirecto.id
    }

    /**
     * Problema central
     */
    let problemaCentralInfo = {
        antecedentes: proyecto.antecedentes,
        marco_conceptual: proyecto.marco_conceptual,
        identificacion_problema: proyecto.identificacion_problema,
        problema_central: proyecto.problema_central,
        justificacion_problema: proyecto.justificacion_problema,
        pregunta_formulacion_problema: proyecto.pregunta_formulacion_problema,
    }

    let showProblemaCentralForm = false
    function showProblemaCentralDialog() {
        dialogTitle = 'Problema central'
        showProblemaCentralForm = true
        dialogOpen = true
        problemaCentralInfo.identificacion_problema = proyecto.identificacion_problema
        problemaCentralInfo.problema_central = proyecto.problema_central
        problemaCentralInfo.justificacion_problema = proyecto.justificacion_problema
        problemaCentralInfo.pregunta_formulacion_problema = proyecto.pregunta_formulacion_problema
    }

    /**
     * Causas directas
     */
    let causaDirectaInfo = {
        id: 0,
        descripcion: '',
    }

    let showCausaDirectaForm = false
    function showCausaDirectaDialog(causaDirecta) {
        codigo = 'CAU-' + causaDirecta.id
        dialogTitle = 'Causa directa'
        showCausaDirectaForm = true
        dialogOpen = true
        causaDirectaInfo.id = causaDirecta.id
        causaDirectaInfo.descripcion = causaDirecta.descripcion
    }

    /**
     * Causas indirectas
     */
    let causaIndirectaInfo = {
        id: 0,
        causa_directa_id: 0,
        descripcion: '',
    }

    let showCausaIndirectaForm = false
    function showCausaIndirectaDialog(causaIndirecta, causaDirectaId) {
        codigo = causaIndirecta?.id != null ? 'CAU-' + causaIndirecta.causa_directa_id + '-IND-' + causaIndirecta.id : ''
        dialogTitle = 'Causa indirecta'
        showCausaIndirectaForm = true
        dialogOpen = true
        if (causaIndirecta != null) {
            causaIndirectaInfo.id = causaIndirecta.id
            causaIndirectaInfo.descripcion = causaIndirecta.descripcion
            causaIndirectaInfo.causa_directa_id = causaIndirecta.causa_directa_id
        } else {
            causaIndirectaInfo.id = null
            causaIndirectaInfo.descripcion = null
            causaIndirectaInfo.causa_directa_id = causaDirectaId
        }
    }

    function closeDialog() {
        dialogOpen = false
    }

    let formEstrategiaRegionalEvaluacion = useForm({
        problema_central_puntaje: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.problema_central_puntaje : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.problema_central_puntaje : null,
        problema_central_comentario: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.problema_central_comentario : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.problema_central_comentario : null,
        problema_central_requiere_comentario: evaluacion.idi_evaluacion ? (evaluacion.idi_evaluacion.problema_central_comentario == null ? true : false) : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.problema_central_comentario : null,
    })
    function submitEstrategiaRegionalEvaluacion() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $formEstrategiaRegionalEvaluacion.put(route('convocatorias.evaluaciones.arbol-problemas.guardar-evaluacion', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    onMount(() => {
        const efectoIndirecto = document.querySelector('#efecto-indirecto-tooltip-placement')
        const efectoIndirectoTooltip = document.querySelector('#efecto-indirecto-tooltip')
        const arrowEfectoIndirecto = document.querySelector('#arrow-efecto-indirecto')

        const efectoDirecto = document.querySelector('#efecto-directo-tooltip-placement')
        const efectoDirectoTooltip = document.querySelector('#efecto-directo-tooltip')
        const arrowEfectoDirecto = document.querySelector('#arrow-efecto-directo')

        const problemaCentral = document.querySelector('#problema-central-tooltip-placement')
        const problemaCentralTooltip = document.querySelector('#problema-central-tooltip')
        const arrowProblemaCentral = document.querySelector('#arrow-problema-central')

        const causaDirecta = document.querySelector('#causa-directa-tooltip-placement')
        const causaDirectaTooltip = document.querySelector('#causa-directa-tooltip')
        const arrowDirectCause = document.querySelector('#arrow-causa-directa')

        const causaIndirecta = document.querySelector('#causa-indirecta-tooltip-placement')
        const causaIndirectaTooltip = document.querySelector('#causa-indirecta-tooltip')
        const arrowCausaIndirecta = document.querySelector('#arrow-causa-indirecta')

        let tooltips = [
            {
                element: {
                    target: efectoIndirecto,
                    tooltip: efectoIndirectoTooltip,
                    arrow: arrowEfectoIndirecto,
                },
            },
            {
                element: {
                    target: efectoDirecto,
                    tooltip: efectoDirectoTooltip,
                    arrow: arrowEfectoDirecto,
                },
            },
            {
                element: {
                    target: problemaCentral,
                    tooltip: problemaCentralTooltip,
                    arrow: arrowProblemaCentral,
                },
            },
            {
                element: {
                    target: causaDirecta,
                    tooltip: causaDirectaTooltip,
                    arrow: arrowDirectCause,
                },
            },
            {
                element: {
                    target: causaIndirecta,
                    tooltip: causaIndirectaTooltip,
                    arrow: arrowCausaIndirecta,
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
        <hr class="mt-10 mb-10" />
        <a class="flex bg-orangered-900 bottom-0 fixed hover:bg-orangered-600 mb-4 px-6 py-2 rounded-3xl shadow-2xl text-center text-white z-50" href="#evaluacion">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Ir a la evaluación
        </a>
    {/if}

    <h1 class="text-3xl mt-24 mb-8 text-center">Árbol de problemas</h1>
    <p class="text-center">Diligenciar el árbol de problemas iniciando con el problema principal (tronco), sus causas (raíces) y efectos (ramas).</p>

    <div class="mt-16">
        <!-- Efectos -->
        <div class="flex mb-14">
            {#each efectosDirectos as efectoDirecto, i}
                <div class="flex-1{proyecto.codigo_linea_programatica == 70 && efectoDirecto.efectos_indirectos.length == 0 ? ' flex items-end' : ''}">
                    {#if i == 0}
                        <!-- Efectos indirectos -->
                        <div id="efecto-indirecto-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                            <small>Efectos indirectos</small>
                            <div id="arrow-efecto-indirecto" class="arrow" data-popper-arrow />
                        </div>
                    {/if}
                    <div class="flex mb-14" id={i == 0 ? 'efecto-indirecto-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                        {#each efectoDirecto.efectos_indirectos as efectoIndirecto}
                            {#if (proyecto.codigo_linea_programatica == 70 && efectoIndirecto.descripcion != ' ') || proyecto.codigo_linea_programatica != 70}
                                <div class="flex-1 efectos-directos relative">
                                    <div
                                        on:click={showEfectoIndirectoDialog(efectoIndirecto, efectoDirecto.id)}
                                        class="{efectoIndirecto.descripcion != null && i % 2 == 0 ? 'bg-indigo-300 hover:bg-indigo-400' : efectoIndirecto.descripcion == null && i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : efectoIndirecto.descripcion != null && i % 2 != 0 ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5"
                                    >
                                        <p class="paragraph-ellipsis text-xs text-white line-height-1-24">
                                            <small class="title block font-bold mb-2">EFE-{efectoDirecto.id}-IND-{efectoIndirecto.id}</small>
                                            {#if efectoIndirecto.descripcion != null && efectoIndirecto.descripcion.length > 0}
                                                {efectoIndirecto.descripcion}
                                            {/if}
                                        </p>
                                    </div>
                                </div>
                            {/if}
                        {/each}
                        {#if proyecto.codigo_linea_programatica != 70}
                            {#each { length: 3 - efectoDirecto.efectos_indirectos.length } as _empty}
                                <div class="flex-1 efectos-directos relative" on:click={showEfectoIndirectoDialog(null, efectoDirecto.id)}>
                                    <div class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                        <p class="text-sm text-white line-height-1-24" />
                                    </div>
                                </div>
                            {/each}
                        {/if}
                    </div>

                    {#if i == 0}
                        <!-- Efecto directo -->
                        <div id="efecto-directo-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                            <small>Efectos directos</small>
                            <div id="arrow-efecto-directo" class="arrow" data-popper-arrow />
                        </div>
                    {/if}
                    <!-- Efecto directo -->
                    <div class="efectos-directos relative flex-1" id={i == 0 ? 'efecto-directo-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                        <div
                            on:click={showEfectoDirectoDialog(efectoDirecto)}
                            class="{efectoDirecto.descripcion != null && i % 2 == 0 ? 'bg-indigo-300 hover:bg-indigo-400' : efectoDirecto.descripcion == null && i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : efectoDirecto.descripcion != null && i % 2 != 0 ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5"
                        >
                            <p class="paragraph-ellipsis text-white text-sm line-height-1-24">
                                <small class="title block font-bold mb-2">EFE-{efectoDirecto.id}</small>
                                {#if efectoDirecto.descripcion != null && efectoDirecto.descripcion.length > 0}
                                    {efectoDirecto.descripcion}
                                {/if}
                            </p>
                        </div>
                    </div>
                </div>
            {/each}
        </div>

        <!-- Problema central -->
        <div id="problema-central-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
            <small class="block">Problema <br /> central</small>
            <div id="arrow-problema-central" class="arrow" data-popper-arrow />
        </div>
        <div class="problema-central relative" id="problema-central-tooltip-placement" aria-describedby="tooltip">
            <div on:click={showProblemaCentralDialog} class="h-36 {proyecto.problema_central != null ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-indigo-300 hover:bg-indigo-400'} rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                {#if proyecto.problema_central != null && proyecto.problema_central.length > 0}
                    <p class="paragraph-ellipsis text-white text-sm line-height-1-24">
                        {proyecto.problema_central}
                    </p>
                {/if}
            </div>
        </div>

        <!-- Causas -->
        <div class="flex mt-14">
            {#each causasDirectas as causaDirecta, i}
                <div class="flex-1">
                    <!-- Causa directa -->
                    {#if i == 0}
                        <div id="causa-directa-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                            <small>Causas directas</small>
                            <div id="arrow-causa-directa" class="arrow" data-popper-arrow />
                        </div>
                    {/if}
                    <div class="causas-directas-line relative flex-1" id={i == 0 ? 'causa-directa-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                        <div
                            on:click={showCausaDirectaDialog(causaDirecta)}
                            class="{causaDirecta.descripcion != null && i % 2 == 0 ? 'bg-indigo-300 hover:bg-indigo-400' : causaDirecta.descripcion == null && i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : causaDirecta.descripcion != null && i % 2 != 0 ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5"
                        >
                            <p class="paragraph-ellipsis text-white text-sm line-height-1-24">
                                <small class="title block font-bold mb-2">CAU-{causaDirecta.id}</small>
                                {#if causaDirecta.descripcion != null && causaDirecta.descripcion.length > 0}
                                    {causaDirecta.descripcion}
                                {/if}
                            </p>
                        </div>
                    </div>

                    {#if i == 0}
                        <div id="causa-indirecta-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                            <small>Causas indirectas</small>
                            <div id="arrow-causa-indirecta" class="arrow" data-popper-arrow />
                        </div>
                    {/if}
                    <!-- Causas indirectas -->
                    <div class="flex flex-wrap causas-directas-line relative mt-14" id={i == 0 ? 'causa-indirecta-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                        {#each causaDirecta.causas_indirectas as causaIndirecta}
                            {#if (proyecto.codigo_linea_programatica == 70 && causaIndirecta.descripcion != ' ') || proyecto.codigo_linea_programatica != 70}
                                <div class="mb-4 relative" style="flex: 1 0 33.333%">
                                    <div
                                        on:click={showCausaIndirectaDialog(causaIndirecta, causaDirecta.id)}
                                        class="{causaIndirecta.descripcion != null && i % 2 == 0 ? 'bg-indigo-300 hover:bg-indigo-400' : causaIndirecta.descripcion == null && i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : causaIndirecta.descripcion != null && i % 2 != 0 ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5"
                                    >
                                        <p class="paragraph-ellipsis text-white text-xs line-height-1-24">
                                            <small class="title block font-bold mb-2">CAU-{causaDirecta.id}-IND-{causaIndirecta.id}</small>
                                            {#if causaIndirecta.descripcion != null && causaIndirecta.descripcion.length > 0}
                                                {causaIndirecta.descripcion}
                                            {/if}
                                        </p>
                                    </div>
                                </div>
                            {/if}
                        {/each}

                        {#if proyecto.codigo_linea_programatica != 68 && proyecto.codigo_linea_programatica != 70}
                            {#each { length: cantidadCeldasCausasIndirectas - causaDirecta.causas_indirectas.length } as _empty}
                                <div class="mb-4 relative" style="flex: 1 0 33.333%">
                                    <div on:click={showCausaIndirectaDialog(null, causaDirecta.id)} class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                        <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                    </div>
                                </div>
                            {/each}
                        {:else if proyecto.codigo_linea_programatica == 68 && i == 0}
                            {#each { length: 14 - causaDirecta.causas_indirectas.length } as _empty}
                                <div class="mb-4 relative" style="flex: 1 0 33.333%">
                                    <div on:click={showCausaIndirectaDialog(null, causaDirecta.id)} class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                        <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                    </div>
                                </div>
                            {/each}
                        {:else if proyecto.codigo_linea_programatica == 68 && i == 1}
                            {#each { length: 3 - causaDirecta.causas_indirectas.length } as _empty}
                                <div class="mb-4 relative" style="flex: 1 0 33.333%">
                                    <div on:click={showCausaIndirectaDialog(null, causaDirecta.id)} class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                        <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                    </div>
                                </div>
                            {/each}
                        {:else if proyecto.codigo_linea_programatica == 68 && i == 2}
                            {#each { length: 3 - causaDirecta.causas_indirectas.length } as _empty}
                                <div class="mb-4 relative" style="flex: 1 0 33.333%">
                                    <div on:click={showCausaIndirectaDialog(null, causaDirecta.id)} class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                        <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                    </div>
                                </div>
                            {/each}
                        {:else if proyecto.codigo_linea_programatica == 68 && i == 3}
                            {#each { length: 2 - causaDirecta.causas_indirectas.length } as _empty}
                                <div class="mb-4 relative" style="flex: 1 0 33.333%">
                                    <div on:click={showCausaIndirectaDialog(null, causaDirecta.id)} class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                        <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                    </div>
                                </div>
                            {/each}
                        {/if}
                    </div>
                </div>
            {/each}
        </div>
    </div>

    {#if proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
        <hr class="mt-10 mb-10" />

        <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>
        <div class="mt-16">
            <div class="mt-44 ">
                <div>
                    <p class="mb-4">Antecedentes</p>
                </div>
                <div>
                    <Textarea disabled label="Antecedentes" maxlength="40000" id="antecedentes" value={problemaCentralInfo.antecedentes} />
                </div>
            </div>

            <div class="mt-44 ">
                <div>
                    <p class="mb-4">Identificación y descripción del problema</p>
                </div>
                <div>
                    <Textarea disabled label="Identificación y descripción del problema" maxlength="40000" id="identificacion_problema" value={problemaCentralInfo.identificacion_problema} />
                </div>
            </div>

            <div class="mt-44 ">
                <div>
                    <p class="mb-4">Justificación</p>
                </div>
                <div>
                    <Textarea disabled label="Justificación" maxlength="40000" id="justificacion_problema" value={problemaCentralInfo.justificacion_problema} />
                </div>
            </div>

            <div class="mt-44 ">
                <div>
                    <p class="mb-4">Marco conceptual</p>
                </div>
                <div>
                    <Textarea disabled label="Marco conceptual" maxlength="20000" id="marco_conceptual" value={problemaCentralInfo.marco_conceptual} />
                </div>
            </div>

            <form on:submit|preventDefault={submitEstrategiaRegionalEvaluacion}>
                <InfoMessage>
                    <h1>Criterios de evaluacion</h1>
                    <ul class="list-disc p-4">
                        <li><strong>Puntaje: 0 a 7</strong> El problema no ha sido identificado a partir de los instrumentos de planeación regional como las agendas departamentales y/o planes tecnológicos y no se encuentra coherencia con los antecedentes, la justificación y el marco conceptual.</li>
                        <li><strong>Puntaje: 8 a 13</strong> El problema se ha identificado a partir de los instrumentos de planeación regional como las agendas departamentales y/o planes tecnológicos y se encuentra coherencia entre los antecedentes, la justificación y el marco conceptual. Sin embargo, es susceptible de ajustes en términos de coherencia en la propuesta</li>
                        <li><strong>Puntaje: 14 a 15</strong> El problema se ha identificado a partir de los instrumentos de planeación regional como las agendas departamentales y/o planes tecnológicos y guarda una coherencia global entre los antecedentes, la justificación y el marco conceptual.</li>
                    </ul>

                    <Label class="mt-4 mb-4" labelFor="problema_central_puntaje" value="Puntaje (Máximo 15)" />
                    <Input
                        disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                        label="Puntaje"
                        id="problema_central_puntaje"
                        type="number"
                        input$step="1"
                        input$min="0"
                        input$max="15"
                        class="mt-1"
                        bind:value={$formEstrategiaRegionalEvaluacion.problema_central_puntaje}
                        placeholder="Puntaje"
                        autocomplete="off"
                        error={errors.problema_central_puntaje}
                    />

                    {#if segundaEvaluacion?.problema_central_comentario}
                        <p class="whitespace-pre-line bg-indigo-400 shadow text-white p-4"><strong>Comentario del segundo evaluador: </strong>{segundaEvaluacion?.problema_central_comentario}</p>
                    {/if}
                    <div class="mt-4">
                        <p>¿Los antecedentes, árbol de problemas, identificación y descripción del problema, justificación y el marco conceptual son correctos? Por favor seleccione si Cumple o No cumple.</p>
                        <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formEstrategiaRegionalEvaluacion.problema_central_requiere_comentario} />
                        {#if $formEstrategiaRegionalEvaluacion.problema_central_requiere_comentario == false}
                            <Textarea
                                disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                                label="Comentario"
                                class="mt-4"
                                maxlength="40000"
                                id="problema_central_comentario"
                                bind:value={$formEstrategiaRegionalEvaluacion.problema_central_comentario}
                                error={errors.problema_central_comentario}
                                required
                            />
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
    <Dialog bind:open={dialogOpen} id="arbol-problemas">
        <div slot="title" class="mb-10 text-center">
            <div class="text-primary">
                {dialogTitle}
            </div>
            {#if codigo}
                <small class="block text-primary-light">
                    Código: {codigo}
                </small>
            {/if}
        </div>
        <div slot="content">
            {#if showCausaIndirectaForm}
                <form id="causa-indirecta">
                    <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                        <div class="mt-4">
                            <InfoMessage class="mb-2" message="Son acciones o hechos que dan origen a las causas directas y que se encuentran a partir del segundo nivel, justamente debajo de las causas directas del árbol de problemas." />
                            <Textarea disabled label="Descripción" maxlength="40000" id="causa-indirecta-descripcion" value={causaIndirectaInfo.descripcion} />
                        </div>
                    </fieldset>
                </form>
            {:else if showCausaDirectaForm}
                <form id="causa-directa">
                    <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                        <div class="mt-4">
                            <InfoMessage class="mb-2" message="Son las acciones o hechos concretos que generan o dan origen al problema central. Aparecen en la estructura del árbol en el primer nivel, inmediatamente abajo del problema central." />
                            <Textarea disabled label="Descripción" maxlength="40000" id="causa-directa-descripcion" value={causaDirectaInfo.descripcion} />
                        </div>
                    </fieldset>
                </form>
            {:else if showEfectoIndirectoForm}
                <form id="efecto-indirecto">
                    <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                        <div class="mt-4">
                            <InfoMessage class="mb-2" message="Corresponden a situaciones negativas generadas por los efectos directos." />
                            <Textarea disabled label="Descripción" maxlength="40000" id="efecto-directo-descripcion" value={efectoIndirectoInfo.descripcion} />
                        </div>
                    </fieldset>
                </form>
            {:else if showProblemaCentralForm}
                <form id="problema-central">
                    <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                        {#if proyecto.codigo_linea_programatica != 68 && proyecto.codigo_linea_programatica != 70}
                            <div class="mt-10">
                                <Label class="mb-4" labelFor="identificacion_problema" value="Identificación y descripción del problema" />
                                <InfoMessage
                                    class="mb-2"
                                    message="1. Descripción de la necesidad, problema u oportunidad identificada del plan tecnológico y/o agendas departamentales de innovación y competitividad.<br>2. Descripción del problema que se atiende con el proyecto, sustentado en el contexto, la caracterización, los datos, las estadísticas, de la regional, entre otros, citar toda la información consignada utilizando normas APA última edición. La información debe ser de fuentes primarias de información, ejemplo: Secretarías, DANE, Artículos científicos, entre otros."
                                />
                                <Textarea disabled label="Identificación y descripción del problema" maxlength="40000" id="identificacion_problema" value={problemaCentralInfo.identificacion_problema} />
                            </div>
                            <div class="mt-10">
                                <Label class="mb-4" labelFor="justificacion_problema" value="Justificación" />
                                <InfoMessage class="mb-2" message="Descripción de la solución al problema (descrito anteriormente) que se presenta en la regional, así como las consideraciones que justifican la elección del proyecto. De igual forma, describir la pertinencia y viabilidad del proyecto en el marco del impacto regional identificado en el instrumento de planeación." />

                                <Textarea disabled label="Justificación del problema" maxlength="5000" id="justificacion_problema" value={problemaCentralInfo.justificacion_problema} />
                            </div>
                        {/if}

                        <div class="mt-10">
                            <Label class="mb-4" labelFor="problema_central" value="Problema central (tronco)" />
                            <InfoMessage
                                class="mb-2"
                                message="Para la redacción del problema central se debe tener en cuenta: a) Se debe referir a una situación existente, teniendo en cuenta la mayoría de los siguientes componentes: social, económico, tecnológico, ambiental. b) Su redacción debe ser una oración corta con sujeto, verbo y predicado. c) Se debe comprender con total claridad; el problema se debe formular mediante una oración clara y sin ambigüedades."
                            />
                            <Textarea disabled label="Problema central" maxlength="5000" id="problema_central" value={problemaCentralInfo.problema_central} />
                        </div>
                    </fieldset>
                </form>
            {:else if showEfectoDirectoForm}
                <form id="efecto-directo">
                    <fieldset disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                        <div class="mt-4">
                            <InfoMessage class="mb-2" message="Son aquellos que caracterizan las consecuencias de la situación que existirá en caso de no ejecutarse el proyecto; es decir, si se mantiene inalterado el orden actual de las cosas." />
                            <Textarea disabled label="Descripción" maxlength="40000" id="efecto-directo-descripcion" value={efectoDirectoInfo.descripcion} />
                        </div>
                    </fieldset>
                </form>
            {/if}
        </div>
        <div slot="actions" class="block flex w-full">
            <Button on:click={closeDialog} type="button" variant={null}>Cancelar</Button>
        </div>
    </Dialog>
</AuthenticatedLayout>

<style>
    .efectos-directos.relative.flex-1:before {
        content: '';
        bottom: -57px;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 57px;
        background: #d2d6ff;
    }

    .causas-directas-line.relative:before {
        content: '';
        position: absolute;
        top: -55px;
        height: 55px;
        right: 50%;
        width: 2px;
        background: #d2d6ff;
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

    .line-height-1-24 {
        line-height: 1.24;
    }

    :global(#arbol-problemas-dialog .mdc-dialog__surface) {
        width: 750px;
        max-width: calc(100vw - 32px) !important;
    }

    :global(#arbol-problemas-dialog .mdc-dialog__content) {
        padding-top: 40px !important;
    }

    :global(#arbol-problemas-dialog .mdc-dialog__title) {
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        margin-bottom: 0;
    }
</style>
