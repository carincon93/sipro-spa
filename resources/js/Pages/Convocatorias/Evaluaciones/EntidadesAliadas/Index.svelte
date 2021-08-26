<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Shared/DataTable'
    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import InfoMessage from '@/Shared/InfoMessage'
    import Switch from '@/Shared/Switch'
    import LoadingButton from '@/Shared/LoadingButton'

    export let convocatoria
    export let evaluacion
    export let proyecto
    export let tipoEntidad
    export let entidadesAliadas

    $title = 'Entidades aliadas'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        entidad_aliada_verificada: evaluacion.idi_evaluacion.entidad_aliada_verificada,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false && evaluacion.habilitado == true)) {
            $form.put(route('convocatorias.evaluaciones.entidades-aliadas.verificar', [convocatoria.id, evaluacion.id]), {
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

    <DataTable class="mt-20" routeParams={[convocatoria.id, evaluacion.id]}>
        <div slot="title">Entidades aliadas</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Tipo de entidad aliada</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each entidadesAliadas.data as entidadAliada (entidadAliada.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {entidadAliada.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {entidadAliada.tipo}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={entidadesAliadas.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkRole(authUser, [11])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.evaluaciones.entidades-aliadas.edit', [convocatoria.id, evaluacion.id, entidadAliada.id]))}>
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

            {#if entidadesAliadas.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={entidadesAliadas.links} />

    <hr class="mt-10 mb-10" />

    <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>
    <InfoMessage>
        <form on:submit|preventDefault={submit}>
            <div class="mt-4">
                <p>Verifique que la información de la entidad o entidades aliadas registradas sea correcta. Luego seleccione una de las siguientes opciones: <strong>Entidad validada</strong> o <strong>Entidad no validada</strong> y finalmente de clic en <strong>Guardar</strong></p>
                <Switch onMessage="Entidad validada" offMessage="Entidad no validada" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false ? true : undefined} bind:checked={$form.entidad_aliada_verificada} />
            </div>
            {#if isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false && evaluacion.habilitado == true)}
                <div class="px-8 py-4 border-t border-gray-200 flex items-center sticky bottom-0">
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                </div>
            {/if}
        </form>
        {#if evaluacion.idi_evaluacion?.entidad_aliada_verificada}
            El puntaje se asigna automáticamente.
            <br />
            <strong>Puntaje:</strong>
            {evaluacion.entidad_aliada_puntaje}
            <br />
            <strong>Tipo de entidad aliada:</strong>
            {tipoEntidad ? tipoEntidad : 'No hay una entidad aliada registrada'}
            <br />
            <strong>Código dependencia presupuestal (SIIF):</strong>
            {proyecto.codigo_linea_programatica}
        {/if}
    </InfoMessage>
</AuthenticatedLayout>
