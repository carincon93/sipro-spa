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

    export let errors
    export let convocatoria
    export let proyecto
    export let inventarioEquipo
    export let estadosInventarioEquipos

    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]

    $: $title = inventarioEquipo ? inventarioEquipo.nombre : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        nombre: inventarioEquipo.nombre,
        marca: inventarioEquipo.marca,
        serial: inventarioEquipo.serial,
        codigo_interno: inventarioEquipo.codigo_interno,
        fecha_adquisicion: inventarioEquipo.fecha_adquisicion,
        estado: {
            value: inventarioEquipo.estado,
            label: estadosInventarioEquipos.find((item) => item.value == inventarioEquipo.estado)?.label,
        },
        uso_st: {
            value: inventarioEquipo.uso_st,
            label: opcionesSiNo.find((item) => item.value == inventarioEquipo.uso_st)?.label,
        },
        uso_otra_dependencia: {
            value: inventarioEquipo.uso_otra_dependencia,
            label: opcionesSiNo.find((item) => item.value == inventarioEquipo.uso_otra_dependencia)?.label,
        },
        dependencia: inventarioEquipo.dependencia,
        descripcion: inventarioEquipo.descripcion,
        mantenimiento_prox_year: {
            value: inventarioEquipo.mantenimiento_prox_year,
            label: opcionesSiNo.find((item) => item.value == inventarioEquipo.mantenimiento_prox_year)?.label,
        },
        calibracion_prox_year: {
            value: inventarioEquipo.calibracion_prox_year,
            label: opcionesSiNo.find((item) => item.value == inventarioEquipo.calibracion_prox_year)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [6, 7]) && proyecto.modificable == true)) {
            $form.put(route('convocatorias.proyectos.inventario-equipos.update', [convocatoria.id, proyecto.id, inventarioEquipo.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || (checkPermission(authUser, [7]) && proyecto.modificable == true)) {
            $form.delete(route('convocatorias.proyectos.inventario-equipos.destroy', [convocatoria.id, proyecto.id, inventarioEquipo.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkPermission(authUser, [6, 7, 16])}
                        <a use:inertia href={route('convocatorias.proyectos.inventario-equipos.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600">Inventario de equipos</a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Editar
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [6]) && proyecto.modificable == true) ? undefined : true}>
                <div class="mt-8">
                    <Textarea label="Nombre del equipamiento" maxlength="255" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>

                <div class="mt-8">
                    <Textarea label="Marca" maxlength="255" id="marca" error={errors.marca} bind:value={$form.marca} required />
                </div>

                <div class="mt-8">
                    <Textarea label="Serial" maxlength="255" id="serial" error={errors.serial} bind:value={$form.serial} required />
                </div>

                <div class="mt-8">
                    <Textarea label="Código interno" maxlength="255" id="codigo_interno" error={errors.codigo_interno} bind:value={$form.codigo_interno} required />
                </div>

                <div class="mt-8">
                    <Label labelFor="fecha_adquisicion" value="Fecha de adquisición" />
                    <input id="fecha_adquisicion" type="date" class="mt-1 block w-full p-4" error={errors.fecha_adquisicion} bind:value={$form.fecha_adquisicion} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="estado" value="Estado" />
                    <Select id="estado" items={estadosInventarioEquipos} bind:selectedValue={$form.estado} error={errors.estado} autocomplete="off" placeholder="Seleccione el estado del equipo" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="uso_st" value="¿Uso exclusivo de Servicios tecnológicos?" />
                    <Select id="uso_st" items={opcionesSiNo} bind:selectedValue={$form.uso_st} error={errors.uso_st} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="uso_otra_dependencia" value="¿Otra dependencia que usa el equipo?" />
                    <Select id="uso_otra_dependencia" items={opcionesSiNo} bind:selectedValue={$form.uso_otra_dependencia} error={errors.uso_otra_dependencia} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>

                {#if $form.uso_otra_dependencia?.value == 1}
                    <div class="mt-8">
                        <Textarea label="Dependencia" maxlength="255" id="dependencia" error={errors.dependencia} bind:value={$form.dependencia} required />
                    </div>
                {/if}

                <div class="mt-8">
                    <Label required class="mb-4" labelFor="descripcion" value="Descripción del equipo (Detalle con qué metodología del proyecto está relacionado este equipamiento)" />
                    <Textarea maxlength="10000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="mantenimiento_prox_year" value="¿Para el próximo año el equipo necesita mantenimiento?" />
                    <Select id="mantenimiento_prox_year" items={opcionesSiNo} bind:selectedValue={$form.mantenimiento_prox_year} error={errors.mantenimiento_prox_year} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="calibracion_prox_year" value="¿Para el próximo año el equipo necesita calibración?" />
                    <Select id="calibracion_prox_year" items={opcionesSiNo} bind:selectedValue={$form.calibracion_prox_year} error={errors.calibracion_prox_year} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (checkPermission(authUser, [7]) && proyecto.modificable == true)}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar inventario </button>
                {/if}
                {#if isSuperAdmin || (checkPermission(authUser, [6, 7]) && proyecto.modificable == true)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar inventario de equipos</LoadingButton>
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
