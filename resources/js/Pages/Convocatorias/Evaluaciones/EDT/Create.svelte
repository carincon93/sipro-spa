<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import Textarea from '@/Shared/Textarea'
    import Input from '@/Shared/Input'

    export let convocatoria
    export let proyecto
    export let errors
    export let tiposEvento
    export let proyectoPresupuesto

    $: $title = 'Crear EDT'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        tipo_evento: '',
        descripcion_evento: '',
        descripcion_participacion_entidad: '',
        publico_objetivo: '',
        numero_asistentes: '',
        estrategia_comunicacion: '',
        proyecto_presupuesto_id: null,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [8]) && proyecto.modificable == true)) {
            $form.post(route('convocatorias.proyectos.edt.store', [convocatoria.id, proyecto.id]), {
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
                    {#if isSuperAdmin || checkPermission(authUser, [8])}
                        <a use:inertia href={route('convocatorias.proyectos.edt.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600">EDT</a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [8]) && proyecto.modificable == true) ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="proyecto_presupuesto_id" value="Presupuesto" />
                    <Select id="proyecto_presupuesto_id" items={proyectoPresupuesto} bind:selectedValue={$form.proyecto_presupuesto_id} error={errors.proyecto_presupuesto_id} autocomplete="off" placeholder="Seleccione el presupuesto" required />
                </div>

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
                {#if isSuperAdmin || (checkPermission(authUser, [8]) && proyecto.modificable == true)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" tipo="submit">Crear EDT</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
