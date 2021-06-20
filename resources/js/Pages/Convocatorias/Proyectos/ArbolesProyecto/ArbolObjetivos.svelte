<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { onMount } from 'svelte'
    import { _ } from 'svelte-i18n'
    import { createPopper } from '@popperjs/core'

    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import InfoMessage from '@/Shared/InfoMessage'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Stepper from '@/Shared/Stepper'

    export let errors
    export let convocatoria
    export let proyecto
    export let efectosDirectos
    export let causasDirectas
    export let tiposImpacto
    export let tiposResultado

    let cantidadCeldasActividades = 3
    if (proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82) {
        cantidadCeldasActividades = 3
    } else if (proyecto.codigo_linea_programatica == 68) {
        cantidadCeldasActividades = 14
    } else if (proyecto.codigo_linea_programatica == 69 || proyecto.codigo_linea_programatica == 70) {
        cantidadCeldasActividades = 10
    }

    let formId
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
        reset()
        dialogTitle = 'Información general'
        dialogOpen = true

        generalInfoType = tipo
        showGeneralInfo = true
    }

    /**
     * Impactos
     */
    let formImpacto = useForm({
        id: 0,
        efecto_indirecto_id: 0,
        descripcion: '',
        tipo: '',
        resultado_id: '',
    })

    let showImpactoForm = false
    let impactoEfectoIndirecto
    function showImpactDialog(efectoIndirecto, efectoIndirectoId, resultadoId) {
        // reset()
        codigo = efectoIndirecto.impacto.id != null ? 'RES-' + resultadoId + '-IMP-' + efectoIndirecto.impacto.id : ''
        dialogTitle = 'Impacto'
        dialogOpen = true
        showImpactoForm = true
        formId = 'impacto-form'
        impactoEfectoIndirecto = efectoIndirecto.descripcion

        if (efectoIndirecto.impacto != null) {
            $formImpacto.id = efectoIndirecto.impacto.id
            $formImpacto.descripcion = efectoIndirecto.impacto.descripcion
            $formImpacto.tipo = {
                value: efectoIndirecto.impacto.tipo,
                label: tiposImpacto.find((item) => item.value == efectoIndirecto.impacto.tipo)?.label,
            }
            $formImpacto.efecto_indirecto_id = efectoIndirecto.impacto.efectoIndirectoId
            $formImpacto.resultado_id = resultadoId
        } else {
            $formImpacto.id = null
            $formImpacto.efecto_indirecto_id = efectoIndirectoId
            $formImpacto.resultado_id = ''
        }
    }

    function submitImpacto() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true)) {
            $formImpacto.post(
                route('proyectos.impacto', {
                    proyecto: proyecto.id,
                    impacto: $formImpacto.id,
                }),
                {
                    onStart: () => {
                        sending = true
                    },
                    onSuccess: () => {
                        closeDialog()
                    },
                    onFinish: () => {
                        sending = false
                    },
                    preserveScroll: true,
                },
            )
        }
    }

    /**
     * Resultados
     */
    let formResultado = useForm({
        descripcion: '',
        tipo: '',
    })

    let showResultadoForm = false
    let descripcionObjetivoEspecifico = []
    let resultadoEfectoDirecto
    $: causasDirectas
    function showResultadoDialog(efectoDirecto, resultado) {
        reset()
        let objetivoEspecifico = causasDirectas.find((causaDirecta) => causaDirecta.objetivo_especifico.id == resultado.objetivo_especifico_id)
        descripcionObjetivoEspecifico = {
            descripcion: objetivoEspecifico.objetivo_especifico?.descripcion ? objetivoEspecifico.objetivo_especifico?.descripcion : 'Sin información registrada',
            numero: objetivoEspecifico.objetivo_especifico?.numero,
        }
        codigo = 'RES-' + resultado.id
        dialogTitle = 'Resultado'
        dialogOpen = true
        showResultadoForm = true
        formId = 'resultado-form'
        $formResultado.id = resultado.id
        $formResultado.descripcion = resultado.descripcion
        $formResultado.tipo = {
            value: resultado.tipo,
            label: tiposResultado.find((item) => item.value == resultado.tipo)?.label,
        }
        $formResultado.trl = resultado.trl
        $formResultado.indicador = resultado.indicador
        $formResultado.medios_verificacion = resultado.medios_verificacion
        resultadoEfectoDirecto = efectoDirecto.descripcion ?? 'Sin información registrada'
    }

    function submitResult() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true)) {
            $formResultado.post(
                route('proyectos.resultado', {
                    proyecto: proyecto.id,
                    resultado: $formResultado.id,
                }),
                {
                    onStart: () => {
                        sending = true
                    },
                    onSuccess: () => {
                        closeDialog()
                    },
                    onFinish: () => {
                        sending = false
                    },
                    preserveScroll: true,
                },
            )
        }
    }

    /**
     * Objetivo general
     */
    let formObjetivoGeneral = useForm({
        objetivo_general: proyecto.objetivo_general,
    })

    let showObjetivoGeneralForm = false
    let problemaCentral
    function showObjetivoGeneralDialog() {
        reset()
        dialogTitle = 'Objetivo general'
        dialogOpen = true
        showObjetivoGeneralForm = true
        formId = 'objetivo-general-form'
        problemaCentral = proyecto.problema_central
        $formObjetivoGeneral.objetivo_general = proyecto.objetivo_general
    }

    function submitObjetivoGeneral() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true)) {
            $formObjetivoGeneral.post(route('proyectos.objetivo-general', proyecto.id), {
                onStart: () => {
                    sending = true
                },
                onSuccess: () => {
                    closeDialog()
                },
                onFinish: () => {
                    sending = false
                },
                preserveScroll: true,
            })
        }
    }

    /**
     * Objetivos específicos
     */
    let formObjetivoEspecifico = useForm({
        id: 0,
        descripcion: '',
        numero: 0,
    })

    let showObjetivoEspecificoForm = false
    let causaDirectaObjetivoEspecifico
    function showObjetivoEspecificoDialog(causaDirecta, numero) {
        reset()
        codigo = 'OBJ-ESP-' + causaDirecta.objetivo_especifico.id
        dialogTitle = causaDirecta.objetivo_especifico.numero
        dialogOpen = true
        showObjetivoEspecificoForm = true
        formId = 'objetivo-especifico-form'
        $formObjetivoEspecifico.id = causaDirecta.objetivo_especifico.id
        $formObjetivoEspecifico.descripcion = causaDirecta.objetivo_especifico.descripcion
        $formObjetivoEspecifico.numero = numero
        causaDirectaObjetivoEspecifico = causaDirecta.descripcion ?? 'Sin información registrada'
    }

    function submitObjetivoEspecifico() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true)) {
            $formObjetivoEspecifico.post(
                route('proyectos.objetivo-especifico', {
                    proyecto: proyecto.id,
                    objetivo_especifico: $formObjetivoEspecifico.id,
                }),
                {
                    onStart: () => {
                        sending = true
                    },
                    onSuccess: () => {
                        closeDialog()
                    },
                    onFinish: () => {
                        sending = false
                    },
                    preserveScroll: true,
                },
            )
        }
    }

    /**
     * Actividades
     */
    let formActividad = useForm({
        id: 0,
        causa_indirecta_id: 0,
        objetivo_especifico_id: 0,
        descripcion: '',
        fecha_inicio: '',
        fecha_finalizacion: '',
    })

    let showActividadForm = false
    let actividadCausaIndirecta
    function showActivityDialog(causaIndirecta, objetivoEspecifico) {
        reset()
        codigo = causaIndirecta.actividad.id != null ? 'OBJ-ESP-' + objetivoEspecifico + '-ACT-' + causaIndirecta.actividad.id : ''
        dialogTitle = 'Actividad'
        dialogOpen = true
        showActividadForm = true
        formId = 'actividad-form'
        $formActividad.id = causaIndirecta.actividad.id
        $formActividad.causa_indirecta_id = causaIndirecta.actividad.causa_indirecta_id
        $formActividad.objetivo_especifico_id = objetivoEspecifico
        $formActividad.descripcion = causaIndirecta.actividad.descripcion
        $formActividad.fecha_inicio = causaIndirecta.actividad.fecha_inicio
        $formActividad.fecha_finalizacion = causaIndirecta.actividad.fecha_finalizacion
        actividadCausaIndirecta = causaIndirecta.descripcion ?? 'Sin información registrada'
    }

    function submitActividad() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true)) {
            $formActividad.post(
                route('proyectos.actividad', {
                    convocatoria: convocatoria.id,
                    proyecto: proyecto.id,
                    actividad: $formActividad.id,
                }),
                {
                    onStart: () => {
                        sending = true
                    },
                    onSuccess: () => {
                        closeDialog()
                    },
                    onFinish: () => {
                        sending = false
                    },
                    preserveScroll: true,
                },
            )
        }
    }

    function reset() {
        showObjetivoGeneralForm = false
        showActividadForm = false
        showResultadoForm = false
        showImpactoForm = false
        showGeneralInfo = false
        showObjetivoEspecificoForm = false
        dialogTitle = ''
        codigo = ''
        formId = ''

        $formImpacto.reset()
        $formResultado.reset()
        $formObjetivoGeneral.reset()
        $formObjetivoEspecifico.reset()
        $formActividad.reset()
    }

    function closeDialog() {
        reset()
        dialogOpen = false
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
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl mt-24 mb-8 text-center">Árbol de objetivos</h1>
    <p class="text-center">El árbol de objetivos se obtiene al transformar en positivo el árbol de problemas manteniendo la misma estructura y niveles de jerarquía.</p>

    <div class="mt-16">
        <div class="flex mb-14">
            {#each efectosDirectos as efectoDirecto, i}
                <div class="flex-1">
                    <!-- Impactos -->
                    {#if i == 0}
                        <div id="impacto-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                            <small>Impactos</small>
                            <div id="arrow-impacto" class="arrow" data-popper-arrow />
                        </div>
                    {/if}
                    <div class="flex mb-14" id={i == 0 ? 'impacto-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                        {#each efectoDirecto.efectos_indirectos as efectoIndirecto}
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
                        {/each}
                        {#each { length: 3 - efectoDirecto.efectos_indirectos.length } as _empty}
                            <div on:click={() => showGeneralInfoDialog(1)} class="flex-1 resultados relative">
                                <div class="h-36 bg-gray-300 rounded shadow-lg hover:bg-gray-400 cursor-pointer mr-1.5 p-2.5">
                                    <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                </div>
                            </div>
                        {/each}
                    </div>
                    <!-- Resultado -->
                    {#if i == 0}
                        <div id="resultado-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                            <small>Resultados</small>
                            <div id="arrow-resultado" class="arrow" data-popper-arrow />
                        </div>
                    {/if}
                    <div class="{i == 0 ? 'resultados' : 'resultados-line'} relative flex-1 flex flex-wrap" id={i == 0 ? 'resultado-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                        {#each efectoDirecto.resultados as resultado, j}
                            <div
                                on:click={showResultadoDialog(efectoDirecto, resultado)}
                                class="{efectoDirecto.descripcion != null && i % 2 == 0
                                    ? 'bg-orangered-400 hover:bg-orangered-500'
                                    : efectoDirecto.descripcion == null && i % 2 == 0
                                    ? 'bg-gray-300 hover:bg-gray-400'
                                    : efectoDirecto.descripcion != null && i % 2 != 0
                                    ? 'bg-orangered-500 hover:bg-orangered-600'
                                    : 'bg-gray-400 hover:bg-gray-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5{j != 4 ? ' mb-4' : ''}"
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
                            class="{causaDirecta.descripcion != null && i % 2 == 0 ? 'bg-orangered-400 hover:bg-orangered-500' : causaDirecta.descripcion == null && i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : causaDirecta.descripcion != null && i % 2 != 0 ? 'bg-orangered-500 hover:bg-orangered-600' : 'bg-gray-400 hover:bg-gray-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5"
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
                        {/each}
                        {#each { length: cantidadCeldasActividades - causaDirecta.causas_indirectas.length } as _empty, j}
                            <div id="{j}_empty_actividad" on:click={() => showGeneralInfoDialog(2)} class="mb-4" style="flex: 1 0 33.333%">
                                <div class="{i % 2 == 0 ? 'bg-gray-300 hover:bg-gray-400' : 'bg-gray-400 hover:bg-gray-500'} h-36 rounded shadow-lg cursor-pointer mr-1.5 p-2.5">
                                    <p class="paragraph-ellipsis text-sm text-white line-height-1-24" />
                                </div>
                            </div>
                        {/each}
                    </div>
                </div>
            {/each}
        </div>
    </div>

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
                <p class="mb-20 whitespace-pre-line">
                    {actividadCausaIndirecta}
                </p>
                <form on:submit|preventDefault={submitActividad} id="actividad-form">
                    <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true) ? undefined : true}>
                        <p class="mt-1 text-center">Fecha de ejecución</p>
                        <div class="mt-1 mb-20 flex items-start justify-around">
                            <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                                <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                                <div class="ml-4">
                                    <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={proyecto.fecha_inicio} max={proyecto.fecha_finalizacion} bind:value={$formActividad.fecha_inicio} required />
                                </div>
                            </div>
                            <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                                <Label labelFor="fecha_finalizacion" class="ml-4 {errors.fecha_finalizacion ? 'top-3.5 relative' : ''}" value="hasta" />
                                <div class="ml-4">
                                    <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={proyecto.fecha_inicio} max={proyecto.fecha_finalizacion} bind:value={$formActividad.fecha_finalizacion} required />
                                </div>
                            </div>
                        </div>
                        {#if errors.fecha_inicio || errors.fecha_finalizacion}
                            <div class="mb-20">
                                <InputError classes="text-center" message={errors.fecha_inicio} />
                                <InputError classes="text-center" message={errors.fecha_finalizacion} />
                            </div>
                        {/if}
                        <div>
                            <Textarea label="Descripción" maxlength="15000" id="descripcion-actividad" error={errors.descripcion} bind:value={$formActividad.descripcion} required />
                        </div>
                    </fieldset>
                </form>
            {:else if showObjetivoEspecificoForm}
                {#if proyecto.codigo_linea_programatica == 68}
                    <InfoMessage class="mb-4">
                        <p>
                            Los objetivos específicos son los medios cuantificables que llevarán al cumplimiento del objetivo general. Estos surgen de pasar a positivo las causas directas identificadas en el árbol de problemas.
                            <br />
                            La redacción de los objetivos específicos deberá iniciar con un verbo en modo infinitivo, es decir, con una palabra terminada en "ar", "er" o "ir". La estructura del objetivo debe contener al menos tres componentes: (1) la acción que se espera realizar, (2) el objeto sobre el cual recae la acción y (3) elementos adicionales de contexto o descriptivos.
                        </p>
                    </InfoMessage>
                {/if}
                <form on:submit|preventDefault={submitObjetivoEspecifico} id="objetivo-especifico-form">
                    <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true) ? undefined : true}>
                        <p class="block font-medium mb-2 text-gray-700 text-sm">Causa directa</p>

                        <p class="mb-20 whitespace-pre-line">
                            {causaDirectaObjetivoEspecifico}
                        </p>
                        <div>
                            <Textarea label="Descripción" maxlength="40000" id="descripcion-objetivo-especifico" error={errors.descripcion} bind:value={$formObjetivoEspecifico.descripcion} required />
                        </div>
                    </fieldset>
                </form>
            {:else if showObjetivoGeneralForm}
                <form on:submit|preventDefault={submitObjetivoGeneral} id="objetivo-general-form">
                    <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true) ? undefined : true}>
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
                            <Label required class="mb-4" labelFor="objetivo-general" value="Objetivo general" />
                            <Textarea label="Descripción" maxlength="10000" id="objetivo-general" error={errors.objetivo_general} bind:value={$formObjetivoGeneral.objetivo_general} required />
                        </div>
                    </fieldset>
                </form>
            {:else if showResultadoForm}
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

                <form on:submit|preventDefault={submitResult} id="resultado-form">
                    <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true) ? undefined : true}>
                        <div class="mb-20">
                            <Textarea label="Descripción" maxlength="1000" id="descripcion-resultado" error={errors.descripcion} bind:value={$formResultado.descripcion} required />
                        </div>

                        <div class="mb-20">
                            <Label required labelFor="tipo-resultado" value="Tipo" />
                            <Select id="tipo-resultado" items={tiposResultado} bind:selectedValue={$formResultado.tipo} error={errors.tipo} autocomplete="off" placeholder="Seleccione un tipo" required />
                        </div>
                    </fieldset>
                </form>
            {:else if showImpactoForm}
                <InfoMessage class="mb-4">Se busca medir la contribución potencial que genera el proyecto en los siguientes ámbitos: ambiental, social, centro de formación, sector productivo</InfoMessage>

                <p class="block font-medium mb-2 text-gray-700 text-sm">Efecto indirecto</p>

                <p class="mt-4 whitespace-pre-line">
                    {impactoEfectoIndirecto}
                </p>

                <form on:submit|preventDefault={submitImpacto} id="impacto-form">
                    <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true) ? undefined : true}>
                        <div class="mt-4">
                            <Label labelFor="tipo-impacto" value="Tipo" />
                            <Select id="tipo-impacto" items={tiposImpacto} bind:selectedValue={$formImpacto.tipo} error={errors.tipo} autocomplete="off" placeholder="Seleccione un tipo" required />
                            {#if $formImpacto.tipo?.value == 4}
                                <InfoMessage
                                    message="Se busca minimizar y/o evitar los impactos negativos sobre el medio ambiente, tales como contaminación del aire, contaminación de corrientes de agua naturales, ruido, destrucción del paisaje, separación de comunidades que operan como unidades, etc. Por otro lado, se busca identificar diversas acciones de impacto ambiental positivo, tales como: producción limpia y sustentable, protección medioambiental, uso de residuos y reciclaje."
                                />
                            {:else if $formImpacto.tipo?.value == 2}
                                <InfoMessage
                                    message="Se busca medir la contribución potencial del proyecto en cualquiera de los siguientes ámbitos: generación y aplicación de nuevos conocimientos y tecnologías, desarrollo de infraestructura científico- tecnológica, articulación de diferentes proyectos para lograr un objetivo común, mejoramiento de la infraestructura, desarrollo de capacidades de gestión tecnológica."
                                />
                            {:else if $formImpacto.tipo?.value == 5}
                                <InfoMessage message="Se busca medir la contribución potencial del proyecto al desarrollo de la comunidad Sena (Aprendices, instructores y a la formación)" />
                            {:else if $formImpacto.tipo?.value == 6}
                                <InfoMessage message="Se busca medir la contribución potencial del proyecto al desarrollo del sector productivo en concordancia con el sector priorizado de Colombia Productiva y a la mesa técnica a la que pertenece el proyecto." />
                            {/if}
                        </div>
                        <div class="mt-4">
                            <Textarea label="Descripción" maxlength="10000" id="descripcion-impacto" error={errors.descripcion} bind:value={$formImpacto.descripcion} required />
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
            {#if (isSuperAdmin && formId) || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true && formId)}
                <LoadingButton loading={sending} class="btn-gray ml-auto" type="submit" form={formId}>Guardar</LoadingButton>
            {/if}
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
        bottom: -364px;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 380px;
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
