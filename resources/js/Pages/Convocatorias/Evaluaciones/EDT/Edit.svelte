<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import Select from '@/Shared/Select'
    import Textarea from '@/Shared/Textarea'
    import Input from '@/Shared/Input'

    export let convocatoria
    export let evaluacion
    export let edt
    export let tiposEvento
    export let proyectoPresupuesto

    $: $title = 'EDT'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let edtInfo = {
        tipo_evento: {
            value: edt.tipo_evento,
            label: tiposEvento.find((item) => item.value == edt.tipo_evento)?.label,
        },
        descripcion_evento: edt.descripcion_evento,
        descripcion_participacion_entidad: edt.descripcion_participacion_entidad,
        publico_objetivo: edt.publico_objetivo,
        numero_asistentes: edt.numero_asistentes,
        estrategia_comunicacion: edt.estrategia_comunicacion,
        proyecto_presupuesto_id: {
            value: edt.proyecto_presupuesto_id,
            label: proyectoPresupuesto.find((item) => item.value == edt.proyecto_presupuesto_id)?.label,
        },
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkPermission(authUser, [9, 10, 15])}
                        <a use:inertia href={route('convocatorias.evaluaciones.edt', [convocatoria.id, evaluacion.id])} class="text-indigo-400 hover:text-indigo-600">EDT</a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Editar
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form class="p-8">
            <div class="mt-4">
                <Label class="mb-4" labelFor="proyecto_presupuesto_id" value="Presupuesto" />
                <Select disabled={true} id="proyecto_presupuesto_id" items={proyectoPresupuesto} bind:selectedValue={edtInfo.proyecto_presupuesto_id} autocomplete="off" placeholder="Seleccione el presupuesto" />
            </div>
            <div class="mt-4">
                <Label class="mb-4" labelFor="tipo_evento" value="Tipo de evento" />
                <Select disabled={true} id="tipo_evento" items={tiposEvento} bind:selectedValue={edtInfo.tipo_evento} autocomplete="off" placeholder="Seleccione el tipo de evento" />
            </div>

            <div class="mt-8">
                <Textarea disabled label="Descripción del evento" maxlength="40000" id="descripcion_evento" bind:value={edtInfo.descripcion_evento} />
            </div>

            <div class="mt-8">
                <Textarea disabled label="Descripción de participación de la entidad" maxlength="40000" id="descripcion_participacion_entidad" bind:value={edtInfo.descripcion_participacion_entidad} />
            </div>

            <div class="mt-8">
                <Input disabled label="Público objetivo" id="publico_objetivo" type="text" class="mt-1" bind:value={edtInfo.publico_objetivo} />
            </div>

            <div class="mt-8">
                <Input disabled label="Número de asistentes" id="numero_asistentes" type="number" input$min="0" input$max="9999" class="mt-1" placeholder="Escriba el número de asistentes" bind:value={edtInfo.numero_asistentes} />
            </div>

            <div class="mt-8">
                <Input disabled label="Estrategia de comunicación" id="estrategia_comunicacion" type="text" class="mt-1" bind:value={edtInfo.estrategia_comunicacion} />
            </div>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0" />
        </form>
    </div>
</AuthenticatedLayout>
