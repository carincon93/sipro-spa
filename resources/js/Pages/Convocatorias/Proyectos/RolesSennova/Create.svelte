<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import InfoMessage from '@/Components/InfoMessage'
    import DynamicList from '@/Dropdowns/DynamicList'

    export let convocatoria
    export let proyecto
    export let lineaProgramatica
    export let errors

    let infoRolSennova

    $: $title = 'Crear rol SENNOVA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let sending = false
    let form = useForm({
        numero_meses: '',
        numero_roles: '',
        descripcion: '',
        convocatoria_rol_sennova_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('convocatorias.proyectos.proyecto-rol-sennova.store', [convocatoria.id, proyecto.id]), {
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
                        <a use:inertia href={route('convocatorias.proyectos.proyecto-rol-sennova.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Roles SENNOVA </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="convocatoria_rol_sennova_id" value="Rol SENNOVA" />
                    <DynamicList id="convocatoria_rol_sennova_id" bind:value={$form.convocatoria_rol_sennova_id} routeWebApi={route('web-api.convocatorias.roles-sennova', [convocatoria.id, lineaProgramatica])} bind:recurso={infoRolSennova} message={errors.convocatoria_rol_sennova_id} placeholder="Busque por el nombre del rol" required />
                    {#if infoRolSennova?.perfil}
                        <div class="mt-10">
                            <h1>Perfil:</h1>
                            <InfoMessage message={infoRolSennova.perfil} />
                        </div>
                    {/if}
                </div>

                {#if infoRolSennova?.experiencia}
                    <div class="mt-4">
                        <p class="block font-medium text-sm text-gray-700 whitespace-pre-wrap">
                            Experiencia (meses)
                            <span class="block border-gray-300 p-4 rounded-md shadow-sm">
                                {infoRolSennova.experiencia}
                            </span>
                        </p>
                    </div>
                {/if}

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="descripcion" value="Descripción" />
                    <Textarea rows="4" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="numero_meses" value="Número de meses que requiere el apoyo" />
                    <Input id="numero_meses" type="number" min="1" max={proyecto.diff_meses} class="mt-1 block w-full" error={errors.numero_meses} bind:value={$form.numero_meses} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="numero_roles" value="Número de personas requeridas" />
                    <Input id="numero_roles" type="number" min="1" class="mt-1 block w-full" error={errors.numero_roles} bind:value={$form.numero_roles} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear rol SENNOVA</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
