<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import Textarea from '@/Shared/Textarea'
    import Dialog from '@/Shared/Dialog'
    import Input from '@/Shared/Input'

    export let errors
    export let convocatoria
    export let proyecto
    export let edt
    export let tiposEvento

    $: $title = 'EDT'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        tipo_evento: {
            value: edt.tipo_evento,
            label: tiposEvento.find((item) => item.value == edt.tipo_evento)?.label,
        },
        descripcion_evento: edt.descripcion_evento,
        descripcion_participacion_entidad: edt.descripcion_participacion_entidad,
        publico_objetivo: edt.publico_objetivo,
        numero_asistentes: edt.numero_asistentes,
        estrategia_comunicacion: edt.estrategia_comunicacion,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [9, 10]) && proyecto.modificable == true)) {
            $form.put(route('convocatorias.proyectos.edt.update', [convocatoria.id, proyecto.id, edt.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || (checkPermission(authUser, [10]) && proyecto.modificable == true)) {
            $form.delete(route('convocatorias.proyectos.edt.destroy', [convocatoria.id, proyecto.id, edt.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkPermission(authUser, [9, 10, 15])}
                        <a use:inertia href={route('convocatorias.proyectos.edt.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600">EDT</a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Editar
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [9, 10]) && proyecto.modificable == true) ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tipo_evento" value="Tipo de evento" />
                    <Select id="tipo_evento" items={tiposEvento} bind:selectedValue={$form.tipo_evento} error={errors.tipo_evento} autocomplete="off" placeholder="Seleccione el tipo de evento" required />
                </div>

                <div class="mt-8">
                    <Textarea label="Descripción del evento" maxlength="40000" id="descripcion_evento" error={errors.descripcion_evento} bind:value={$form.descripcion_evento} required />
                </div>

                <div class="mt-8">
                    <Textarea label="Descripción de participación de la entidad" maxlength="40000" id="descripcion_participacion_entidad" error={errors.descripcion_participacion_entidad} bind:value={$form.descripcion_participacion_entidad} required />
                </div>

                <div class="mt-8">
                    <Input label="Público objetivo" id="publico_objetivo" type="text" class="mt-1" error={errors.publico_objetivo} bind:value={$form.publico_objetivo} required />
                </div>

                <div class="mt-8">
                    <Input label="Número de asistentes" id="numero_asistentes" type="number" input$min="0" input$max="9999" class="mt-1" error={errors.numero_asistentes} placeholder="Escriba el número de asistentes" bind:value={$form.numero_asistentes} required />
                </div>

                <div class="mt-8">
                    <Input label="Estrategia de comunicación" id="estrategia_comunicacion" type="text" class="mt-1" error={errors.estrategia_comunicacion} bind:value={$form.estrategia_comunicacion} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (checkPermission(authUser, [10]) && proyecto.modificable == true)}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar EDT </button>
                {/if}
                {#if isSuperAdmin || (checkPermission(authUser, [9, 10]) && proyecto.modificable == true)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar EDT</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
    <Dialog bind:open={dialogOpen}>
        <div slot="title" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro(a) que desea eliminar este recurso?
                <br />
                Todos los datos se eliminarán de forma permanente.
                <br />
                Está acción no se puede deshacer.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
