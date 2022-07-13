<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import Switch from '@/Shared/Switch'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let presupuestoSennova
    export let primerGrupoPresupuestal
    export let segundoGrupoPresupuestal
    export let tercerGrupoPresupuestal
    export let usosPresupuestales
    export let lineasProgramaticas

    $: $title = presupuestoSennova ? presupuestoSennova.id : null

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false

    let form = useForm({
        requiere_estudio_mercado: presupuestoSennova.requiere_estudio_mercado,
        sumar_al_presupuesto: presupuestoSennova.sumar_al_presupuesto,
        mensaje: presupuestoSennova.mensaje,
        habilitado: presupuestoSennova.habilitado,
        primer_grupo_presupuestal_id: {
            value: presupuestoSennova.primer_grupo_presupuestal_id,
            label: primerGrupoPresupuestal.find((item) => item.value == presupuestoSennova.primer_grupo_presupuestal_id)?.label,
        },
        segundo_grupo_presupuestal_id: {
            value: presupuestoSennova.segundo_grupo_presupuestal_id,
            label: segundoGrupoPresupuestal.find((item) => item.value == presupuestoSennova.segundo_grupo_presupuestal_id)?.label,
        },
        tercer_grupo_presupuestal_id: {
            value: presupuestoSennova.tercer_grupo_presupuestal_id,
            label: tercerGrupoPresupuestal.find((item) => item.value == presupuestoSennova.tercer_grupo_presupuestal_id)?.label,
        },
        uso_presupuestal_id: {
            value: presupuestoSennova.uso_presupuestal_id,
            label: usosPresupuestales.find((item) => item.value == presupuestoSennova.uso_presupuestal_id)?.label,
        },
        linea_programatica_id: {
            value: presupuestoSennova.linea_programatica_id,
            label: lineasProgramaticas.find((item) => item.value == presupuestoSennova.linea_programatica_id)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('presupuesto-sennova.update', presupuestoSennova.id), {
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin) {
            $form.delete(route('presupuesto-sennova.destroy', presupuestoSennova.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin}
                        <a use:inertia href={route('presupuesto-sennova.index')} class="text-violet-400 hover:text-violet-600"> Presupuesto SENNOVA </a>
                    {/if}
                    <span class="text-violet-400 font-medium">/</span>
                    Editar presupuesto SENNOVA
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="primer_grupo_presupuestal_id" value="Primer grupo presupuestal" />
                    <Select id="primer_grupo_presupuestal_id" items={primerGrupoPresupuestal} bind:selectedValue={$form.primer_grupo_presupuestal_id} error={errors.primer_grupo_presupuestal_id} autocomplete="off" placeholder="Seleccione un rubro del primer grupo presupuestal" required />
                </div>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="segundo_grupo_presupuestal_id" value="Segundo grupo presupuestal" />
                    <Select id="segundo_grupo_presupuestal_id" items={segundoGrupoPresupuestal} bind:selectedValue={$form.segundo_grupo_presupuestal_id} error={errors.segundo_grupo_presupuestal_id} autocomplete="off" placeholder="Seleccione un rubro del segundo grupo presupuestal" required />
                </div>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tercer_grupo_presupuestal_id" value="Tercer grupo presupuestal" />
                    <Select id="tercer_grupo_presupuestal_id" items={tercerGrupoPresupuestal} bind:selectedValue={$form.tercer_grupo_presupuestal_id} error={errors.tercer_grupo_presupuestal_id} autocomplete="off" placeholder="Seleccione un rubro del tercer grupo presupuestal" required />
                </div>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="uso_presupuestal_id" value="Uso presupuestal" />
                    <Select id="uso_presupuestal_id" items={usosPresupuestales} bind:selectedValue={$form.uso_presupuestal_id} error={errors.uso_presupuestal_id} autocomplete="off" placeholder="Seleccione un uso presupuestal" required />
                </div>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="linea_programatica_id" value="Línea programática" />
                    <Select id="linea_programatica_id" items={lineasProgramaticas} bind:selectedValue={$form.linea_programatica_id} error={errors.linea_programatica_id} autocomplete="off" placeholder="Seleccione una línea programática" required />
                </div>
                <div class="mt-4">
                    <Label labelFor="mensaje" value="Mensaje" />
                    <InfoMessage>
                        Escribe un mensaje si desea que se muestre cuando se seleccione el rubro.
                        <br />
                        Ejemplo: Para el proyecto solo se podrá destinar hasta $4.460.000 de viáticos, lo cual comprende la sumatoria de todos los rubros que tengan esta finalidad.
                    </InfoMessage>
                    <Textarea maxlength="40000" id="mensaje" bind:value={$form.mensaje} error={errors.mensaje} />
                </div>

                <hr class="mt-10 mb-10" />

                <div class="mt-4">
                    <Label labelFor="requiere_estudio_mercado" value="¿Requiere estudio mercado? Nota: Si la opción es 'Si' el sistema solicitará al proponente el estudio de mercado" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.requiere_estudio_mercado} />
                    <InputError message={errors.requiere_estudio_mercado} />
                </div>

                <hr class="mt-10 mb-10" />

                <div class="mt-4">
                    <Label labelFor="sumar_al_presupuesto" value="¿Este rubro suma al presupuesto? Nota: Si la opción es 'Si' el sistema sumará este rubro al total del precio del proyecto" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.sumar_al_presupuesto} />
                    <InputError message={errors.sumar_al_presupuesto} />
                </div>

                <hr class="mt-10 mb-10" />

                <div class="mt-4">
                    <Label labelFor="habilitado" value="¿Este rubro esta habilitado? Nota: Si la opción es 'Si' el sistema hará visible el rubro en el formulario de presupuesto" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.habilitado} />
                    <InputError message={errors.habilitado} />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={() => (dialogOpen = true)}> Eliminar uso presupuestal </button>
                {/if}
                {#if isSuperAdmin}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Editar uso presupuestal</LoadingButton>
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
                <Button on:click={() => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
