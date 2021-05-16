<script>
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import InputError from '@/Components/InputError'
    import Label from '@/Components/Label'
    import File from '@/Components/File'
    import Switch from '@/Components/Switch'
    import PercentageProgress from '@/Components/PercentageProgress'

    export let errors
    export let call
    export let project
    export let projectSennovaBudget
    export let callBudget
    export let dialogOpen

    export let sending = false

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let form = useForm({
        requires_third_market_research: false,
        call_budget_id: callBudget.id,
        qty_items: '',
        fact_sheet: '',
        first_price_quote: '',
        first_company_name: '',
        first_price_quote_file: '',

        second_price_quote: '',
        second_company_name: '',
        second_price_quote_file: '',

        third_price_quote: '',
        third_company_name: '',
        third_price_quote_file: '',
    })

    function submit() {
        if (isSuperAdmin) {
            ;(sending = true),
                $form.post(route('calls.projects.project-sennova-budgets.project-budget-batches.store', [call.id, project.id, projectSennovaBudget]), {
                    onStart: () => (sending = true),
                    onFinish: () => {
                        ;(sending = false), (dialogOpen = false)
                    },
                    onError: () => {
                        $form.requires_third_market_research = errors.third_price_quote || errors.third_company_name || errors.third_price_quote_file ? true : false
                    },
                    onSuccess: () => {
                        $form.reset()
                    },
                })
        }
    }

    let average
    // afterUpdate(() => {
    $: average = (parseInt($form.first_price_quote) + parseInt($form.second_price_quote) + (parseInt($form.third_price_quote) > 0 ? parseInt($form.third_price_quote) : 0)) / (parseInt($form.third_price_quote) > 0 ? 3 : 2)
    // })
</script>

<form on:submit|preventDefault={submit} id="market-reseach-form">
    <fieldset class="p-8">
        <div class="mt-4">
            <Label required class="mb-4" labelFor="qty_items" value="Indique la cantidad requerida del producto o servicio relacionado" />
            <Input id="qty_items" type="number" min="1" class="mt-1 block w-full" bind:value={$form.qty_items} error={errors.qty_items} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="fact_sheet" value="ANEXO 2. Fichas técnicas para maquinaria y equipos" />
            <File id="fact_sheet" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.fact_sheet} error={errors.fact_sheet} required />
        </div>

        <h1 class="text-center mt-20 mb-20">Primer estudio de mercado</h1>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="first_price_quote" value="Valor (incluido IVA)" />
            <Input id="first_price_quote" type="number" min="1" class="mt-1 block w-full" bind:value={$form.first_price_quote} error={errors.first_price_quote} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="first_company_name" value="Nombre de la empresa" />
            <Input id="first_company_name" type="text" class="mt-1 block w-full" bind:value={$form.first_company_name} error={errors.first_company_name} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="first_price_quote_file" value="Soporte" />
            <File id="first_price_quote_file" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.first_price_quote_file} error={errors.first_price_quote_file} required />
        </div>

        <h1 class="text-center mt-20 mb-20">Segundo estudio de mercado</h1>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="second_price_quote" value="Valor (incluido IVA)" />
            <Input id="second_price_quote" type="number" min="1" class="mt-1 block w-full" bind:value={$form.second_price_quote} error={errors.second_price_quote} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="second_company_name" value="Nombre de la empresa" />
            <Input id="second_company_name" type="text" class="mt-1 block w-full" bind:value={$form.second_company_name} error={errors.second_company_name} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="second_price_quote_file" value="Soporte" />
            <File id="second_price_quote_file" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.second_price_quote_file} error={errors.second_price_quote_file} required />
        </div>

        <div class="mt-8">
            <Label labelFor="requires_third_market_research" value="¿Requiere de un tercer estudio de mercado?" class="inline-block mb-4" />
            <br />
            <Switch bind:checked={$form.requires_third_market_research} />
            <InputError message={errors.requires_third_market_research} />
        </div>

        {#if $form.requires_third_market_research}
            <h1 class="text-center mt-20 mb-20">Tercer estudio de mercado</h1>
            <div class="mt-4">
                <Label required class="mb-4" labelFor="third_price_quote" value="Valor (incluido IVA)" />
                <Input id="third_price_quote" type="number" min="0" class="mt-1 block w-full" bind:value={$form.third_price_quote} error={errors.third_price_quote} required />
            </div>

            <div class="mt-4">
                <Label required class="mb-4" labelFor="third_company_name" value="Nombre de la empresa" />
                <Input id="third_company_name" type="text" class="mt-1 block w-full" bind:value={$form.third_company_name} error={errors.third_company_name} required />
            </div>

            <div class="mt-4">
                <Label required class="mb-4" labelFor="third_price_quote_file" value="Soporte" />
                <File id="third_price_quote_file" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.third_price_quote_file} error={errors.third_price_quote_file} required />
            </div>
        {/if}
    </fieldset>
    <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
        <p class="break-all w-72">
            Valor promedio: ${average > 0 ? new Intl.NumberFormat('de-DE').format(average) : 0} COP
        </p>
    </div>
</form>
{#if $form.progress}
    <PercentageProgress percentage={$form.progress.percentage} />
{/if}
