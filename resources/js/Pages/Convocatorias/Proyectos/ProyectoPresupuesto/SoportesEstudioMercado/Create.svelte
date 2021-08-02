<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import File from '@/Shared/File'
    import LoadingButton from '@/Shared/LoadingButton'
    import PercentageProgress from '@/Shared/PercentageProgress'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoPresupuesto

    $: $title = 'Crear soporte'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        soporte: '',
        empresa: '',
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)) {
            $form.post(route('convocatorias.proyectos.presupuesto.soportes.store', [convocatoria.id, proyecto.id, proyectoPresupuesto]), {
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
                <h1 class="flex">
                    {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 14, 16, 15, 20, 21])}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block"> Presupuesto </a>
                    {/if}
                    <span class="text-indigo-400 font-medium ml-2 mr-2">/</span>
                    {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 14, 16, 15, 20, 21])}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.edit', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])} class="text-indigo-400 hover:text-indigo-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block">
                            {proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium ml-2 mr-2">/</span>
                    {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 14, 16, 15, 20, 21])}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.soportes.index', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])} class="text-indigo-400 hover:text-indigo-600 overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap inline-block"> Soportes </a>
                    {/if}
                    <span class="text-indigo-400 font-medium ml-2 mr-2">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit} id="form-estudio-mercado">
            <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true) ? undefined : true}>
                <div class="mt-4">
                    <Input label="Nombre de la empresa" id="empresa" type="text" class="mt-1" bind:value={$form.empresa} error={errors.empresa} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="soporte" value="Soporte/Cotización" />
                    <File id="soporte" type="file" accept="application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" maxSize="10000" class="mt-1" bind:value={$form.soporte} error={errors.soporte} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear soporte</LoadingButton>
                {/if}
            </div>
        </form>
        {#if $form.progress}
            <PercentageProgress percentage={$form.progress.percentage} />
        {/if}
    </div>
</AuthenticatedLayout>
