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
    import Select from '@/Shared/Select'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoPresupuesto
    export let tiposLicencia
    export let tiposSoftware
    export let opcionesServiciosEdicion

    $: $title = proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal ? proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        codigo_uso_presupuestal: '',

        segundo_grupo_presupuestal_id: proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal_id,
        tercer_grupo_presupuestal_id: proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal_id,
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
        if (isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true)) {
            $form.put(route('convocatorias.proyectos.presupuesto.update', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || (checkPermission(authUser, [4, 7, 10, 13]) && proyecto.modificable == true)) {
            $form.delete(route('convocatorias.proyectos.presupuesto.destroy', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]))
        }
    }

    let usoPresupuestal

    let prevSegundoGrupoPresupuestal

    $: {
        if ($form.segundo_grupo_presupuestal_id != prevSegundoGrupoPresupuestal) {
            usoPresupuestal = null
        }

        $form.codigo_uso_presupuestal = usoPresupuestal?.codigo
        prevSegundoGrupoPresupuestal = $form.segundo_grupo_presupuestal_id
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 21, 14, 16, 15, 20])}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Presupuesto </a>
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
                <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true) ? undefined : true}>
                    <div class="mt-4">
                        <Label required labelFor="segundo_grupo_presupuestal_id" value="Homologable 2018" />
                        <DynamicList id="segundo_grupo_presupuestal_id" bind:value={$form.segundo_grupo_presupuestal_id} routeWebApi={route('web-api.segundo-grupo-presupuestal', proyecto.linea_programatica)} placeholder="Busque por el homologable 2018" message={errors.segundo_grupo_presupuestal_id} required />
                    </div>

                    {#if $form.segundo_grupo_presupuestal_id}
                        <div class="mt-4">
                            <Label required labelFor="tercer_grupo_presupuestal_id" value="Rubro 2019" />
                            <DynamicList id="tercer_grupo_presupuestal_id" bind:value={$form.tercer_grupo_presupuestal_id} routeWebApi={route('web-api.tercer-grupo-presupuestal', $form.segundo_grupo_presupuestal_id)} placeholder="Busque por el nombre del rubro 2019" message={errors.tercer_grupo_presupuestal_id} required />
                        </div>
                    {/if}

                    {#if $form.segundo_grupo_presupuestal_id && $form.tercer_grupo_presupuestal_id}
                        <div class="mt-4">
                            <Label required labelFor="convocatoria_presupuesto_id" value="Uso presupuestal" />
                            <DynamicList
                                id="convocatoria_presupuesto_id"
                                bind:value={$form.convocatoria_presupuesto_id}
                                routeWebApi={route('web-api.usos-presupuestales', [convocatoria, proyecto.linea_programatica, $form.segundo_grupo_presupuestal_id, $form.tercer_grupo_presupuestal_id])}
                                placeholder="Busque por el nombre del uso presupuestal"
                                message={errors.convocatoria_presupuesto_id}
                                bind:recurso={usoPresupuestal}
                                required
                            />
                        </div>

                        {#if usoPresupuestal?.mensaje}
                            <InfoMessage message={usoPresupuestal.mensaje} />
                        {/if}
                    {/if}

                    {#if usoPresupuestal?.requiere_estudio_mercado == false}
                        <InfoMessage message="<strong>Importante:</strong> El uso presupuestal seleccionado no requiere de estudio de mercado. Si este ítem tiene estudios de mercado generados estos se eliminarán." />
                    {/if}

                    <hr class="mt-10 mb-10" />

                    <div class="mt-4">
                        <Textarea label="Describa el bien o servicio a adquirir. Sea específico" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                    </div>

                    <div class="mt-4">
                        <Textarea label="Justificación de la necesidad: ¿por qué se requiere este producto o servicio?" maxlength="40000" id="justificacion" error={errors.justificacion} bind:value={$form.justificacion} required />
                    </div>

                    {#if usoPresupuestal?.requiere_estudio_mercado == false || proyectoPresupuesto.valor != null}
                        <div class="mt-4">
                            <Input label="Indique la cantidad requerida del producto o servicio relacionado" id="numero_items" type="number" input$min="0" class="mt-1" bind:value={$form.numero_items} error={errors.numero_items} required />
                        </div>
                        <div class="mt-4">
                            <Input label="Valor" id="valor" type="number" input$min="0" class="mt-1" bind:value={$form.valor} error={errors.valor} required />
                        </div>
                    {/if}

                    {#if $form.codigo_uso_presupuestal == '2010100600203101'}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="tipo_licencia" value="Tipo de licencia" />
                            <select id="tipo_licencia" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 p-4" bind:value={$form.tipo_licencia} required>
                                <option value="">Seleccione el tipo de licencia </option>
                                {#each tiposLicencia as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="tipo_software" value="Tipo de software" />
                            <select id="tipo_software" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 p-4" bind:value={$form.tipo_software} required>
                                <option value="">Seleccione el tipo de software </option>
                                {#each tiposSoftware as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-8">
                            <p>Periodo de uso</p>
                            <div class="mt-4">
                                <Label required class="mb-4" labelFor="fecha_inicio" value="Fecha de inicio" />
                                <input label="Fecha de inicio" id="fecha_inicio" type="date" class="mt-1 p-4" bind:value={$form.fecha_inicio} required />
                            </div>
                            <div class="mt-4">
                                <Label required class="mb-4" labelFor="fecha_finalizacion" value="Fecha de finalización (Cuando aplique)" />
                                <input label="Fecha de finalización" id="fecha_finalizacion" type="date" class="mt-1 p-4" bind:value={$form.fecha_finalizacion} />
                            </div>
                        </div>
                        {#if errors.fecha_inicio || errors.fecha_finalizacion}
                            <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                        {/if}
                    {:else if $form.codigo_uso_presupuestal == '2020200800901'}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="servicio_edicion_info" value="Nodo" />
                            <Select id="servicio_edicion_info" items={opcionesServiciosEdicion} bind:selectedValue={$form.servicio_edicion_info} error={errors.servicio_edicion_info} autocomplete="off" placeholder="Seleccione una opción" required />
                        </div>
                    {/if}
                </fieldset>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkPermission(authUser, [4, 7, 10, 13]) && proyecto.modificable == true)}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar presupuesto </button>
                    {/if}

                    {#if isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true)}
                        {#if $form.convocatoria_presupuesto_id != '' || $form.convocatoria_presupuesto_id != ''}
                            <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar presupuesto</LoadingButton>
                        {/if}
                    {/if}
                </div>
            </form>
        </div>
        {#if proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.requiere_estudio_mercado}
            <div class="px-4">
                <h1 class="mb-4">Enlaces de interés</h1>
                <ul>
                    <li>
                        <a class="bg-indigo-100 hover:bg-indigo-200 mb-4 px-6 py-2 rounded-3xl text-center text-indigo-400" use:inertia href={route('convocatorias.proyectos.presupuesto.lote.index', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])}>Estudios de mercado</a>
                    </li>
                </ul>
            </div>
        {/if}
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
