<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import DropdownPresupuesto from '@/Dropdowns/DropdownPresupuesto'
    import Textarea from '@/Components/Textarea'
    import InputError from '@/Components/InputError'

    export let convocatoria
    export let proyecto
    export let errors
    export let tiposLicencia
    export let tiposSoftware

    $: $title = 'Crear presupuesto'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let showQtyInput = true
    let sending = false
    $: software = $form.codigo_uso_presupuestal == '2010100600203101' ? true : false
    let form = useForm({
        software: software,
        convocatoria_presupuesto_id: null,
        descripcion: '',
        justificacion: '',
        valor: '',
        numero_items: '',
        tipo_software: '',
        tipo_licencia: '',
        fecha_inicio: '',
        fecha_finalizacion: '',
        codigo_uso_presupuestal: '',
    })

    function submit() {
        if (isSuperAdmin) {
            ;(sending = true),
                $form.post(route('convocatorias.proyectos.proyecto-presupuesto.store', [convocatoria.id, proyecto.id]), {
                    onStart: () => (sending = true),
                    onFinish: () => (sending = false),
                })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('convocatorias.proyectos.proyecto-presupuesto.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Presupuestos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <DropdownPresupuesto bind:selectedUsoPresupuestal={$form.convocatoria_presupuesto_id} bind:showQtyInput bind:codigoUsoPresupuestal={$form.codigo_uso_presupuestal} message={errors.convocatoria_presupuesto_id} {convocatoria} lineaProgramatica={proyecto.tipo_proyecto.linea_programatica} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="descripcion" value="Describa el bien o servicio a adquirir. Sea específico" />
                    <Textarea rows="4" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="justificacion" value="Justificación de la necesidad: ¿por qué se requiere este producto o servicio?" />
                    <Textarea rows="4" id="justificacion" error={errors.justificacion} bind:value={$form.justificacion} required />
                </div>

                {#if !showQtyInput}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="numero_items" value="Indique la cantidad requerida del producto o servicio relacionado" />
                        <Input id="numero_items" type="number" min="1" class="mt-1 block w-full" bind:value={$form.numero_items} error={errors.numero_items} required />
                    </div>
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="valor" value="Valor" />
                        <Input id="valor" type="number" min="1" class="mt-1 block w-full" bind:value={$form.valor} error={errors.valor} required />
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
                        <InputError message={errors.tipo_licencia} />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="tipo_software" value="Tipo de software" />
                        <select id="tipo_software" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200" bind:value={$form.tipo_software} required>
                            <option value="">Seleccione el tipo de software </option>
                            {#each tiposSoftware as { value, label }}
                                <option {value}>{label}</option>
                            {/each}
                        </select>
                        <InputError message={errors.tipo_software} />
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
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if $form.convocatoria_presupuesto_id != '' || (isSuperAdmin && $form.convocatoria_presupuesto_id != '')}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear presupuesto</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
