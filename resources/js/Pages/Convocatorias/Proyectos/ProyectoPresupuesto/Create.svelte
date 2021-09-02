<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InputError from '@/Shared/InputError'
    import Select from '@/Shared/Select'
    import File from '@/Shared/File'
    import InfoMessage from '@/Shared/InfoMessage'

    export let convocatoria
    export let proyecto
    export let errors
    export let tiposLicencia
    export let tiposSoftware
    export let opcionesServiciosEdicion

    $: $title = 'Crear presupuesto'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        convocatoria_presupuesto_id: null,
        descripcion: '',
        justificacion: '',
        valor_total: '',
        numero_items: '',
        tipo_software: '',
        tipo_licencia: '',
        fecha_inicio: '',
        fecha_finalizacion: '',
        codigo_uso_presupuestal: '',
        servicio_edicion_info: '',
        formato_estudio_mercado: '',
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)) {
            ;(sending = true),
                $form.post(route('convocatorias.proyectos.presupuesto.store', [convocatoria.id, proyecto.id]), {
                    onStart: () => (sending = true),
                    onFinish: () => (sending = false),
                })
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
                <h1>
                    {#if isSuperAdmin || checkPermission(authUser, [1, 5, 8, 11, 17])}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Presupuestos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class={presupuestoSennova?.requiere_estudio_mercado || $form.codigo_uso_presupuestal == '020202008005096' ? 'flex' : ''}>
        <div class="bg-white rounded shadow max-w-3xl">
            <form on:submit|preventDefault={submit}>
                <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true) ? undefined : true}>
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
                                bind:recurso={presupuestoSennova}
                                required
                            />
                        </div>

                        {#if presupuestoSennova?.mensaje}
                            <InfoMessage message={presupuestoSennova.mensaje} />
                        {/if}
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
                            <Label class="mb-4" labelFor="formato_estudio_mercado" value="Estudio de mercado - Convocatoria Sennova 2021" />
                            <File id="formato_estudio_mercado" type="file" accept="application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" maxSize="10000" class="mt-1" bind:value={$form.formato_estudio_mercado} error={errors.formato_estudio_mercado} />
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
                                <input id="fecha_inicio" type="date" class="mt-1 p-4" bind:value={$form.fecha_inicio} required />
                            </div>
                            <div class="mt-4">
                                <Label required class="mb-4" labelFor="fecha_finalizacion" value="Fecha de finalización (Cuando aplique)" />
                                <input id="fecha_finalizacion" type="date" class="mt-1 p-4" bind:value={$form.fecha_finalizacion} />
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
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)}
                        {#if $form.convocatoria_presupuesto_id != '' || $form.convocatoria_presupuesto_id != ''}
                            <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear presupuesto</LoadingButton>
                        {/if}
                    {/if}
                </div>
            </form>
        </div>
        {#if presupuestoSennova?.requiere_estudio_mercado || $form.codigo_uso_presupuestal == '020202008005096'}
            <div class="px-4">
                <h1 class="mb-4 text-2xl">Enlaces de interés</h1>
                <ul>
                    <li class="mt-4">
                        <a class="flex bg-indigo-100 hover:bg-indigo-200 mb-4 px-6 py-2 rounded-3xl text-center text-indigo-400" target="_blank" download href={route('convocatorias.proyectos.presupuesto.download-formato-estudio-mercado', [convocatoria.id, proyecto.id, 0])}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Descargar Formato guía 4: Estudio de mercado
                        </a>
                    </li>
                </ul>
            </div>
        {/if}
    </div>
</AuthenticatedLayout>

<style>
    :global(#tipo_licencia) {
        border-radius: 4px;
        border: 1px solid #dbdbdb;
        height: 56px;
        padding: 0 10px;
    }

    :global(#tipo_software) {
        border-radius: 4px;
        border: 1px solid #dbdbdb;
        height: 56px;
        padding: 0 10px;
    }
</style>
