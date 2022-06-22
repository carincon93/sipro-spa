<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { useForm, page, inertia } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import Select from '@/Shared/Select'
    import LoadingButton from '@/Shared/LoadingButton'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import InfoMessage from '@/Shared/InfoMessage'
    import Switch from '@/Shared/Switch'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'

    export let errors
    export let proyectoCapacidadInstalada
    export let tiposDocumento
    export let tiposVinculacion
    export let roles
    export let autorPrincipal

    let resultados = []
    let existenciaDocumentos = proyectoCapacidadInstalada.entidades_aliadas.length > 0 ? true : false

    $: $title = 'Integrantes'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    /**
     * Buscar
     */
    let form = useForm({
        search_integrante: '',
    })
    let formID
    let dialogOpen = false
    let dialogTitle
    let sending = false
    let sended = false
    function search() {
        if (isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && authUser.id == autorPrincipal.id)) {
            sending = true
            sended = false
            axios
                .post(route('proyectos-capacidad-instalada.integrantes.users', proyectoCapacidadInstalada.id), $form)
                .then((response) => {
                    resultados = response.data
                    sending = false
                    sended = true
                })
                .catch((error) => {
                    sending = false
                })
        }
    }

    function removeParticipante(id) {
        if (isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && authUser.id == autorPrincipal.id)) {
            Inertia.post(route('proyectos-capacidad-instalada.integrantes.users.unlink', proyectoCapacidadInstalada.id), { user_id: id, _method: 'DELETE' }, { preserveScroll: true })
        }
    }

    /**
     * Integrantes
     */
    let formParticipante = useForm({
        _method: null,
        user_id: 0,
        cantidad_horas: 0,
        cantidad_meses: 0,
        rol_sennova: null,
    })

    function showParticipante(user) {
        reset()
        dialogTitle = user.nombre
        dialogOpen = true
        formID = 'integrante-form'
        $formParticipante.user_id = user.id
        if (user.pivot) {
            $formParticipante._method = 'PUT'
            $formParticipante.cantidad_meses = user.pivot.cantidad_meses
            $formParticipante.cantidad_horas = user.pivot.cantidad_horas
            $formParticipante.rol_sennova = {
                value: user.pivot.rol_sennova,
                label: roles.find((item) => item.value == user.pivot.rol_sennova)?.label,
            }
        }
    }

    function submitParticipante() {
        if (isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && authUser.id == autorPrincipal.id)) {
            $formParticipante.post(route('proyectos-capacidad-instalada.integrantes.users.link', proyectoCapacidadInstalada.id), {
                onStart: () => {
                    sending = true
                },
                onSuccess: () => {
                    closeDialog()
                },
                onFinish: () => {
                    sending = false
                },
                preserveScroll: true,
            })
        }
    }

    /**
     * Registrar nuevo integrante
     */
    let formNuevoIntegrante = useForm({
        nombre: '',
        email: '',
        tipo_documento: '',
        numero_documento: '',
        numero_celular: '',
        tipo_vinculacion: '',
        cantidad_meses: 0,
        cantidad_horas: 0,
        rol_sennova: null,
        centro_formacion_id: null,
        autorizacion_datos: false,
    })

    let formNuevoIntegranteId
    let openNuevoParticipanteDialog = false
    function showRegister() {
        reset()
        openNuevoParticipanteDialog = true
        formNuevoIntegranteId = 'nuevo-integrante-form'
    }

    function submitRegister() {
        if (isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && authUser.id == autorPrincipal.id)) {
            $formNuevoIntegrante.post(route('proyectos-capacidad-instalada.integrantes.users.register', proyectoCapacidadInstalada.id), {
                onStart: () => {
                    sending = true
                },
                onSuccess: () => {
                    closeDialog()
                },
                onFinish: () => {
                    sending = false
                },
                preserveScroll: true,
            })
        }
    }

    function reset() {
        //Participante - form
        $formParticipante.reset()
        //Nuevo integrante - form
        $formNuevoIntegrante.reset()
    }

    function closeDialog() {
        reset()
        dialogOpen = false
        openNuevoParticipanteDialog = false
        sending = false
    }

    let nuevoAutorPrinciaplId = null
    let dialogAutorPrincipal = false
    let integrantes = []

    proyectoCapacidadInstalada.integrantes.map((item) => {
        integrantes.push({
            value: item.id,
            label: item.nombre,
        })
    })

    function showNuevoAutor(integrante) {
        dialogAutorPrincipal = true
        nuevoAutorPrinciaplId = integrante.id
    }

    function submitNuevoAutorPrincipal() {
        if (isSuperAdmin || authUser.id == autorPrincipal.id) {
            Inertia.post(route('proyectos-capacidad-instalada.nuevo-autor-principal', [proyectoCapacidadInstalada.id, nuevoAutorPrinciaplId]), [], {
                onStart: () => {
                    sending = true
                },
                onSuccess: () => {
                    dialogAutorPrincipal = false
                },
                onFinish: () => {
                    sending = false
                },
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('proyectos-capacidad-instalada.index')} class="text-indigo-400 hover:text-indigo-600"> Proyectos de capacidad instalada </a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.edit', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Información básica</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.integrantes.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600 font-extrabold underline">Integrantes</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.objetivos-especificos.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Objetivos específicos y resultados</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.productos.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Productos</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.finalizar', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Estado</a>
                </h1>
            </div>
        </div>
    </header>

    <a use:inertia href={route('proyectos-capacidad-instalada.objetivos-especificos.index', proyectoCapacidadInstalada.id)} class="flex bottom-0 fixed hover:bg-indigo-600 mb-4 px-6 py-2 bg-indigo-700 rounded-lg shadow-2xl text-center text-white z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        Ir a los objetivos específicos y resultados
    </a>

    {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && authUser.id == autorPrincipal.id)}
        <div class="bg-indigo-100 p-4">
            <h1 class="text-4xl text-center">Integrantes</h1>
            <p class="text-center m-auto mt-8">Realice la búsqueda de integrantes por nombre, número de documento o por el correo electrónico institucional</p>
            <form on:submit|preventDefault={search} on:input={() => (sended = false)}>
                <fieldset>
                    <div class="mt-4 flex flex-row">
                        <Input label="Escriba el nombre, número de documento o el correo electrónico instiucional" id="search_integrante" type="search" class="mt-1 m-auto block flex-1" bind:value={$form.search_integrante} input$minLength="4" autocomplete="off" required />
                        <LoadingButton loading={sending} class="btn-indigo m-auto ml-1" type="submit">Buscar</LoadingButton>
                    </div>
                </fieldset>
            </form>

            {#if sended}
                <h1 class="mt-24 mb-8 text-center text-3xl">Resultados de la búsqueda de integrantes</h1>
                <InfoMessage message="Una vez arroje los resultados de clic en los tres puntos y seleccione la opción <strong>Vincular</strong>" />
                <div class="bg-white rounded shadow">
                    <table class="w-full whitespace-no-wrap table-fixed data-table">
                        <thead>
                            <tr class="text-left font-bold">
                                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Correo electrónico</th>
                                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Centro de formación</th>
                                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Regional</th>
                                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {#each resultados as resultado (resultado.id)}
                                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                                    <td class="border-t">
                                        <p class="px-6 py-4 focus:text-indigo-500">
                                            {resultado.nombre}
                                        </p>
                                    </td>
                                    <td class="border-t">
                                        <p class="px-6 py-4">
                                            {resultado.email}
                                        </p>
                                    </td>
                                    <td class="border-t">
                                        <p class="px-6 py-4">
                                            {resultado.centro_formacion ? resultado.centro_formacion.nombre : ''}
                                        </p>
                                    </td>
                                    <td class="border-t">
                                        <p class="px-6 py-4">
                                            {resultado.centro_formacion ? resultado.centro_formacion.regional.nombre : ''}
                                        </p>
                                    </td>
                                    <td class="border-t td-actions">
                                        <DataTableMenu class={resultados.length < 4 ? 'z-50' : ''}>
                                            <Item on:SMUI:action={() => showParticipante(resultado)}>
                                                <Text>Vincular</Text>
                                            </Item>
                                        </DataTableMenu>
                                    </td>
                                </tr>
                            {/each}

                            {#if resultados.length === 0}
                                <tr>
                                    <td class="border-t px-6 py-4" colspan="5">
                                        {$_('No data recorded')}
                                        <Button on:click={() => showRegister()} type="button" variant={null}>Crear integrante</Button>
                                    </td>
                                </tr>
                            {/if}
                        </tbody>
                    </table>
                </div>
            {/if}
        </div>
    {/if}

    <h1 class="mt-24 mb-8 text-center text-3xl">Integrantes vinculados</h1>
    <div class="bg-white rounded shadow">
        <table class="w-full whitespace-no-wrap table-fixed data-table">
            <thead>
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Correo electrónico</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Centro de formación / Regional</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Rol SENNOVA</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {#each proyectoCapacidadInstalada.integrantes as integrante (integrante.id)}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t">
                            <p class="px-6 py-4 focus:text-indigo-500">
                                {integrante.nombre}
                                {#if integrante.id == autorPrincipal.id}
                                    <span class="inline-block text-xs bg-green-100 py-1 px-4 rounded-full shadow">Autor(a) principal</span>
                                {/if}
                            </p>
                        </td>
                        <td class="border-t">
                            <p class="px-6 py-4">
                                {integrante.email}
                            </p>
                        </td>
                        <td class="border-t">
                            <p class="px-6 py-4">
                                {integrante.centro_formacion ? integrante.centro_formacion.nombre : ''} / {integrante.centro_formacion ? integrante.centro_formacion.regional.nombre : ''}
                            </p>
                        </td>
                        <td class="border-t">
                            <p class="px-6 py-4">
                                {#if integrante.pivot.rol_sennova == 1}
                                    Dinamizador SENNOVA
                                {:else if integrante.pivot.rol_sennova == 2}
                                    Investigador experto
                                {:else if integrante.pivot.rol_sennova == 3}
                                    Instructor investigador
                                {:else if integrante.pivot.rol_sennova == 4}
                                    Aprendiz Sena de semillero de investigación
                                {:else if integrante.pivot.rol_sennova == 5}
                                    Investigador junior
                                {/if}
                            </p>
                        </td>
                        <td class="border-t td-actions">
                            <DataTableMenu class={proyectoCapacidadInstalada.integrantes.length < 4 ? 'z-50' : ''}>
                                {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && authUser.id == autorPrincipal.id)}
                                    <Item on:SMUI:action={() => showParticipante(integrante)}>
                                        <Text>Editar</Text>
                                    </Item>
                                    <Item on:SMUI:action={() => showNuevoAutor(integrante)}>
                                        <Text>Convertir en autor principal</Text>
                                    </Item>
                                    {#if authUser.id != integrante.pivot.user_id}
                                        <Item on:SMUI:action={() => removeParticipante(integrante.id)}>
                                            <Text>Quitar</Text>
                                        </Item>
                                    {/if}
                                {:else}
                                    <Item>
                                        <Text>No tiene permisos</Text>
                                    </Item>
                                {/if}
                            </DataTableMenu>
                        </td>
                    </tr>
                {/each}

                {#if proyectoCapacidadInstalada.integrantes.length === 0}
                    <tr>
                        <td class="border-t px-6 py-4" colspan="6">{$_('No data recorded')}</td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>

    <hr class="mt-10 mb-10" />

    <h1 class="mt-24 mb-8 text-center text-3xl">Entidades aliadas</h1>

    <InfoMessage>
        <p>¿Existen documentos tipo contrato, convenio o acuerdos que hagan parte del proyecto con entidades aliadas u otros centros de formación SENA?</p>
        <Switch bind:checked={existenciaDocumentos} disabled={isSuperAdmin || proyectoCapacidadInstalada.integrantes.find((item) => item.id == authUser.id) ? undefined : true} />
    </InfoMessage>

    {#if existenciaDocumentos}
        {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && authUser.id == autorPrincipal.id)}
            <div class="mb-6 flex justify-between items-center mt-4">
                <Button on:click={() => Inertia.visit(route('proyectos-capacidad-instalada.entidades-aliadas.create', [proyectoCapacidadInstalada.id]))} variant="raised">Crear entidad aliada</Button>
            </div>
        {/if}
        <div class="bg-white rounded shadow">
            <table class="w-full whitespace-no-wrap table-fixed data-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">NIT</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Documento</th>
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {#each proyectoCapacidadInstalada.entidades_aliadas as entidadAliada}
                        <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t">
                                <p class="px-6 py-4 focus:text-indigo-500">
                                    {entidadAliada.nombre}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="px-6 py-4 focus:text-indigo-500">
                                    {entidadAliada.nit}
                                </p>
                            </td>
                            <td class="border-t">
                                <p class="px-6 py-4 focus:text-indigo-500">
                                    <a target="_blank" class="text-indigo-400 underline mt-4 mb-4 flex" download href={entidadAliada.documento}>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Descargar documento
                                    </a>
                                </p>
                            </td>
                            <td class="border-t td-actions">
                                <DataTableMenu class={proyectoCapacidadInstalada.entidades_aliadas.length < 4 ? 'z-50' : ''}>
                                    <Item on:SMUI:action={() => Inertia.visit(route('proyectos-capacidad-instalada.entidades-aliadas.edit', [proyectoCapacidadInstalada.id, entidadAliada.id]))}>
                                        <Text>Ver detalles</Text>
                                    </Item>
                                </DataTableMenu>
                            </td>
                        </tr>
                    {/each}

                    {#if proyectoCapacidadInstalada.entidades_aliadas.length === 0}
                        <tr>
                            <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                        </tr>
                    {/if}
                </tbody>
            </table>
        </div>
    {/if}

    <!-- Dialog -->
    <Dialog bind:open={dialogOpen} id="integrante">
        <div slot="title">
            <div class="mb-10 text-center">
                <div class="text-primary">
                    {#if $formParticipante._method != null}
                        Editar vinculación del integrante: {dialogTitle}
                    {:else}
                        Vincular integrante: {dialogTitle}
                    {/if}
                </div>
            </div>
        </div>
        <div slot="content">
            <form on:submit|preventDefault={submitParticipante} id="integrante-form">
                <fieldset disabled={isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4, 6]) && authUser.id == autorPrincipal.id) ? undefined : true}>
                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="rol_sennova" value="Rol SENNOVA" />
                        <Select id="rol_sennova" items={roles} bind:selectedValue={$formParticipante.rol_sennova} error={errors.rol_sennova} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                    </div>
                    <div class="mt-4">
                        <Input label="Número de meses de vinculación al proyecto" id="cantidad_meses" type="number" input$step="0.1" input$min="1" input$max="11.5" class="mt-1" bind:value={$formParticipante.cantidad_meses} placeholder="Número de meses de vinculación" autocomplete="off" required />
                    </div>
                    <div class="mt-4">
                        <Input label="Número de horas semanales dedicadas para el desarrollo del proyecto" id="cantidad_horas" type="number" input$step="1" input$min="1" input$max={$formParticipante.rol_sennova?.maxHoras} class="mt-1" bind:value={$formParticipante.cantidad_horas} placeholder="Número de horas semanales dedicadas para el desarrollo del proyecto" autocomplete="off" required />
                    </div>
                </fieldset>
            </form>
        </div>

        <div slot="actions" class="flex w-full">
            <Button on:click={closeDialog} type="button" variant={null}>
                {$_('Cancel')}
            </Button>
            <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit" form={formID}>Vincular</LoadingButton>
        </div>
    </Dialog>

    <!-- Dialog Register -->
    <Dialog bind:open={openNuevoParticipanteDialog} id="nuevo-integrante">
        <div slot="title">
            <div class="mb-10 text-center">
                <div class="text-primary">Registar nuevo integrante</div>
            </div>
        </div>
        <div slot="content">
            <form on:submit|preventDefault={submitRegister} id={formNuevoIntegranteId}>
                <fieldset>
                    <div class="mt-8">
                        <Input label="Nombre completo" id="nombre_nuevo_integrante" type="text" class="mt-1" bind:value={$formNuevoIntegrante.nombre} error={errors.nombre} required />
                    </div>

                    <div class="mt-8">
                        <Input label="Correo electrónico institucional" id="email_nuevo_integrante" type="email" class="mt-1" bind:value={$formNuevoIntegrante.email} error={errors.email} required />
                    </div>

                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="tipo_documento_nuevo_integrante" value="Tipo de documento" />
                        <Select id="tipo_documento_nuevo_integrante" items={tiposDocumento} bind:selectedValue={$formNuevoIntegrante.tipo_documento} error={errors.tipo_documento} autocomplete="off" placeholder="Seleccione un tipo de documento" required />
                    </div>

                    <div class="mt-8">
                        <Input label="Número de documento" id="numero_documento_nuevo_integrante" type="number" input$min="55555" input$max="9999999999999" class="mt-1" bind:value={$formNuevoIntegrante.numero_documento} error={errors.numero_documento} required />
                    </div>

                    <div class="mt-8">
                        <Input label="Número de celular" id="numero_celular_nuevo_integrante" type="number" input$min="3000000000" input$max="9999999999" class="mt-1" bind:value={$formNuevoIntegrante.numero_celular} error={errors.numero_celular} required />
                    </div>

                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="centro_formacion_id_nuevo_integrante" value="Centro de formación" />
                        <DynamicList id="centro_formacion_id_nuevo_integrante" bind:reset={openNuevoParticipanteDialog} bind:value={$formNuevoIntegrante.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                    </div>

                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="tipo_vinculacion_nuevo_participante" value="Tipo de vinculación" />
                        <Select id="tipo_vinculacion_nuevo_participante" items={tiposVinculacion} bind:selectedValue={$formNuevoIntegrante.tipo_vinculacion} error={errors.tipo_vinculacion} autocomplete="off" placeholder="Seleccione el tipo de vinculación" required />
                    </div>

                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="rol_sennova" value="Rol SENNOVA" />
                        <Select id="rol_sennova" items={roles} bind:selectedValue={$formNuevoIntegrante.rol_sennova} error={errors.rol_sennova} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                    </div>

                    <div class="mt-8">
                        <Input label="Número de meses de vinculación al proyecto" id="cantidad_meses_nuevo_participante" type="number" input$step="0.1" input$min="1" input$max="11.5" class="mt-1" bind:value={$formNuevoIntegrante.cantidad_meses} placeholder="Número de meses de vinculación" autocomplete="off" error={errors.cantidad_meses} required />
                    </div>

                    <div class="mt-8">
                        <Input
                            label="Número de horas semanales dedicadas para el desarrollo del proyecto"
                            id="cantidad_horas_nuevo_participante"
                            type="number"
                            input$step="1"
                            input$min="1"
                            input$max={$formNuevoIntegrante.rol_sennova?.maxHoras}
                            class="mt-1"
                            bind:value={$formNuevoIntegrante.cantidad_horas}
                            placeholder="Número de horas semanales dedicadas para el desarrollo del proyecto"
                            autocomplete="off"
                            required
                        />
                    </div>

                    <div class="mt-8">
                        <InfoMessage message="Los datos proporcionados serán tratados de acuerdo con la política de tratamiento de datos personales del SENA y a la ley 1581 de 2012 (acuerdo No. 0009 del 2016" />
                        <FormField>
                            <Checkbox bind:checked={$formNuevoIntegrante.autorizacion_datos} />
                            <span slot="label">¿La persona autoriza el tratamiento de datos personales?. <a href="https://www.sena.edu.co/es-co/transparencia/Documents/proteccion_datos_personales_sena_2016.pdf" target="_blank" class="text-indigo-500">Leer acuerdo No. 0009 del 2016</a></span>
                        </FormField>
                    </div>
                </fieldset>
            </form>
        </div>

        <div slot="actions" class="flex w-full">
            <Button on:click={closeDialog} type="button" variant={null}>
                {$_('Cancel')}
            </Button>
            <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit" form={formNuevoIntegranteId}>
                {$_('Save')}
            </LoadingButton>
        </div>
    </Dialog>

    <Dialog bind:open={dialogAutorPrincipal}>
        <div slot="title" class="flex items-center">Cambiar de autor principal</div>
        <div slot="content">
            <p>
                ¿Está seguro(a) que desea convertir a este usuario en autor principal?
                <br />
                Solo debe haber un autor principal por proyecto y será el único que podrá modificar la información.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={() => (dialogAutorPrincipal = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={submitNuevoAutorPrincipal}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>

<style>
    :global(#nuevo-integrante-dialog .mdc-dialog__surface) {
        max-width: 1050px;
    }

    :global(#integrante-dialog .mdc-dialog__surface) {
        max-width: 1050px;
    }
</style>
