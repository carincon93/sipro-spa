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
    export let convocatoria
    export let proyecto
    export let proyectoPresupuesto
    export let convocatoriaPresupuesto
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
        requiere_tercer_estudio_mercado: false,
        convocatoria_presupuesto_id: convocatoriaPresupuesto.id,
        numero_items: '',
        ficha_tecnica: '',

        primer_valor: '',
        primer_empresa: '',
        primer_archivo: '',

        segundo_valor: '',
        segunda_empresa: '',
        segundo_archivo: '',

        tercer_valor: '',
        tercer_empresa: '',
        tercer_archivo: '',
    })

    function submit() {
        if (isSuperAdmin) {
            ;(sending = true),
                $form.post(route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.store', [convocatoria.id, proyecto.id, proyectoPresupuesto]), {
                    onStart: () => (sending = true),
                    onFinish: () => {
                        ;(sending = false), (dialogOpen = false)
                    },
                    onError: () => {
                        $form.requiere_tercer_estudio_mercado = errors.tercer_valor || errors.tercer_empresa || errors.tercer_archivo ? true : false
                    },
                    onSuccess: () => {
                        console.log('success')
                        $form.reset()
                    },
                })
        }
    }

    let average
    // afterUpdate(() => {
    $: average = (parseInt($form.primer_valor) + parseInt($form.segundo_valor) + (parseInt($form.tercer_valor) > 0 ? parseInt($form.tercer_valor) : 0)) / (parseInt($form.tercer_valor) > 0 ? 3 : 2)
    // })
</script>

<form on:submit|preventDefault={submit} id="form-estudio-mercado">
    <fieldset class="p-8">
        <div class="mt-4">
            <Label required class="mb-4" labelFor="numero_items" value="Indique la cantidad requerida del producto o servicio relacionado" />
            <Input id="numero_items" type="number" min="1" class="mt-1 block w-full" bind:value={$form.numero_items} error={errors.numero_items} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="ficha_tecnica" value="ANEXO 2. Fichas técnicas para maquinaria y equipos" />
            <File id="ficha_tecnica" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.ficha_tecnica} error={errors.ficha_tecnica} required />
        </div>

        <h1 class="text-center mt-20 mb-20">Primer estudio de mercado</h1>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="primer_valor" value="Valor (incluido IVA)" />
            <Input id="primer_valor" type="number" min="1" class="mt-1 block w-full" bind:value={$form.primer_valor} error={errors.primer_valor} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="primer_empresa" value="Nombre de la empresa" />
            <Input id="primer_empresa" type="text" class="mt-1 block w-full" bind:value={$form.primer_empresa} error={errors.primer_empresa} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="primer_archivo" value="Soporte" />
            <File id="primer_archivo" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.primer_archivo} error={errors.primer_archivo} required />
        </div>

        <h1 class="text-center mt-20 mb-20">Segundo estudio de mercado</h1>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="segundo_valor" value="Valor (incluido IVA)" />
            <Input id="segundo_valor" type="number" min="1" class="mt-1 block w-full" bind:value={$form.segundo_valor} error={errors.segundo_valor} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="segunda_empresa" value="Nombre de la empresa" />
            <Input id="segunda_empresa" type="text" class="mt-1 block w-full" bind:value={$form.segunda_empresa} error={errors.segunda_empresa} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="segundo_archivo" value="Soporte" />
            <File id="segundo_archivo" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.segundo_archivo} error={errors.segundo_archivo} required />
        </div>

        <div class="mt-8">
            <Label labelFor="requiere_tercer_estudio_mercado" value="¿Requiere de un tercer estudio de mercado?" class="inline-block mb-4" />
            <br />
            <Switch bind:checked={$form.requiere_tercer_estudio_mercado} />
            <InputError message={errors.requiere_tercer_estudio_mercado} />
        </div>

        {#if $form.requiere_tercer_estudio_mercado}
            <h1 class="text-center mt-20 mb-20">Tercer estudio de mercado</h1>
            <div class="mt-4">
                <Label required class="mb-4" labelFor="tercer_valor" value="Valor (incluido IVA)" />
                <Input id="tercer_valor" type="number" min="0" class="mt-1 block w-full" bind:value={$form.tercer_valor} error={errors.tercer_valor} required />
            </div>

            <div class="mt-4">
                <Label required class="mb-4" labelFor="tercer_empresa" value="Nombre de la empresa" />
                <Input id="tercer_empresa" type="text" class="mt-1 block w-full" bind:value={$form.tercer_empresa} error={errors.tercer_empresa} required />
            </div>

            <div class="mt-4">
                <Label required class="mb-4" labelFor="tercer_archivo" value="Soporte" />
                <File id="tercer_archivo" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.tercer_archivo} error={errors.tercer_archivo} required />
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
