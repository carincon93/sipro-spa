<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import Textarea from '@/Components/Textarea'

    export let convocatoria
    export let proyecto
    export let errors
    export let nivelesRiesgo
    export let tiposRiesgo
    export let probabilidadesRiesgo
    export let impactosRiesgo

    $: $title = 'Crear análisis de riesgo'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        nivel: '',
        tipo: '',
        descripcion: '',
        impacto: '',
        probabilidad: '',
        efectos: '',
        medidas_mitigacion: '',
    })

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [1, 5, 8])) {
            $form.post(route('convocatorias.proyectos.analisis-riesgos.store', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin || checkPermission(authUser, [1, 5, 8])}
                        <a use:inertia href={route('convocatorias.proyectos.analisis-riesgos.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600">Análisis de riesgos</a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkPermission(authUser, [1, 5, 8]) ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nivel" value="Nivel de riesgo" />
                    <Select id="nivel" items={nivelesRiesgo} bind:selectedValue={$form.nivel} error={errors.nivel} autocomplete="off" placeholder="Seleccione el nivel del riesgo" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tipo" value="Tipo de riesgo" />
                    <Select id="tipo" items={tiposRiesgo} bind:selectedValue={$form.tipo} error={errors.tipo} autocomplete="off" placeholder="Seleccione el tipo de riesgo" required />
                </div>

                <div class="mt-4">
                    <Textarea label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="probabilidad" value="Probabilidad" />
                    <Select id="probabilidad" items={probabilidadesRiesgo} bind:selectedValue={$form.probabilidad} error={errors.probabilidad} autocomplete="off" placeholder="Seleccione la probabilidad" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="impacto" value="Impactos" />
                    <Select id="impacto" items={impactosRiesgo} bind:selectedValue={$form.impacto} error={errors.impacto} autocomplete="off" placeholder="Seleccione la probabilidad" required />
                </div>

                <div class="mt-4">
                    <Textarea label="Efectos" maxlength="40000" id="efectos" error={errors.efectos} bind:value={$form.efectos} required />
                </div>

                <div class="mt-4">
                    <Textarea label="Medidas de mitigación" maxlength="40000" id="medidas_mitigacion" error={errors.medidas_mitigacion} bind:value={$form.medidas_mitigacion} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkPermission(authUser, [1, 5, 8])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" tipo="submit">Crear análisis de riesgo</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
