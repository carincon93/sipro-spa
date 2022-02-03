<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Textarea from '@/Shared/Textarea'
    import File from '@/Shared/File'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'

    export let errors

    $: $title = 'Crear semillero de investigación'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        nombre: '',
        fecha_creacion_semillero: '',
        nombre_lider_semillero: '',
        email_contacto: '',
        reconocimientos_semillero_investigacion: '',
        vision: '',
        mision: '',
        objetivo_general: '',
        objetivos_especificos: '',
        link_semillero: '',
        formato_gic_f020: '',
        formato_gic_f032: '',
        formato_aval_semillero: '',
        centro_formacion_id: isSuperAdmin ? null : checkRole(authUser, [4, 21, 20, 18, 19, 5, 17]) ? authUser.centro_formacion_id : null,
        linea_investigacion_id: null,
    })

    function submit() {
        if (isSuperAdmin || checkRole(authUser, [4, 21, 20, 18, 19, 5, 17])) {
            $form.post(route('semilleros-investigacion.store'), {
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
                    {#if isSuperAdmin || checkRole(authUser, [4, 21, 20, 18, 19, 5, 17])}
                        <a use:inertia href={route('semilleros-investigacion.index')} class="text-indigo-400 hover:text-indigo-600"> Semilleros de investigación </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkRole(authUser, [4, 21, 20, 18, 19, 5, 17]) ? undefined : true}>
                <div class="mt-4">
                    <Input label="Nombre" id="nombre" type="text" class="mt-1" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                {#if isSuperAdmin}
                    <div class="mt-4">
                        <div>
                            <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                            <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                        </div>
                    </div>
                {/if}

                {#if $form.centro_formacion_id}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea de investigación" />
                        <DynamicList id="linea_investigacion_id" bind:value={$form.linea_investigacion_id} routeWebApi={route('web-api.lineas-investigacion', $form.centro_formacion_id)} classes="min-h" placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional" message={errors.linea_investigacion_id} required />
                    </div>
                {/if}

                <div class="mt-4 ">
                    <Label required labelFor="fecha_creacion_semillero" value="Fecha creación del semillero" />
                    <Input id="fecha_creacion_semillero" type="date" class="mt-1" bind:value={$form.fecha_creacion_semillero} required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="nombre_lider_semillero" value="Nombre del líder del semillero" />
                    <Input id="nombre_lider_semillero" type="text" class="mt-1" bind:value={$form.nombre_lider_semillero} error={errors.nombre_lider_semillero} required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="email_contacto" value="Email de contacto" />
                    <Input id="email_contacto" type="email" class="mt-1" bind:value={$form.email_contacto} error={errors.email_contacto} required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="reconocimientos_semillero_investigacion" value="Reconocimientos semillero de investigación" />
                    <Textarea maxlength="40000" id="reconocimientos_semillero_investigacion" bind:value={$form.reconocimientos_semillero_investigacion} error={errors.reconocimientos_semillero_investigacion} required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="vision" value="Visión" />
                    <Textarea maxlength="40000" id="vision" bind:value={$form.vision} error={errors.vision} required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="mision" value="Misión" />
                    <Textarea maxlength="40000" id="mision" bind:value={$form.mision} error={errors.mision} required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="objetivo_general" value="Objetivo general" />
                    <Textarea maxlength="40000" id="objetivo_general" bind:value={$form.objetivo_general} error={errors.objetivo_general} required />
                </div>

                <div class="mt-4">
                    <Label required labelFor="objetivos_especificos" value="Objetivos específicos " />
                    <Textarea maxlength="40000" id="objetivos_especificos" bind:value={$form.objetivos_especificos} error={errors.objetivos_especificos} required />
                </div>

                <div class="mt-4">
                    <Label labelFor="link_semillero" value="Link del semillero" />
                    <Input id="link_semillero" type="url" class="mt-1" bind:value={$form.link_semillero} error={errors.link_semillero} />
                </div>

                <div class="mt4-">
                    <Label class="mb-4 mt-8" labelFor="formato_gic_f020" value="GIC – F – 020" />
                    <File type="file" maxSize="10000" class="mt-1" bind:value={$form.formato_gic_f020} error={errors?.formato_gic_f020} />
                </div>

                <div class="mt4-">
                    <Label class="mb-4 mt-8" labelFor="formato_gic_f032" value="GIC – F – 032" />
                    <File type="file" maxSize="10000" class="mt-1" bind:value={$form.formato_gic_f032} error={errors?.formato_gic_f032} />
                </div>

                <div class="mt4-">
                    <Label class="mb-4 mt-8" labelFor="formato_aval_semillero" value="Aval del Semillero" />
                    <File type="file" maxSize="10000" class="mt-1" bind:value={$form.formato_aval_semillero} error={errors?.formato_aval_semillero} />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkRole(authUser, [4, 21, 20, 18, 19, 5, 17])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear semillero de investigación</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
