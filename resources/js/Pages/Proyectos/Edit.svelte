<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { Inertia } from '@inertiajs/inertia'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Switch from '@/Shared/Switch'
    import InfoMessage from '@/Shared/InfoMessage'
    import Button from '@/Shared/Button'

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
        subsanacion: proyecto.a_evaluar == false && proyecto.modificable == true && proyecto.finalizado == false ? true : false,
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

    <Button class="mb-2" variant="raised" on:click={() => Inertia.visit(route('convocatorias.proyectos.edit', [proyecto.convocatoria_id, proyecto.id]))}>Detalles del proyecto</Button>

    <br />

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8">
                <div class="mt-4">
                    <Label labelFor="subsanacion" value="¿El proyecto puede ser subsanado? Nota: Se finalizarán todas la evaluaciones del proyecto {proyecto.codigo}" class="inline-block mb-4" />

                    <InfoMessage>
                        <p class="font-black">Tenga en cuenta: Información del estado del proyecto (Se tienen en cuenta la(s) {JSON.parse(proyecto.estado).evaluacionesHabilitadas} evaluacion(es) habilitada(s))</p>
                        <ul>
                            <li>Estado del proyecto: {JSON.parse(proyecto.estado).estado}</li>
                            <li>Número de recomendaciones: {JSON.parse(proyecto.estado).numeroRecomendaciones}</li>
                            <li>Puntaje total: {JSON.parse(proyecto.estado).puntaje}</li>
                            <li>¿Requiere ser subsanado?: {JSON.parse(proyecto.estado).requiereSubsanar ? 'Si' : 'No'}</li>
                        </ul>
                    </InfoMessage>
                    <br />
                    <Switch bind:checked={$form.subsanacion} />
                    <InputError message={errors.subsanacion} />
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
