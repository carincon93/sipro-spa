<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { onMount } from 'svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Dialog from '@/Components/Dialog'
    import Button from '@/Components/Button'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import InfoMessage from '@/Components/InfoMessage'
    import Stepper from '@/Components/Stepper'

    import { createPopper } from '@popperjs/core'

    export let errors
    export let convocatoria
    export let proyecto
    export let efectosDirectos
    export let causasDirectas

    let formId
    let dialogTitle
    let codigo
    let sending = false
    let dialogOpen = false

    $: $title = 'Árbol de problemas'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

    /**
     * Efectos indirectos
     */
    let formEfectoIndirecto = useForm({
        id: 0,
        efecto_directo_id: 0,
        descripcion: '',
    })

    let showEfectoIndirectoForm = false
    function showEfectoindirectoDialog(efectoIndirecto, efectoDirectoId) {
        reset()
        codigo = efectoIndirecto?.id != null ? 'EFE-' + efectoIndirecto.efecto_directo_id + '-IND-' + efectoIndirecto.id : ''
        dialogTitle = 'Efecto indirecto'
        formId = 'efecto-indirecto'
        showEfectoIndirectoForm = true
        dialogOpen = true

        if (efectoIndirecto != null) {
            $formEfectoIndirecto.descripcion = efectoIndirecto.descripcion
            $formEfectoIndirecto.id = efectoIndirecto.id
            $formEfectoIndirecto.efecto_directo_id = efectoIndirecto.efecto_directo_id
        } else {
            $formEfectoIndirecto.id = null
            $formEfectoIndirecto.efecto_directo_id = efectoDirectoId
        }
    }

    function submitEfectoIndirecto() {
        if (isSuperAdmin || checkRole(74)) {
            $formEfectoIndirecto.post(
                route('proyectos.efecto-indirecto', {
                    proyecto: proyecto.id,
                    efecto_directo: $formEfectoIndirecto.efecto_directo_id,
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
     * Efectos directos
     */
    let formEfectoDirecto = useForm({
        id: 0,
        descripcion: '',
    })

    let showEfectoDirectoForm = false
    function showEfectoDirectoDialog(efectoDirecto) {
        reset()
        codigo = 'EFE-' + efectoDirecto.id
        dialogTitle = 'Efecto directo'
        formId = 'efecto-directo'
        showEfectoDirectoForm = true
        dialogOpen = true
        $formEfectoDirecto.descripcion = efectoDirecto.descripcion
        $formEfectoDirecto.id = efectoDirecto.id
    }

    function submitEfectoDirecto() {
        if (isSuperAdmin || checkRole(74)) {
            $formEfectoDirecto.post(
                route('proyectos.efecto-directo', {
                    proyecto: proyecto.id,
                    efecto_directo: $formEfectoDirecto.id,
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
     * Planteamiento del problema
     */
    let formPlanteamientoProblema = useForm({
        planteamiento_problema: proyecto.planteamiento_problema,
        justificacion_problema: proyecto.justificacion_problema,
    })

    let showPlanteamientoProblemaForm = false
    function showStatementProblemDialog() {
        reset()
        dialogTitle = 'Planteamiento del problema'
        formId = 'planteamiento-problema'
        showPlanteamientoProblemaForm = true
        dialogOpen = true
        $formPlanteamientoProblema.planteamiento_problema = proyecto.planteamiento_problema
        $formPlanteamientoProblema.justificacion_problema = proyecto.justificacion_problema
    }

    function submitPlanteamientoProblema() {
        if (isSuperAdmin || checkRole(74)) {
            $formPlanteamientoProblema.post(route('proyectos.planteamiento-problema', proyecto.id), {
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
     * Causas directas
     */
    let formCausaDirecta = useForm({
        id: 0,
        descripcion: '',
    })

    let showCausaDirectaForm = false
    function showCausaDirectaDialog(causaDirecta) {
        reset()
        codigo = 'CAU-' + causaDirecta.id
        dialogTitle = 'Causa directa'
        formId = 'causa-directa'
        showCausaDirectaForm = true
        dialogOpen = true
        $formCausaDirecta.id = causaDirecta.id
        $formCausaDirecta.descripcion = causaDirecta.descripcion
    }

    function submitCausaDirecta() {
        if (isSuperAdmin || checkRole(74)) {
            $formCausaDirecta.post(
                route('proyectos.causa-directa', {
                    proyecto: proyecto.id,
                    causa_directa: $formCausaDirecta.id,
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
     * Causas indirectas
     */
    let formCausaIndirecta = useForm({
        id: 0,
        causa_directa_id: 0,
        descripcion: '',
    })

    let showCausaIndirectaForm = false
    function showCausaIndirectaDialog(causaIndirecta, causaDirectaId) {
        reset()
        codigo = causaIndirecta?.id != null ? 'CAU-' + causaIndirecta.causa_directa_id + '-IND-' + causaIndirecta.id : ''
        dialogTitle = 'Causa indirecta'
        formId = 'causa-indirecta'
        showCausaIndirectaForm = true
        dialogOpen = true
        if (causaIndirecta != null) {
            $formCausaIndirecta.id = causaIndirecta.id
            $formCausaIndirecta.descripcion = causaIndirecta.descripcion
            $formCausaIndirecta.causa_directa_id = causaIndirecta.causa_directa_id
        } else {
            $formCausaIndirecta.id = null
            $formCausaIndirecta.causa_directa_id = causaDirectaId
        }
    }

    function submitCausaIndirecta() {
        if (isSuperAdmin || checkRole(74)) {
            $formCausaIndirecta.post(
                route('proyectos.causa-indirecta', {
                    proyecto: proyecto.id,
                    causa_directa: $formCausaIndirecta.causa_directa_id,
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
        showEfectoIndirectoForm = false
        showEfectoDirectoForm = false
        showPlanteamientoProblemaForm = false
        showCausaDirectaForm = false
        showCausaIndirectaForm = false
        dialogTitle = ''
        codigo = ''
        formId = ''

        $formCausaIndirecta.reset()
        $formCausaDirecta.reset()
        $formPlanteamientoProblema.reset()
        $formEfectoDirecto.reset()
        $formEfectoIndirecto.reset()
    }

    function closeDialog() {
        reset()
        dialogOpen = false
    }

    onMount(() => {
        const efectoIndirecto = document.querySelector('#efecto-indirecto-tooltip-placement')
        const efectoIndirectoTooltip = document.querySelector('#efecto-indirecto-tooltip')
        const arrowEfectoIndirecto = document.querySelector('#arrow-efecto-indirecto')

        const efectoDirecto = document.querySelector('#efecto-directo-tooltip-placement')
        const efectoDirectoTooltip = document.querySelector('#efecto-directo-tooltip')
        const arrowEfectoDirecto = document.querySelector('#arrow-efecto-directo')

        const planteamientoProblema = document.querySelector('#planteamiento-problema-tooltip-placement')
        const planteamientoProblemaTooltip = document.querySelector('#planteamiento-problema-tooltip')
        const arrowPlanteamientoProblema = document.querySelector('#arrow-planteamiento-problema')

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
                    target: planteamientoProblema,
                    tooltip: planteamientoProblemaTooltip,
                    arrow: arrowPlanteamientoProblema,
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
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl mt-24 mb-8 text-center">Árbol de problemas</h1>
    <p class="text-center">Debe generar el árbol de problemas iniciando con el planteamiento del problema, relacionando sus causas y efectos.</p>

    <div class="mt-16">
        <!-- Efectos -->
        <div class="flex mb-14">
            {#each efectosDirectos as efectoDirecto, i}
                <div class="flex-1">
                    {#if i == 0}
                        <!-- Efectos indirectos -->
                        <div id="efecto-indirecto-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
                            <small>Efectos indirectos</small>
                            <div id="arrow-efecto-indirecto" class="arrow" data-popper-arrow />
                        </div>
                    {/if}
                    <div class="flex mb-14" id={i == 0 ? 'efecto-indirecto-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                        {#each efectoDirecto.efectos_indirectos as efectoIndirecto}
                            <div class="flex-1 efectos-directos relative">
                                <div on:click={showEfectoindirectoDialog(efectoIndirecto, efectoDirecto.id)} class="{efectoIndirecto.descripcion != null ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-indigo-300 hover:bg-indigo-400'} h-36 rounded shadow-lg cursor-pointer mr-1.5">
                                    <p class="h-36 line-height-1 overflow-y-hidden p-2.5 text-xs node text-white">
                                        <small class="title block font-bold mb-2">EFE-{efectoDirecto.id}-IND-{efectoIndirecto.id}</small>
                                        {#if efectoIndirecto.descripcion != null && efectoIndirecto.descripcion.length > 0}
                                            {efectoIndirecto.descripcion}
                                        {/if}
                                    </p>
                                </div>
                            </div>
                        {/each}
                        {#each { length: 3 - efectoDirecto.efectos_indirectos.length } as _empty}
                            <div class="flex-1 efectos-directos relative" on:click={showEfectoindirectoDialog(null, efectoDirecto.id)}>
                                <div class="h-36 bg-gray-300 rounded shadow-lg hover:bg-gray-400 cursor-pointer mr-1.5">
                                    <p class="h-36 line-height-1 overflow-y-hidden p-2.5 text-sm text-white" />
                                </div>
                            </div>
                        {/each}
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
                        <div on:click={showEfectoDirectoDialog(efectoDirecto)} class="{efectoDirecto.descripcion != null ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-indigo-300 hover:bg-indigo-400'} h-36 rounded shadow-lg cursor-pointer mr-1.5">
                            <p class="h-36 overflow-hidden text-white p-2.5 text-sm line-height-1">
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

        <!-- Planteamiento del problema -->
        <div id="planteamiento-problema-tooltip" class="tooltip bg-black" role="tooltip" data-popper-placement="left">
            <small class="block line-height-1">Planteamiento <br /> del problema</small>
            <div id="arrow-planteamiento-problema" class="arrow" data-popper-arrow />
        </div>
        <div class="planteamiento-problema relative" id="planteamiento-problema-tooltip-placement" aria-describedby="tooltip">
            <div on:click={showStatementProblemDialog} class="h-36 {proyecto.planteamiento_problema != null ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-indigo-300 hover:bg-indigo-400'} rounded shadow-lg cursor-pointer mr-1.5">
                {#if proyecto.planteamiento_problema != null && proyecto.planteamiento_problema.length > 0}
                    <p class="h-36 overflow-hidden text-white p-2.5 text-sm line-height-1">
                        {proyecto.planteamiento_problema}
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
                    <div class="causas-directas relative flex-1" id={i == 0 ? 'causa-directa-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                        <div on:click={showCausaDirectaDialog(causaDirecta)} class="{causaDirecta.descripcion != null ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-indigo-300 hover:bg-indigo-400'} h-36 rounded shadow-lg cursor-pointer mr-1.5">
                            <p class="h-36 overflow-hidden text-white p-2.5 text-sm line-height-1">
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
                    <div class="flex mt-14" id={i == 0 ? 'causa-indirecta-tooltip-placement' : ''} aria-describedby={i == 0 ? 'tooltip' : ''}>
                        {#each causaDirecta.causas_indirectas as causaIndirecta}
                            <div class="causas-directas relative flex-1">
                                <div on:click={showCausaIndirectaDialog(causaIndirecta, causaDirecta.id)} class="{causaIndirecta.descripcion != null ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-indigo-300 hover:bg-indigo-400'} h-36 rounded shadow-lg cursor-pointer mr-1.5">
                                    <p class="h-36 line-height-1 overflow-y-hidden p-2.5 text-xs node text-white">
                                        <small class="title block font-bold mb-2">CAU-{causaDirecta.id}-IND-{causaIndirecta.id}</small>
                                        {#if causaIndirecta.descripcion != null && causaIndirecta.descripcion.length > 0}
                                            {causaIndirecta.descripcion}
                                        {/if}
                                    </p>
                                </div>
                            </div>
                        {/each}
                        {#each { length: 3 - causaDirecta.causas_indirectas.length } as _empty}
                            <div class="causas-directas relative flex-1">
                                <div on:click={showCausaIndirectaDialog(null, causaDirecta.id)} class="h-36 bg-gray-300 rounded shadow-lg hover:bg-gray-400 cursor-pointer mr-1.5">
                                    <p class="h-36 line-height-1 overflow-y-hidden p-2.5 text-sm text-white" />
                                </div>
                            </div>
                        {/each}
                    </div>
                </div>
            {/each}
        </div>
    </div>

    <!-- Dialog -->
    <Dialog bind:open={dialogOpen}>
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
                <form on:submit|preventDefault={submitCausaIndirecta} id="causa-indirecta">
                    <fieldset disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
                        <div class="mt-4">
                            <Textarea label="Descripción" maxlength="40000" id="causa-indirecta-descripcion" error={errors.descripcion} bind:value={$formCausaIndirecta.descripcion} required />
                        </div>
                    </fieldset>
                </form>
            {:else if showCausaDirectaForm}
                <form on:submit|preventDefault={submitCausaDirecta} id="causa-directa">
                    <fieldset disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
                        <div class="mt-4">
                            <Textarea label="Descripción" maxlength="40000" id="causa-directa-descripcion" error={errors.descripcion} bind:value={$formCausaDirecta.descripcion} required />
                        </div>
                    </fieldset>
                </form>
            {:else if showEfectoIndirectoForm}
                <form on:submit|preventDefault={submitEfectoIndirecto} id="efecto-indirecto">
                    <fieldset disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
                        <div class="mt-4">
                            <Textarea label="Descripción" maxlength="40000" id="efecto-directo-descripcion" error={errors.descripcion} bind:value={$formEfectoIndirecto.descripcion} required />
                        </div>
                    </fieldset>
                </form>
            {:else if showPlanteamientoProblemaForm}
                <form on:submit|preventDefault={submitPlanteamientoProblema} id="planteamiento-problema">
                    <fieldset disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="planteamiento_problema" value="Planteamiento del problema" />

                            <InfoMessage
                                class="mb-2"
                                message="1. Descripción de la necesidad, problema u oportunidad identificada del plan tecnologógico y/o agendas departamentales de innovación y competitividad.<br>2. Descripción del problema que se atiende con el proyecto, sustentado en el contexto, la caracterización, los datos, las estadísticas, de la regional, entre otros, citar toda la información consignada utilizando normas APA sexta edición. La información debe ser de fuentes primarias de información, ejemplo: Secretarías, DANE, Artículos científicos, entre otros."
                            />

                            <Textarea label="Descripción" maxlength="40000" id="planteamiento_problema" error={errors.planteamiento_problema} bind:value={$formPlanteamientoProblema.planteamiento_problema} required />
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="justificacion_problema" value="Justificación" />
                            <InfoMessage class="mb-2" message="Descripción de la solución al problema (descrito anteriormente) que se presenta en la regional, así como las consideraciones que justifican la elección del proyecto. De igual forma, describir la pertinencia y viabilidad del proyecto en el marco del impacto regional identificado en el instrumento de planeación." />
                            <Textarea label="Descripción" maxlength="40000" id="justificacion_problema" error={errors.justificacion_problema} bind:value={$formPlanteamientoProblema.justificacion_problema} required />
                        </div>
                    </fieldset>
                </form>
            {:else if showEfectoDirectoForm}
                <form on:submit|preventDefault={submitEfectoDirecto} id="efecto-directo">
                    <fieldset disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
                        <div class="mt-4">
                            <Textarea label="Descripción" maxlength="40000" id="efecto-directo-descripcion" error={errors.descripcion} bind:value={$formEfectoDirecto.descripcion} required />
                        </div>
                    </fieldset>
                </form>
            {/if}
        </div>
        <div slot="actions" class="block flex w-full">
            <Button on:click={closeDialog} type="button" variant={null}>Cancelar</Button>
            {#if (isSuperAdmin && formId) || (checkRole(74) && formId)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit" form={formId}>Guardar</LoadingButton>
            {/if}
        </div>
    </Dialog>
</AuthenticatedLayout>

<style>
    .efectos-directos.relative.flex-1:before {
        content: '';
        bottom: -40%;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 57px;
        background: #d2d6ff;
    }

    .causas-directas.relative.flex-1:before {
        content: '';
        top: -38%;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 55px;
        background: #d2d6ff;
    }

    .line-height-1 {
        line-height: 1;
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

    :global(.mdc-dialog__surface) {
        width: 750px;
        max-width: calc(100vw - 32px) !important;
    }

    :global(.mdc-dialog__content) {
        padding-top: 40px !important;
    }

    :global(.mdc-dialog__title) {
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        margin-bottom: 0;
    }
</style>
