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
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let primerGrupoPresupuestal
    export let segundoGrupoPresupuestal
    export let tercerGrupoPresupuestal
    export let usosPresupuestales
    export let lineasProgramaticas

    $: $title = 'Crear presupuesto SENNOVA'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        requiere_estudio_mercado: null,
        sumar_al_presupuesto: null,
        mensaje: '',
        habilitado: null,
        primer_grupo_presupuestal_id: null,
        segundo_grupo_presupuestal_id: null,
        tercer_grupo_presupuestal_id: null,
        uso_presupuestal_id: null,
        linea_programatica_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('presupuesto-sennova.store'))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('presupuesto-sennova.index')} class="text-violet-400 hover:text-violet-600"> Presupuesto SENNOVA </a>
                    <span class="text-violet-400 font-medium">/</span>
                    Crear
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
            <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                {#if isSuperAdmin}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Crear presupuesto SENNOVA</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
