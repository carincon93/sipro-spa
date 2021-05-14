<script>
    import { onMount } from 'svelte'
    import fitTextarea from 'fit-textarea'
    import InputError from '@/Components/InputError'

    export let id
    export let value
    export let error
    export let classes = ''
    export let rows = 10

    let textarea

    $: props = {
        ...$$restProps,
    }

    onMount(() => {
        fitTextarea.watch(textarea)
    })

    function update(event) {
        value = event.target.value
    }
</script>

<textarea
    {...props}
    {rows}
    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 {classes}{error
        ? ' ring ring-opacity-50 border-red-200 ring-red-200 focus:border-red-200 focus:ring-red-200'
        : 'focus:border-indigo-200 focus:ring-indigo-200'}"
    bind:this={textarea}
    {id}
    {value}
    on:input={update}
/>

{#if error}
    <InputError message={error} />
{/if}
