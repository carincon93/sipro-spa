<script>
    import { afterUpdate, onMount } from 'svelte'
    import axios from 'axios'
    import Select from 'svelte-select'
    import InputError from '@/Components/InputError'
    import { _ } from 'svelte-i18n'

    export let classes = ''
    export let id = ''
    export let message
    export let value
    export let required
    export let placeholder
    export let routeWebApi

    let items = []
    let itemFiltered = null
    let select = null

    onMount(() => {
        getItems()
        select = document.getElementById(id)
    })

    afterUpdate(() => {
        if (required) {
            value != null && select != null
                ? select.setCustomValidity('')
                : select.setCustomValidity($_('Please fill out this field.'))
        }
    })

    async function getItems() {
        let res = await axios.get(routeWebApi)
        items = res.data
        selectItem()
    }

    function handleSelect(event) {
        value = event.detail.value
    }

    function selectItem() {
        if (value) {
            let filterItem = items.filter(function (item) {
                return item.value == value
            })
            itemFiltered = filterItem[0]
        }
    }
</script>

<Select
    selectedValue={itemFiltered}
    inputAttributes={{ id: id }}
    {placeholder}
    containerClasses="items {classes}"
    {items}
    on:select={handleSelect}
    on:clear={() => (value = null)}
/>
<InputError {message} />

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
