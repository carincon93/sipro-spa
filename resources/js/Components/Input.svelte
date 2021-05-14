<script>
    import Label from '@/Components/Label'
    import InputError from './InputError.svelte'

    export let id
    export let value
    export let label
    export let error
    export let type

    let input

    export const focus = () => input.focus()
    export const select = () => input.select()

    $: props = {
        ...$$restProps,
        class:
            'w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm',
    }

    function update(event) {
        value = event.target.value
    }
</script>

<div class={$$restProps.class}>
    <Label {label} {id} />

    <input
        {...props}
        bind:this={input}
        class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50{error
            ? ' ring ring-opacity-50 border-red-200 ring-red-200 focus:border-red-200 focus:ring-red-200'
            : 'focus:border-indigo-200 focus:ring-indigo-200'}"
        {id}
        {type}
        {value}
        on:input={update}
    />

    {#if error}
        <InputError message={error} />
    {/if}
</div>
