<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import PercentageProgress from '@/Shared/PercentageProgress'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoPresupuesto

    $: $title = 'Crear soporte'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        soporte: '',
        empresa: '',
    })

    function submit() {
        if (proyecto.allowed.to_update) {
            $form.post(route('convocatorias.proyectos.presupuesto.soportes.store', [convocatoria.id, proyecto.id, proyectoPresupuesto]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="flex">
                    <a use:inertia href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="text-violet-400 hover:text-violet-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block"> Presupuesto </a>
                    <span class="text-violet-400 font-medium ml-2 mr-2">/</span>
                    <a use:inertia href={route('convocatorias.proyectos.presupuesto.edit', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])} class="text-violet-400 hover:text-violet-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block">
                        {proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion}
                    </a>
                    <span class="text-violet-400 font-medium ml-2 mr-2">/</span>
                    <a use:inertia href={route('convocatorias.proyectos.presupuesto.soportes.index', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])} class="text-violet-400 hover:text-violet-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block"> Soportes </a>
                    <span class="text-violet-400 font-medium ml-2 mr-2">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit} id="form-estudio-mercado">
            <fieldset class="p-8" disabled={proyecto.allowed.to_update ? undefined : true}>
                <div class="mt-4">
                    <Input label="Nombre de la empresa" id="empresa" type="text" class="mt-1" bind:value={$form.empresa} error={errors.empresa} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="soporte" value="Url del soporte/cotización" />
                    <Input label="Url" id="soporte" type="url" class="mt-1" error={errors.soporte} placeholder="Url https://www.google.com.co" bind:value={$form.soporte} required />
                </div>
            </fieldset>
            <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                {#if proyecto.allowed.to_update}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Crear soporte</LoadingButton>
                {/if}
            </div>
        </form>
        {#if $form.progress}
            <PercentageProgress percentage={$form.progress.percentage} />
        {/if}
    </div>
</AuthenticatedLayout>
