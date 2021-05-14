<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { onMount } from 'svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import Select from '@/Components/Select'
    import InfoMessage from '@/Components/InfoMessage'
    import Dialog from '@/Components/Dialog'
    import Button from '@/Components/Button'
    import Stepper from '@/Components/Stepper'

    import { createPopper } from '@popperjs/core'

    export let errors
    export let call
    export let project
    export let directEffects
    export let directCauses
    export let impactTypes
    export let resultTypes

    let formID
    let sending = false
    let dialog_open = false
    let dialogTitle
    let code

    $: $title = $_('Objectives tree')

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
     * Mensaje para ítems bloqueados
     */
    let generalInfoType = 0
    let showGeneralInfo = false
    function showGeneralInfoDialog(type) {
        reset()
        dialogTitle = 'Información general'
        dialog_open = true

        generalInfoType = type
        showGeneralInfo = true
    }

    /**
     * Impactos
     */
    let formImpact = useForm({
        id: 0,
        indirect_effect_id: 0,
        description: '',
        type: '',
        result_id: '',
    })

    let showImpactForm = false
    let impactIndirectEffect
    function showImpactDialog(indirectEffect, indirectEffectID, resultID) {
        // reset()
        code =
            indirectEffect.impact.id != null
                ? 'RES-' + resultID + '-IMP-' + indirectEffect.impact.id
                : ''
        dialogTitle = 'Impacto'
        dialog_open = true
        showImpactForm = true
        formID = 'impact-form'
        impactIndirectEffect = indirectEffect.description

        if (indirectEffect.impact != null) {
            $formImpact.id = indirectEffect.impact.id
            $formImpact.description = indirectEffect.impact.description
            $formImpact.type = {
                value: indirectEffect.impact.type,
                label: impactTypes.find(
                    (item) => item.value == indirectEffect.impact.type,
                )?.label,
            }
            $formImpact.indirect_effect_id =
                indirectEffect.impact.indirectEffectID
            $formImpact.result_id = resultID
        } else {
            $formImpact.id = null
            $formImpact.indirect_effect_id = indirectEffectID
            $formImpact.result_id = ''
        }
    }

    function submitImpact() {
        if (canCreateOrUpdate == undefined) {
            $formImpact.post(
                route('projects.impact', {
                    project: project.id,
                    impact: $formImpact.id,
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
    let formResult = useForm({
        description: '',
        type: '',
        trl: 1,
        indicator: '',
        means_of_verification: '',
        specificObjective: '',
    })

    let showResultForm = false
    let specificObjectiveDescription = []
    let resultDirectEffect
    $: directCauses
    function showResultDialog(directEffect) {
        reset()
        let specificObjective = directCauses.find(
            (directCause) =>
                directCause.specific_objective.id ==
                directEffect.project_result.specific_objective_id,
        )
        specificObjectiveDescription = {
            description: specificObjective.specific_objective?.description
                ? specificObjective.specific_objective?.description
                : $_('No data recorded'),
            number: specificObjective.specific_objective?.number,
        }
        code = 'RES-' + directEffect.project_result.id
        dialogTitle = 'Resultado'
        dialog_open = true
        showResultForm = true
        formID = 'project-result-form'
        $formResult.id = directEffect.project_result.id
        $formResult.description = directEffect.project_result.description
        $formResult.type = {
            value: directEffect.project_result.type,
            label: resultTypes.find(
                (item) => item.value == directEffect.project_result.type,
            )?.label,
        }
        $formResult.trl = directEffect.project_result.trl
        $formResult.indicator = directEffect.project_result.indicator
        $formResult.means_of_verification =
            directEffect.project_result.means_of_verification
        resultDirectEffect = directEffect.description ?? $_('No data recorded')
    }

    function submitResult() {
        if (canCreateOrUpdate == undefined) {
            $formResult.post(
                route('projects.project_result', {
                    project: project.id,
                    project_result: $formResult.id,
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
    let formPrimaryObjective = useForm({
        objetivo_general: project.objetivo_general,
    })

    let showPrimaryObjectiveForm = false
    let problemStatement
    function showPrimaryObjectiveDialog() {
        reset()
        dialogTitle = 'Objetivo general'
        dialog_open = true
        showPrimaryObjectiveForm = true
        formID = 'primary-objective-form'
        problemStatement = project.planteamiento_problema
        $formPrimaryObjective.objetivo_general = project.objetivo_general
    }

    function submitGeneralObjetive() {
        if (canCreateOrUpdate == undefined) {
            $formPrimaryObjective.post(
                route('projects.objetivo_general', project.id),
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
     * Objetivos específicos
     */
    let formSpecificObjective = useForm({
        id: 0,
        description: '',
        number: 0,
    })

    let showSpecificObjectiveForm = false
    let SpecificObjectiveDirectCause
    function showSpecificObjectiveDialog(directCause, number) {
        reset()
        code = 'OBJ-ESP-' + directCause.specific_objective.id
        dialogTitle = directCause.specific_objective.number
        dialog_open = true
        showSpecificObjectiveForm = true
        formID = 'specific-objective-form'
        $formSpecificObjective.id = directCause.specific_objective.id
        $formSpecificObjective.description =
            directCause.specific_objective.description
        $formSpecificObjective.number = number
        SpecificObjectiveDirectCause =
            directCause.description ?? $_('No data recorded')
    }

    function submitSpecificObjective() {
        if (canCreateOrUpdate == undefined) {
            $formSpecificObjective.post(
                route('projects.specific_objective', {
                    project: project.id,
                    specific_objective: $formSpecificObjective.id,
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
    let formActivity = useForm({
        id: 0,
        indirect_cause_id: 0,
        specific_objective_id: 0,
        description: '',
        fecha_incio: '',
        fecha_finalizacion: '',
    })

    let showActivityForm = false
    let activityIndirectCause
    function showActivityDialog(indirectCause, specificObjective) {
        reset()
        code =
            indirectCause.activity.id != null
                ? 'OBJ-ESP-' +
                  specificObjective +
                  '-ACT-' +
                  indirectCause.activity.id
                : ''
        dialogTitle = 'Actividad'
        dialog_open = true
        showActivityForm = true
        formID = 'activity-form'
        $formActivity.id = indirectCause.activity.id
        $formActivity.indirect_cause_id =
            indirectCause.activity.indirect_cause_i
        $formActivity.specific_objective_id = specificObjective
        $formActivity.description = indirectCause.activity.description
        $formActivity.fecha_incio = indirectCause.activity.fecha_incio
        $formActivity.fecha_finalizacion =
            indirectCause.activity.fecha_finalizacion
        activityIndirectCause =
            indirectCause.description ?? $_('No data recorded')
    }

    function submitActivity() {
        if (canCreateOrUpdate == undefined) {
            $formActivity.post(
                route('projects.activity', {
                    call: call.id,
                    project: project.id,
                    activity: $formActivity.id,
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
        showPrimaryObjectiveForm = false
        showActivityForm = false
        showResultForm = false
        showImpactForm = false
        showGeneralInfo = false
        showSpecificObjectiveForm = false
        dialogTitle = ''
        code = ''
        formID = ''

        $formImpact.reset()
        $formResult.reset()
        $formPrimaryObjective.reset()
        $formSpecificObjective.reset()
        $formActivity.reset()
    }

    function closeDialog() {
        reset()
        dialog_open = false
    }

    onMount(() => {
        const impact = document.querySelector('#impact-tooltip-placement')
        const impactTooltip = document.querySelector('#impact-tooltip')
        const arrowImpact = document.querySelector('#arrow-impact')

        const result = document.querySelector('#result-tooltip-placement')
        const resultTooltip = document.querySelector('#result-tooltip')
        const arrowResult = document.querySelector('#arrow-result')

        const primaryObjective = document.querySelector(
            '#primary-objective-tooltip-placement',
        )
        const primaryObjectiveTooltip = document.querySelector(
            '#primary-objective-tooltip',
        )
        const arrowPrimaryObjective = document.querySelector(
            '#arrow-primary-objective',
        )

        const specificObjective = document.querySelector(
            '#specific-objective-tooltip-placement',
        )
        const specificObjectiveTooltip = document.querySelector(
            '#specific-objective-tooltip',
        )
        const arrowSpecificObjective = document.querySelector(
            '#arrow-specific-objective',
        )

        const activity = document.querySelector('#activity-tooltip-placement')
        const activityTooltip = document.querySelector('#activity-tooltip')
        const arrowActivity = document.querySelector('#arrow-activity')

        let tooltips = [
            {
                element: {
                    target: impact,
                    tooltip: impactTooltip,
                    arrow: arrowImpact,
                },
            },
            {
                element: {
                    target: result,
                    tooltip: resultTooltip,
                    arrow: arrowResult,
                },
            },
            {
                element: {
                    target: primaryObjective,
                    tooltip: primaryObjectiveTooltip,
                    arrow: arrowPrimaryObjective,
                },
            },
            {
                element: {
                    target: specificObjective,
                    tooltip: specificObjectiveTooltip,
                    arrow: arrowSpecificObjective,
                },
            },
            {
                element: {
                    target: activity,
                    tooltip: activityTooltip,
                    arrow: arrowActivity,
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
        <h1 class="text-4xl text-center">Árbol de objetivos</h1>
        <p class="text-center">
            Debe generar el árbol de objetivos iniciando desde el objetivo
            general, los respectivos objetivos específicos, resultados,
            actividades e impactos.
        </p>

        <div class="mt-16">
            <div class="flex mb-14">
                {#each directEffects as directEffect, i}
                    <div class="flex-1">
                        <!-- Impactos -->
                        {#if i == 0}
                            <div
                                id="impact-tooltip"
                                class="tooltip bg-black"
                                role="tooltip"
                                data-popper-placement="left"
                            >
                                <small>Impactos</small>
                                <div
                                    id="arrow-impact"
                                    class="arrow"
                                    data-popper-arrow
                                />
                            </div>
                        {/if}
                        <div
                            class="flex mb-14"
                            id={i == 0 ? 'impact-tooltip-placement' : ''}
                            aria-describedby={i == 0 ? 'tooltip' : ''}
                        >
                            {#each directEffect.indirect_effects as indirectEffect}
                                <div class="flex-1 results relative">
                                    <div
                                        on:click={showImpactDialog(
                                            indirectEffect,
                                            indirectEffect.id,
                                            directEffect.project_result.id,
                                        )}
                                        class="{indirectEffect.impact &&
                                        indirectEffect.impact.description !=
                                            null
                                            ? 'bg-orangered-500 hover:bg-orangered-600'
                                            : 'bg-orangered-400 hover:bg-orangered-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5"
                                    >
                                        <p
                                            class="h-32 overflow-y-hidden p-2.5 text-sm text-white"
                                        >
                                            {#if indirectEffect.impact}
                                                <small
                                                    class="title block font-bold mb-2 line-height-1"
                                                    >RES-{directEffect
                                                        .project_result
                                                        .id}-IMP-{indirectEffect
                                                        .impact.id}</small
                                                >
                                                {#if indirectEffect.impact.description != null && indirectEffect.impact.description.length > 0}
                                                    {indirectEffect.impact
                                                        .description}
                                                {/if}
                                            {/if}
                                        </p>
                                    </div>
                                </div>
                            {/each}
                            {#each { length: 3 - directEffect.indirect_effects.length } as _empty}
                                <div
                                    on:click={() => showGeneralInfoDialog(1)}
                                    class="flex-1 results relative"
                                >
                                    <div
                                        class="h-36 bg-gray-300 rounded shadow-lg hover:bg-gray-400 cursor-pointer mr-1.5"
                                    >
                                        <p
                                            class="h-32 overflow-y-hidden p-2.5 text-sm text-white"
                                        />
                                    </div>
                                </div>
                            {/each}
                        </div>
                        <!-- Resultado -->
                        {#if i == 0}
                            <div
                                id="result-tooltip"
                                class="tooltip bg-black"
                                role="tooltip"
                                data-popper-placement="left"
                            >
                                <small>Resultados</small>
                                <div
                                    id="arrow-result"
                                    class="arrow"
                                    data-popper-arrow
                                />
                            </div>
                        {/if}
                        <div
                            class="results relative flex-1"
                            id={i == 0 ? 'result-tooltip-placement' : ''}
                            aria-describedby={i == 0 ? 'tooltip' : ''}
                        >
                            <div
                                on:click={showResultDialog(directEffect)}
                                class="{directEffect.project_result
                                    .description != null
                                    ? 'bg-orangered-500 hover:bg-orangered-600'
                                    : 'bg-orangered-400 hover:bg-orangered-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5"
                            >
                                <p
                                    class="h-32 overflow-hidden text-white p-2.5 text-sm line-height-1"
                                >
                                    <small
                                        class="title block font-bold mb-2 line-height-1"
                                        >RES-{directEffect.project_result
                                            .id}</small
                                    >
                                    {#if directEffect.project_result.description != null && directEffect.project_result.description.length > 0}
                                        {directEffect.project_result
                                            .description}
                                    {/if}
                                </p>
                            </div>
                        </div>
                    </div>
                {/each}
            </div>

            <!-- Objetivo general -->
            <div
                id="primary-objective-tooltip"
                class="tooltip bg-black"
                role="tooltip"
                data-popper-placement="left"
            >
                <small>Objetivo general</small>
                <div
                    id="arrow-primary-objective"
                    class="arrow"
                    data-popper-arrow
                />
            </div>
            <div
                class="primary-objective relative"
                id="primary-objective-tooltip-placement"
                aria-describedby="tooltip"
            >
                <div
                    on:click={showPrimaryObjectiveDialog}
                    class="{project.objetivo_general != null
                        ? 'bg-orangered-500 hover:bg-orangered-600'
                        : 'bg-orangered-400 hover:bg-orangered-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5"
                >
                    {#if project.objetivo_general != null && project.objetivo_general.length > 0}
                        <p
                            class="h-32 overflow-hidden text-white p-2.5 text-sm line-height-1"
                        >
                            {project.objetivo_general}
                        </p>
                    {/if}
                </div>
            </div>

            <div class="flex mt-14">
                {#each directCauses as directCause, i}
                    <div class="flex-1">
                        <!-- Objetivo específico -->
                        {#if i == 0}
                            <div
                                id="specific-objective-tooltip"
                                class="tooltip bg-black"
                                role="tooltip"
                                data-popper-placement="left"
                            >
                                <small class="block line-height-1"
                                    >Objetivos <br /> específicos</small
                                >
                                <div
                                    id="arrow-specific-objective"
                                    class="arrow"
                                    data-popper-arrow
                                />
                            </div>
                        {/if}
                        <div
                            class="specific-objectives relative flex-1"
                            id={i == 0
                                ? 'specific-objective-tooltip-placement'
                                : ''}
                            aria-describedby={i == 0 ? 'tooltip' : ''}
                        >
                            <div
                                on:click={showSpecificObjectiveDialog(
                                    directCause,
                                    i + 1,
                                )}
                                class="{directCause.specific_objective
                                    .description != null
                                    ? 'bg-orangered-500 hover:bg-orangered-600'
                                    : 'bg-orangered-400 hover:bg-orangered-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5"
                            >
                                <p
                                    class="h-32 overflow-hidden text-white p-2.5 text-sm line-height-1"
                                >
                                    <small
                                        class="title block font-bold mb-2 line-height-1"
                                    >
                                        OBJ-ESP-{directCause.specific_objective
                                            .id}
                                    </small>
                                    {#if directCause.specific_objective.description != null && directCause.specific_objective.description.length > 0}
                                        {directCause.specific_objective
                                            .description}
                                    {/if}
                                </p>
                            </div>
                        </div>
                        <!-- Actividades -->
                        {#if i == 0}
                            <div
                                id="activity-tooltip"
                                class="tooltip bg-black"
                                role="tooltip"
                                data-popper-placement="left"
                            >
                                <small>Actividades</small>
                                <div
                                    id="arrow-activity"
                                    class="arrow"
                                    data-popper-arrow
                                />
                            </div>
                        {/if}
                        <div
                            class="flex mt-14"
                            id={i == 0 ? 'activity-tooltip-placement' : ''}
                            aria-describedby={i == 0 ? 'tooltip' : ''}
                        >
                            {#each directCause.indirect_causes as indirectCause}
                                <div
                                    class="specific-objectives relative flex-1"
                                >
                                    <div
                                        on:click={showActivityDialog(
                                            indirectCause,
                                            directCause.specific_objective.id,
                                        )}
                                        class="{indirectCause.activity &&
                                        indirectCause.activity.description !=
                                            null
                                            ? 'bg-orangered-500 hover:bg-orangered-600'
                                            : 'bg-orangered-400 hover:bg-orangered-500'} tree-label h-36 rounded shadow-lg cursor-pointer mr-1.5"
                                    >
                                        <p
                                            class="h-32 overflow-y-hidden p-2.5 text-sm text-white"
                                        >
                                            {#if indirectCause.activity}
                                                <small
                                                    class="title block font-bold mb-2 line-height-1"
                                                >
                                                    OBJ-ESP-{directCause
                                                        .specific_objective
                                                        .id}-ACT-{indirectCause
                                                        .activity.id}
                                                </small>
                                                {#if indirectCause.activity.description != null && indirectCause.activity.description.length > 0}
                                                    {indirectCause.activity
                                                        .description}
                                                {/if}
                                            {/if}
                                        </p>
                                    </div>
                                </div>
                            {/each}
                            {#each { length: 3 - directCause.indirect_causes.length } as _empty, j}
                                <div
                                    id="{j}_empty_activity"
                                    on:click={() => showGeneralInfoDialog(2)}
                                    class="specific-objectives relative flex-1"
                                >
                                    <div
                                        class="h-36 bg-gray-300 rounded shadow-lg hover:bg-gray-400 cursor-pointer mr-1.5"
                                    >
                                        <p
                                            class="h-32 overflow-y-hidden p-2.5 text-sm text-white"
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
        <div slot="title">
            <div class="mb-10 text-center">
                <div class="text-primary">
                    {dialogTitle}
                </div>
                {#if code}
                    <small class="block text-primary-light">
                        Código: {code}
                    </small>
                {/if}
            </div>
        </div>
        <div slot="content">
            {#if showActivityForm}
                <form
                    on:submit|preventDefault={submitActivity}
                    id="activity-form"
                >
                    <fieldset disabled={canCreateOrUpdate}>
                        <p class="block font-medium mb-2 text-gray-700 text-sm">
                            Causa indirecta
                        </p>
                        <p class="mb-20 whitespace-pre-line">
                            {activityIndirectCause}
                        </p>
                        <p class="mt-1 text-center">Fecha de ejecución</p>
                        <div class="mt-1 mb-20 flex items-start justify-around">
                            <div
                                class="mt-4 flex {errors.fecha_incio
                                    ? ''
                                    : 'items-center'}"
                            >
                                <Label
                                    labelFor="fecha_incio"
                                    class={errors.fecha_incio
                                        ? 'top-3.5 relative'
                                        : ''}
                                    value="Del"
                                />
                                <div class="ml-4">
                                    <Input
                                        id="fecha_incio"
                                        type="date"
                                        class="mt-1 block w-full"
                                        bind:value={$formActivity.fecha_incio}
                                        required
                                    />
                                </div>
                            </div>
                            <div
                                class="mt-4 flex {errors.fecha_finalizacion
                                    ? ''
                                    : 'items-center'}"
                            >
                                <Label
                                    labelFor="fecha_finalizacion"
                                    class="ml-4 {errors.fecha_finalizacion
                                        ? 'top-3.5 relative'
                                        : ''}"
                                    value="hasta"
                                />
                                <div class="ml-4">
                                    <Input
                                        id="fecha_finalizacion"
                                        type="date"
                                        class="mt-1 block w-full"
                                        bind:value={$formActivity.fecha_finalizacion}
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                        {#if errors.fecha_incio || errors.fecha_finalizacion}
                            <div class="mb-20">
                                <InputError
                                    classes="text-center"
                                    message={errors.fecha_incio}
                                />
                                <InputError
                                    classes="text-center"
                                    message={errors.fecha_finalizacion}
                                />
                            </div>
                        {/if}
                        <div>
                            <Label
                                class="mb-4"
                                labelFor="description"
                                value="Descripción"
                            />
                            <Textarea
                                rows="4"
                                id="description"
                                error={errors.description}
                                bind:value={$formActivity.description}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {:else if showSpecificObjectiveForm}
                <form
                    on:submit|preventDefault={submitSpecificObjective}
                    id="specific-objective-form"
                >
                    <fieldset disabled={canCreateOrUpdate}>
                        <p class="block font-medium mb-2 text-gray-700 text-sm">
                            Causa directa
                        </p>

                        <p class="mb-20 whitespace-pre-line">
                            {SpecificObjectiveDirectCause}
                        </p>
                        <div>
                            <Label
                                class="mb-4"
                                labelFor="description"
                                value="Descripción"
                            />
                            <Textarea
                                rows="4"
                                id="description"
                                error={errors.description}
                                bind:value={$formSpecificObjective.description}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {:else if showPrimaryObjectiveForm}
                <form
                    on:submit|preventDefault={submitGeneralObjetive}
                    id="primary-objective-form"
                >
                    <fieldset disabled={canCreateOrUpdate}>
                        <p class="block font-medium mb-2 text-gray-700 text-sm">
                            Planteamiento del problema
                        </p>

                        <p class="mb-20 whitespace-pre-line">
                            {problemStatement}
                        </p>
                        <div>
                            <Label
                                class="mb-4"
                                labelFor="objetivo_general"
                                value="Objetivo general"
                            />
                            <InfoMessage
                                message="Establece que pretende alcanzar la investigación. Se inicia con un verbo en modo infinitivo, es medible y alcanzable. Responde al Qué, Cómo y el Para qué"
                            />
                            <Textarea
                                rows="4"
                                id="objetivo_general"
                                error={errors.objetivo_general}
                                bind:value={$formPrimaryObjective.objetivo_general}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {:else if showResultForm}
                <form
                    on:submit|preventDefault={submitResult}
                    id="project-result-form"
                >
                    <fieldset disabled={canCreateOrUpdate}>
                        <p class="block font-medium mb-2 text-gray-700 text-sm">
                            Efecto directo
                        </p>
                        <p class="mb-20 whitespace-pre-line">
                            {resultDirectEffect}
                        </p>

                        <p class="block font-medium mb-2 text-gray-700 text-sm">
                            {specificObjectiveDescription.number}
                        </p>
                        <p class="mb-20 whitespace-pre-line">
                            {specificObjectiveDescription.description}
                        </p>
                        <div class="mb-20">
                            <Label labelFor="type" value="Tipo" />
                            <Select
                                id="type"
                                items={resultTypes}
                                bind:selectedValue={$formResult.type}
                                error={errors.type}
                                autocomplete="off"
                                placeholder="Seleccione un tipo"
                                required
                            />
                        </div>
                        <div class="mb-20">
                            <Label labelFor="description" value="Descripción" />
                            <Textarea
                                rows="4"
                                id="description"
                                maxlength="200"
                                error={errors.description}
                                bind:value={$formResult.description}
                                required
                            />
                        </div>
                        <div class="mb-20">
                            <Label labelFor="trl" value="TRL" />
                            <Input
                                id="trl"
                                type="number"
                                max="9"
                                min="1"
                                class="block w-full"
                                error={errors.trl}
                                bind:value={$formResult.trl}
                                required
                            />
                        </div>
                        <div class="mb-20">
                            <Label labelFor="indicator" value="Indicador" />
                            <Textarea
                                rows="4"
                                id="indicator"
                                maxlength="200"
                                error={errors.indicator}
                                bind:value={$formResult.indicator}
                                required
                            />
                        </div>
                        <div class="mb-20">
                            <Label
                                labelFor="means_of_verification"
                                value="Medio de verificación"
                            />
                            <Textarea
                                rows="4"
                                id="means_of_verification"
                                maxlength="200"
                                error={errors.means_of_verification}
                                bind:value={$formResult.means_of_verification}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {:else if showImpactForm}
                <form on:submit|preventDefault={submitImpact} id="impact-form">
                    <fieldset disabled={canCreateOrUpdate}>
                        <p class="block font-medium mb-2 text-gray-700 text-sm">
                            Efecto indirecto
                        </p>

                        <p class="mt-4 whitespace-pre-line">
                            {impactIndirectEffect}
                        </p>
                        <div class="mt-4">
                            <Label labelFor="type" value="Tipo" />
                            <Select
                                id="type"
                                items={impactTypes}
                                bind:selectedValue={$formImpact.type}
                                error={errors.type}
                                autocomplete="off"
                                placeholder="Seleccione un tipo"
                                required
                            />
                        </div>
                        <div class="mt-4">
                            <Label
                                class="mb-4"
                                labelFor="description"
                                value="Descripción"
                            />
                            <Textarea
                                rows="4"
                                id="description"
                                error={errors.description}
                                bind:value={$formImpact.description}
                                required
                            />
                        </div>
                    </fieldset>
                </form>
            {:else if showGeneralInfo}
                {#if generalInfoType == 1}
                    <p>
                        Para poder editar este impacto, primero debe generar el
                        efecto indirecto en el árbol de problemas.
                    </p>
                {/if}
                {#if generalInfoType == 2}
                    <p>
                        Para poder editar esta actividad, primero debe generar
                        la causa indirecta en el árbol de problemas.
                    </p>
                {/if}
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
    .results.relative.flex-1:before {
        content: '';
        bottom: -40%;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 57px;
        background: #ff906e;
    }

    .specific-objectives.relative.flex-1:before {
        content: '';
        top: -38%;
        position: absolute;
        right: 50%;
        width: 2px;
        height: 55px;
        background: #ff906e;
    }

    .line-height-1 {
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
