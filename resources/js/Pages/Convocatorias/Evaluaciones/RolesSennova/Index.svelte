<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Shared/DataTable'
    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import Input from '@/Shared/Input'

    export let convocatoria
    export let evaluacion
    export let proyecto
    export let proyectoRolesSennova = []

    $title = 'Roles SENNOVA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let cantidadesRolesInfo = {
        cantidad_instructores_planta: proyecto.cantidad_instructores_planta,
        cantidad_dinamizadores_planta: proyecto.cantidad_dinamizadores_planta,
        cantidad_psicopedagogos_planta: proyecto.cantidad_psicopedagogos_planta,
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
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]}>
        <div slot="title">Roles SENNOVA</div>

        <div slot="caption">
            {#if proyecto.codigo_linea_programatica == 70}
                <form>
                    <fieldset disabled={true}>
                        <div class="mt-4">
                            <Input label="Número de instructores de planta" id="cantidad_instructores_planta" type="number" input$min="1" input$max="32767" class="mt-1" value={cantidadesRolesInfo.cantidad_instructores_planta} required />
                        </div>

                        <div class="mt-4">
                            <Input label="Número de dinamizadores de planta" id="cantidad_dinamizadores_planta" type="number" input$min="1" input$max="32767" class="mt-1" value={cantidadesRolesInfo.cantidad_dinamizadores_planta} required />
                        </div>

                        <div class="mt-4">
                            <Input label="Número de psicopedagógos de planta" id="cantidad_psicopedagogos_planta" type="number" input$min="1" input$max="32767" class="mt-1" value={cantidadesRolesInfo.cantidad_psicopedagogos_planta} required />
                        </div>
                    </fieldset>
                </form>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nivel académico</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Asignación mensual</th>
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
                    <td class="border-t td-actions">
                        <DataTableMenu class={proyectoRolesSennova.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkRole(authUser, [11])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.evaluaciones.proyecto-rol-sennova.edit', [convocatoria.id, evaluacion.id, proyectoRolSennova.id]))}>
                                    <Text>Evaluar</Text>
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
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
        <tfoot slot="tfoot">
            <tr>
                <td colspan="4" class="border-t p-4">
                    <strong>Actualmente el total del costo de los roles requeridos es de:</strong> ${new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.total_roles_sennova) ? proyecto.total_roles_sennova : 0)} COP
                </td>
            </tr>
        </tfoot>
    </DataTable>
    <Pagination links={proyectoRolesSennova.links} />
</AuthenticatedLayout>
