<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import Pagination from '@/Shared/Pagination'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Shared/DataTable'
    import Stepper from '@/Shared/Stepper'
    import InfoMessage from '@/Shared/InfoMessage'
    import Label from '@/Shared/Label'
    import Select from '@/Shared/Select'
    import LoadingButton from '@/Shared/LoadingButton'

    export let errors
    export let convocatoria
    export let proyecto
    export let entidadesAliadas
    export let infraestructuraTecnoacademia

    $title = 'Entidades aliadas'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        _method: 'put',
        infraestructura_tecnoacademia: {
            value: proyecto.infraestructura_tecnoacademia,
            label: infraestructuraTecnoacademia.find((item) => item.value == proyecto.infraestructura_tecnoacademia)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [8, 9]) && proyecto.modificable == true)) {
            $form.post(route('convocatorias.ta.infraestrucutra.update', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]}>
        <div slot="title">Entidades aliadas</div>

        <div slot="caption">
            {#if proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
                {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                    {#each proyecto.evaluaciones as evaluacion, i}
                        {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                            <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                                <div class="flex text-orangered-900 font-black">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Recomendación del {i == 0 ? 'primer' : i == 1 ? 'segundo' : ''} evaluador:
                                </div>
                                {#if evaluacion.idi_evaluacion}
                                    <p class="whitespace-pre-line">{evaluacion.idi_evaluacion?.entidad_aliada_comentario ? evaluacion.idi_evaluacion.entidad_aliada_comentario : 'Sin recomendación'}</p>
                                {/if}
                            </div>
                        {/if}
                    {/each}
                {/if}
            {/if}

            {#if proyecto.codigo_linea_programatica == 70}
                <form on:submit|preventDefault={submit} class="mt-8 mb-40">
                    <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [8, 9]) && proyecto.modificable == true) ? undefined : true}>
                        <div>
                            <Label required class="mb-4" labelFor="infraestructura_tecnoacademia" value="La infraestructura donde opera la TecnoAcademia es:" />
                            <Select id="infraestructura_tecnoacademia" items={infraestructuraTecnoacademia} bind:selectedValue={$form.infraestructura_tecnoacademia} error={errors.infraestructura_tecnoacademia} autocomplete="off" placeholder="Seleccione el tipo de insfraestructura" required />
                        </div>
                        {#if isSuperAdmin || (checkPermission(authUser, [8, 9]) && proyecto.modificable == true)}
                            <div class="py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                            </div>
                        {/if}
                    </fieldset>
                </form>

                <InfoMessage message="En el caso de tener un acuerdo, convenio o contrato de arrendamiento para la operación de la TecnoAcademia en una infraestructura de un tercero, es indispensable, adjuntar el documento contractual una vez este creando la entidad aliada." />
            {/if}
        </div>

        <div slot="actions">
            {#if isSuperAdmin || (checkPermission(authUser, [1, 8]) && proyecto.modificable == true)}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.entidades-aliadas.create', [convocatoria.id, proyecto.id]))} variant="raised">Crear entidad aliada</Button>
            {/if}
        </div>

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
                            {#if isSuperAdmin || checkPermission(authUser, [3, 4, 9, 10, 21, 14, 15])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.entidades-aliadas.edit', [convocatoria.id, proyecto.id, entidadAliada.id]))}>
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
</AuthenticatedLayout>
