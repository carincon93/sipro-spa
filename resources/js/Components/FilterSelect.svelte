<script>
    import Select from 'svelte-select'

    export let id
    export let items
    export let value
    export let error
    export let selectedValue

    $: props = {
        ...$$restProps,
    }

    function handleSelect(event) {
        value = event.detail.value
    }
</script>

<Select
    {items}
    on:select={handleSelect}
    {selectedValue}
    containerClasses={error
        ? 'selectContainer svelte-17qb5ew w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50  ring ring-opacity-50 border-red-200 ring-red-200 focus:border-red-200 focus:ring-red-200'
        : ''}
    inputAttributes={{ id: id }}
    {...props}
/>
