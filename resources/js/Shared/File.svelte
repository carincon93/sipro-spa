<script>
    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'

    export let id
    export let value
    export let label
    export let error
    export let maxSize

    let input
    let size

    export const focus = () => input.focus()
    export const select = () => input.select()

    $: props = {
        ...$$restProps,
        class: `w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm ${$$restProps.class || ''}`,
    }

    function update(event) {
        value = event.target.files[0]
    }

    $: value?.size / 1000 < 1000 ? (size = value?.size / 1000 + ' KB') : (size = value?.size / 1000 + ' MB')

    $: value?.size / 1000 > maxSize ? (error = `El archivo pesa más de ${maxSize / 1000} MB. Por favor optimice el tamaño.`) : (error = null)
</script>

<div class={$$restProps.class}>
    <Label {label} {id} />

    <input {...props} type="file" class="w-full border-gray-300 shadow-sm focus:ring focus:ring-opacity-50{error ? ' ring ring-opacity-50 border-red-200 ring-red-200 focus:border-red-200 focus:ring-red-200' : 'focus:border-indigo-200 focus:ring-indigo-200'}" {id} on:change={(event) => update(event)} />

    {#if value?.size}
        <span class="inline-block mt-4 mb-4">Peso del archivo: {size}</span>
    {/if}

    {#if error}
        <InputError message={error} />
    {/if}
</div>
