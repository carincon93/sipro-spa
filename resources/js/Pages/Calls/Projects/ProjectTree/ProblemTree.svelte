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
    export let call
    export let project
    export let directEffects
    export let directCauses

    let formID
    let sending = false
    let dialog_open = false
    let dialogTitle
    let code

    $: $title = $_('Problem tree')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canCreateRDI =
        authUser.can.find((element) => element == 'rdi.create') == 'rdi.create'
    let canEditRDI =
        authUser.can.find((element) => element == 'rdi.edit') == 'rdi.edit'

    let canCreateOrUpdate = isSuperAdmin
        ? undefined
        : canCreateRDI
        ? undefined
        : canEditRDI
        ? undefined
        : true

    /**
     * Efectos indirectos
     */
    let formIndirectEffect = useForm({
        id: 0,
        direct_effect_id: 0,
        description: '',
    })

    let showIndirectEffectForm = false
    function showIndirectEffectDialog(indirectEffect, directEffectID) {
        reset()
        code =
            indirectEffect?.id != null
                ? 'EFE-' +
                  indirectEffect.direct_effect_id +
                  '-IND-' +
                  indirectEffect.id
                : ''
        dialogTitle = 'Efecto indirecto'
        formID = 'indirect-effect'
        showIndirectEffectForm = true
        dialog_open = true

        if (indirectEffect != null) {
            $formIndirectEffect.description = indirectEffect.description
            $formIndirectEffect.id = indirectEffect.id
            $formIndirectEffect.direct_effect_id =
                indirectEffect.direct_effect_id
        } else {
            $formIndirectEffect.id = null
            $formIndirectEffect.direct_effect_id = directEffectID
        }
    }

    function submitIndirectEffect() {
        if (canCreateOrUpdate == undefined) {
            $formIndirectEffect.post(
                route('projects.indirect_effect', {
                    project: project.id,
                    direct_effect: $formIndirectEffect.direct_effect_id,
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
    let formDirectEffect = useForm({
        id: 0,
        description: '',
    })

    let showDirectEffectForm = false
    function showDirectEffectDialog(directEffect) {
        reset()
        code = 'EFE-' + directEffect.id
        dialogTitle = 'Efecto directo'
        formID = 'direct-effect'
        showDirectEffectForm = true
        dialog_open = true
        $formDirectEffect.description = directEffect.description
        $formDirectEffect.id = directEffect.id
    }

    function submitDirectEffect() {
        if (canCreateOrUpdate == undefined) {
            $formDirectEffect.post(
                route('projects.direct_effect', {
                    project: project.id,
                    direct_effect: $formDirectEffect.id,
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
    let formStatementProblem = useForm({
        planteamiento_problema: project.planteamiento_problema,
        justificacion_problema: project.justificacion_problema,
    })

    let showStatementProblemForm = false
    function showStatementProblemDialog() {
        reset()
        dialogTitle = 'Planteamiento del problema'
        formID = 'statement-problem'
        showStatementProblemForm = true
        dialog_open = true
        $formStatementProblem.planteamiento_problema =
            project.planteamiento_problema
        $formStatementProblem.justificacion_problema =
            project.justificacion_problema
    }

    function submitStatementProblem() {
        if (canCreateOrUpdate == undefined) {
            $formStatementProblem.post(
                route('projects.planteamiento_problema', project.id),
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
     * Causas directas
     */
    let formDirectCause = useForm({
        id: 0,
        description: '',
    })

    let showDirectCauseForm = false
    function showDirectCauseDialog(directCause) {
        reset()
        code = 'CAU-' + directCause.id
        dialogTitle = 'Causa directa'
        formID = 'direct-cause'
        showDirectCauseForm = true
        dialog_open = true
        $formDirectCause.id = directCause.id
        $formDirectCause.description = directCause.description
    }

    function submitDirectCause() {
        if (canCreateOrUpdate == undefined) {
            $formDirectCause.post(
                route('projects.direct_cause', {
                    project: project.id,
                    direct_cause: $formDirectCause.id,
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
    let formIndirectCause = useForm({
        id: 0,
        direct_cause_id: 0,
        description: '',
    })

    let showIndirectCauseForm = false
    function showIndirectCauseDialog(indirectCause, directCauseID) {
        reset()
        code =
            indirectCause?.id != null
                ? 'CAU-' +
                  indirectCause.direct_cause_id +
                  '-IND-' +
                  indirectCause.id
                : ''
        dialogTitle = 'Causa indirecta'
        formID = 'indirect-cause'
        showIndirectCauseForm = true
        dialog_open = true
        if (indirectCause != null) {
            $formIndirectCause.id = indirectCause.id
            $formIndirectCause.description = indirectCause.description
            $formIndirectCause.direct_cause_id = indirectCause.direct_cause_id
        } else {
            $formIndirectCause.id = null
            $formIndirectCause.direct_cause_id = directCauseID
        }
    }

    function submitIndirectCause() {
        if (canCreateOrUpdate == undefined) {
            $formIndirectCause.post(
                route('projects.indirect_cause', {
                    project: project.id,
                    direct_cause: $formIndirectCause.direct_cause_id,
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
        showIndirectEffectForm = false
        showDirectEffectForm = false
        showStatementProblemForm = false
        showDirectCauseForm = false
        showIndirectCauseForm = false
        dialogTitle = ''
        code = ''
        formID = ''

        $formIndirectCause.reset()
        $formDirectCause.reset()
        $formStatementProblem.reset()
        $formDirectEffect.reset()
        $formIndirectEffect.reset()
    }

    function closeDialog() {
        reset()
        dialog_open = false
    }

    onMount(() => {
        const indirectEffect = document.querySelector(
            '#indirect-effect-tooltip-placement',
        )
        const indirectEffectTooltip = document.querySelector(
            '#indirect-effect-tooltip',
        )
        const arrowIndirectEffect = document.querySelector(
            '#arrow-indirect-effect',
        )

        const directEffect = document.querySelector(
            '#direct-effect-tooltip-placement',
        )
        const directEffectTooltip = document.querySelector(
            '#direct-effect-tooltip',
        )
        const arrowDirectEffect = document.querySelector('#arrow-direct-effect')

        const statementProblem = document.querySelector(
            '#statement-problem-tooltip-placement',
        )
        const statementProblemTooltip = document.querySelector(
            '#statement-problem-tooltip',
        )
        const arrowStatementProblem = document.querySelector(
            '#arrow-statement-problem',
        )

        const directCause = document.querySelector(
            '#direct-cause-tooltip-placement',
        )
        const directCauseTooltip = document.querySelector(
            '#direct-cause-tooltip',
        )
        const arrowDirectCause = document.querySelector('#arrow-direct-cause')

        const indrectCause = document.querySelector(
            '#indirect-cause-tooltip-placement',
        )
        const indrectCauseTooltip = document.querySelector(
            '#indirect-cause-tooltip',
        )
        const arrowIndirectCause = document.querySelector(
            '#arrow-indirect-cause',
        )

        let tooltips = [
            {
                element: {
                    target: indirectEffect,
                    tooltip: indirectEffectTooltip,
                    arrow: arrowIndirectEffect,
                },
            },
            {
                element: {
                    target: directEffect,
                    tooltip: directEffectTooltip,
                    arrow: arrowDirectEffect,
                },
            },
            {
                element: {
                    target: statementProblem,
                    tooltip: statementProblemTooltip,
                    arrow: arrowStatementProblem,
                },
            },
            {
                element: {
                    target: directCause,
                    tooltip: directCauseTooltip,
                    arrow: arrowDirectCause,
                },
            },
            {
                element: {
                    target: indrectCause,
                    tooltip: indrectCauseTooltip,
                    arrow: arrowIndirectCause,
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
    <Stepper {call} {project} />

    <div class="py-12">
        <h1 class="text-4xl text-center">Árbol de problemas</h1>
        <p class="text-center">
            Debe generar el árbol de problemas iniciando con el planteamiento
            del problema, relacionando sus causas y efectos.
        </p>

        <div class="mt-16">
            <!-- Efectos -->
            <div class="flex mb-14">
                {#each directEffects as directEffect, i}
                    <div class="flex-1">
                        {#if i == 0}
                            <!-- Efectos indirectos -->
                            <div
                                id="indirect-effect-tooltip"
                                class="tooltip bg-black"
                                role="tooltip"
                                data-popper-placement="left"
                            >
                                <small>Efectos indirectos</small>
                                <div
                                    id="arrow-indirect-effect"
                                    class="arrow"
                                    data-popper-arrow
                                />
                            </div>
                        {/if}
                        <div
                            class="flex mb-14"
                            id={i == 0
                                ? 'indirect-effect-tooltip-placement'
                                : ''}
                            aria-describedby={i == 0 ? 'tooltip' : ''}
                        >
                            {#each directEffect.indirect_effects as indirectEffect}
                                <div class="flex-1 direct-effects relative">
                                    <div
                                        on:click={showIndirectEffectDialog(
                                            indirectEffect,
                                            directEffect.id,
                                        )}
                                        class="{indirectEffect.description !=
                                        null
                                            ? 'bg-indigo-500 hover:bg-indigo-600'
                                            : 'bg-indigo-300 hover:bg-indigo-400'} h-36 rounded shadow-lg cursor-pointer mr-1.5"
                                    >
                                        <p
                                            class="h-36 line-height-1 overflow-y-hidden p-2.5 text-sm text-white"
                                        >
                                            <small
                                                class="title block font-bold mb-2"
                                                >EFE-{directEffect.id}-IND-{indirectEffect.id}</small
                                            >
                                            {#if indirectEffect.description != null && indirectEffect.description.length > 0}
                                                {indirectEffect.description}
                                            {/if}
                                        </p>
                                    </div>
                                </div>
                            {/each}
                            {#each { length: 3 - directEffect.indirect_effects.length } as _empty}
                                <div
                                    class="flex-1 direct-effects relative"
                                    on:click={showIndirectEffectDialog(
                                        null,
                                        directEffect.id,
                                    )}
                                >
                                    <div
                                        class="h-36 bg-gray-300 rounded shadow-lg hover:bg-gray-400 cursor-pointer mr-1.5"
                                    >
                                        <p
                                            class="h-36 line-height-1 overflow-y-hidden p-2.5 text-sm text-white"
                                        />
                                    </div>
                                </div>
                            {/each}
                        </div>

                        {#if i == 0}
                            <!-- Efecto directo -->
                            <div
                                id="direct-effect-tooltip"
                                class="tooltip bg-black"
                                role="tooltip"
                                data-popper-placement="left"
                            >
                                <small>Efectos directos</small>
                                <div
                                    id="arrow-direct-effect"
                                    class="arrow"
                                    data-popper-arrow
                                />
                            </div>
                        {/if}
                        <!-- Efecto directo -->
                        <div
                            class="direct-effects relative flex-1"
                            id={i == 0 ? 'direct-effect-tooltip-placement' : ''}
                            aria-describedby={i == 0 ? 'tooltip' : ''}
                        >
                            <div
                                on:click={showDirectEffectDialog(directEffect)}
                                class="{directEffect.description != null
                                    ? 'bg-indigo-500 hover:bg-indigo-600'
                                    : 'bg-indigo-300 hover:bg-indigo-400'} h-36 rounded shadow-lg cursor-pointer mr-1.5"
                            >
                                <p
                                    class="h-36 overflow-hidden text-white p-2.5 text-sm line-height-1"
                                >
                                    <small class="title block font-bold mb-2"
                                        >EFE-{directEffect.id}</small
                                    >
                                    {#if directEffect.description != null && directEffect.description.length > 0}
                                        {directEffect.description}
                                    {/if}
                                </p>
                            </div>
                        </div>
                    </div>
                {/each}
            </div>

            <!-- Planteamiento del problema -->
            <div
                id="statement-problem-tooltip"
                class="tooltip bg-black"
                role="tooltip"
                data-popper-placement="left"
            >
                <small class="block line-height-1"
                    >Planteamiento <br /> del problema</small
                >
                <div
                    id="arrow-statement-problem"
                    class="arrow"
                    data-popper-arrow
                />
            </div>
            <div
                class="statement-problem relative"
                id="statement-problem-tooltip-placement"
                aria-describedby="tooltip"
            >
                <div
                    on:click={showStatementProblemDialog}
                    class="h-36 {project.planteamiento_problema != null
                        ? 'bg-indigo-500 hover:bg-indigo-600'
                        : 'bg-indigo-300 hover:bg-indigo-400'} rounded shadow-lg cursor-pointer mr-1.5"
                >
                    {#if project.planteamiento_problema != null && project.planteamiento_problema.length > 0}
                        <p
                            class="h-36 overflow-hidden text-white p-2.5 text-sm line-height-1"
                        >
                            {project.planteamiento_problema}
                        </p>
                    {/if}
                </div>
            </div>

            <!-- Causas -->
            <div class="flex mt-14">
                {#each directCauses as directCause, i}
                    <div class="flex-1">
                        <!-- Causa directa -->
                        {#if i == 0}
                            <div
                                id="direct-cause-tooltip"
                                class="tooltip bg-black"
                                role="tooltip"
                                data-popper-placement="left"
                            >
                                <small>Causas directas</small>
                                <div
                                    id="arrow-direct-cause"
                                    class="arrow"
                                    data-popper-arrow
                                />
                            </div>
                        {/if}
                        <div
                            class="direct-causes relative flex-1"
                            id={i == 0 ? 'direct-cause-tooltip-placement' : ''}
                            aria-describedby={i == 0 ? 'tooltip' : ''}
                        >
                            <div
                                on:click={showDirectCauseDialog(directCause)}
                                class="{directCause.description != null
                                    ? 'bg-indigo-500 hover:bg-indigo-600'
                                    : 'bg-indigo-300 hover:bg-indigo-400'} h-36 rounded shadow-lg cursor-pointer mr-1.5"
                            >
                                <p
                                    class="h-36 overflow-hidden text-white p-2.5 text-sm line-height-1"
                                >
                                    <small class="title block font-bold mb-2"
                                        >CAU-{directCause.id}</small
                                    >
                                    {#if directCause.description != null && directCause.description.length > 0}
                                        {directCause.description}
                                    {/if}
                                </p>
                            </div>
                        </div>

                        {#if i == 0}
                            <div
                                id="indirect-cause-tooltip"
                                class="tooltip bg-black"
                                role="tooltip"
                                data-popper-placement="left"
                            >
                                <small>Causas indirectas</small>
                                <div
                                    id="arrow-indirect-cause"
                                    class="arrow"
                                    data-popper-arrow
                                />
                            </div>
                        {/if}
                        <!-- Causas indirectas -->
                        <div
                            class="flex mt-14"
                            id={i == 0
                                ? 'indirect-cause-tooltip-placement'
                                : ''}
                            aria-describedby={i == 0 ? 'tooltip' : ''}
                        >
                            {#each directCause.indirect_causes as indirectCause}
                                <div class="direct-causes relative flex-1">
                                    <div
                                        on:click={showIndirectCauseDialog(
                                            indirectCause,
                                            directCause.id,
                                        )}
                                        class="{indirectCause.description !=
                                        null
                                            ? 'bg-indigo-500 hover:bg-indigo-600'
                                            : 'bg-indigo-300 hover:bg-indigo-400'} h-36 rounded shadow-lg cursor-pointer mr-1.5"
                                    >
                                        <p
                                            class="h-36 line-height-1 overflow-y-hidden p-2.5 text-sm text-white"
                                        >
                                            <small
                                                class="title block font-bold mb-2"
                                                >CAU-{directCause.id}-IND-{indirectCause.id}</small
                                            >
                                            {#if indirectCause.description != null && indirectCause.description.length > 0}
                                                {indirectCause.description}
                                            {/if}
                                        </p>
                                    </div>
                                </div>
                            {/each}
                            {#each { length: 3 - directCause.indirect_causes.length } as _empty}
                                <div class="direct-causes relative flex-1">
                                    <div
                                        on:click={showIndirectCauseDialog(
                                            null,
                                            directCause.id,
                                        )}
                                        class="h-36 bg-gray-300 rounded shadow-lg hover:bg-gray-400 cursor-pointer mr-1.5"
                                    >
                                        <p
                                            class="h-36 line-height-1 overflow-y-hidden p-2.5 text-sm text-white"
                                        />
                                    </div>
                                </div>
                            {/each}
                        </div>
                    </div>
                {/each}
            </div>
        </div>
    </div>

    <!-- Dialog -->
    <Dialog bind:open={dialog_open}>
        <div slot="title" class="mb-10 text-center">
            <div class="text-primary">
                {dialogTitle}
            </div>
            {#if code}
                <small class="block text-primary-light">
                    Código: {code}
                </small>
            {/if}
        </div>
        <div slot="content">
            {#if showIndirectCauseForm}
                <form
                    on:submit|preventDefault={submitIndirectCause}
                    id="indirect-cause"
                >
                    <fieldset disabled={canCreateOrUpdate}>
                        <div class="mt-4">
                            <Label
                                required
                                class="mb-4"
                                labelFor="description"
                                value="Descripción"
                            />
                            <Textarea
                                rows="4"
                                id="description"
                                error={errors.description}
                                bind:value={$formIndirectCause.description}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {:else if showDirectCauseForm}
                <form
                    on:submit|preventDefault={submitDirectCause}
                    id="direct-cause"
                >
                    <fieldset disabled={canCreateOrUpdate}>
                        <div class="mt-4">
                            <Label
                                required
                                class="mb-4"
                                labelFor="description"
                                value="Descripción"
                            />
                            <Textarea
                                rows="4"
                                id="description"
                                error={errors.description}
                                bind:value={$formDirectCause.description}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {:else if showIndirectEffectForm}
                <form
                    on:submit|preventDefault={submitIndirectEffect}
                    id="indirect-effect"
                >
                    <fieldset disabled={canCreateOrUpdate}>
                        <div class="mt-4">
                            <Label
                                required
                                class="mb-4"
                                labelFor="description"
                                value="Descripción"
                            />
                            <Textarea
                                rows="4"
                                id="description"
                                error={errors.description}
                                bind:value={$formIndirectEffect.description}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {:else if showStatementProblemForm}
                <form
                    on:submit|preventDefault={submitStatementProblem}
                    id="statement-problem"
                >
                    <fieldset disabled={canCreateOrUpdate}>
                        <div class="mt-4">
                            <Label
                                required
                                class="mb-4"
                                labelFor="planteamiento_problema"
                                value="Planteamiento del problema"
                            />

                            <InfoMessage
                                message="1. Descripción de la necesidad, problema u oportunidad identificada del plan tecnologógico y/o agendas departamentales de innovación y competitividad.
                            <br>
                            2. Descripción del problema que se atiende con el proyecto, sustentado en el contexto, la caracterización, los datos, las estadísticas, de la regional, entre otros, citar toda la información consignada utilizando normas APA sexta edición. La información debe ser de fuentes primarias de información, ejemplo: Secretarías, DANE, Artículos científicos, entre otros."
                            />

                            <Textarea
                                rows="4"
                                id="planteamiento_problema"
                                error={errors.planteamiento_problema}
                                bind:value={$formStatementProblem.planteamiento_problema}
                                required
                            />
                        </div>

                        <div class="mt-4">
                            <Label
                                required
                                class="mb-4"
                                labelFor="justificacion_problema"
                                value="Justificación"
                            />
                            <InfoMessage
                                message="Descripción de la solución al problema (descrito anteriormente) que se presenta en la regional, así como las consideraciones que justifican la elección del proyecto. De igual forma, describir la pertinencia y viabilidad del proyecto en el marco del impacto regional identificado en el instrumento de planeación."
                            />
                            <Textarea
                                rows="4"
                                id="justificacion_problema"
                                error={errors.justificacion_problema}
                                bind:value={$formStatementProblem.justificacion_problema}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {:else if showDirectEffectForm}
                <form
                    on:submit|preventDefault={submitDirectEffect}
                    id="direct-effect"
                >
                    <fieldset disabled={canCreateOrUpdate}>
                        <div class="mt-4">
                            <Label
                                required
                                class="mb-4"
                                labelFor="description"
                                value="Descripción"
                            />
                            <Textarea
                                rows="4"
                                id="description"
                                error={errors.description}
                                bind:value={$formDirectEffect.description}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {/if}
        </div>
        <div slot="actions" class="block flex w-full">
            <Button on:click={closeDialog} type="button" variant={null}>
                Cancelar
            </Button>
            {#if !canCreateOrUpdate && formID}
                <LoadingButton
                    loading={sending}
                    class="btn-indigo ml-auto"
                    type="submit"
                    form={formID}
                >
                    {$_('Save')}
                </LoadingButton>
            {/if}
        </div>
    </Dialog>
</AuthenticatedLayout>

<style>
    .direct-effects.relative.flex-1:before {
        content: '';
        bottom: -40%;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 57px;
        background: #d2d6ff;
    }

    .direct-causes.relative.flex-1:before {
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
