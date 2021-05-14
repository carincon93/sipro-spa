<script>
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'

    export let id
    export let value
    export let label
    export let error

    let input

    export const focus = () => input.focus()
    export const select = () => input.select()

    $: props = {
        ...$$restProps,
        class:
            'w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm',
    }

    function update(event) {
        value = event.target.files[0]
    }
</script>

<div class={$$restProps.class}>
    <Label {label} {id} />

    <input
        {...props}
        type="file"
        class="w-full border-gray-300 shadow-sm focus:ring focus:ring-opacity-50{error
            ? ' ring ring-opacity-50 border-red-200 ring-red-200 focus:border-red-200 focus:ring-red-200'
            : 'focus:border-indigo-200 focus:ring-indigo-200'}"
        {id}
        on:change={(event) => update(event)}
    />

    {#if error}
        <InputError message={error} />
    {/if}
</div>
