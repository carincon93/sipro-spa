<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import Textarea from '@/Components/Textarea'

    export let call
    export let project
    export let errors
    export let riskLevels
    export let riskTypes
    export let riskProbabilities
    export let riskImpacts

    $: $title = $_('Create') + ' ' + $_('Risk analysis.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.index') ==
        'risk-analysis.index'
    let canShowRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.show') ==
        'risk-analysis.show'
    let canCreateRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.create') ==
        'risk-analysis.create'
    let canEditRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.edit') ==
        'risk-analysis.edit'
    let canDestroyRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.destroy') ==
        'risk-analysis.delete'

    let sending = false
    let form = useForm({
        level: '',
        type: '',
        description: '',
        impact: '',
        probability: '',
        effects: '',
        mitigation_measures: '',
    })

    function submit() {
        if (canCreateRiskAnalysis || isSuperAdmin) {
            $form.post(
                route('calls.projects.risk-analysis.store', [
                    call.id,
                    project.id,
                ]),
                {
                    onStart: () => (sending = true),
                    onFinish: () => (sending = false),
                },
            )
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1>
                    {#if canIndexRiskAnalysis || canCreateRiskAnalysis || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('calls.projects.risk-analysis.index', [
                                call.id,
                                project.id,
                            ])}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Risk analysis.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canCreateRiskAnalysis || isSuperAdmin
                    ? undefined
                    : true}
            >
                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="level"
                        value="Nivel de riesgo"
                    />
                    <Select
                        id="level"
                        items={riskLevels}
                        bind:selectedValue={$form.level}
                        error={errors.level}
                        autocomplete="off"
                        placeholder="Seleccione el nivel del riesgo"
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="type"
                        value="Tipo de riesgo"
                    />
                    <Select
                        id="type"
                        items={riskTypes}
                        bind:selectedValue={$form.type}
                        error={errors.type}
                        autocomplete="off"
                        placeholder="Seleccione el tipo de riesgo"
                        required
                    />
                </div>

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
                        bind:value={$form.description}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="probability"
                        value="Probabilidad"
                    />
                    <Select
                        id="probability"
                        items={riskProbabilities}
                        bind:selectedValue={$form.probability}
                        error={errors.probability}
                        autocomplete="off"
                        placeholder="Seleccione la probabilidad"
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="impact"
                        value="Impactos"
                    />
                    <Select
                        id="impact"
                        items={riskImpacts}
                        bind:selectedValue={$form.impact}
                        error={errors.impact}
                        autocomplete="off"
                        placeholder="Seleccione la probabilidad"
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="effects"
                        value="Efectos"
                    />
                    <Textarea
                        rows="4"
                        id="effects"
                        error={errors.effects}
                        bind:value={$form.effects}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="mitigation_measures"
                        value="Medidas de mitigación"
                    />
                    <Textarea
                        rows="4"
                        id="mitigation_measures"
                        error={errors.mitigation_measures}
                        bind:value={$form.mitigation_measures}
                        required
                    />
                </div>
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canCreateRiskAnalysis || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear
                        {$_('Risk analysis.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
