<script>
    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import InfoMessage from '@/Shared/InfoMessage'
    import Button from '@/Shared/Button'

    export let id
    export let value
    export let label
    export let error
    export let maxSize
    export let route
    export let showInput = true

    let input
    let size

    export const focus = () => input.focus()
    export const select = () => input.select()

    $: props = {
        ...$$restProps,
        class: `w-full border-gray-300 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 rounded-md shadow-sm ${$$restProps.class || ''}`,
    }

    function update(event) {
        value = event.target.files[0]
    }

    $: if (value?.size) size = value?.size / 1e6

    $: size > maxSize / 1000 ? (error = `El archivo pesa más de ${maxSize / 1000} MB. Por favor optimice el tamaño.`) : (error = null)
</script>

<div class={$$restProps.class}>
    <Label {label} {id} />

    <div>
        {#if route}
            <a href={route} target="_blank" class="flex items-center justify-center hover:bg-cyan-100 bg-cyan-200 delay-100 text-cyan-500 p-2 rounded shadow-lg mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                </svg>
                Visualizar archivo cargado previamente
            </a>
            {#if showInput == false}
                <Button on:click={() => (showInput = true)} class="w-full">¿Desea reemplazar el archivo?</Button>
            {/if}
        {/if}
        {#if showInput}
            <input {...props} type="file" class="w-full bg-white p-2 border rounded-md" {id} on:change={(event) => update(event)} />

            {#if maxSize}
                <InfoMessage message="El archivo debe pesar máximo {maxSize / 1000} MB" />
            {/if}

            {#if value?.size}
                <span class="inline-block mt-4 mb-4">Peso del archivo: {size.toFixed(2)} MB</span>
            {/if}

            {#if error}
                <InputError message={error} />
            {/if}
        {/if}
    </div>
</div>
