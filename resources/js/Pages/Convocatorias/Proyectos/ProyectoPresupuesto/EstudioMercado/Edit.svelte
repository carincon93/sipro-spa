<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import File from '@/Shared/File'
    import Switch from '@/Shared/Switch'
    import Dialog from '@/Shared/Dialog'
    import PercentageProgress from '@/Shared/PercentageProgress'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoPresupuesto
    export let proyectoLoteEstudioMercado

    $: $title = proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion ? proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        _method: 'put',
        requiere_tercer_estudio_mercado: proyectoLoteEstudioMercado.estudios_mercado[2] || errors.tercer_valor || errors.tercer_empresa || errors.tercer_archivo ? true : false,
        convocatoria_presupuesto_id: proyectoPresupuesto.convocatoria_presupuesto.id,
        numero_items: proyectoLoteEstudioMercado.numero_items,
        ficha_tecnica: null,

        primer_estudio_mercado_id: proyectoLoteEstudioMercado.estudios_mercado[0].id,
        primer_valor: proyectoLoteEstudioMercado.estudios_mercado[0].valor,
        primer_empresa: proyectoLoteEstudioMercado.estudios_mercado[0].empresa,
        primer_archivo: null,

        segundo_estudio_mercado_id: proyectoLoteEstudioMercado.estudios_mercado[1].id,
        segundo_valor: proyectoLoteEstudioMercado.estudios_mercado[1].valor,
        segunda_empresa: proyectoLoteEstudioMercado.estudios_mercado[1].empresa,
        segundo_archivo: null,

        tercer_estudio_mercado_id: proyectoLoteEstudioMercado.estudios_mercado[2]?.id ?? null,
        tercer_valor: proyectoLoteEstudioMercado.estudios_mercado[2]?.valor ?? null,
        tercer_empresa: proyectoLoteEstudioMercado.estudios_mercado[2]?.empresa ?? null,
        tercer_archivo: null,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(3, 4, 6, 7, 9, 10, 12, 13) && proyecto.modificable == true)) {
            ;(sending = true),
                $form.post(route('convocatorias.proyectos.presupuesto.lote.update', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.id]), {
                    onStart: () => (sending = true),
                    onFinish: () => {
                        sending = false
                    },
                    onError: () => {
                        $form.requiere_tercer_estudio_mercado = errors.tercer_valor || errors.tercer_empresa || errors.tercer_archivo ? true : false
                    },
                    preserveScroll: true,
                })
        }
    }

    function destroy() {
        if (isSuperAdmin || (checkPermission(4, 7, 10, 13) && proyecto.modificable == true)) {
            $form.delete(route('convocatorias.proyectos.presupuesto.lote.destroy', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.id]))
        }
    }

    let average
    $: average = (parseInt($form.primer_valor) + parseInt($form.segundo_valor) + (parseInt($form.tercer_valor) > 0 && $form.requiere_tercer_estudio_mercado ? parseInt($form.tercer_valor) : 0)) / (parseInt($form.tercer_valor) > 0 && $form.requiere_tercer_estudio_mercado ? 3 : 2)
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkPermission(3, 4, 6, 7, 9, 10, 12, 13)}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Presupuesto </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {#if isSuperAdmin || checkPermission(3, 4, 6, 7, 9, 10, 12, 13)}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.lote.index', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])} class="text-indigo-400 hover:text-indigo-600">
                            {proyectoPresupuesto.convocatoria_presupuesto.presupuesto_sennova.uso_presupuestal.descripcion}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Estudios de mercado
                    <span class="text-indigo-400 font-medium">/</span>
                    Editar
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(3, 4, 6, 7, 9, 10, 12, 13) && proyecto.modificable == true) ? undefined : true}>
                <div class="mt-4">
                    <InfoMessage class="mb-2" message="Diligencie los campos de <strong>Cantidad</strong> y <strong>Ficha técnica</strong> solo si este rubro es de maquinaria/equipos." />

                    <Label class="mb-4" labelFor="numero_items" value="Indique la cantidad de maquinaria/equipos referenciado en el ANEXO 2 Fichas técnicas para maquinaria y equipos" />
                    <Input label="Cantidad" id="numero_items" type="number" input$min="1" class="mt-1" bind:value={$form.numero_items} error={errors.numero_items} />
                </div>

                <div class="mt-4">
                    <Label class="mb-4" labelFor="ficha_tecnica" value="ANEXO 2. Fichas técnicas para maquinaria y equipos" />
                    <File id="ficha_tecnica" type="file" accept="application/pdf" maxSize="10000" class="mt-1" bind:value={$form.ficha_tecnica} error={errors.ficha_tecnica} />
                    <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.presupuesto.lote.download', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.id])}>Descargar ficha técnica</a>
                </div>

                <h1 class="text-center mt-20 mb-20">Primer estudio de mercado</h1>

                <div class="mt-4">
                    <Input label="Valor (incluido IVA)" id="primer_valor" type="number" input$min="1" class="mt-1" bind:value={$form.primer_valor} error={errors.primer_valor} required />
                </div>

                <div class="mt-4">
                    <Input label="Nombre de la empresa" id="primer_empresa" type="text" class="mt-1" bind:value={$form.primer_empresa} error={errors.primer_empresa} required />
                </div>

                <div class="mt-4">
                    <Label class="mb-4" labelFor="primer_archivo" value="Soporte" />
                    <File id="primer_archivo" type="file" accept="application/pdf" maxSize="10000" class="mt-1" bind:value={$form.primer_archivo} error={errors.primer_archivo} />
                    <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.presupuesto.download-soporte', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.estudios_mercado[0].id])}>Descargar soporte</a>
                </div>

                <h1 class="text-center mt-20 mb-20">Segundo estudio de mercado</h1>

                <div class="mt-4">
                    <Input label="Valor (incluido IVA)" id="segundo_valor" type="number" input$min="1" class="mt-1" bind:value={$form.segundo_valor} error={errors.segundo_valor} required />
                </div>

                <div class="mt-4">
                    <Input label="Nombre de la empresa" id="segunda_empresa" type="text" class="mt-1" bind:value={$form.segunda_empresa} error={errors.segunda_empresa} required />
                </div>

                <div class="mt-4">
                    <Label class="mb-4" labelFor="segundo_archivo" value="Soporte" />
                    <File id="segundo_archivo" type="file" accept="application/pdf" maxSize="10000" class="mt-1" bind:value={$form.segundo_archivo} error={errors.segundo_archivo} />
                    <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.presupuesto.download-soporte', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.estudios_mercado[1].id])}>Descargar soporte</a>
                </div>

                <div class="mt-8">
                    <Label labelFor="requiere_tercer_estudio_mercado" value="¿Requiere de un tercer estudio de mercado?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.requiere_tercer_estudio_mercado} />
                    <InputError message={errors.requiere_tercer_estudio_mercado} />
                </div>

                {#if $form.requiere_tercer_estudio_mercado}
                    <h1 class="text-center mt-20 mb-20">Tercer estudio de mercado</h1>
                    <div class="mt-4">
                        <Input label="Valor (incluido IVA)" id="tercer_valor" type="number" input$min="0" class="mt-1" bind:value={$form.tercer_valor} error={errors.tercer_valor} required />
                    </div>

                    <div class="mt-4">
                        <Input label="Nombre de la empresa" id="tercer_empresa" type="text" class="mt-1" bind:value={$form.tercer_empresa} error={errors.tercer_empresa} required />
                    </div>

                    <div class="mt-4">
                        <Label labelFor="tercer_archivo" value="Soporte" required />
                        <File id="tercer_archivo" type="file" accept="application/pdf" maxSize="10000" class="mt-1" bind:value={$form.tercer_archivo} error={errors.tercer_archivo} />
                        {#if proyectoLoteEstudioMercado.estudios_mercado[2] != undefined}
                            <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.presupuesto.download-soporte', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.estudios_mercado[2].id])} required>Descargar soporte</a>
                        {/if}
                    </div>
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (checkPermission(4, 7, 10, 13) && proyecto.modificable == true)}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar estudio de mercado </button>
                {/if}
                <p class="break-all w-72">
                    Valor promedio: ${average > 0 ? new Intl.NumberFormat('de-DE').format(average) : 0} COP
                </p>
                {#if isSuperAdmin || (checkPermission(3, 4, 6, 7, 9, 10, 12, 13) && proyecto.modificable == true)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar estudio de mercado</LoadingButton>
                {/if}
            </div>
        </form>
        {#if $form.progress}
            <PercentageProgress percentage={$form.progress.percentage} />
        {/if}
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
