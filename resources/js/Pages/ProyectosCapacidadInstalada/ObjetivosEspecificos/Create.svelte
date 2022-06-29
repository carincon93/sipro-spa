<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'

    export let errors
    export let proyectoCapacidadInstalada

    $: $title = 'Crear objetivo específico'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        descripcion: '',
        descripcion_resultado: '',
    })

    function submit() {
        if (isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))) {
            $form.post(route('proyectos-capacidad-instalada.objetivos-especificos.store', [proyectoCapacidadInstalada.id]), {
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
                    <a use:inertia href={route('proyectos-capacidad-instalada.index')} class="text-cyan-400 hover:text-cyan-600"> Proyectos de capacidad instalada </a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.edit', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Información básica</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.integrantes.index', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Integrantes</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.objetivos-especificos.index', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600 font-extrabold underline">Objetivos específicos y resultados</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.productos.index', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Productos</a>
                    {#if isSuperAdmin || (checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))}
                        <span class="text-cyan-400 font-medium">/</span>
                        <a use:inertia href={route('proyectos-capacidad-instalada.finalizar', proyectoCapacidadInstalada.id)} class="text-cyan-400 hover:text-cyan-600">Finalizar</a>
                    {/if}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id)) ? undefined : true}>
                <div class="mt-8">
                    <Textarea label="Descripción del objetivo específico" maxlength="255" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>
                <div class="mt-8">
                    <Textarea label="Descripción del resultado" maxlength="255" id="descripcion_resultado" error={errors.descripcion_resultado} bind:value={$form.descripcion_resultado} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id))}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear objetivo específico</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
