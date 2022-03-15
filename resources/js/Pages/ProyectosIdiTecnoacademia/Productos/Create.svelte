<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import File from '@/Shared/File'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let proyectoIdiTecnoacademia
    export let tiposProductos
    export let estadosProductos

    $: $title = 'Crear producto'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        descripcion: '',
        tipo_producto_idi_id: null,
        estado: null,
        soporte: null,
        link: '',
        lugar: '',
        fecha_realizacion: '',
    })

    function submit() {
        if (isSuperAdmin || checkRole(authUser, [12])) {
            $form.post(route('proyectos-idi-tecnoacademia.productos.store', [proyectoIdiTecnoacademia.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
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
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.index')} class="text-indigo-400 hover:text-indigo-600"> Proyectos I+D+i TecnoAcademia </a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.edit', proyectoIdiTecnoacademia.id)} class="text-indigo-400 hover:text-indigo-600">Información básica</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.participantes.index', proyectoIdiTecnoacademia.id)} class="text-indigo-400 hover:text-indigo-600">Participantes</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.productos.index', proyectoIdiTecnoacademia.id)} class="text-indigo-400 hover:text-indigo-600 font-extrabold underline">Productos</a>
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkRole(authUser, [12]) ? undefined : true}>
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
                    <Label class="mb-4" labelFor="soporte" value="Adjuntar soportes" />
                    <File id="soporte" type="file" accept="application/pdf" maxSize="10000" class="mt-1" bind:value={$form.soporte} error={errors.soporte} />
                    <InfoMessage>Si son varios archivos PDF por favor unirlos en <a href="https://www.ilovepdf.com/" target="_blank">https://www.ilovepdf.com/</a></InfoMessage>
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
                {#if isSuperAdmin || checkRole(authUser, [12])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear producto</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
