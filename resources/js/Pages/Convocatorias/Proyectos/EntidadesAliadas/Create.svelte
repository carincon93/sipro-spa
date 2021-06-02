<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import Input from '@/Components/Input'
    import Switch from '@/Components/Switch'
    import File from '@/Components/File'
    import Select from '@/Components/Select'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import InputError from '@/Components/InputError'

    export let errors
    export let convocatoria
    export let proyecto
    export let actividades
    export let tiposEntidadAliada
    export let naturalezaEntidadAliada
    export let tiposEmpresa

    let grupoInvestigacion = false
    let convenio = false

    $: $title = 'Crear entidad aliada'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

    let sending = false
    let form = useForm({
        tipo: '',
        nombre: '',
        naturaleza: '',
        tipo_empresa: '',
        nit: '',
        descripcion_convenio: '',
        grupo_investigacion: '',
        codigo_gruplac: '',
        enlace_gruplac: '',
        actividades_transferencia_conocimiento: '',
        recursos_especie: '',
        descripcion_recursos_especie: '',
        recursos_dinero: '',
        descripcion_recursos_dinero: '',
        carta_intencion: null,
        carta_propiedad_intelectual: null,
        soporte_convenio: null,
        idi: proyecto.idi ? true : false,
        actividad_id: [],
    })

    function submit() {
        if (isSuperAdmin || checkRole(74)) {
            $form.post(route('convocatorias.proyectos.entidades-aliadas.store', [convocatoria.id, proyecto.id]), {
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
                    {#if isSuperAdmin || checkRole(74)}
                        <a use:inertia href={route('convocatorias.proyectos.entidades-aliadas.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Entidades aliadas </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tipo" value="Tipo de entidad aliada" />
                    <Select id="tipo" items={tiposEntidadAliada} bind:selectedValue={$form.tipo} error={errors.tipo} autocomplete="off" placeholder="Seleccione el nivel del riesgo" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre de la entidad aliada/Centro de formación" />
                    <Textarea rows="4" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="naturaleza" value="Naturaleza de la entidad" />
                    <Select id="naturaleza" items={naturalezaEntidadAliada} bind:selectedValue={$form.naturaleza} error={errors.naturaleza} autocomplete="off" placeholder="Seleccione el tipo de riesgo" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tipo_empresa" value="Tipo de empresa" />
                    <Select id="tipo_empresa" items={tiposEmpresa} bind:selectedValue={$form.tipo_empresa} error={errors.tipo_empresa} autocomplete="off" placeholder="Seleccione la probabilidad" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nit" value="NIT" />
                    <Input id="nit" type="text" class="mt-1 block w-full" bind:value={$form.nit} error={errors.nit} required />
                </div>

                {#if proyecto.idi}
                    <div class="mt-4">
                        <p>¿Hay convenio?</p>
                        <Switch bind:checked={convenio} />
                    </div>
                    {#if convenio}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="descripcion_convenio" value="Descipción del convenio" />
                            <Textarea rows="4" id="descripcion_convenio" error={errors.descripcion_convenio} bind:value={$form.descripcion_convenio} required />
                        </div>
                    {/if}

                    <div class="mt-4">
                        <p>¿La entidad aliada tiene grupo de investigación?</p>
                        <Switch bind:checked={grupoInvestigacion} />
                    </div>
                    {#if grupoInvestigacion}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="grupo_investigacion" value="Grupo de investigación" />
                            <Textarea rows="4" id="grupo_investigacion" error={errors.grupo_investigacion} bind:value={$form.grupo_investigacion} required />
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="codigo_gruplac" value="Código del GrupLAC" />
                            <Input id="codigo_gruplac" type="text" class="mt-1 block w-full" error={errors.codigo_gruplac} placeholder="Ejemplo: COL0000000" bind:value={$form.codigo_gruplac} required={!grupoInvestigacion ? undefined : 'required'} />
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="enlace_gruplac" value="Enlace del GrupLAC" />
                            <Input id="enlace_gruplac" type="url" class="mt-1 block w-full" error={errors.enlace_gruplac} placeholder="Ejemplo: https://scienti.minciencias.gov.co/gruplac/jsp/Medicion/graficas/verPerfiles.jsp?id_convocatoria=0nroIdGrupo=0000000" bind:value={$form.enlace_gruplac} required={!grupoInvestigacion ? undefined : 'required'} />
                        </div>
                    {/if}
                {:else}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="soporte_convenio" value="Convenio" />
                        <File id="soporte_convenio" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.soporte_convenio} error={errors.soporte_convenio} required />
                    </div>
                {/if}

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="recursos_especie" value="Recursos en especie entidad aliada ($COP)" />
                    <Input id="recursos_especie" type="number" min="0" class="mt-1 block w-full" error={errors.recursos_especie} placeholder="COP" bind:value={$form.recursos_especie} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="descripcion_recursos_especie" value="Descripción de los recursos en especie aportados" />
                    <Textarea rows="4" id="descripcion_recursos_especie" error={errors.descripcion_recursos_especie} bind:value={$form.descripcion_recursos_especie} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="recursos_dinero" value="Recursos en dinero entidad aliada ($COP)" />
                    <Input id="recursos_dinero" type="number" min="0" class="mt-1 block w-full" error={errors.recursos_dinero} placeholder="COP" bind:value={$form.recursos_dinero} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="descripcion_recursos_dinero" value="Descripción de la destinación del dinero aportado" />
                    <Textarea rows="4" id="descripcion_recursos_dinero" error={errors.descripcion_recursos_dinero} bind:value={$form.descripcion_recursos_dinero} required />
                </div>

                {#if proyecto.idi}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="actividades_transferencia_conocimiento" value="Metodología o actividades de transferencia al centro de formación" />
                        <Textarea rows="4" id="actividades_transferencia_conocimiento" error={errors.actividades_transferencia_conocimiento} bind:value={$form.actividades_transferencia_conocimiento} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="carta_intencion" value="ANEXO 7. Carta de intención o acta que soporta el trabajo articulado con entidades aliadas (diferentes al SENA)" />
                        <File id="carta_intencion" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.carta_intencion} error={errors.carta_intencion} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="carta_propiedad_intelectual" value="ANEXO 8. Propiedad intelectual" />
                        <File id="carta_propiedad_intelectual" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.carta_propiedad_intelectual} error={errors.carta_propiedad_intelectual} required />
                    </div>
                {/if}

                {#if $form.progress}
                    <progress value={$form.progress.percentage} max="100" class="mt-4">
                        {$form.progress.percentage}%
                    </progress>
                {/if}

                <h6 class="mt-20">Actividades</h6>
                <div class="bg-white rounded shadow overflow-hidden">
                    <div class="p-4">
                        <Label required class="mb-4" labelFor="actividad_id" value="Relacione alguna actividad" />
                        <InputError message={errors.actividad_id} />
                    </div>
                    <div class="grid grid-cols-2">
                        {#each actividades as { id, descripcion }, i}
                            <FormField>
                                <Checkbox bind:group={$form.actividad_id} value={id} />
                                <span slot="label">{descripcion}</span>
                            </FormField>
                        {/each}
                    </div>
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkRole(74)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear entidad aliada</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
