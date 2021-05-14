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
    export let call

    $: $title = $_('Create') + ' ' + $_('RDI.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexRDI =
        authUser.can.find((element) => element == 'rdi.index') == 'rdi.index'
    let canShowRDI =
        authUser.can.find((element) => element == 'rdi.show') == 'rdi.show'
    let canCreateRDI =
        authUser.can.find((element) => element == 'rdi.create') == 'rdi.create'
    let canEditRDI =
        authUser.can.find((element) => element == 'rdi.edit') == 'rdi.edit'
    let canDestroyRDI =
        authUser.can.find((element) => element == 'rdi.destroy') ==
        'calls.delete'

    let sending = false
    let form = useForm({
        academic_centre_id: '',
        linea_investigacion_id: '',
        disciplina_subarea_conocimiento_id: '',
        tematica_estrategica_id: '',
        red_conocimiento_id: '',
        project_type_id: '',
        actividad_economica_id: '',
        title: 'Escriba aquí el título del proyecto. No mayor a 20 palabras.',
        fecha_incio: '',
        fecha_finalizacion: '',
    })

    function submit() {
        if (canCreateRDI || isSuperAdmin) {
            $form.post(route('calls.rdi.store', [call.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1>
                    {#if canIndexRDI || canCreateRDI || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('calls.rdi.index', [call.id])}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('RDI.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8">
            <div class="mt-28">
                <Label
                    required
                    labelFor="title"
                    class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full"
                    value="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué."
                />
                <Textarea
                    rows="4"
                    id="title"
                    error={errors.title}
                    bind:value={$form.title}
                    classes="bg-transparent block border-0 {errors.title
                        ? ''
                        : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full"
                    required
                />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"
                    >* Campo obligatorio</small
                >
                <div class="mt-4 flex items-start justify-around">
                    <div
                        class="mt-4 flex {errors.fecha_incio
                            ? ''
                            : 'items-center'}"
                    >
                        <Label
                            labelFor="fecha_incio"
                            class={errors.fecha_incio ? 'top-3.5 relative' : ''}
                            value="Del"
                        />
                        <div class="ml-4">
                            <Input
                                id="fecha_incio"
                                type="date"
                                class="mt-1 block w-full"
                                error={errors.fecha_incio}
                                bind:value={$form.fecha_incio}
                                required
                            />
                        </div>
                    </div>
                    <div
                        class="mt-4 flex {errors.fecha_finalizacion
                            ? ''
                            : 'items-center'}"
                    >
                        <Label
                            labelFor="fecha_finalizacion"
                            class={errors.fecha_finalizacion
                                ? 'top-3.5 relative'
                                : ''}
                            value="hasta"
                        />
                        <div class="ml-4">
                            <Input
                                id="fecha_finalizacion"
                                type="date"
                                class="mt-1 block w-full"
                                error={errors.fecha_finalizacion}
                                bind:value={$form.fecha_finalizacion}
                                required
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="academic_centre_id"
                        value="Centro de formación"
                    />
                    <small
                        >Nota: El Centro de Formación relacionado es el ejecutor
                        del proyecto</small
                    >
                </div>
                <div>
                    <DynamicList
                        id="academic_centre_id"
                        bind:value={$form.academic_centre_id}
                        routeWebApi={route('web-api.centros-formacion')}
                        placeholder="Busque por el nombre del centro de formación"
                        message={errors.academic_centre_id}
                        required
                    />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="linea_investigacion_id"
                        value="Línea de investigación"
                    />
                </div>
                <div>
                    <DynamicList
                        id="linea_investigacion_id"
                        bind:value={$form.linea_investigacion_id}
                        routeWebApi={route('web-api.lineas-investigacion')}
                        placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional"
                        message={errors.linea_investigacion_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="project_type_id"
                        value="Tipo de proyecto"
                    />
                </div>
                <div>
                    <DynamicList
                        id="project_type_id"
                        bind:value={$form.project_type_id}
                        routeWebApi={route('web-api.project-types')}
                        placeholder="Busque por el nombre del tipo de proyecto, línea programática"
                        message={errors.project_type_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="red_conocimiento_id"
                        value="Red de conocimiento sectorial"
                    />
                </div>
                <div>
                    <DynamicList
                        id="red_conocimiento_id"
                        bind:value={$form.red_conocimiento_id}
                        routeWebApi={route('web-api.knowledge-networks')}
                        placeholder="Busque por el nombre de la red de conocimiento sectorial"
                        message={errors.red_conocimiento_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="disciplina_subarea_conocimiento_id"
                        value="Disciplina de la subárea de conocimiento"
                    />
                </div>
                <div>
                    <DynamicList
                        id="disciplina_subarea_conocimiento_id"
                        bind:value={$form.disciplina_subarea_conocimiento_id}
                        routeWebApi={route(
                            'web-api.knowledge-subarea-disciplines',
                        )}
                        placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento"
                        message={errors.disciplina_subarea_conocimiento_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="actividad_economica_id"
                        value="¿En cuál de estas actividades económicas se puede aplicar el proyecto de investigación?"
                    />
                </div>
                <div>
                    <DynamicList
                        id="actividad_economica_id"
                        bind:value={$form.actividad_economica_id}
                        routeWebApi={route('web-api.economic-activities')}
                        placeholder="Busque por el nombre de la actividad económica"
                        message={errors.actividad_economica_id}
                        required
                    />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label
                        required
                        class="mb-4"
                        labelFor="tematica_estrategica_id"
                        value="Temática estratégica SENA"
                    />
                </div>
                <div>
                    <DynamicList
                        id="tematica_estrategica_id"
                        bind:value={$form.tematica_estrategica_id}
                        routeWebApi={route('web-api.strategic-thematics')}
                        placeholder="Busque por el nombre de la temática estrategica SENA"
                        message={errors.tematica_estrategica_id}
                        required
                    />
                </div>
            </div>
        </fieldset>

        <div
            class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
        >
            {#if canCreateRDI || isSuperAdmin}
                <LoadingButton
                    loading={sending}
                    class="btn-indigo ml-auto"
                    type="submit"
                >
                    {$_('Continue')}
                </LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
