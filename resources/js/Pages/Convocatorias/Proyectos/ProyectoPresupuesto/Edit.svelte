<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Button from '@/Shared/Button'
    import Textarea from '@/Shared/Textarea'
    import Dialog from '@/Shared/Dialog'
    import InfoMessage from '@/Shared/InfoMessage'
    import DropdownPresupuesto from '@/Shared/Dropdowns/DropdownPresupuesto'
    import Select from '@/Shared/Select'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoPresupuesto
    export let tiposLicencia
    export let tiposSoftware
    export let opcionesServiciosEdicion

    let presupuestoSennova = proyectoPresupuesto

    $: $title = proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal ? proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let showQtyInput = proyectoPresupuesto.valor != null ? false : true
    let dialogOpen = false
    let sending = false
    let form = useForm({
        codigo_uso_presupuestal: '',
        convocatoria_presupuesto_id: proyectoPresupuesto.convocatoria_presupuesto_id,
        descripcion: proyectoPresupuesto.descripcion,
        justificacion: proyectoPresupuesto.justificacion,
        valor: proyectoPresupuesto.valor,
        numero_items: proyectoPresupuesto.numero_items,
        tipo_software: proyectoPresupuesto.software_info?.tipo_software,
        tipo_licencia: proyectoPresupuesto.software_info?.tipo_licencia,
        fecha_inicio: proyectoPresupuesto.software_info?.fecha_inicio,
        fecha_finalizacion: proyectoPresupuesto.software_info?.fecha_finalizacion,
        servicio_edicion_info: {
            value: opcionesServiciosEdicion.find((item) => item.label == proyectoPresupuesto.servicio_edicion_info?.info)?.value,
            label: opcionesServiciosEdicion.find((item) => item.label == proyectoPresupuesto.servicio_edicion_info?.info)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10])) {
            $form.put(route('convocatorias.proyectos.proyecto-presupuesto.update', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || checkPermission(authUser, [4, 7, 10])) {
            $form.delete(route('convocatorias.proyectos.proyecto-presupuesto.destroy', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-1/3 whitespace-nowrap">
                    {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10])}
                        <a use:inertia href={route('convocatorias.proyectos.proyecto-presupuesto.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Presupuesto </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion}
                </h1>
            </div>
        </div>
    </header>

    <div class="flex">
        <div class="bg-white rounded shadow max-w-3xl flex-1">
            <form on:submit|preventDefault={submit} id="form-proyecto-presupuesto">
                <div class="p-8">
                    <div class="mt-4">
                        <DropdownPresupuesto bind:selectedUsoPresupuestal={$form.convocatoria_presupuesto_id} bind:showQtyInput bind:codigoUsoPresupuestal={$form.codigo_uso_presupuestal} {presupuestoSennova} message={errors.convocatoria_presupuesto_id} {convocatoria} lineaProgramatica={proyecto.tipo_proyecto.linea_programatica} required />
                        <InputError message={errors.convocatoria_presupuesto_id} />
                    </div>

                    {#if showQtyInput != undefined && !showQtyInput && proyectoPresupuesto.proyecto_lote_estudio_mercado?.length > 0}
                        <InfoMessage message="El uso presupuestal seleccionado no requiere de estudio de mercado. Si el ítem a actualizar tenía estudios de mercado generados estos se eliminarán." />
                    {/if}

                    <div class="mt-4">
                        <Textarea label="Describa el bien o servicio a adquirir. Sea específico" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                    </div>

                    <div class="mt-4">
                        <Textarea label="Justificación de la necesidad: ¿por qué se requiere este producto o servicio?" maxlength="40000" id="justificacion" error={errors.justificacion} bind:value={$form.justificacion} required />
                    </div>

                    {#if showQtyInput != undefined && !showQtyInput}
                        <div class="mt-4">
                            <Input label="Indique la cantidad requerida del producto o servicio relacionado" id="numero_items" type="number" min="0" class="mt-1" bind:value={$form.numero_items} error={errors.numero_items} required />
                        </div>
                        <div class="mt-4">
                            <Input label="Valor" id="valor" type="number" input$min="0" class="mt-1" bind:value={$form.valor} error={errors.valor} required />
                        </div>
                    {/if}

                    {#if $form.codigo_uso_presupuestal == '2010100600203101'}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="tipo_licencia" value="Tipo de licencia" />
                            <select id="tipo_licencia" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200" bind:value={$form.tipo_licencia} required>
                                <option value="">Seleccione el tipo de licencia </option>
                                {#each tiposLicencia as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="tipo_software" value="Tipo de software" />
                            <select id="tipo_software" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200" bind:value={$form.tipo_software} required>
                                <option value="">Seleccione el tipo de software </option>
                                {#each tiposSoftware as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-8">
                            <p>Periodo de uso</p>
                            <div class="mt-4">
                                <input label="Fecha de inicio" id="fecha_inicio" type="date" class="mt-1 p-4" bind:value={$form.fecha_inicio} required />
                            </div>
                            <div class="mt-4">
                                <input label="Fecha de finalización" id="fecha_finalizacion" type="date" class="mt-1 p-4" bind:value={$form.fecha_finalizacion} />
                            </div>
                        </div>
                        {#if errors.fecha_inicio || errors.fecha_finalizacion}
                            <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                        {/if}
                    {:else if $form.codigo_uso_presupuestal == '2020200800901'}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="servicio_edicion_info" value="Más información" />
                            <Select id="servicio_edicion_info" items={opcionesServiciosEdicion} bind:selectedValue={$form.servicio_edicion_info} error={errors.servicio_edicion_info} autocomplete="off" placeholder="Seleccione una opción" required />
                        </div>
                    {/if}
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || checkPermission(authUser, [4, 7, 10])}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar presupuesto </button>
                    {/if}

                    {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10])}
                        {#if $form.convocatoria_presupuesto_id != '' || $form.convocatoria_presupuesto_id != ''}
                            <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar presupuesto</LoadingButton>
                        {/if}
                    {/if}
                </div>
            </form>
        </div>
        <div class="px-4">
            <h1 class="mb-4">Enlaces de interés</h1>
            <ul>
                {#if proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.requiere_estudio_mercado}
                    <li>
                        <a class="bg-indigo-100 hover:bg-indigo-200 mb-4 px-6 py-2 rounded-3xl text-center text-indigo-400" use:inertia href={route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.index', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])}>Estudios de mercado</a>
                    </li>
                {/if}
            </ul>
        </div>
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
