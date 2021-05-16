<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Textarea from '@/Components/Textarea'

    export let errors
    export let convocatoria

    $: $title = 'Crear I+D+i'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexIDi = authUser.can.find((element) => element == 'IDi.index') == 'IDi.index'

    let canCreateIDi = authUser.can.find((element) => element == 'IDi.create') == 'IDi.create'

    let canShowIDi = authUser.can.find((element) => element == 'IDi.show') == 'IDi.show'

    let canEditIDi = authUser.can.find((element) => element == 'IDi.edit') == 'IDi.edit'

    let canDestroyIDi = authUser.can.find((element) => element == 'IDi.destroy') == 'IDi.destroy'

    let sending = false
    let form = useForm({
        centro_formacion_id: null,
        linea_investigacion_id: null,
        disciplina_subarea_conocimiento_id: null,
        tematica_estrategica_id: null,
        red_conocimiento_id: null,
        tipo_proyecto_id: null,
        actividad_economica_id: null,
        titulo: 'Escriba aquí el título del proyecto. No mayor a 20 palabras.',
        fecha_inicio: '',
        fecha_finalizacion: '',
    })

    function submit() {
        if (canCreateIDi || isSuperAdmin) {
            $form.post(route('convocatorias.idi.store', [convocatoria.id]), {
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
                        <a use:inertia href={route('convocatorias.idi.index', [convocatoria.id])} class="text-indigo-400 hover:text-indigo-600"> I+D+i </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit} novalidate>
        <fieldset class="p-8">
            <div class="mt-28">
                <Label required labelFor="titulo" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué." />
                <Textarea rows="4" id="titulo" error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <Input id="fecha_inicio" type="date" class="mt-1 block w-full" error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <Input id="fecha_finalizacion" type="date" class="mt-1 block w-full" error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                </div>
                <div>
                    <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea de investigación" />
                </div>
                <div>
                    <DynamicList id="linea_investigacion_id" bind:value={$form.linea_investigacion_id} routeWebApi={route('web-api.lineas-investigacion')} placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional" message={errors.linea_investigacion_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipo_proyecto_id" value="Tipo de proyecto" />
                </div>
                <div>
                    <DynamicList id="tipo_proyecto_id" bind:value={$form.tipo_proyecto_id} routeWebApi={route('web-api.tipos-proyecto', 2)} placeholder="Busque por el nombre del tipo de proyecto, línea programática" message={errors.tipo_proyecto_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="red_conocimiento_id" value="Red de conocimiento sectorial" />
                </div>
                <div>
                    <DynamicList id="red_conocimiento_id" bind:value={$form.red_conocimiento_id} routeWebApi={route('web-api.redes-conocimiento')} placeholder="Busque por el nombre de la red de conocimiento sectorial" message={errors.red_conocimiento_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="disciplina_subarea_conocimiento_id" value="Disciplina de la subárea de conocimiento" />
                </div>
                <div>
                    <DynamicList id="disciplina_subarea_conocimiento_id" bind:value={$form.disciplina_subarea_conocimiento_id} routeWebApi={route('web-api.disciplinas-subarea-conocimiento')} placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento" message={errors.disciplina_subarea_conocimiento_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="actividad_economica_id" value="¿En cuál de estas actividades económicas se puede aplicar el proyecto de investigación?" />
                </div>
                <div>
                    <DynamicList id="actividad_economica_id" bind:value={$form.actividad_economica_id} routeWebApi={route('web-api.actividades-economicas')} placeholder="Busque por el nombre de la actividad económica" message={errors.actividad_economica_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tematica_estrategica_id" value="Temática estratégica SENA" />
                </div>
                <div>
                    <DynamicList id="tematica_estrategica_id" bind:value={$form.tematica_estrategica_id} routeWebApi={route('web-api.tematicas-estrategicas')} placeholder="Busque por el nombre de la temática estrategica SENA" message={errors.tematica_estrategica_id} required />
                </div>
            </div>
        </fieldset>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                    {$_('Continue')}
                </LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
