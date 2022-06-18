<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Select from '@/Shared/Select'
    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let proyectoIdiTecnoacademia
    export let producto
    export let tiposProductos
    export let estadosProductos

    $: $title = 'Editar producto'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        _method: 'put',
        descripcion: producto.descripcion,
        tipo_producto_idi_id: {
            value: tiposProductos.find((item) => item.value == producto.tipo_producto_idi_id)?.value,
            label: tiposProductos.find((item) => item.value == producto.tipo_producto_idi_id)?.label,
        },
        estado: {
            value: estadosProductos.find((item) => item.value == producto.estado)?.value,
            label: estadosProductos.find((item) => item.value == producto.estado)?.label,
        },
        soporte: producto.soporte,
        link: producto.link,
        lugar: producto.lugar,
        fecha_realizacion: producto.fecha_realizacion,
    })

    function submit() {
        if (isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])) {
            $form.post(route('proyectos-idi-tecnoacademia.productos.update', [proyectoIdiTecnoacademia.id, producto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])) {
            $form.delete(route('proyectos-idi-tecnoacademia.productos.destroy', [proyectoIdiTecnoacademia.id, producto.id]))
        }
    }

    let mensajeDescripcion = ''
    let mensajeLink = ''
    let mensajeFecha = ''
    $: if ($form.tipo_producto_idi_id?.value == 2) {
        mensajeLink = 'Link de consulta'
    }
    $: if ($form.tipo_producto_idi_id?.value == 6) {
        mensajeFecha = 'Fecha de realización'
    } else {
        mensajeFecha = 'Fecha'
    }

    $: if ($form.tipo_producto_idi_id?.value == 8) {
        mensajeFecha = 'Fecha de publicación'
    } else {
        mensajeFecha = 'Fecha'
    }
    $: if ($form.tipo_producto_idi_id?.value == 1 || $form.tipo_producto_idi_id?.value == 2) {
        mensajeDescripcion = 'Referencia bibliográfica (NA Si no aplica)'
    } else if ($form.tipo_producto_idi_id?.value == 4) {
        mensajeDescripcion = 'Productos tecnológicos (NA Si no aplica)'
    } else if ($form.tipo_producto_idi_id?.value == 6) {
        mensajeDescripcion = 'Espacio o evento de participación cuidadana (NA Si no aplica)'
    } else if ($form.tipo_producto_idi_id?.value == 7) {
        mensajeDescripcion = 'Programa/Estrategia pedagógica de fomento a la CTI (NA Si no aplica)'
    } else if ($form.tipo_producto_idi_id?.value == 8) {
        mensajeDescripcion = 'Estrategias de comunicación del conocimiento, generación de contenidos impresos, multimedia y virtuales (NA Si no aplica)'
    } else if ($form.tipo_producto_idi_id?.value == 9) {
        mensajeDescripcion = 'Eventos científicos y participación en redes de conocimiento (NA Si no aplica) Incluir organizador y persona (s) representante(s)'
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.index')} class="text-cyan-400 hover:text-cyan-600"> Proyectos I+D+i TecnoAcademia </a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.edit', proyectoIdiTecnoacademia.id)} class="text-cyan-400 hover:text-cyan-600">Información básica</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.participantes.index', proyectoIdiTecnoacademia.id)} class="text-cyan-400 hover:text-cyan-600">Participantes</a>
                    <span class="text-cyan-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.productos.index', proyectoIdiTecnoacademia.id)} class="text-cyan-400 hover:text-cyan-600 font-extrabold underline">Productos</a>
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkRole(authUser, [5, 10, 12, 22]) ? undefined : true}>
                <div class="mt-4">
                    <Select id="tipo_producto_idi_id" items={tiposProductos} bind:selectedValue={$form.tipo_producto_idi_id} error={errors.tipo_producto_idi_id} autocomplete="off" placeholder="Seleccione un tipo" required />
                </div>
                <div class="mt-4">
                    <Select id="estado" items={estadosProductos} bind:selectedValue={$form.estado} error={errors.estado} autocomplete="off" placeholder="Seleccione un estado" required />
                </div>
                <div class="mt-8">
                    <Label class="mb-4" value={mensajeDescripcion} />
                    <Textarea label="Descripción del producto" maxlength="255" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>
                {#if $form.tipo_producto_idi_id?.value == 2 || $form.tipo_producto_idi_id?.value == 8}
                    <div class="mt-8">
                        <Label class="mb-4" value={mensajeLink} />
                        <Input label="Link" id="link" type="url" class="mt-1" error={errors.link} placeholder="Ejemplo: https://dominio.co" bind:value={$form.link} />
                    </div>
                {/if}
                <div class="mt-8">
                    <Label class="mb-4" labelFor="soporte" value="Url del soporte" />
                    <Input label="Url" id="soporte" type="url" class="mt-1" error={errors.soporte} placeholder="Url https://www.google.com.co" bind:value={$form.soporte} />
                    <InfoMessage>
                        Si son varios archivos PDF por favor unirlos en <a href="https://www.ilovepdf.com/" target="_blank">https://www.ilovepdf.com/</a>
                        <br />
                        <a href={producto.soporte} class="underline font-black" target="_blank" download>De clic aquí para descargar los soportes</a>
                    </InfoMessage>
                </div>
                {#if $form.tipo_producto_idi_id?.value == 1 || $form.tipo_producto_idi_id?.value == 6 || $form.tipo_producto_idi_id?.value == 8 || $form.tipo_producto_idi_id?.value == 9}
                    <div class="mt-4">
                        <Label labelFor="fecha_realizacion" value={mensajeFecha} />
                        <div>
                            <input id="fecha_realizacion" type="date" class="mt-1 block w-full p-4" bind:value={$form.fecha_realizacion} />
                        </div>
                    </div>
                {/if}
                {#if $form.tipo_producto_idi_id?.value == 6 || $form.tipo_producto_idi_id?.value == 8 || $form.tipo_producto_idi_id?.value == 9}
                    <div class="mt-8">
                        <Input label="Lugar" id="lugar" type="text" class="mt-1" error={errors.lugar} bind:value={$form.lugar} />
                    </div>
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar producto </button>
                {/if}
                {#if isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                        {$_('Edit')}
                    </LoadingButton>
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
