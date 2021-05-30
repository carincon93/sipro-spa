<script>
    import { afterUpdate, onMount } from 'svelte'
    import axios from 'axios'
    import { _ } from 'svelte-i18n'

    import Select from 'svelte-select'
    import InputError from '@/Components/InputError'
    import InfoMessage from '@/Components/InfoMessage'

    export let classes = ''
    export let id = ''
    export let message
    export let formProyectRolSennova
    export let convocatoria
    export let lineaProgramatica
    export let infoRolSennova
    export let required

    let proyectoRolesSennova = []
    let proyectoRolSennovaFiltered = null
    let select = null
    let rolSennovaMessage

    onMount(() => {
        getProyectoRolesSennova()
        select = document.getElementById(id)
    })

    afterUpdate(() => {
        if (required && select != null) {
            formProyectRolSennova != null ? select.setCustomValidity('') : select.setCustomValidity($_('Please fill out this field.'))
        }
    })

    async function getProyectoRolesSennova() {
        let res = await axios.get(route('web-api.convocatorias.roles-sennova', [convocatoria.id, lineaProgramatica]))
        proyectoRolesSennova = res.data
        selectProyectoRolSennova()
    }

    function handleProyectoRolSennova(event) {
        formProyectRolSennova = event.detail.value
        infoRolSennova = event.detail
        rolSennovaMessage = event.detail.mensaje
    }

    function selectProyectoRolSennova() {
        if (formProyectRolSennova) {
            let filterItem = proyectoRolesSennova.filter(function (proyectoRolSennova) {
                return proyectoRolSennova.value == formProyectRolSennova
            })
            proyectoRolSennovaFiltered = filterItem[0]
        }
    }
</script>

<Select selectedValue={proyectoRolSennovaFiltered} inputAttributes={{ id: id }} placeholder="Busque por el nombre del rol" containerClasses="proyecto-roles-sennova {classes}" items={proyectoRolesSennova} on:select={handleProyectoRolSennova} on:clear={() => (formProyectRolSennova = null)} noOptionsMessage="No hay ítems, por favor revise los filtros" />
<InputError {message} />

{#if rolSennovaMessage}
    <InfoMessage message={rolSennovaMessage} />
{/if}

<style>
    :global(.proyecto-roles-sennova .listItem) {
        border-bottom: 1px solid #ccc;
    }

    :global(.proyecto-roles-sennova .item) {
        height: auto !important;
        line-height: 1.6 !important;
        text-overflow: initial !important;
        overflow: initial !important;
        white-space: break-spaces !important;
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>
