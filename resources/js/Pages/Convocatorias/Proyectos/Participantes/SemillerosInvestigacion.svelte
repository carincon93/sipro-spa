<script>
    import { Inertia } from '@inertiajs/inertia'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Input from '@/Components/Input'
    import DataTable from '@/Components/DataTable'
    import LoadingButton from '@/Components/LoadingButton'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'

    export let convocatoria
    export let proyecto

    let resultados = []

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

    /**
     * Buscar
     */
    let form = useForm({
        search_semillero_investigacion: '',
    })
    let sending = false
    let sended = false
    function submit() {
        sending = true
        sended = false
        try {
            axios
                .post(route('convocatorias.proyectos.participantes.semilleros-investigacion', { convocatoria: convocatoria.id, proyecto: proyecto.id }), $form)
                .then((response) => {
                    resultados = response.data
                    sending = false
                    sended = true
                })
                .catch((error) => {
                    sending = false
                })
        } catch (error) {
            sending = false
        }
    }

    function linkSemilleroInvestigacion(id) {
        Inertia.post(
            route('convocatorias.proyectos.participantes.semilleros-investigacion.link', {
                proyecto: proyecto.id,
                convocatoria: convocatoria.id,
            }),
            { semillero_investigacion_id: id },
            { preserveScroll: true },
        )
    }

    function removeSemilleroInvestigacion(id) {
        Inertia.post(
            route('convocatorias.proyectos.participantes.semilleros-investigacion.unlink', {
                proyecto: proyecto.id,
                convocatoria: convocatoria.id,
            }),
            { semillero_investigacion_id: id, _method: 'DELETE' },
            { preserveScroll: true },
        )
    }
</script>

<div class="bg-indigo-100 py-4">
    <h1 class="text-4xl text-center">Semilleros de investigación</h1>
    <p class="text-center w-1/3 m-auto mt-8">Realiza la búsqueda de semilleros de investigación</p>

    <form on:submit|preventDefault={submit} on:input={() => (sended = false)}>
        <div class="p-8">
            <div class="mt-4 flex flex-row">
                <Input id="search_semillero_investigacion" type="search" placeholder="Escriba el nombre completo del semillero de investigación" class="mt-1 m-auto block flex-1" bind:value={$form.search_semillero_investigacion} minlength="4" autocomplete="off" required />
                <LoadingButton loading={sending} class="btn-indigo m-auto ml-1" type="submit">Buscar</LoadingButton>
            </div>
        </div>
    </form>
</div>

{#if sended}
    <DataTable class="bg-indigo-100 p-4">
        <div slot="title">Resultados de la búsqueda de semilleros de investigación</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Línea de investigación</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Grupo de investigación</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Acciones</th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each resultados as resultado (resultado.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {resultado.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {resultado.linea_investigacion.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center">
                            {resultado.linea_investigacion.grupo_investigacion.nombre} - {resultado.linea_investigacion.grupo_investigacion.acronimo}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            <Item on:SMUI:action={() => linkSemilleroInvestigacion(resultado.id)}>
                                <Text>Vincular</Text>
                            </Item>
                        </ResourceMenu>
                    </td>
                </tr>
            {/each}

            {#if resultados.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">{$_('No data recorded')}</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
{/if}

<DataTable class="mt-10">
    <div slot="title">Semilleros de investigación vinculados</div>

    <thead slot="thead">
        <tr class="text-left font-bold">
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Nombre</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Línea de investigación</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Grupo de investigación</th>
            <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Acciones</th>
        </tr>
    </thead>
    <tbody slot="tbody">
        {#each proyecto.semillerosInvestigacion as semilleroInvestigacion (semilleroInvestigacion.id)}
            <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                        {semilleroInvestigacion.nombre}
                    </p>
                </td>
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center">
                        {semilleroInvestigacion.linea_investigacion.nombre}
                    </p>
                </td>
                <td class="border-t">
                    <p class="px-6 py-4 flex items-center">
                        {semilleroInvestigacion.linea_investigacion.grupo_investigacion.nombre} - {semilleroInvestigacion.linea_investigacion.grupo_investigacion.acronimo}
                    </p>
                </td>
                <td class="border-t td-actions">
                    <ResourceMenu>
                        <Item on:SMUI:action={() => removeSemilleroInvestigacion(semilleroInvestigacion.id)}>
                            <Text>Quitar</Text>
                        </Item>
                    </ResourceMenu>
                </td>
            </tr>
        {/each}

        {#if proyecto.semillerosInvestigacion.length === 0}
            <tr>
                <td class="border-t px-6 py-4" colspan="4">{$_('No data recorded')}</td>
            </tr>
        {/if}
    </tbody>
</DataTable>
