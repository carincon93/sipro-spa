<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Switch from '@/Shared/Switch'

    export let errors
    export let proyecto

    $: $title = proyecto ? proyecto.codigo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        a_evaluar: proyecto.a_evaluar,
        modificable: proyecto.modificable,
        finalizado: proyecto.finalizado,
        radicado: proyecto.radicado,
        mostrar_recomendaciones: proyecto.mostrar_recomendaciones,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('proyectos.update', proyecto.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin}
                        <a use:inertia href={route('proyectos.index')} class="text-indigo-400 hover:text-indigo-600"> Proyectos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {proyecto.codigo}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8">
                <div class="mt-4">
                    <Label labelFor="a_evaluar" value="¿El proyecto ha sido enviado a evaluación?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.a_evaluar} />
                    <InputError message={errors.a_evaluar} />
                </div>

                <div class="mt-4">
                    <Label labelFor="modificable" value="¿El proyecto es modificable?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.modificable} />
                    <InputError message={errors.modificable} />
                </div>

                <div class="mt-4">
                    <Label labelFor="finalizado" value="¿El proyecto está finalizado?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.finalizado} />
                    <InputError message={errors.finalizado} />
                </div>
                <div class="mt-4">
                    <Label labelFor="radicado" value="¿El proyecto está radicado?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.radicado} />
                    <InputError message={errors.radicado} />
                </div>
                <div class="mt-4">
                    <Label labelFor="mostrar_recomendaciones" value="¿El formulador puede observar las recomendaciones?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.mostrar_recomendaciones} />
                    <InputError message={errors.mostrar_recomendaciones} />
                </div>
            </fieldset>

            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar proyecto</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
