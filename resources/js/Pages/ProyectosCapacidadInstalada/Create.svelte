<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import Input from '@/Shared/Input'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors
    export let centrosFormacion
    export let listaBeneficiados
    export let roles

    $: $title = 'Crear proyecto de capacidad instalada'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        centro_formacion_id: null,
        linea_investigacion_id: null,
        semillero_investigacion_id: null,
        disciplina_subarea_conocimiento_id: null,
        red_conocimiento_id: null,
        actividad_economica_id: null,
        tipo_proyecto_capacidad_instalada_id: null,
        subtipo_proyecto_capacidad_instalada_id: null,
        titulo: '',
        fecha_inicio: null,
        fecha_finalizacion: null,
        beneficia_a: '',
        rol_sennova: null,
        cantidad_meses: 0,
        cantidad_horas: 0,
    })

    function submit() {
        if (isSuperAdmin || checkRole(authUser, [4, 6])) {
            $form.post(route('proyectos-capacidad-instalada.store'), {
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
                    {#if isSuperAdmin || checkRole(authUser, [4, 6])}
                        <a use:inertia href={route('proyectos-capacidad-instalada.index')} class="text-cyan-400 hover:text-cyan-600"> Proyectos de capacidad instalada </a>
                    {/if}
                    <span class="text-cyan-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || checkRole(authUser, [4, 6]) ? undefined : true}>
            <div class="mt-28">
                <Label required labelFor="titulo" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué. (Máximo 20 palabras)" />
                <Textarea label="Título" id="titulo" sinContador={true} error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion || errors.max_meses_ejecucion}
                    <div class="mb-20 flex justify-center mt-4">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                    </div>
                {/if}
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                </div>
                <div>
                    <Select id="centro_formacion_id" items={centrosFormacion} bind:selectedValue={$form.centro_formacion_id} error={errors.centro_formacion_id} autocomplete="off" placeholder="Busque por el nombre del centro de formación" required />
                </div>
            </div>

            {#if $form.centro_formacion_id?.value}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea de investigación" />
                    </div>
                    <div>
                        <DynamicList id="linea_investigacion_id" bind:value={$form.linea_investigacion_id} routeWebApi={route('web-api.lineas-investigacion', $form.centro_formacion_id?.value)} classes="min-h" placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional" message={errors.linea_investigacion_id} required />
                    </div>
                </div>
            {/if}
            {#if $form.linea_investigacion_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="semillero_investigacion_id" value="Semillero de investigación" />
                    </div>
                    <div>
                        <DynamicList id="semillero_investigacion_id" bind:value={$form.semillero_investigacion_id} routeWebApi={route('web-api.semilleros-investigacion', $form.linea_investigacion_id)} classes="min-h" placeholder="Busque por el nombre del semillero de investigación" message={errors.semillero_investigacion_id} required />
                    </div>
                </div>
            {/if}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="red_conocimiento_id" value="Red de conocimiento sectorial" />
                </div>
                <div>
                    <DynamicList id="red_conocimiento_id" bind:value={$form.red_conocimiento_id} routeWebApi={route('web-api.redes-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la red de conocimiento sectorial" message={errors.red_conocimiento_id} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="area_conocimiento_id" value="Área de conocimiento" />
                </div>
                <div>
                    <DynamicList id="area_conocimiento_id" bind:value={$form.area_conocimiento_id} routeWebApi={route('web-api.areas-conocimiento')} classes="min-h" placeholder="Busque por el nombre de la área de conocimiento" message={errors.area_conocimiento_id} required />
                </div>
            </div>
            {#if $form.area_conocimiento_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="subarea_conocimiento_id" value="Subárea de conocimiento" />
                    </div>
                    <div>
                        <DynamicList id="subarea_conocimiento_id" bind:value={$form.subarea_conocimiento_id} routeWebApi={route('web-api.subareas-conocimiento', $form.area_conocimiento_id)} classes="min-h" placeholder="Busque por el nombre de la subárea de conocimiento" message={errors.subarea_conocimiento_id} required />
                    </div>
                </div>
            {/if}
            {#if $form.subarea_conocimiento_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="disciplina_subarea_conocimiento_id" value="Disciplina de la subárea de conocimiento" />
                    </div>
                    <div>
                        <DynamicList id="disciplina_subarea_conocimiento_id" bind:value={$form.disciplina_subarea_conocimiento_id} routeWebApi={route('web-api.disciplinas-subarea-conocimiento', $form.subarea_conocimiento_id)} classes="min-h" placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento" message={errors.disciplina_subarea_conocimiento_id} required />
                    </div>
                </div>
            {/if}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="actividad_economica_id" value="¿En cuál de estas actividades económicas se puede aplicar el proyecto?" />
                </div>
                <div>
                    <DynamicList id="actividad_economica_id" bind:value={$form.actividad_economica_id} routeWebApi={route('web-api.actividades-economicas')} placeholder="Busque por el nombre de la actividad económica" classes="min-h" message={errors.actividad_economica_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipo_proyecto_capacidad_instalada_id" value="Tipo de proyecto" />
                </div>
                <div>
                    <DynamicList id="tipo_proyecto_capacidad_instalada_id" bind:value={$form.tipo_proyecto_capacidad_instalada_id} routeWebApi={route('web-api.tipos-proyecto-capacidad-instalada')} classes="min-h" placeholder="Busque por el nombre del tipo de proyecto" message={errors.tipo_proyecto_capacidad_instalada_id} required />
                </div>
            </div>
            {#if $form.tipo_proyecto_capacidad_instalada_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="subtipo_proyecto_capacidad_instalada_id" value="Subtipo de proyecto" />
                    </div>
                    <div>
                        <DynamicList id="subtipo_proyecto_capacidad_instalada_id" bind:value={$form.subtipo_proyecto_capacidad_instalada_id} routeWebApi={route('web-api.subtipos-proyecto-capacidad-instalada', $form.tipo_proyecto_capacidad_instalada_id)} classes="min-h" placeholder="Busque por el nombre del subtipo de proyecto" message={errors.subtipo_proyecto_capacidad_instalada_id} required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="beneficia_a" value="El proyecto beneficiará a:" />
                </div>
                <div>
                    <Select id="beneficia_a" items={listaBeneficiados} bind:selectedValue={$form.beneficia_a} error={errors.centro_formacion_id} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="rol_sennova" value="Rol SENNOVA" />
                </div>

                <div>
                    <Select id="rol_sennova" items={roles} bind:selectedValue={$form.rol_sennova} error={errors.rol_sennova} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="cantidad_meses" value="Número de meses de vinculación al proyecto" />
                </div>
                <div>
                    <Input label="Número de meses de vinculación" id="cantidad_meses" type="number" input$step="0.1" input$min="1" input$max={monthDiff($form.fecha_inicio, $form.fecha_finalizacion)} class="mt-1" bind:value={$form.cantidad_meses} placeholder="Número de meses de vinculación" autocomplete="off" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="cantidad_horas" value="Número de horas semanales dedicadas para el desarrollo del proyecto" />
                </div>
                <div>
                    <Input label="Número de horas semanales dedicadas para el desarrollo del proyecto" id="cantidad_horas" type="number" input$step="1" input$min="1" input$max={$form.rol_sennova?.maxHoras} class="mt-1" bind:value={$form.cantidad_horas} placeholder="Número de horas semanales dedicadas para el desarrollo del proyecto" autocomplete="off" required />
                    {#if $form.rol_sennova?.maxHoras}
                        <InfoMessage>Horas máximas permitidas para este rol: {$form.rol_sennova?.maxHoras}.</InfoMessage>
                    {/if}
                </div>
            </div>
        </fieldset>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || checkRole(authUser, [4, 6])}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                    {$_('Continue')}
                </LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
