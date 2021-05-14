<script>
    import { afterUpdate, onMount } from 'svelte'
    import Select from 'svelte-select'
    import InputError from '@/Components/InputError'
    import { _ } from 'svelte-i18n'

    export let classes = ''
    export let id = ''
    export let error
    export let required
    export let placeholder
    export let autocomplete
    export let items = []
    export let selectedValue
    export let isMulti = false
    export let groupBy
    export let isSearchable = true

    let select = null

    onMount(() => {
        select = document.getElementById(id)
    })

    afterUpdate(() => {
        if (required && select != null) {
            selectedValue?.value != undefined
                ? select.setCustomValidity('')
                : select.setCustomValidity($_('Please fill out this field.'))
        }
    })

    function handleSelect(event) {
        selectedValue = event.detail
    }
</script>

<Select
    selectedValue={selectedValue?.value ? selectedValue : null}
    inputAttributes={{ id: id }}
    {placeholder}
    containerClasses="items {classes}"
    {items}
    {autocomplete}
    {isMulti}
    {isSearchable}
    {groupBy}
    on:select={(e) => handleSelect(e)}
    on:clear={() => (selectedValue = null)}
/>
<InputError message={error} />

<style>
    :global(.items .listItem) {
        border-bottom: 1px solid #ccc;
    }

    :global(.items .item) {
        height: auto !important;
        line-height: 1.6 !important;
        text-overflow: initial !important;
        overflow: initial !important;
        white-space: break-spaces !important;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    :global(.min-h .listContainer) {
        min-height: 50vh;
    }
</style>
