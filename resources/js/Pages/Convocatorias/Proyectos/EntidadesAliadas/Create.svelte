<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Input from '@/Shared/Input'
    import Switch from '@/Shared/Switch'
    import Select from '@/Shared/Select'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import InputError from '@/Shared/InputError'

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
    let isSuperAdmin = checkRole(authUser, [1])

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
        infraestructura_tecnoacademia: '',
        fecha_inicio_convenio: '',
        fecha_fin_convenio: '',
        actividad_id: [],
        tipo_convocatoria: convocatoria.tipo_convocatoria,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 8]) && proyecto.modificable == true)) {
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
                    {#if isSuperAdmin || (checkPermission(authUser, [1, 8]) && proyecto.modificable == true)}
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
            <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [1, 8]) && proyecto.modificable == true) ? undefined : true}>
                <div class="mt-8">
                    <Label required class="mb-4" labelFor="tipo" value="Tipo de entidad aliada" />
                    <Select id="tipo" items={tiposEntidadAliada} bind:selectedValue={$form.tipo} error={errors.tipo} autocomplete="off" placeholder="Seleccione el tipo de entidad" required />
                </div>

                <div class="mt-8">
                    <Textarea label="Nombre de la entidad aliada/Centro de formación" maxlength="255" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>

                <div class="mt-8">
                    <Label required class="mb-4" labelFor="naturaleza" value="Naturaleza de la entidad" />
                    <Select id="naturaleza" items={naturalezaEntidadAliada} bind:selectedValue={$form.naturaleza} error={errors.naturaleza} autocomplete="off" placeholder="Seleccione la naturaleza de la entidad" required />
                </div>

                <div class="mt-8">
                    <Label required class="mb-4" labelFor="tipo_empresa" value="Tipo de empresa" />
                    <Select id="tipo_empresa" items={tiposEmpresa} bind:selectedValue={$form.tipo_empresa} error={errors.tipo_empresa} autocomplete="off" placeholder="Seleccione el tipo de empresa" required />
                </div>

                <div class="mt-8">
                    <Input label="NIT" id="nit" type="text" class="mt-1" bind:value={$form.nit} error={errors.nit} required />
                </div>

                {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
                    <div class="mt-8">
                        <p>¿Hay convenio?</p>
                        <Switch bind:checked={convenio} />
                    </div>
                    {#if convenio}
                        <div class="mt-8">
                            <Textarea label="Descipción del convenio" maxlength="400" id="descripcion_convenio" error={errors.descripcion_convenio} bind:value={$form.descripcion_convenio} required />
                        </div>
                    {/if}

                    <div class="mt-8">
                        <p>¿La entidad aliada tiene grupo de investigación?</p>
                        <Switch bind:checked={grupoInvestigacion} />
                    </div>
                    {#if grupoInvestigacion}
                        <div class="mt-8">
                            <Textarea label="Grupo de investigación" maxlength="191" id="grupo_investigacion" error={errors.grupo_investigacion} bind:value={$form.grupo_investigacion} required />
                        </div>

                        <div class="mt-8">
                            <Input label="Código del GrupLAC" id="codigo_gruplac" type="text" class="mt-1" error={errors.codigo_gruplac} placeholder="Ejemplo: COL0000000" bind:value={$form.codigo_gruplac} required={!grupoInvestigacion ? undefined : 'required'} />
                        </div>

                        <div class="mt-8">
                            <Input label="Enlace del GrupLAC" id="enlace_gruplac" type="url" class="mt-1" error={errors.enlace_gruplac} placeholder="Ejemplo: https://scienti.minciencias.gov.co/gruplac/jsp/Medicion/graficas/verPerfiles.jsp?id_convocatoria=0nroIdGrupo=0000000" bind:value={$form.enlace_gruplac} required={!grupoInvestigacion ? undefined : 'required'} />
                        </div>
                    {/if}
                    <div class="mt-8">
                        <Input label="Recursos en especie entidad aliada ($COP)" id="recursos_especie" type="number" input$min="0" class="mt-1" error={errors.recursos_especie} placeholder="COP" bind:value={$form.recursos_especie} required />
                    </div>

                    <div class="mt-8">
                        <Textarea label="Descripción de los recursos en especie aportados" maxlength="2500" id="descripcion_recursos_especie" error={errors.descripcion_recursos_especie} bind:value={$form.descripcion_recursos_especie} required />
                    </div>

                    <div class="mt-8">
                        <Input label="Recursos en dinero entidad aliada ($COP)" id="recursos_dinero" type="number" input$min="0" class="mt-1" error={errors.recursos_dinero} placeholder="COP" bind:value={$form.recursos_dinero} required />
                    </div>

                    <div class="mt-8">
                        <Textarea label="Descripción de la destinación del dinero aportado" maxlength="2500" id="descripcion_recursos_dinero" error={errors.descripcion_recursos_dinero} bind:value={$form.descripcion_recursos_dinero} required />
                    </div>

                    <div class="mt-8">
                        <Textarea label="Metodología o actividades de transferencia al centro de formación" maxlength="2500" id="actividades_transferencia_conocimiento" error={errors.actividades_transferencia_conocimiento} bind:value={$form.actividades_transferencia_conocimiento} required />
                    </div>

                    {#if convocatoria.tipo_convocatoria == 1 || convocatoria.tipo_convocatoria == 3}
                        <div class="mt-8">
                            <Label required class="mb-4" labelFor="carta_intencion" value="ANEXO 7. Carta de intención o acta que soporta el trabajo articulado con entidades aliadas (diferentes al SENA)" />
                            <Input label="Url" id="carta_intencion" type="url" class="mt-1" error={errors.carta_intencion} placeholder="Url https://www.google.com.co" bind:value={$form.carta_intencion} required />
                        </div>

                        <div class="mt-8">
                            <Label required class="mb-4" labelFor="carta_propiedad_intelectual" value="ANEXO 8. Propiedad intelectual" />
                            <Input label="Url" id="carta_propiedad_intelectual" type="url" class="mt-1" error={errors.carta_propiedad_intelectual} placeholder="Url https://www.google.com.co" bind:value={$form.carta_propiedad_intelectual} required />
                        </div>
                    {/if}
                {:else if proyecto.codigo_linea_programatica == 70}
                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="soporte_convenio" value="Url del archivo del convenio" />
                        <Input label="Url" id="soporte_convenio" type="url" class="mt-1" error={errors.soporte_convenio} placeholder="Url https://www.google.com.co" bind:value={$form.soporte_convenio} required />
                    </div>

                    <div class="mt-4">
                        <p class="text-center">Fechas de vigencia Convenio/Acuerdos</p>
                        <div class="mt-4 flex items-start justify-around">
                            <div class="mt-4 flex {errors.fecha_inicio_convenio ? '' : 'items-center'}">
                                <Label required labelFor="fecha_inicio_convenio" class={errors.fecha_inicio_convenio ? 'top-3.5 relative' : ''} value="Del" />
                                <div class="ml-4">
                                    <input id="fecha_inicio_convenio" type="date" class="mt-1 block w-full p-4" bind:value={$form.fecha_inicio_convenio} required />
                                </div>
                            </div>
                            <div class="mt-4 flex {errors.fecha_fin_convenio ? '' : 'items-center'}">
                                <Label required labelFor="fecha_fin_convenio" class={errors.fecha_fin_convenio ? 'top-3.5 relative' : ''} value="hasta" />
                                <div class="ml-4">
                                    <input id="fecha_fin_convenio" type="date" class="mt-1 block w-full p-4" bind:value={$form.fecha_fin_convenio} required />
                                </div>
                            </div>
                        </div>
                        {#if errors.fecha_inicio_convenio || errors.fecha_fin_convenio}
                            <InputError message={errors.fecha_inicio_convenio || errors.fecha_fin_convenio} />
                        {/if}
                    </div>
                {/if}

                {#if $form.progress}
                    <progress value={$form.progress.percentage} max="100" class="mt-4">
                        {$form.progress.percentage}%
                    </progress>
                {/if}

                {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
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
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (checkPermission(authUser, [1, 8]) && proyecto.modificable == true)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear entidad aliada</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
