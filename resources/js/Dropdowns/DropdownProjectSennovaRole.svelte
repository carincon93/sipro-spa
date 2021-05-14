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
    export let formProjectSennovaRole
    export let call
    export let programmaticLine
    export let projectSennovaRoleInfo
    export let required

    let projectSennovaRoles = []
    let projectSennovaRoleFiltered = null
    let select = null
    let sennovaRoleMessage

    onMount(() => {
        getProjectSennovaRoles()
        select = document.getElementById(id)
    })

    afterUpdate(() => {
        if (required && select != null) {
            formProjectSennovaRole != null
                ? select.setCustomValidity('')
                : select.setCustomValidity($_('Please fill out this field.'))
        }
    })

    async function getProjectSennovaRoles() {
        let res = await axios.get(
            route('web-api.calls.project-sennova-roles', [
                call.id,
                programmaticLine,
            ]),
        )
        projectSennovaRoles = res.data
        selectProjectSennovaRole()
    }

    function handleProjectSennovaRole(event) {
        formProjectSennovaRole = event.detail.value
        projectSennovaRoleInfo = event.detail

        sennovaRoleMessage = event.detail.message
    }

    function selectProjectSennovaRole() {
        if (formProjectSennovaRole) {
            let filterItem = projectSennovaRoles.filter(function (
                projectSennovaRole,
            ) {
                return projectSennovaRole.value == formProjectSennovaRole
            })
            projectSennovaRoleFiltered = filterItem[0]
        }
    }
</script>

<Select
    selectedValue={projectSennovaRoleFiltered}
    inputAttributes={{ id: id }}
    placeholder="Busque por el nombre del rol"
    containerClasses="project-sennova-roles {classes}"
    items={projectSennovaRoles}
    on:select={handleProjectSennovaRole}
    on:clear={() => (formProjectSennovaRole = null)}
/>
<InputError {message} />

{#if sennovaRoleMessage}
    <InfoMessage message={sennovaRoleMessage} />
{/if}

<style>
    :global(.project-sennova-roles .listItem) {
        border-bottom: 1px solid #ccc;
    }

    :global(.project-sennova-roles .item) {
        height: auto !important;
        line-height: 1.6 !important;
        text-overflow: initial !important;
        overflow: initial !important;
        white-space: break-spaces !important;
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>
