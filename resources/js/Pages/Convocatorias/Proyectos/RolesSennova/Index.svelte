<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import Button from '@/Shared/Button'
    import DataTable from '@/Shared/DataTable'
    import Stepper from '@/Shared/Stepper'
    import Input from '@/Shared/Input'
    import LoadingButton from '@/Shared/LoadingButton'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoRolesSennova = []

    $title = 'Roles SENNOVA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        cantidad_instructores_planta: proyecto.cantidad_instructores_planta,
        cantidad_dinamizadores_planta: proyecto.cantidad_dinamizadores_planta,
        cantidad_psicopedagogos_planta: proyecto.cantidad_psicopedagogos_planta,
    })
    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)) {
            $form.put(route('convocatorias.proyectos.rol-sennova-ta.update', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function nivelAcademico(numeroNivel) {
        let nivelAcademico = ''
        switch (numeroNivel) {
            case 1:
                nivelAcademico = 'técnico'
                break
            case 2:
                nivelAcademico = 'tecnólogo'
                break
            case 3:
                nivelAcademico = 'pregrado'
                break
            case 4:
                nivelAcademico = 'especalización'
                break
            case 5:
                nivelAcademico = 'maestría'
                break
            case 6:
                nivelAcademico = 'doctorado'
                break
            case 7:
                nivelAcademico = 'ninguno'
                break
            case 8:
                nivelAcademico = 'técnico con especialización'
                break
            case 9:
                nivelAcademico = 'tecnólogo con especialización'
                break
            default:
                break
        }

        return nivelAcademico
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]}>
        <div slot="title">Roles SENNOVA</div>

        <div slot="caption">
            <h2 class="text-center mt-10 mb-24">
                {#if proyecto.codigo_linea_programatica == 70}
                    Ingrese el número de instructores de planta, dinamizadores de planta y psicopedagógos de planta que requiere el proyecto.
                {:else}
                    Ingrese cada uno de los roles SENNOVA que requiere el proyecto.
                {/if}
            </h2>
            {#if proyecto.codigo_linea_programatica == 70}
                <form on:submit|preventDefault={submit} class="mb-40">
                    <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true) ? undefined : true}>
                        <div class="mt-4">
                            <Input label="Número de instructores de planta" id="cantidad_instructores_planta" type="number" input$min="1" input$max="32767" class="mt-1" error={errors.cantidad_instructores_planta} bind:value={$form.cantidad_instructores_planta} required />
                        </div>

                        <div class="mt-4">
                            <Input label="Número de dinamizadores de planta" id="cantidad_dinamizadores_planta" type="number" input$min="1" input$max="32767" class="mt-1" error={errors.cantidad_dinamizadores_planta} bind:value={$form.cantidad_dinamizadores_planta} required />
                        </div>

                        <div class="mt-4">
                            <Input label="Número de psicopedagógos de planta" id="cantidad_psicopedagogos_planta" type="number" input$min="1" input$max="32767" class="mt-1" error={errors.cantidad_psicopedagogos_planta} bind:value={$form.cantidad_psicopedagogos_planta} required />
                        </div>
                    </fieldset>
                    <div class="py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                        {#if isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)}
                            <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
                        {/if}
                    </div>
                </form>
            {/if}
        </div>

        <div slot="actions">
            {#if isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.proyecto-rol-sennova.create', [convocatoria.id, proyecto.id]))} variant="raised">Crear Rol SENNOVA</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nivel académico</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Asignación mensual</th>
                {#if proyecto.en_subsanacion}
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Evaluación</th>
                {/if}
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each proyectoRolesSennova.data as proyectoRolSennova (proyectoRolSennova.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyectoRolSennova?.convocatoria_rol_sennova?.rol_sennova?.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {nivelAcademico(proyectoRolSennova?.convocatoria_rol_sennova?.nivel_academico)}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            ${new Intl.NumberFormat('de-DE').format(!isNaN(proyectoRolSennova?.convocatoria_rol_sennova?.asignacion_mensual) ? proyectoRolSennova?.convocatoria_rol_sennova?.asignacion_mensual : 0)}
                        </p>
                    </td>
                    {#if proyecto.en_subsanacion}
                        <td class="border-t">
                            <div class="px-6 py-4">
                                {#if proyectoRolSennova.rol_aprobado}
                                    Aprobado
                                {:else}
                                    Reprobado
                                {/if}
                            </div>
                        </td>
                    {/if}
                    <td class="border-t td-actions">
                        <DataTableMenu class={proyectoRolesSennova.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 21, 14, 16, 15, 20])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.proyecto-rol-sennova.edit', [convocatoria.id, proyecto.id, proyectoRolSennova.id]))}>
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

            {#if proyectoRolesSennova.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="5">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
        <tfoot slot="tfoot">
            <tr>
                <td colspan="5" class="border-t p-4">
                    <strong>Actualmente el total del costo de los roles requeridos es de:</strong> ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_roles_sennova) ? proyecto.total_roles_sennova : 0)} COP
                </td>
            </tr>
        </tfoot>
    </DataTable>
    <Pagination links={proyectoRolesSennova.links} />
</AuthenticatedLayout>
