<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'

    export let errors
    export let convocatoria
    export let proyecto
    export let resultados
    export let actividades

    $: $title = 'Crear producto'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        nombre: '',
        resultado_id: null,
        subtipologia_minciencias_id: null,
        fecha_inicio: '',
        fecha_finalizacion: '',
        indicador: '',
        trl: null,
        tatp_servicio_tecnologico: proyecto.tatp || proyecto.servicio_tecnologico ? true : false,
        valor_proyectado: null,
        medio_verificacion: '',
        actividad_id: [],
    })

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [1, 5, 8])) {
            $form.post(route('convocatorias.proyectos.productos.store', [convocatoria.id, proyecto.id]), {
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
                    {#if isSuperAdmin || checkPermission(authUser, [1, 5, 8])}
                        <a use:inertia href={route('convocatorias.proyectos.productos.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Productos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkPermission(authUser, [1, 5, 8]) ? undefined : true}>
                <div class="mt-8 mb-8">
                    <p class="text-center">Fecha de ejecución</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex">
                            <Label required labelFor="fecha_inicio" value="Del" />
                            <div class="ml-4">
                                <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={proyecto.fecha_inicio} max={proyecto.fecha_finalizacion} bind:value={$form.fecha_inicio} required />
                            </div>
                        </div>
                        <div class="mt-4 flex">
                            <Label required labelFor="fecha_finalizacion" value="hasta" />
                            <div class="ml-4">
                                <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={proyecto.fecha_inicio} max={proyecto.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                            </div>
                        </div>
                    </div>
                    {#if errors.fecha_inicio || errors.fecha_finalizacion}
                        <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                    {/if}
                </div>

                <hr />

                <div class="mt-8">
                    <Textarea label="Nombre" maxlength="40000" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="resultado_id" value="Resultado" />
                    <Select id="resultado_id" items={resultados} bind:selectedValue={$form.resultado_id} error={errors.resultado_id} autocomplete="off" placeholder="Seleccione un resultado" required />
                </div>
                <div class="mt-4">
                    <Label required labelFor="indicador" value="Indicador" />

                    {#if $form.tatp_servicio_tecnologico == true}
                        <InfoMessage class="mb-2" message="Deber ser medible y con una fórmula. Por ejemplo: (# metodologías validadas/# metodologías totales) X 100" />
                    {:else}
                        <InfoMessage class="mb-2" message="Especifique los medios de verificación para validar los logros del proyecto." />
                    {/if}
                    <Textarea label="Descripción" maxlength="40000" id="indicador" error={errors.indicador} bind:value={$form.indicador} required />
                </div>

                {#if $form.tatp_servicio_tecnologico == false}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="subtipologia_minciencias_id" value="Subtipología Minciencias" />
                        <DynamicList id="subtipologia_minciencias_id" bind:value={$form.subtipologia_minciencias_id} routeWebApi={route('web-api.subtipologias-minciencias')} placeholder="Busque por el nombre de la subtipología Minciencias" message={errors.subtipologia_minciencias_id} required />
                    </div>

                    <div class="mt-4">
                        <Input label="TRL" id="trl" type="number" input$max="9" input$min="1" class="block w-full" error={errors.trl} bind:value={$form.trl} required />
                    </div>
                {:else if proyecto.ta_tp}
                    <div class="mt-4">
                        <Input label="Valor proyectado" id="valor_proyectado" type="number" input$min="0" input$max="100" class="mt-1" bind:value={$form.valor_proyectado} required />
                    </div>
                {/if}

                {#if $form.tatp_servicio_tecnologico == true}
                    <div class="mt-4">
                        <Label required labelFor="medio_verificacion" value="Medio de verificación" />

                        <InfoMessage message="Especifique los medios de verificación para validar los logros del objetivo específico." />
                        <Textarea maxlength="40000" id="medio_verificacion" error={errors.medio_verificacion} bind:value={$form.medio_verificacion} required />
                    </div>
                {/if}

                <h6 class="mt-20 mb-12 text-2xl">Actividades</h6>
                <div class="bg-white rounded shadow overflow-hidden">
                    <div class="p-4">
                        <Label required class="mb-4" labelFor="actividad_id" value="Relacione alguna actividad" />
                        <InputError message={errors.actividad_id} />
                    </div>
                    <div class="grid grid-cols-2">
                        {#each actividades as { id, descripcion }, i}
                            <FormField class="border-b border-l py-4">
                                <Checkbox bind:group={$form.actividad_id} value={id} />
                                <span slot="label">{descripcion}</span>
                            </FormField>
                        {/each}
                        {#if actividades.length == 0}
                            <p class="p-4">Sin información registrada</p>
                        {/if}
                    </div>
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkPermission(authUser, [1, 5, 8])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear producto</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
