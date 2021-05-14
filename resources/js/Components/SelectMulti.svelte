<script>
    import { onMount } from 'svelte'
    import { _ } from 'svelte-i18n'

    import SelectMulti from 'svelte-select'
    import InputError from '@/Components/InputError'

    export let id = ''
    export let selectedValue
    export let items
    export let placeholder
    export let error
    export let required

    const groupBy = (item) => item.group

    let select = null

    onMount(() => {
        select = document.getElementById(id)
    })

    $: selectedValue
    $: if (required && select != null) {
        selectedValue != undefined
            ? select.setCustomValidity('')
            : select.setCustomValidity($_('Please fill out this field.'))
    }
</script>

<SelectMulti
    inputAttributes={{ id: id }}
    bind:selectedValue
    {items}
    isMulti={true}
    {groupBy}
    {placeholder}
/>
<InputError classes="text-center" message={error} />
