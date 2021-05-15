<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import Textarea from '@/Components/Textarea'
    import Dialog from '@/Components/Dialog'

    export let call
    export let project
    export let errors
    export let riskAnalysis = {}
    export let riskLevels
    export let riskTypes
    export let riskProbabilities
    export let riskImpacts

    $: $title = riskAnalysis ? riskAnalysis.name : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexRiskAnalysis = authUser.can.find((element) => element == 'risk-analysis.index') == 'risk-analysis.index'
    let canShowRiskAnalysis = authUser.can.find((element) => element == 'risk-analysis.show') == 'risk-analysis.show'
    let canCreateRiskAnalysis = authUser.can.find((element) => element == 'risk-analysis.create') == 'risk-analysis.create'
    let canEditRiskAnalysis = authUser.can.find((element) => element == 'risk-analysis.edit') == 'risk-analysis.edit'
    let canDestroyRiskAnalysis = authUser.can.find((element) => element == 'risk-analysis.destroy') == 'risk-analysis.delete'

    let dialog_open = false
    let sending = false
    let form = useForm({
        level: {
            value: riskLevels.find((item) => item.label == riskAnalysis.level)?.value,
            label: riskLevels.find((item) => item.label == riskAnalysis.level)?.label,
        },
        type: {
            value: riskTypes.find((item) => item.label == riskAnalysis.type)?.value,
            label: riskTypes.find((item) => item.label == riskAnalysis.type)?.label,
        },
        description: riskAnalysis.description,
        impact: {
            value: riskImpacts.find((item) => item.label == riskAnalysis.impact)?.value,
            label: riskImpacts.find((item) => item.label == riskAnalysis.impact)?.label,
        },
        probability: {
            value: riskProbabilities.find((item) => item.label == riskAnalysis.probability)?.value,
            label: riskProbabilities.find((item) => item.label == riskAnalysis.probability)?.label,
        },
        effects: riskAnalysis.effects,
        mitigation_measures: riskAnalysis.mitigation_measures,
    })

    function submit() {
        if (canEditRiskAnalysis || isSuperAdmin) {
            $form.put(route('calls.projects.risk-analysis.update', [call.id, project.id, riskAnalysis.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (canDestroyRiskAnalysis || isSuperAdmin) {
            $form.delete(route('calls.projects.risk-analysis.destroy', [call.id, project.id, riskAnalysis.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if canIndexRiskAnalysis || canShowRiskAnalysis || canEditRiskAnalysis || canDestroyRiskAnalysis || isSuperAdmin}
                        <a use:inertia href={route('calls.projects.risk-analysis.index', [call.id, project.id])} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Risk analysis.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {riskAnalysis.type}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canEditRiskAnalysis || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="level" value="Nivel de riesgo" />
                    <Select id="level" items={riskLevels} bind:selectedValue={$form.level} error={errors.level} autocomplete="off" placeholder="Seleccione el nivel del riesgo" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="type" value="Tipo de riesgo" />
                    <Select id="type" items={riskTypes} bind:selectedValue={$form.type} error={errors.type} autocomplete="off" placeholder="Seleccione el tipo de riesgo" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="description" value="Descripción" />
                    <Textarea rows="4" id="description" error={errors.description} bind:value={$form.description} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="probability" value="Probabilidad" />
                    <Select id="probability" items={riskProbabilities} bind:selectedValue={$form.probability} error={errors.probability} autocomplete="off" placeholder="Seleccione la probabilidad" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="impact" value="Impactos" />
                    <Select id="impact" items={riskImpacts} bind:selectedValue={$form.impact} error={errors.impact} autocomplete="off" placeholder="Seleccione la probabilidad" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="effects" value="Efectos" />
                    <Textarea rows="4" id="effects" error={errors.effects} bind:value={$form.effects} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="mitigation_measures" value="Medidas de mitigación" />
                    <Textarea rows="4" id="mitigation_measures" error={errors.mitigation_measures} bind:value={$form.mitigation_measures} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canDestroyRiskAnalysis || isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}>
                        Eliminar
                        {$_('Risk analysis.singular').toLowerCase()}
                    </button>
                {/if}
                {#if canEditRiskAnalysis || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                        Editar
                        {$_('Risk analysis.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
    <Dialog bind:open={dialog_open}>
        <div slot="title" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro(a) que desea eliminar este recurso?
                <br />
                Todos los datos se eliminarán de forma permanente.
                <br />
                Está acción no se puede deshacer.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialog_open = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
