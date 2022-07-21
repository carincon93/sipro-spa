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
    import RecomendacionEvaluador from '@/Shared/RecomendacionEvaluador'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoPresupuesto
    export let tiposLicencia
    export let tiposSoftware
    export let opcionesServiciosEdicion

    $: $title = proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal ? proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion : null

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false

    let form = useForm({
        _method: 'put',
        codigo_uso_presupuestal: '',

        segundo_grupo_presupuestal_id: proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal_id,
        tercer_grupo_presupuestal_id: proyectoPresupuesto.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal_id,
        convocatoria_presupuesto_id: proyectoPresupuesto.convocatoria_presupuesto_id,

        descripcion: proyectoPresupuesto.descripcion,
        justificacion: proyectoPresupuesto.justificacion,
        valor_total: proyectoPresupuesto.valor_total,
        numero_items: proyectoPresupuesto.numero_items,
        tipo_software: proyectoPresupuesto.software_info?.tipo_software,
        tipo_licencia: proyectoPresupuesto.software_info?.tipo_licencia,
        fecha_inicio: proyectoPresupuesto.software_info?.fecha_inicio,
        fecha_finalizacion: proyectoPresupuesto.software_info?.fecha_finalizacion,
        servicio_edicion_info: proyectoPresupuesto.servicio_edicion_info?.info,
        formato_estudio_mercado: proyectoPresupuesto.formato_estudio_mercado,
    })

    function submit() {
        if (proyecto.allowed.to_update) {
            $form.post(route('convocatorias.proyectos.presupuesto.update', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]), {
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (proyecto.allowed.to_update) {
            $form.delete(route('convocatorias.proyectos.presupuesto.destroy', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]))
        }
    }

    let presupuestoSennova

    let prevSegundoGrupoPresupuestal

    $: {
        if ($form.segundo_grupo_presupuestal_id != prevSegundoGrupoPresupuestal) {
            presupuestoSennova = null
        }

        $form.codigo_uso_presupuestal = presupuestoSennova?.codigo
        prevSegundoGrupoPresupuestal = $form.segundo_grupo_presupuestal_id
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    <a use:inertia href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="text-violet-400 hover:text-violet-600"> Presupuesto </a>
                    <span class="text-violet-400 font-medium">/</span>
                    {proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion}
                </h1>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-3">
        <div>
            <h1 class="font-black text-4xl sticky top-0 uppercase">Presupuesto</h1>
        </div>
        <div class="col-span-2">
            {#if isSuperAdmin || proyecto.mostrar_recomendaciones}
                <RecomendacionEvaluador class="w-1/3">
                    {#each proyectoPresupuesto.proyecto_presupuestos_evaluaciones as evaluacionPresupuesto, i}
                        <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                            <p class="text-xs">Evaluador COD-{evaluacionPresupuesto.evaluacion.id}:</p>
                            {#if evaluacionPresupuesto.correcto == false && evaluacionPresupuesto.evaluacion.habilitado}
                                <p class="whitespace-pre-line text-xs">{evaluacionPresupuesto.comentario ? evaluacionPresupuesto.comentario : 'Sin recomendación'}</p>
                            {:else}
                                Aprobado
                            {/if}
                        </div>
                    {/each}
                </RecomendacionEvaluador>
            {/if}
            <form on:submit|preventDefault={submit} id="form-proyecto-presupuesto" class="bg-white rounded shadow">
                <fieldset class="p-8" disabled={proyecto.allowed.to_update ? undefined : true}>
                    <div class="mt-4">
                        <Label required labelFor="segundo_grupo_presupuestal_id" value="Homologable 2018" />
                    </div>

                    {#if $form.segundo_grupo_presupuestal_id}
                        <div class="mt-4">
                            <Label required labelFor="tercer_grupo_presupuestal_id" value="Rubro 2019" />
                        </div>
                    {/if}

                    {#if $form.segundo_grupo_presupuestal_id && $form.tercer_grupo_presupuestal_id}
                        <div class="mt-4">
                            <Label required labelFor="convocatoria_presupuesto_id" value="Uso presupuestal" />
                        </div>

                        {#if presupuestoSennova?.mensaje}
                            <InfoMessage message={presupuestoSennova.mensaje} />
                        {/if}
                    {/if}

                    {#if presupuestoSennova?.requiere_estudio_mercado == false}
                        <InfoMessage message="<strong>Importante:</strong> El uso presupuestal seleccionado no requiere de estudio de mercado. Si este ítem tiene estudios de mercado generados estos se eliminarán." />
                    {/if}

                    <hr class="mt-10 mb-10" />

                    <div class="mt-4">
                        <Textarea label="Describa el bien o servicio a adquirir. Sea específico" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                    </div>

                    <div class="mt-4">
                        <Textarea label="Justificación de la necesidad: ¿por qué se requiere este producto o servicio?" maxlength="40000" id="justificacion" error={errors.justificacion} bind:value={$form.justificacion} required />
                    </div>

                    <div class="mt-4">
                        <Input label="Valor total" id="valor_total" type="number" input$min="0" class="mt-1" bind:value={$form.valor_total} error={errors.valor_total} required />
                    </div>
                    {#if presupuestoSennova?.requiere_estudio_mercado || $form.codigo_uso_presupuestal == '020202008005096'}
                        <InfoMessage message="Por favor indique el valor total que arrojó el Estudio de mercado - Convocatoria Sennova 2021" />
                        <div class="mt-4">
                            <Label class="mb-4" labelFor="formato_estudio_mercado" value="Url del estudio de mercado - Convocatoria Sennova 2021" />
                            <Input label="Url" id="formato_estudio_mercado" type="url" class="mt-1" error={errors.formato_estudio_mercado} placeholder="Url https://www.google.com.co" bind:value={$form.formato_estudio_mercado} />
                        </div>
                    {/if}

                    {#if $form.codigo_uso_presupuestal == '2010100600203101'}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="tipo_licencia" value="Tipo de licencia" />
                            <select id="tipo_licencia" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-violet-200 focus:ring-violet-200 p-4" bind:value={$form.tipo_licencia} required>
                                <option value="">Seleccione el tipo de licencia </option>
                                {#each tiposLicencia as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="tipo_software" value="Tipo de software" />
                            <select id="tipo_software" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-violet-200 focus:ring-violet-200 p-4" bind:value={$form.tipo_software} required>
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
                            <Label required class="mb-4" labelFor="servicio_edicion_info" value="Nodo editorial" />
                            <Select id="servicio_edicion_info" items={opcionesServiciosEdicion} bind:selectedValue={$form.servicio_edicion_info} error={errors.servicio_edicion_info} autocomplete="off" placeholder="Seleccione una opción" required />
                        </div>
                    {/if}
                </fieldset>

                <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                    <small class="flex items-center text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {proyectoPresupuesto.updated_at}
                    </small>
                    {#if proyecto.allowed.to_update}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={() => (dialogOpen = true)}> Eliminar ítem </button>
                        {#if $form.convocatoria_presupuesto_id != '' || $form.convocatoria_presupuesto_id != ''}
                            <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Guardar</LoadingButton>
                        {/if}
                    {:else}
                        <span class="inline-block ml-1.5"> El proyecto no se puede modificar </span>
                    {/if}
                </div>
            </form>
        </div>
        <div class="px-4 flex-1">
            {#if proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.requiere_estudio_mercado || $form.codigo_uso_presupuestal == '020202008005096'}
                <h1 class="mb-4 text-2xl">Enlaces de interés</h1>
                <ul>
                    <li>
                        <a class="bg-violet-100 hover:bg-violet-200 mb-4 px-6 py-2 rounded-3xl text-center text-violet-400" use:inertia href={route('convocatorias.proyectos.presupuesto.soportes.index', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])}>Soportes / Cotizaciones</a>
                    </li>
                    <li class="mt-4">
                        <a class="flex bg-violet-100 hover:bg-violet-200 mb-4 px-6 py-2 rounded-3xl text-center text-violet-400" target="_blank" download href={route('convocatorias.proyectos.presupuesto.download-formato-estudio-mercado', [convocatoria.id, proyecto.id])}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Descargar Formato guía 4: Estudio de mercado
                        </a>
                    </li>
                </ul>
            {/if}
        </div>
    </div>

    {#if proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.requiere_estudio_mercado || $form.codigo_uso_presupuestal == '020202008005096'}
        <h1 class="text-2xl mt-10">Archivos</h1>
        <div class="mt-4 bg-white rounded shadow">
            <table class="w-full whitespace-no-wrap table-fixed data-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre archivo</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Archivo</th>
                    </tr>
                </thead>

                <tbody>
                    {#if !proyectoPresupuesto.formato_estudio_mercado}
                        <tr>
                            <td class="border-t px-6 py-4" colspan="2">Sin información registrada</td>
                        </tr>
                    {:else}
                        <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t px-6 pt-6 pb-4"> Estudio de mercado </td>

                            <td class="border-t px-6 pt-6 pb-4">
                                <a target="_blank" class="flex text-violet-400 underline mb-4" download href={proyectoPresupuesto.formato_estudio_mercado}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar estudio de mercado
                                </a>
                            </td>
                        </tr>
                    {/if}
                </tbody>
            </table>
        </div>
    {/if}

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
                <Button on:click={() => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
