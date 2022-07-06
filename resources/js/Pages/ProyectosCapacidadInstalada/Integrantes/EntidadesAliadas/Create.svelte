<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Input from '@/Shared/Input'

    export let errors
    export let proyectoCapacidadInstalada

    $: $title = 'Crear entidad aliada'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let isDinamizadorSennova = proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4]) && proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion.id && authUser.centro_formacion_id
    let isAutorPrincipal = proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id)

    let form = useForm({
        nombre: '',
        nit: '',
        documento: null,
    })

    function submit() {
        if (isSuperAdmin || isDinamizadorSennova || isAutorPrincipal) {
            $form.post(route('proyectos-capacidad-instalada.entidades-aliadas.store', [proyectoCapacidadInstalada.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('proyectos-capacidad-instalada.index')} class="text-cyan-400 hover:text-cyan-600"> Proyectos de capacidad instalada </a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.edit', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Información básica</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.integrantes.index', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600 font-extrabold underline">Integrantes</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.objetivos-especificos.index', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Objetivos específicos y resultados</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.productos.index', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Productos</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.finalizar', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Finalizar</a>
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || isDinamizadorSennova || isAutorPrincipal ? undefined : true}>
                <div class="mt-8">
                    <Textarea label="Nombre de la entidad aliada/Centro de formación" maxlength="255" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>

                <div class="mt-8">
                    <Input label="NIT" id="nit" type="text" class="mt-1" bind:value={$form.nit} error={errors.nit} required />
                </div>

                <div class="mt-8">
                    <Label required class="mb-4" labelFor="documento" value="Url del documento" />
                    <Input label="Url" id="documento" type="url" class="mt-1" error={errors?.documento} placeholder="Url https://www.google.com.co" bind:value={$form.documento} required />
                </div>

                {#if $form.progress}
                    <progress value={$form.progress.percentage} max="100" class="mt-4">
                        {$form.progress.percentage}%
                    </progress>
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole( authUser, [4], ) && proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion.id && authUser.centro_formacion_id) || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole( authUser, [6], ) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Crear entidad aliada</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
