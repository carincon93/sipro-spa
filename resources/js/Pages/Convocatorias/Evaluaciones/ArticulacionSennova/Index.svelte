<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Stepper from '@/Shared/Stepper'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import SelectMulti from '@/Shared/SelectMulti'
    import Textarea from '@/Shared/Textarea'
    import Tags from '@/Shared/Tags'

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

    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]
    let sending = false
    let form = useForm({
        lineas_investigacion: lineasInvestigacionRelacionadas.length > 0 ? lineasInvestigacionRelacionadas : null,
        grupos_investigacion: gruposInvestigacionRelacionados.length > 0 ? gruposInvestigacionRelacionados : null,
        semilleros_investigacion: semillerosInvestigacionRelacionados.length > 0 ? semillerosInvestigacionRelacionados : null,
        proyectos_ejecucion: proyecto.proyectos_ejecucion,
        semilleros_en_formalizacion: proyecto.semilleros_en_formalizacion,
        articulacion_semillero: {
            value: proyecto.articulacion_semillero,
            label: opcionesSiNo.find((item) => item.value == proyecto.articulacion_semillero)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [8, 9, 10]) && proyecto.modificable == true)) {
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
        <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [8, 9, 10]) && proyecto.modificable == true) ? undefined : true}>
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
                    <Label required class="mb-4" labelFor="articulacion_semillero" value="¿Está la TecnoAcademia articulada con un semillero?" />
                </div>
                <div>
                    <Select items={opcionesSiNo} id="articulacion_semillero" bind:selectedValue={$form.articulacion_semillero} error={errors.articulacion_semillero} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            {#if $form.articulacion_semillero?.value == 1}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" for="semilleros_investigacion" value="Semillero(s) de investigación de la TecnoAcademia" />
                    </div>
                    <div>
                        <SelectMulti id="semilleros_investigacion" bind:selectedValue={$form.semilleros_investigacion} items={semillerosInvestigacion} isMulti={true} error={errors.semilleros_investigacion} placeholder="Buscar por el nombre del semillero de investigación" required />
                    </div>
                </div>
            {/if}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="proyectos_ejecucion" value="Proyectos o iniciativas en ejecución en el año {convocatoria.year - 1}" />
                </div>
                <div>
                    <Textarea label="Proyectos / Iniciativas" maxlength="40000" id="proyectos_ejecucion" error={errors.proyectos_ejecucion} bind:value={$form.proyectos_ejecucion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="semilleros_en_formalizacion" value="Semilleros en proceso de formalización (Separados por coma)" />
                </div>
                <div>
                    <Tags id="semilleros_en_formalizacion" class="mt-4" enforceWhitelist={false} bind:tags={$form.semilleros_en_formalizacion} placeholder="Nombre del semillero" error={errors.semilleros_en_formalizacion} />
                </div>
            </div>
        </fieldset>
        <div class="py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkPermission(authUser, [8, 9, 10]) && proyecto.modificable == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
