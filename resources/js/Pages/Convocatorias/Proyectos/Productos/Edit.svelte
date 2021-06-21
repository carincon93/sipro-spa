<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import Dialog from '@/Shared/Dialog'
    import InfoMessage from '@/Shared/InfoMessage'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'

    export let errors
    export let convocatoria
    export let proyecto
    export let producto
    export let resultados
    export let actividades
    export let actividadesRelacionadas

    $: $title = producto ? producto.nombre : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        nombre: producto.nombre,
        resultado_id: {
            value: producto.resultado_id,
            label: resultados.find((item) => item.value == producto.resultado_id)?.label,
        },
        fecha_inicio: producto.fecha_inicio,
        fecha_finalizacion: producto.fecha_finalizacion,
        indicador: producto.indicador,
        medio_verificacion: producto.producto_ta_tp ? producto.producto_ta_tp?.medio_verificacion : producto.producto_servicio_tecnologico?.medio_verificacion,

        nombre_indicador: producto.producto_servicio_tecnologico?.nombre_indicador,
        formula_indicador: producto.producto_servicio_tecnologico?.formula_indicador,

        trl: producto.producto_idi ? producto.producto_idi?.trl : producto.producto_cultura_innovacion?.trl,
        subtipologia_minciencias_id: producto.producto_idi ? producto.producto_idi?.subtipologia_minciencias_id : producto.producto_cultura_innovacion?.subtipologia_minciencias_id,

        valor_proyectado: producto.producto_ta_tp?.valor_proyectado,
        tatp_servicio_tecnologico: proyecto.tatp || proyecto.servicio_tecnologico ? true : false,
        actividad_id: actividadesRelacionadas,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13]) && proyecto.modificable == true)) {
            $form.put(route('convocatorias.proyectos.productos.update', [convocatoria.id, proyecto.id, producto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || (checkPermission(authUser, [4, 7, 10, 13]) && proyecto.modificable == true)) {
            $form.delete(route('convocatorias.proyectos.productos.destroy', [convocatoria.id, proyecto.id, producto.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13])}
                        <a use:inertia href={route('convocatorias.proyectos.productos.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Productos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {producto.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13]) && proyecto.modificable == true) ? undefined : true}>
                <div class="mt-8 mb-8">
                    <Label class="text-center" required value="Fecha de ejecución" />
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex">
                            <Label labelFor="fecha_inicio" value="Del" />
                            <div class="ml-4">
                                <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={proyecto.fecha_inicio} max={proyecto.fecha_finalizacion} bind:value={$form.fecha_inicio} required />
                            </div>
                        </div>
                        <div class="mt-4 flex">
                            <Label labelFor="fecha_finalizacion" value="hasta" />
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
                    {#if $form.tatp_servicio_tecnologico == true}
                        <InfoMessage>
                            <p>
                                Los productos pueden corresponder a bienes o servicios. Un bien es un objeto tangible, almacenable o transportable, mientras que el servicio es una prestación intangible.
                                <br />
                                El producto debe cumplir con la siguiente estructura:
                                <br />
                                Cuando el producto es un bien: nombre del bien + la condición deseada. Ejemplo: Vía construida.
                                <br />
                                Cuando el producto es un servicio: nombre del servicio + el complemento. Ejemplo: Servicio de asistencia técnica para el mejoramiento de hábitos alimentarios
                            </p>
                        </InfoMessage>
                    {/if}
                    <Textarea maxlength="40000" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>
                <div class="mt-8">
                    <Label required class="mb-4" labelFor="resultado_id" value="Resultado" />
                    <Select id="resultado_id" items={resultados} bind:selectedValue={$form.resultado_id} error={errors.resultado_id} autocomplete="off" placeholder="Seleccione un resultado" required />
                </div>
                <div class="mt-8">
                    <Label required labelFor="indicador" value="Indicador" />

                    {#if $form.tatp_servicio_tecnologico == true}
                        <InfoMessage class="mb-2" message="Deber ser medible y con una fórmula. Por ejemplo: (# metodologías validadas/# metodologías totales) X 100" />
                    {:else}
                        <InfoMessage class="mb-2" message="Especifique los medios de verificación para validar los logros del proyecto." />
                    {/if}
                    <Textarea maxlength="40000" id="indicador" error={errors.indicador} bind:value={$form.indicador} required />
                </div>

                {#if $form.tatp_servicio_tecnologico == false}
                    <div class="mt-8">
                        <Label required class="mb-4" labelFor="subtipologia_minciencias_id" value="Subtipología Minciencias" />
                        <DynamicList id="subtipologia_minciencias_id" bind:value={$form.subtipologia_minciencias_id} routeWebApi={route('web-api.subtipologias-minciencias')} placeholder="Busque por el nombre de la subtipología Minciencias" message={errors.subtipologia_minciencias_id} required />
                    </div>

                    <div class="mt-8">
                        <Input label="TRL" id="trl" type="number" input$max="9" input$min="1" class="mt-2" error={errors.trl} bind:value={$form.trl} required />
                    </div>
                {:else if proyecto.ta_tp}
                    <div class="mt-8">
                        <Input label="Valor proyectado" id="valor_proyectado" type="number" input$min="0" input$max="100" class="mt-1" error={errors.valor_proyectado} bind:value={$form.valor_proyectado} required />
                    </div>
                {/if}

                {#if $form.tatp_servicio_tecnologico == true}
                    <div class="mt-8">
                        <Label required labelFor="medio_verificacion" value="Medio de verificación" />

                        {#if proyecto.servicio_tecnologico}
                            <InfoMessage message="Los medios de verificación corresponden a las evidencias y/o fuentes de información en las que está disponibles los registros, la información necesaria y suficiente. Dichos medios pueden ser documentos oficiales, informes, evaluaciones, encuestas, documentos o reportes internos que genera el proyecto, entre otros." />
                        {:else}
                            <InfoMessage message="Especifique los medios de verificación para validar los logros del objetivo específico." />
                        {/if}

                        <Textarea maxlength="40000" id="medio_verificacion" error={errors.medio_verificacion} bind:value={$form.medio_verificacion} required />
                    </div>
                {/if}
                {#if proyecto.servicio_tecnologico}
                    <div class="mt-8">
                        <Label required labelFor="nombre_indicador" value="Nombre del Indicador del producto" />

                        <InfoMessage message="El indicador debe mantener una estructura coherente. Esta se compone de dos elementos: en primer lugar, debe ir el objeto a cuantificar, descrito por un sujeto y posteriormente la condición deseada, definida a través de un verbo en participio. Por ejemplo: Kilómetros de red vial nacional construidos." />
                        <Textarea maxlength="40000" id="nombre_indicador" error={errors.nombre_indicador} bind:value={$form.nombre_indicador} required />
                    </div>

                    <div class="mt-8">
                        <Label required labelFor="formula_indicador" value="Fórmula del Indicador del producto" />

                        <InfoMessage
                            message="El método de cálculo debe ser una expresión matemática definida de manera adecuada y de fácil comprensión, es decir, deben quedar claras cuáles son las variables utilizadas. Los métodos de cálculo más comunes son el porcentaje, la tasa de variación, la razón y el número índice. Aunque éstos no son las únicas expresiones para los indicadores, sí son las más frecuentes."
                        />
                        <Textarea maxlength="40000" id="formula_indicador" error={errors.formula_indicador} bind:value={$form.formula_indicador} required />
                    </div>
                {/if}

                <h6 class="mt-20 mb-12 text-2xl">Actividades</h6>
                <div class="bg-white rounded shadow overflow-hidden">
                    <div class="p-4">
                        <Label required class="mb-4" labelFor="actividad_id" value="Relacione alguna actividad" />
                        <InputError message={errors.actividad_id} />
                    </div>
                    {#if isSuperAdmin || proyecto.modificable == true}
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
                    {:else}
                        <div class="p-2">
                            <ul class="list-disc p-4">
                                {#each actividades as { id, descripcion }, i}
                                    {#each $form.actividad_id as actividad}
                                        {#if id == actividad}
                                            <li class="first-letter-uppercase mb-4">{descripcion}</li>
                                        {/if}
                                    {/each}
                                {/each}
                            </ul>
                        </div>
                    {/if}
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (checkPermission(authUser, [4, 7, 10]) && proyecto.modificable == true)}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar producto </button>
                {/if}
                {#if isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13]) && proyecto.modificable == true)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar producto</LoadingButton>
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
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
