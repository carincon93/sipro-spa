<script>
    import { Inertia } from '@inertiajs/inertia'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import axios from 'axios'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Select from '@/Components/Select'
    import DataTable from '@/Components/DataTable'
    import LoadingButton from '@/Components/LoadingButton'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'
    import Dialog from '@/Components/Dialog'
    import Button from '@/Components/Button'
    import DynamicList from '@/Dropdowns/DynamicList'

    export let errors
    export let convocatoria
    export let proyecto
    export let tiposDocumento
    export let tiposParticipacion
    export let lineaProgramatica

    let resultados = []

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    /**
     * Buscar
     */
    let form = useForm({
        search_participante: '',
    })
    let formID
    let dialogOpen = false
    let dialogTitle
    let sending = false
    let sended = false
    function submit() {
        sending = true
        sended = false
        try {
            axios
                .post(route('convocatorias.proyectos.participantes.users', { convocatoria: convocatoria.id, proyecto: proyecto.id }), $form)
                .then((response) => {
                    resultados = response.data
                    sending = false
                    sended = true
                })
                .catch((error) => {
                    sending = false
                })
        } catch (error) {
            sending = false
        }
    }

    function removeParticipante(id) {
        Inertia.post(
            route('convocatorias.proyectos.participantes.users.unlink', {
                proyecto: proyecto.id,
                convocatoria: convocatoria.id,
            }),
            { user_id: id, _method: 'DELETE' },
            { preserveScroll: true },
        )
    }

    /**
     * Participantes
     */
    let formParticipante = useForm({
        _method: null,
        user_id: 0,
        es_autor: 0,
        cantidad_horas: 0,
        cantidad_meses: 0,
    })

    function showParticipante(user) {
        reset()
        dialogTitle = user.nombre
        dialogOpen = true
        formID = 'participante-form'
        $formParticipante.user_id = user.id
        if (user.pivot) {
            $formParticipante._method = 'PUT'
            $formParticipante.es_autor = user.pivot.es_autor ? 1 : 0
            $formParticipante.cantidad_meses = user.pivot.cantidad_meses
            $formParticipante.cantidad_horas = user.pivot.cantidad_horas
        }
    }

    function submitParticipante() {
        $formParticipante.post(
            route('convocatorias.proyectos.participantes.users.link', {
                proyecto: proyecto.id,
                convocatoria: convocatoria.id,
            }),
            {
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
            },
        )
    }

    /**
     * Registrar nuevo participante
     */
    let formNuevoParticipante = useForm({
        nombre: '',
        email: '',
        tipo_documento: '',
        numero_documento: '',
        numero_celular: '',
        tipo_participacion: '',
        es_autor: 0,
        cantidad_meses: 0,
        cantidad_horas: 0,
        centro_formacion_id: null,
        convocatoria_rol_sennova_id: null,
    })

    let formNuevoParticipanteId
    let openNuevoParticipanteDialog = false
    function showRegister() {
        reset()
        openNuevoParticipanteDialog = true
        formNuevoParticipanteId = 'nuevo-participante-form'
    }

    function submitRegister() {
        $formNuevoParticipante.post(route('convocatorias.proyectos.participantes.users.register', { convocatoria: convocatoria.id, proyecto: proyecto.id }), {
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

    function reset() {
        //Participante - form
        $formParticipante.reset()
        //Nuevo participante - form
        $formNuevoParticipante.reset()
        $formNuevoParticipante.convocatoria_rol_sennova_id = null
    }

    function closeDialog() {
        $formNuevoParticipante.convocatoria_rol_sennova_id = null
        reset()
        dialogOpen = false
        openNuevoParticipanteDialog = false
        sending = false
    }
</script>

<div class="bg-indigo-100 py-4">
    <h1 class="text-4xl text-center">Participantes</h1>
    <p class="text-center w-1/3 m-auto mt-8">Realiza la búsqueda de participantes por número de documento o por el correo electrónico institucional</p>
    <form on:submit|preventDefault={submit} on:input={() => (sended = false)}>
        <div class="p-8">
            <div class="mt-4 flex flex-row">
                <Input id="search_participante" type="search" placeholder="Escriba el número de documento o el correo electrónico instiucional" class="mt-1 m-auto block flex-1" bind:value={$form.search_participante} minlength="4" autocomplete="off" required />
                <LoadingButton loading={sending} class="btn-indigo m-auto ml-1" type="submit">Buscar</LoadingButton>
            </div>
        </div>
    </form>
</div>

{#if sended}
    <DataTable class="bg-indigo-100 p-4">
        <div slot="title">Resultados de la búsqueda de participantes</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Correo electrónico</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Documento</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Celular</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Centro de formación</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Regional</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Acciones</th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each resultados as resultado (resultado.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {resultado.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {resultado.email}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {resultado.numero_documento}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {resultado.numero_celular}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {resultado.centro_formacion ? resultado.centro_formacion.nombre : ''}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {resultado.centro_formacion ? resultado.centro_formacion.regional.nombre : ''}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            <Item on:SMUI:action={() => showParticipante(resultado)}>
                                <Text>Vincular</Text>
                            </Item>
                        </ResourceMenu>
                    </td>
                </tr>
            {/each}

            {#if resultados.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">
                        {$_('No data recorded')}
                        <Button on:click={() => showRegister()} type="button" variant={null}>Crear participante</Button>
                    </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
{/if}

<DataTable class="mt-10">
    <div slot="title">Participantes vinculados</div>

    <thead slot="thead">
        <tr class="text-left font-bold">
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Nombre</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Correo electrónico</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Centro de formación</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Regional</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">¿Es autor?</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Meses</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Horas</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Acciones</th>
        </tr>
    </thead>
    <tbody slot="tbody">
        {#each proyecto.participantes as participante (participante.id)}
            <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                        {participante.nombre}
                    </p>
                </td>
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center">
                        {participante.email}
                    </p>
                </td>
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center">
                        {participante.centro_formacion ? participante.centro_formacion.nombre : ''}
                    </p>
                </td>
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center">
                        {participante.centro_formacion ? participante.centro_formacion.regional.nombre : ''}
                    </p>
                </td>
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center">
                        {participante.pivot.es_autor ? 'Si' : 'No'}
                    </p>
                </td>
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center">
                        {participante.pivot.cantidad_meses}
                    </p>
                </td>
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center">
                        {participante.pivot.cantidad_horas}
                    </p>
                </td>
                <td class="border-t td-actions">
                    <ResourceMenu>
                        <Item on:SMUI:action={() => showParticipante(participante)}>
                            <Text>Editar</Text>
                        </Item>
                        <Item on:SMUI:action={() => removeParticipante(participante.id)}>
                            <Text>Quitar</Text>
                        </Item>
                    </ResourceMenu>
                </td>
            </tr>
        {/each}

        {#if proyecto.participantes.length === 0}
            <tr>
                <td class="border-t px-6 py-4" colspan="4">{$_('No data recorded')}</td>
            </tr>
        {/if}
    </tbody>
</DataTable>

<!-- Dialog -->
<Dialog bind:open={dialogOpen}>
    <div slot="title">
        <div class="mb-10 text-center">
            <div class="text-primary">
                {#if $formParticipante._method != null}
                    Editar vinculación del participante: {dialogTitle}
                {:else}
                    Vincular participante: {dialogTitle}
                {/if}
            </div>
        </div>
    </div>
    <div slot="content">
        <form on:submit|preventDefault={submitParticipante} id="participante-form">
            <fieldset>
                <p class="block font-medium mb-2 text-gray-700 text-sm">Por favor diligencie la siguiente información sobre la vinculación del participante.</p>
                <div class="mb-2">
                    <Label required class="mb-4" labelFor="cantidad_meses" value="Número de meses de vinculación" />
                    <Input id="cantidad_meses" type="number" step="0.5" min="1" max={proyecto.diff_meses > 11.5 ? 11.5 : proyecto.diff_meses} class="mt-1 block w-full" bind:value={$formParticipante.cantidad_meses} placeholder="Número de meses de vinculación" autocomplete="off" required />
                </div>
                <div class="mb-2">
                    <Label required class="mb-4" labelFor="cantidad_horas" value="Número de horas semanales dedicadas para el desarrollo del proyecto" />
                    <Input id="cantidad_horas" type="number" step="1" min="1" class="mt-1 block w-full" bind:value={$formParticipante.cantidad_horas} placeholder="Número de horas semanales dedicadas para el desarrollo del proyecto" autocomplete="off" required />
                </div>
                <div class="mb-2">
                    <Label class="mb-4" labelFor="es_autor" value="¿Es autor?" />
                    <select id="es_autor" class="presupuesto-info w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200" bind:value={$formParticipante.es_autor} required>
                        <option value="1" selected={$formParticipante.es_autor == 1 ? true : false}>Si</option>
                        <option value="0" selected={$formParticipante.es_autor == 0 ? true : false}>No</option>
                    </select>
                </div>
            </fieldset>
        </form>
    </div>

    <div slot="actions" class="block flex w-full">
        <Button on:click={closeDialog} type="button" variant={null}>
            {$_('Cancel')}
        </Button>
        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit" form={formID}>
            {$_('Save')}
        </LoadingButton>
    </div>
</Dialog>

<!-- Dialog Register -->
<Dialog bind:open={openNuevoParticipanteDialog} id="nuevo-participante">
    <div slot="title">
        <div class="mb-10 text-center">
            <div class="text-primary">Registar nuevo participante</div>
        </div>
    </div>
    <div slot="content">
        <form on:submit|preventDefault={submitRegister} id={formNuevoParticipanteId}>
            <fieldset>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre_nuevo_participante" value="Nombre completo" />
                    <Input id="nombre_nuevo_participante" type="text" class="mt-1 block w-full" bind:value={$formNuevoParticipante.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="email_nuevo_participante" value="Correo electrónico" />
                    <Input id="email_nuevo_participante" type="email" class="mt-1 block w-full" bind:value={$formNuevoParticipante.email} error={errors.email} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tipo_documento_nuevo_participante" value="Tipo de documento" />
                    <Select id="tipo_documento_nuevo_participante" items={tiposDocumento} bind:selectedValue={$formNuevoParticipante.tipo_documento} error={errors.tipo_documento} autocomplete="off" placeholder="Seleccione un tipo de documento" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="numero_documento_nuevo_participante" value="Número de documento" />
                    <Input id="numero_documento_nuevo_participante" type="number" min="0" class="mt-1 block w-full" bind:value={$formNuevoParticipante.numero_documento} error={errors.numero_documento} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="numero_celular_nuevo_participante" value="Número de celular" />
                    <Input id="numero_celular_nuevo_participante" type="number" min="0" class="mt-1 block w-full" bind:value={$formNuevoParticipante.numero_celular} error={errors.numero_celular} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="centro_formacion_id_nuevo_participante" value="Centro de formación" />
                    <DynamicList id="centro_formacion_id_nuevo_participante" bind:reset={openNuevoParticipanteDialog} bind:value={$formNuevoParticipante.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tipo_participacion_nuevo_participante" value="Tipo de participación" />
                    <Select id="tipo_participacion_nuevo_participante" items={tiposParticipacion} bind:selectedValue={$formNuevoParticipante.tipo_participacion} error={errors.tipo_participacion} autocomplete="off" placeholder="Seleccione el tipo de participación" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="convocatoria_rol_sennova_id" value="Rol SENNOVA" />
                    <DynamicList id="convocatoria_rol_sennova_id" bind:reset={openNuevoParticipanteDialog} bind:value={$formNuevoParticipante.convocatoria_rol_sennova_id} routeWebApi={route('web-api.convocatorias.roles-sennova', [convocatoria.id, lineaProgramatica])} message={errors.convocatoria_rol_sennova_id} placeholder="Busque por el nombre del rol" required />
                </div>

                <p class="block font-medium mt-10 mb-10 text-gray-700 text-sm">Por favor diligencie la siguiente información sobre la vinculación del participante.</p>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="cantidad_meses_nuevo_participante" value="Número de meses de vinculación" />
                    <Input id="cantidad_meses_nuevo_participante" type="number" step="0.5" min="1" max={proyecto.diff_meses > 11.5 ? 11.5 : proyecto.diff_meses} class="mt-1 block w-full" bind:value={$formNuevoParticipante.cantidad_meses} placeholder="Número de meses de vinculación" autocomplete="off" error={errors.cantidad_meses} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="cantidad_horas_nuevo_participante" value="Número de horas semanales dedicadas para el desarrollo del proyecto" />
                    <Input id="cantidad_horas_nuevo_participante" type="number" step="1" min="1" class="mt-1 block w-full" bind:value={$formNuevoParticipante.cantidad_horas} placeholder="Número de horas semanales dedicadas para el desarrollo del proyecto" autocomplete="off" error={errors.cantidad_horas} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="es_autor" value="¿Es autor?" />
                    <select id="es_autor" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200" bind:value={$formNuevoParticipante.es_autor} required>
                        <option value="">Seleccione una opción</option>
                        <option value="1" selected={$formNuevoParticipante.es_autor == 1 ? true : false}>Si</option>
                        <option value="0" selected={$formNuevoParticipante.es_autor == 0 ? true : false}>No</option>
                    </select>
                </div>
            </fieldset>
        </form>
    </div>

    <div slot="actions" class="block flex w-full">
        <Button on:click={closeDialog} type="button" variant={null}>
            {$_('Cancel')}
        </Button>
        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit" form={formNuevoParticipanteId}>
            {$_('Save')}
        </LoadingButton>
    </div>
</Dialog>

<style>
    :global(#nuevo-participante-dialog .mdc-dialog__surface) {
        max-width: 750px;
    }
</style>
