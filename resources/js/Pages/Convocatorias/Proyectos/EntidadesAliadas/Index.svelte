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
