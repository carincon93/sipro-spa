<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import LoadingButton from '@/Components/LoadingButton'
    import Button from '@/Components/Button'
    import Textarea from '@/Components/Textarea'
    import Dialog from '@/Components/Dialog'
    import InfoMessage from '@/Components/InfoMessage'
    import DropdownPresupuesto from '@/Dropdowns/DropdownPresupuesto'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoPresupuesto
    export let tiposLicencia
    export let tiposSoftware

    let presupuestoSennova = proyectoPresupuesto

    $: $title = proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal ? proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let showQtyInput = proyectoPresupuesto.valor != null ? false : true
    let dialog_open = false
    let sending = false
    $: software = $form.codigo_uso_presupuestal == '2010100600203101' ? true : false
    let form = useForm({
        codigo_uso_presupuestal: '',
        convocatoria_presupuesto_id: proyectoPresupuesto.convocatoria_presupuesto_id,
        descripcion: proyectoPresupuesto.descripcion,
        justificacion: proyectoPresupuesto.justificacion,
        valor: proyectoPresupuesto.valor,
        numero_items: proyectoPresupuesto.numero_items,
        software: software,
        tipo_software: proyectoPresupuesto.software_info?.tipo_software,
        tipo_licencia: proyectoPresupuesto.software_info?.tipo_licencia,
        fecha_inicio: proyectoPresupuesto.software_info?.fecha_inicio,
        fecha_finalizacion: proyectoPresupuesto.software_info?.fecha_finalizacion,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('convocatorias.proyectos.proyecto-presupuesto.update', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin) {
            $form.delete(route('convocatorias.proyectos.proyecto-presupuesto.destroy', [convocatoria.id, proyecto.id, proyectoPresupuesto.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
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
                        <Label required class="mb-4" labelFor="descripcion" value="Describa el bien o servicio a adquirir. Sea específico" />
                        <Textarea rows="4" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="justificacion" value="Justificación de la necesidad: ¿por qué se requiere este producto o servicio?" />
                        <Textarea rows="4" id="justificacion" error={errors.justificacion} bind:value={$form.justificacion} required />
                    </div>

                    {#if showQtyInput != undefined && !showQtyInput}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="numero_items" value="Indique la cantidad requerida del producto o servicio relacionado" />
                            <Input id="numero_items" type="number" min="0" class="mt-1 block w-full" bind:value={$form.numero_items} error={errors.numero_items} required />
                        </div>
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="valor" value="Valor" />
                            <Input id="valor" type="number" min="0" class="mt-1 block w-full" bind:value={$form.valor} error={errors.valor} required />
                        </div>
                    {/if}

                    {#if $form.codigo_uso_presupuestal == '2010100600203101'}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="tipo_licencia" value="Tipo de licencia" />
                            <select id="tipo_licencia" bind:value={$form.tipo_licencia} required>
                                <option value="">Seleccione el tipo de licencia </option>
                                {#each tiposLicencia as { value, label }}
                                    <option {value}>{label}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="tipo_software" value="Tipo de software" />
                            <select id="tipo_software" bind:value={$form.tipo_software} required>
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
                                <Input id="fecha_inicio" type="date" class="mt-1 block w-full" bind:value={$form.fecha_inicio} required />
                            </div>
                            <div class="mt-4">
                                <Label class="mb-4" labelFor="fecha_finalizacion" value="Fecha de finalización" />
                                <Input id="fecha_finalizacion" type="date" class="mt-1 block w-full" bind:value={$form.fecha_finalizacion} />
                            </div>
                        </div>
                        {#if errors.fecha_inicio || errors.fecha_finalizacion}
                            <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                        {/if}
                    {/if}
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}> Eliminar presupuesto </button>
                    {/if}
                    {#if $form.convocatoria_presupuesto_id != '' || (isSuperAdmin && $form.convocatoria_presupuesto_id != '')}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar presupuesto</LoadingButton>
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
    <Dialog bind:open={dialog_open}>
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
                <Button on:click={(event) => (dialog_open = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
