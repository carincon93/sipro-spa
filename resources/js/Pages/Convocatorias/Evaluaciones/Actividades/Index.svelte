<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import Gantt from '@/Shared/Gantt'
    import InfoMessage from '@/Shared/InfoMessage'
    import Label from '@/Shared/Label'
    import Textarea from '@/Shared/Textarea'
    import LoadingButton from '@/Shared/LoadingButton'
    import Input from '@/Shared/Input'
    import Switch from '@/Shared/Switch'
    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyecto
    export let actividades
    export let actividadesGantt
    export let year

    $title = 'Actividades'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let actividadInfo = {
        metodologia: proyecto.metodologia,
        metodologia_local: proyecto.metodologia_local,
    }

    let showGantt = false
    let sending = false
    let form = useForm({
        metodologia_puntaje: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.metodologia_puntaje : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.metodologia_puntaje : null,
        metodologia_comentario: evaluacion.idi_evaluacion ? evaluacion.idi_evaluacion.metodologia_comentario : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.metodologia_comentario : null,
        metodologia_requiere_comentario: evaluacion.idi_evaluacion ? (evaluacion.idi_evaluacion.metodologia_comentario == null ? false : true) : evaluacion.cultura_innovacion_evaluacion ? evaluacion.cultura_innovacion_evaluacion.metodologia_comentario : null,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true)) {
            $form.put(route('convocatorias.evaluaciones.actividades.guardar-evaluacion', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    <a class="flex bg-orangered-900 bottom-0 fixed hover:bg-orangered-600 mb-4 px-6 py-2 rounded-3xl shadow-2xl text-center text-white z-50" href="#evaluacion">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        Ir a la evaluación
    </a>

    <h1 class="text-3xl m-24 text-center">Metodología</h1>

    <form>
        <div class="mt-4">
            <div>
                <Label class="mb-4" labelFor="metodologia" value="Metodología" />
                <InfoMessage message="Se debe evidenciar que la metodología se presente de forma organizada y de manera secuencial, de acuerdo con el ciclo P-H-V-A “Planificar – Hacer – Verificar - Actuar” para alcanzar el objetivo general y cada uno de los objetivos específicos." />
                <Textarea disabled sinContador={true} id="metodologia" value={actividadInfo.metodologia} />
            </div>
        </div>
        {#if proyecto.codigo_linea_programatica == 69 || proyecto.codigo_linea_programatica == 70}
            <div class="mt-4">
                <div>
                    <Label class="mb-4" labelFor="metodologia_local" value="Descripcion de la metodología aplicada a nivel local" />
                    <Textarea disabled maxlength="20000" id="metodologia_local" value={actividadInfo.metodologia_local} />
                </div>
            </div>
        {/if}
    </form>

    <hr class="mb-20 mt-20" />

    <h1 class="text-3xl m-24 text-center">Actividades</h1>

    {#if showGantt}
        <Button on:click={() => (showGantt = false)}>Ocultar diagrama de Gantt</Button>
    {:else}
        <Button on:click={() => (showGantt = true)}>Visualizar diagrama de Gantt</Button>
    {/if}

    {#if showGantt}
        <Gantt
            items={actividadesGantt}
            request={isSuperAdmin || checkRole(authUser, [11])
                ? {
                      uri: 'convocatorias.evaluaciones.actividades.edit',
                      params: [convocatoria.id, evaluacion.id],
                  }
                : null}
        />
    {:else}
        <DataTable class="mt-20" routeParams={[convocatoria.id, evaluacion.id]}>
            <thead slot="thead">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Descripción</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Fechas</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Objetivo específico</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
                </tr>
            </thead>

            <tbody slot="tbody">
                {#each actividades.data as actividad (actividad.id)}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t">
                            <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                                {actividad.descripcion}
                            </p>
                        </td>

                        <td class="border-t">
                            <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                                {#if actividad.fecha_inicio}
                                    Del {actividad.fecha_inicio} al {actividad.fecha_finalizacion}
                                {:else}
                                    <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center inline-block mt-2 mb-2">Sin fechas definidas</span>
                                {/if}
                            </p>
                        </td>
                        <td class="border-t">
                            <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                                {actividad.objetivo_especifico.descripcion}
                            </p>
                        </td>

                        <td class="border-t td-actions">
                            <DataTableMenu class={actividades.data.length < 4 ? 'z-50' : ''}>
                                {#if isSuperAdmin || checkRole(authUser, [11])}
                                    <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.evaluaciones.actividades.edit', [convocatoria.id, evaluacion.id, actividad.id]))}>
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

                {#if actividades.data.length === 0}
                    <tr>
                        <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                    </tr>
                {/if}
            </tbody>
        </DataTable>
        <Pagination links={actividades.links} />
    {/if}

    <hr class="mt-10 mb-10" />

    <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>

    <div class="mt-16">
        <form on:submit|preventDefault={submit}>
            <InfoMessage>
                <h1>Criterios de evaluacion</h1>
                <ul class="list-disc p-4">
                    <li>
                        <strong>Puntaje: 0 a 7</strong> La selección y descripción de la metodología o metodologías no son claras para el contexto y desarrollo del proyecto. Las actividades no estan descritas de forma secuencial, tampoco muestran como se lograrán los objetivos específicos, generarán los resultados y/o productos y no estan formuladas en el marco de la vigencia del proyecto. Algunas
                        de las actividades no se desarrollarán durante la vigencia {year}.
                    </li>
                    <li>
                        <strong>Puntaje: 8 a 13</strong> La selección y descripción de la metodología o metodologías son claras para el contexto y desarrollo del proyecto. Las actividades están descritas de forma secuencial; sin embargo, son susceptibles de mejora en cuanto a como se lograrán los objetivos específicos, generarán los resultados y/o productos y estan formuladas en el marco de la
                        vigencia del proyecto. Todas las actividades se desarrollarán durante la vigencia {year} y el tiempo dispuesto para ello es suficiente para garantizar su ejecución.
                    </li>
                    <li>
                        <strong>Puntaje: 14 a 15</strong> La selección y descripción de la metodología o metodologías son precisas para el contexto y desarrollo del proyecto. Las actividades están descritas de forma secuencial, evidencian de forma clara como se lograrán los objetivos específicos, generarán los resultados, productos y están formuladas en el marco de la vigencia del proyecto. Todas
                        las actividades se desarrollarán durante la vigencia {year} y el tiempo dispuesto para ello es suficiente para garantizar su ejecución.
                    </li>
                </ul>

                <Label class="mt-4 mb-4" labelFor="metodologia_puntaje" value="Puntaje (Máximo 15)" />
                <Input disabled={evaluacion.finalizado && evaluacion.habilitado ? true : undefined} label="Puntaje" id="metodologia_puntaje" type="number" input$step="1" input$min="0" input$max="15" class="mt-1" bind:value={$form.metodologia_puntaje} placeholder="Puntaje" autocomplete="off" error={errors.metodologia_puntaje} />

                <div class="mt-4">
                    <p>¿La metodología o las actividades requieren de alguna recomendación?</p>
                    <Switch disabled={evaluacion.finalizado && evaluacion.habilitado ? true : undefined} bind:checked={$form.metodologia_requiere_comentario} />
                    {#if $form.metodologia_requiere_comentario}
                        <Textarea disabled={evaluacion.finalizado && evaluacion.habilitado ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="metodologia_comentario" bind:value={$form.metodologia_comentario} error={errors.metodologia_comentario} required />
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
