<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import Button from '@/Shared/Button'
    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import Switch from '@/Shared/Switch'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import Create from './Create'

    import Stepper from '@/Shared/Stepper'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoAnexo
    export let anexos

    $title = 'Anexos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        video: proyecto.video,
        infraestructura_adecuada: proyecto.infraestructura_adecuada ? proyecto.infraestructura_adecuada : false,
        especificaciones_area: proyecto.especificaciones_area,
    })

    function submit() {
        if ((isSuperAdmin && !sending) || (checkPermission(authUser, [5, 6, 7]) && proyecto.modificable == true && !sending)) {
            $form.put(route('convocatorias.servicios-tecnologicos.infraestructura', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    {#if proyecto.codigo_linea_programatica == 68}
        <h1 class="mt-24 mb-8 text-center text-3xl">Especificaciones e infraestructura</h1>

        <form on:submit|preventDefault={submit} class="mt-4 p-4">
            <fieldset disabled={(isSuperAdmin && !sending) || (checkPermission(authUser, [5, 6, 7]) && proyecto.modificable == true) ? undefined : true}>
                <div class="mt-4">
                    <Label required labelFor="infraestructura_adecuada" value="¿Cuenta con infraestructura adecuada y propia para el funcionamiento de la línea servicios tecnológicos en el centro de formación?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.infraestructura_adecuada} />
                </div>
                <div class="mt-4">
                    <Label required labelFor="especificaciones_area" value="Relacione las especificaciones del área donde se desarrollan las actividades de servicios tecnológicos en el centro de formación" class="inline-block mb-4" />
                    <Textarea label="Especificaciones del área" maxlength="40000" id="especificaciones_area" error={errors.especificaciones_area} bind:value={$form.especificaciones_area} required />
                </div>
                <div class="mt-4">
                    <Label required labelFor="video" value="Enlace del video de las instalaciones donde se desarrollan las actividades de la línea servicios tecnológicos. (Youtube, Vídeo en Google Drive con visualización pública)" class="inline-block mb-4" />
                    <Input label="Enlace del video" type="url" class="mt-1" bind:value={$form.video} error={errors?.video} required />
                    <InfoMessage message="El vídeo debe incluir durante el recorrido en las instalaciones, una voz en off que justifique puntualmente el proyecto e incluya: el impacto a la formación, al sector productivo y a la política nacional de ciencia, tecnología e innovación." />
                </div>
                <div class="w-1/12">
                    <Button loading={sending} class="w-full mt-4" type="submit">Guardar</Button>
                </div>
            </fieldset>
        </form>
    {/if}

    <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]}>
        <div slot="title">Anexos</div>

        <div slot="caption">
            {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                {#each proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>
                            {#if evaluacion.idi_evaluacion}
                                <p class="whitespace-pre-line">{evaluacion.idi_evaluacion?.anexos_comentario ? evaluacion.idi_evaluacion.anexos_comentario : 'Sin recomendación'}</p>
                            {:else if evaluacion.ta_evaluacion}
                                <p class="whitespace-pre-line">{evaluacion.ta_evaluacion?.anexos_comentario ? evaluacion.ta_evaluacion.anexos_comentario : 'Sin recomendación'}</p>
                            {:else if evaluacion.servicio_tecnologico_evaluacion}
                                <hr class="mt-10 mb-10 border-black-200" />
                                <h1 class="font-black">Anexos</h1>

                                <ul class="list-disc pl-4">
                                    <li class="whitespace-pre-line mb-10">{evaluacion.servicio_tecnologico_evaluacion?.anexos_comentario ? 'Recomendación anexos: ' + evaluacion.servicio_tecnologico_evaluacion.anexos_comentario : 'Sin recomendación'}</li>
                                </ul>
                            {/if}
                        </div>
                    {/if}
                {/each}
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Archivo</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each anexos.data as anexo (anexo.id)}
                <tr>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {anexo.nombre}
                            <br />
                            {#if anexo.obligatorio}
                                <span class="text-red-500">* El anexo es obligatorio</span>
                            {/if}
                            {#if anexo.archivo}
                                <a target="_blank" class="text-indigo-400 underline inline-block mt-4 mb-4 flex" download href={route('anexos.download', [anexo.id])}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar formato
                                </a>
                            {/if}
                        </p>
                    </td>
                    <td class="border-t">
                        {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19])}
                            <Create {convocatoria} {proyecto} {anexo} bind:proyectoAnexo bind:sending />
                        {/if}
                    </td>
                </tr>
            {/each}

            {#if anexos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={anexos.links} />
</AuthenticatedLayout>
