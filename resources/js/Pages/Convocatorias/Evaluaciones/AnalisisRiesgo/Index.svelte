<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import InfoMessage from '@/Shared/InfoMessage'
    import Label from '@/Shared/Label'
    import Input from '@/Shared/Input'
    import Switch from '@/Shared/Switch'
    import Textarea from '@/Shared/Textarea'
    import LoadingButton from '@/Shared/LoadingButton'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyecto
    export let analisisRiesgos

    $title = 'Análisis de riesgos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        analisis_riesgos_puntaje: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.analisis_riesgos_puntaje : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.analisis_riesgos_puntaje : null,
        analisis_riesgos_comentario: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.analisis_riesgos_comentario : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.analisis_riesgos_comentario : null,
        analisis_riesgos_requiere_comentario: evaluacion.idi_evaluacion ? (evaluacion.idi_evaluacion.analisis_riesgos_comentario == null ? false : true) : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.analisis_riesgos_comentario : null,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true)) {
            $form.put(route('convocatorias.evaluaciones.analisis-riesgos.guardar-evaluacion', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    <a class="flex bg-orangered-900 bottom-0 fixed hover:bg-orangered-600 mb-4 px-6 py-2 right-10 rounded-3xl shadow-2xl text-center text-white z-50" href="#evaluacion">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        Ir a la evaluación
    </a>

    <DataTable class="mt-20" routeParams={[convocatoria.id, evaluacion.id]}>
        <div slot="title">Análisis de riesgos</div>

        <p class="text-center mt-10 mb-24" slot="caption">
            Los riesgos son eventos inciertos que pueden llegar a suceder en el futuro, dentro del horizonte de la ejecución del proyecto y representaran efectos de diferente magnitud en uno o más de sus objetivos.
            <br />
            Se debe establecer un riesgo por cada nivel (A nivel de objetivo general - A nivel de actividades - A nivel de productos). Estos riesgos podrán ser clasificados conforme los siguientes tipos: mercados, operacionales, legales, administrativos.
        </p>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Descripción</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nivel</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Tipo de riesgo</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each analisisRiesgos.data as analisisRiesgo (analisisRiesgo.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {analisisRiesgo.descripcion}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {analisisRiesgo.nivel}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {analisisRiesgo.tipo}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <DataTableMenu class={analisisRiesgos.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkRole(authUser, [11])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.evaluaciones.analisis-riesgos.edit', [convocatoria.id, evaluacion.id, analisisRiesgo.id]))}>
                                    <Text>Ver detalles</Text>
                                </Item>
                            {:else}
                                <Item>
                                    <Text>No tiene permisos</Text>
                                </Item>
                            {/if}
                        </DataTableMenu>
                    </td>
                </tr>
            {/each}

            {#if analisisRiesgos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={analisisRiesgos.links} />

    <hr class="mt-10 mb-10" />

    <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>

    <div class="mt-16">
        <form on:submit|preventDefault={submit}>
            <InfoMessage>
                <h1>Criterios de evaluacion</h1>
                <ul class="list-disc p-4">
                    <li>
                        <strong>Puntaje: 0,0 a 2,0</strong> Los riesgos descritos en los tres niveles de análisis no son coherentes con las situaciones que se presentarán en el desarrollo del proyecto y las medidas de mitigación son insuficientes para darles solución.
                    </li>
                    <li>
                        <strong>Puntaje: 2,1 a 3,9</strong> Los riesgos descritos en los tres niveles de análisis son coherentes con las situaciones que se presentarán en el desarrollo del proyecto y las medidas de mitigación son susceptibles de mejora para dar un tratamiento más acertado a los riesgos.
                    </li>
                    <li>
                        <strong>Puntaje: 4,0 a 5,0</strong> Los riesgos descritos en los tres niveles de análisis son coherentes con las situaciones que se presentarán en el desarrollo del proyecto y las medidas de mitigación son suficientes para dar tratamiento a los riesgos.
                    </li>
                </ul>

                <Label class="mt-4 mb-4" labelFor="analisis_riesgos_puntaje" value="Puntaje (Máximo 5)" />
                <Input disabled={evaluacion.finalizado ? true : undefined} label="Puntaje" id="analisis_riesgos_puntaje" type="number" input$step="0.1" input$min="0" input$max="5" class="mt-1" bind:value={$form.analisis_riesgos_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.analisis_riesgos_puntaje} />

                <div class="mt-4">
                    <p>¿Los análisis de riesgos requieren de alguna recomendación?</p>
                    <Switch disabled={evaluacion.finalizado ? true : undefined} bind:checked={$form.analisis_riesgos_requiere_comentario} />
                    {#if $form.analisis_riesgos_requiere_comentario}
                        <Textarea disabled={evaluacion.finalizado ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="analisis_riesgos_comentario" bind:value={$form.analisis_riesgos_comentario} error={errors.analisis_riesgos_comentario} required />
                    {/if}
                </div>
            </InfoMessage>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
