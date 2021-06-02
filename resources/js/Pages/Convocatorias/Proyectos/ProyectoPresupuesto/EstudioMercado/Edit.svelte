<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import InputError from '@/Components/InputError'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import File from '@/Components/File'
    import Switch from '@/Components/Switch'
    import Dialog from '@/Components/Dialog'
    import PercentageProgress from '@/Components/PercentageProgress'

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
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

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
        if (isSuperAdmin || checkRole(74)) {
            ;(sending = true),
                $form.post(route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.update', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.id]), {
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
        if (isSuperAdmin || checkRole(74)) {
            $form.delete(route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.destroy', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.id]))
        }
    }

    let average
    $: average = (parseInt($form.primer_valor) + parseInt($form.segundo_valor) + (parseInt($form.tercer_valor) > 0 && $form.requiere_tercer_estudio_mercado ? parseInt($form.tercer_valor) : 0)) / (parseInt($form.tercer_valor) > 0 && $form.requiere_tercer_estudio_mercado ? 3 : 2)
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin || checkRole(74)}
                        <a use:inertia href={route('convocatorias.proyectos.proyecto-presupuesto.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Presupuesto </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {#if isSuperAdmin || checkRole(74)}
                        <a use:inertia href={route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.index', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])} class="text-indigo-400 hover:text-indigo-600">
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
            <fieldset class="p-8" disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
                <div class="mt-4">
                    <Input label="Indique la cantidad requerida del producto o servicio relacionado" id="numero_items" type="number" min="1" class="mt-1 block w-full" bind:value={$form.numero_items} error={errors.numero_items} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="ficha_tecnica" value="ANEXO 2. Fichas técnicas para maquinaria y equipos" />
                    <File id="ficha_tecnica" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.ficha_tecnica} error={errors.ficha_tecnica} />
                    <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.download', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.id])}>Descargar ficha técnica</a>
                </div>

                <h1 class="text-center mt-20 mb-20">Primer estudio de mercado</h1>

                <div class="mt-4">
                    <Input label="Valor (incluido IVA)" id="primer_valor" type="number" min="1" class="mt-1 block w-full" bind:value={$form.primer_valor} error={errors.primer_valor} required />
                </div>

                <div class="mt-4">
                    <Input label="Nombre de la empresa" id="primer_empresa" type="text" class="mt-1 block w-full" bind:value={$form.primer_empresa} error={errors.primer_empresa} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="primer_archivo" value="Soporte" />
                    <File id="primer_archivo" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.primer_archivo} error={errors.primer_archivo} />
                    <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.proyecto-presupuesto.download-soporte', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.estudios_mercado[0].id])}>Descargar soporte</a>
                </div>

                <h1 class="text-center mt-20 mb-20">Segundo estudio de mercado</h1>

                <div class="mt-4">
                    <Input label="Valor (incluido IVA)" id="segundo_valor" type="number" min="1" class="mt-1 block w-full" bind:value={$form.segundo_valor} error={errors.segundo_valor} required />
                </div>

                <div class="mt-4">
                    <Input label="Nombre de la empresa" id="segunda_empresa" type="text" class="mt-1 block w-full" bind:value={$form.segunda_empresa} error={errors.segunda_empresa} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="segundo_archivo" value="Soporte" />
                    <File id="segundo_archivo" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.segundo_archivo} error={errors.segundo_archivo} />
                    <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.proyecto-presupuesto.download-soporte', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.estudios_mercado[1].id])}>Descargar soporte</a>
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
                        <Input label="Valor (incluido IVA)" id="tercer_valor" type="number" min="0" class="mt-1 block w-full" bind:value={$form.tercer_valor} error={errors.tercer_valor} required />
                    </div>

                    <div class="mt-4">
                        <Input label="Nombre de la empresa" id="tercer_empresa" type="text" class="mt-1 block w-full" bind:value={$form.tercer_empresa} error={errors.tercer_empresa} required />
                    </div>

                    <div class="mt-4">
                        <Label labelFor="tercer_archivo" value="Soporte" required />
                        <File id="tercer_archivo" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.tercer_archivo} error={errors.tercer_archivo} />
                        {#if proyectoLoteEstudioMercado.estudios_mercado[2] != undefined}
                            <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.proyecto-presupuesto.download-soporte', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, proyectoLoteEstudioMercado.estudios_mercado[2].id])} required>Descargar soporte</a>
                        {/if}
                    </div>
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkRole(74)}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar estudio de mercado </button>
                {/if}
                <p class="break-all w-72">
                    Valor promedio: ${average > 0 ? new Intl.NumberFormat('de-DE').format(average) : 0} COP
                </p>
                {#if isSuperAdmin || checkRole(74)}
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
