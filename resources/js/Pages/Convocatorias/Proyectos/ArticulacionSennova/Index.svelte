<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Stepper from '@/Shared/Stepper'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import SelectMulti from '@/Shared/SelectMulti'
    import Textarea from '@/Shared/Textarea'

    export let errors
    export let convocatoria
    export let proyecto
    export let lineasInvestigacion
    export let gruposInvestigacion
    export let semillerosInvestigacion
    export let gruposInvestigacionRelacionados
    export let lineasInvestigacionRelacionadas
    export let semillerosInvestigacionRelacionados

    $title = 'Articulación SENNOVA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        lineas_investigacion: lineasInvestigacionRelacionadas.length > 0 ? lineasInvestigacionRelacionadas : null,
        grupos_investigacion: gruposInvestigacionRelacionados.length > 0 ? gruposInvestigacionRelacionados : null,
        semilleros_investigacion: semillerosInvestigacionRelacionados.length > 0 ? semillerosInvestigacionRelacionados : null,
        proyectos_ejecucion: proyecto.proyectos_ejecucion,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [9, 10]) && proyecto.modificable == true)) {
            $form.post(route('convocatorias.proyectos.articulacion-sennova.store', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl mt-24 mb-8 text-center">Articulación SENNOVA</h1>

    <p class="text-center mb-8">A continuación, registre la información relacionada con la articulación de la línea de TecnoAcademia con las otras líneas de SENNOVA en el centro y la regional:</p>

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [9, 10]) && proyecto.modificable == true) ? undefined : true}>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="lineas_investigacion" value="Líneas de Investigación en las cuales se están ejecutando iniciativas o proyectos de la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti id="lineas_investigacion" bind:selectedValue={$form.lineas_investigacion} items={lineasInvestigacion} isMulti={true} error={errors.lineas_investigacion} placeholder="Buscar por el nombre de la línea de investigación" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="grupos_investigacion" value="Grupos de investigación en los cuales está vinculada la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti id="grupos_investigacion" bind:selectedValue={$form.grupos_investigacion} items={gruposInvestigacion} isMulti={true} error={errors.grupos_investigacion} placeholder="Buscar por el nombre del grupo de investigación" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="semilleros_investigacion" value="Semillero(s) de investigación de la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti id="semilleros_investigacion" bind:selectedValue={$form.semilleros_investigacion} items={semillerosInvestigacion} isMulti={true} error={errors.semilleros_investigacion} placeholder="Buscar por el nombre del semillero de investigación" required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="proyectos_ejecucion" value="Proyectos o iniciativas en ejecución en el año {convocatoria.year - 1}" />
                </div>
                <div>
                    <Textarea label="Proyectos / Iniciativas" maxlength="40000" id="proyectos_ejecucion" error={errors.proyectos_ejecucion} bind:value={$form.proyectos_ejecucion} required />
                </div>
                <div class="py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true)}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                    {/if}
                </div>
            </div>
        </fieldset>
    </form>
</AuthenticatedLayout>
