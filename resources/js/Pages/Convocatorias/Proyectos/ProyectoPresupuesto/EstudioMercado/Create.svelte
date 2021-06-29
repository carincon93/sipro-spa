<script>
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import File from '@/Shared/File'
    import Switch from '@/Shared/Switch'
    import PercentageProgress from '@/Shared/PercentageProgress'
    import InfoMessage from '@/Shared/InfoMessage'

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
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        requiere_tercer_estudio_mercado: false,
        convocatoria_presupuesto_id: convocatoriaPresupuesto.id,
        numero_items: '',
        ficha_tecnica: null,

        primer_valor: '',
        primer_empresa: '',
        primer_archivo: null,

        segundo_valor: '',
        segunda_empresa: '',
        segundo_archivo: null,

        tercer_valor: '',
        tercer_empresa: '',
        tercer_archivo: null,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)) {
            ;(sending = true),
                $form.post(route('convocatorias.proyectos.presupuesto.lote.store', [convocatoria.id, proyecto.id, proyectoPresupuesto]), {
                    onStart: () => (sending = true),
                    onFinish: () => {
                        ;(sending = false), (dialogOpen = false)
                        document.getElementById('ficha_tecnica') ? (document.getElementById('ficha_tecnica').value = null) : null
                        document.getElementById('primer_archivo') ? (document.getElementById('primer_archivo').value = null) : null
                        document.getElementById('segundo_archivo') ? (document.getElementById('segundo_archivo').value = null) : null
                        document.getElementById('tercer_archivo') ? (document.getElementById('tercer_archivo').value = null) : null
                    },
                    onError: () => {
                        $form.requiere_tercer_estudio_mercado = errors.tercer_valor || errors.tercer_empresa || errors.tercer_archivo ? true : false
                    },
                    onSuccess: () => {
                        if (!$page.props.flash.error) {
                            $form.reset()
                        }
                    },
                })
        }
    }

    let average
    $: average = (parseInt($form.primer_valor) + parseInt($form.segundo_valor) + (parseInt($form.tercer_valor) > 0 ? parseInt($form.tercer_valor) : 0)) / (parseInt($form.tercer_valor) > 0 ? 3 : 2)
</script>

<form on:submit|preventDefault={submit} id="form-estudio-mercado">
    <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true) ? undefined : true}>
        <div class="mt-4">
            <InfoMessage class="mb-2" message="Diligencie los campos de <strong>Cantidad</strong> y <strong>Ficha técnica</strong> solo si este rubro es de maquinaria/equipos." />

            <Label class="mb-4" labelFor="numero_items" value="Indique la cantidad de maquinaria/equipos referenciado en el ANEXO 2 Fichas técnicas para maquinaria y equipos" />
            <Input label="Cantidad" id="numero_items" type="number" input$min="1" class="mt-1" bind:value={$form.numero_items} error={errors.numero_items} />
        </div>

        <div class="mt-4">
            <Label class="mb-4" labelFor="ficha_tecnica" value="ANEXO 2. Fichas técnicas para maquinaria y equipos" />
            <File id="ficha_tecnica" type="file" accept="application/pdf" maxSize="10000" class="mt-1" bind:value={$form.ficha_tecnica} error={errors.ficha_tecnica} />
        </div>

        <h1 class="text-center mt-20 mb-20">Primer estudio de mercado</h1>

        <div class="mt-4">
            <Input label="Valor (incluido IVA)" id="primer_valor" type="number" input$min="1" class="mt-1" bind:value={$form.primer_valor} error={errors.primer_valor} required />
        </div>

        <div class="mt-4">
            <Input label="Nombre de la empresa" id="primer_empresa" type="text" class="mt-1" bind:value={$form.primer_empresa} error={errors.primer_empresa} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="primer_archivo" value="Soporte" />
            <File id="primer_archivo" type="file" accept="application/pdf" maxSize="10000" class="mt-1" bind:value={$form.primer_archivo} error={errors.primer_archivo} required />
        </div>

        <h1 class="text-center mt-20 mb-20">Segundo estudio de mercado</h1>

        <div class="mt-4">
            <Input label="Valor (incluido IVA)" id="segundo_valor" type="number" input$min="1" class="mt-1" bind:value={$form.segundo_valor} error={errors.segundo_valor} required />
        </div>

        <div class="mt-4">
            <Input label="Nombre de la empresa" id="segunda_empresa" type="text" class="mt-1" bind:value={$form.segunda_empresa} error={errors.segunda_empresa} required />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="segundo_archivo" value="Soporte" />
            <File id="segundo_archivo" type="file" accept="application/pdf" maxSize="10000" class="mt-1" bind:value={$form.segundo_archivo} error={errors.segundo_archivo} required />
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
                <Input label="Valor (incluido IVA)" id="tercer_valor" type="number" input$min="0" class="mt-1" bind:value={$form.tercer_valor} error={errors.tercer_valor} required />
            </div>

            <div class="mt-4">
                <Input label="Nombre de la empresa" id="tercer_empresa" type="text" class="mt-1" bind:value={$form.tercer_empresa} error={errors.tercer_empresa} required />
            </div>

            <div class="mt-4">
                <Label required class="mb-4" labelFor="tercer_archivo" value="Soporte" />
                <File id="tercer_archivo" type="file" accept="application/pdf" maxSize="10000" class="mt-1" bind:value={$form.tercer_archivo} error={errors.tercer_archivo} required />
            </div>
        {/if}
    </fieldset>
    <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
        <p>
            Valor promedio: ${average > 0 ? new Intl.NumberFormat('de-DE').format(average) : 0} COP
        </p>
    </div>
</form>
{#if $form.progress}
    <PercentageProgress percentage={$form.progress.percentage} />
{/if}
