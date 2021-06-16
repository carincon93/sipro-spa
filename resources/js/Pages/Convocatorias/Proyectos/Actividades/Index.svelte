<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Stepper from '@/Shared/Stepper'
    import Gantt from '@/Shared/Gantt'
    import InfoMessage from '@/Shared/InfoMessage'
    import Label from '@/Shared/Label'
    import Textarea from '@/Shared/Textarea'
    import LoadingButton from '@/Shared/LoadingButton'

    export let convocatoria
    export let proyecto
    export let actividades
    export let errors

    $title = 'Actividades'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false

    let form = useForm({
        metodologia: proyecto.metodologia,
        metodologia_local: proyecto.metodologia_local,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13]) && proyecto.modificable == true)) {
            $form.put(route('convocatorias.proyectos.metodologia', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl m-24 text-center">Actividades</h1>

    <form on:submit|preventDefault={submit}>
        <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13]) && proyecto.modificable == true) ? undefined : true}>
            <div class="mt-4">
                <div>
                    <Label required class="mb-4" labelFor="metodologia" value="Metodología" />
                    <InfoMessage message="Se debe evidenciar que la metodología se presente de forma organizada y de manera secuencial, de acuerdo con el ciclo P-H-V-A “Planificar – Hacer – Verificar - Actuar” para alcanzar el objetivo general y cada uno de los objetivos específicos." />
                    <Textarea maxlength="10000" id="metodologia" error={errors.metodologia} bind:value={$form.metodologia} required />
                </div>
            </div>
            {#if proyecto.codigo_linea_programatica == 69 || proyecto.codigo_linea_programatica == 70}
                <div class="mt-4">
                    <div>
                        <Label required class="mb-4" labelFor="metodologia_local" value="Descripcion de la metodología aplicada a nivel local" />
                        <Textarea maxlength="40000" id="metodologia_local" error={errors.metodologia_local} bind:value={$form.metodologia_local} required />
                    </div>
                </div>
            {/if}
        </fieldset>
        <div class="mt-4 bg-gray-100 border-t border-gray-200 flex items-center">
            {#if isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13]) && proyecto.modificable == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <hr class="mb-20 mt-20" />

    <InfoMessage message="Debe generar las actividades en el 'Árbol de objetivos'. <br /><strong>Importante</strong> Una vez creadas las actividades, edite cada una haciendo clic en los tres puntos, a continuación, enlace los productos y rubros correspondientes, de esta manera se completa la cadena de valor." />

    <Gantt
        items={actividades.data}
        request={isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13])
            ? {
                  uri: 'convocatorias.proyectos.actividades.edit',
                  params: [convocatoria.id, proyecto.id],
              }
            : null}
    />
</AuthenticatedLayout>
