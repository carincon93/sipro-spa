<script>
    import Label from '@/Shared/Label'
    import InputError from './InputError'
    import InfoMessage from './InfoMessage'
    import Textfield from '@smui/textfield'
    import { onMount } from 'svelte'

    export let id
    export let value
    export let label
    export let error
    export let type
    export let disabled
    export let message = ''

    let input
    let container

    export const focus = () => input.focus()
    export const select = () => input.select()

    $: props = {
        ...$$restProps,
        class: 'w-full block bg-white',
    }

    function update(event) {
        value = event.target.value
    }

    onMount(() => {
        container.querySelector('input').setAttribute('id', id)
    })
</script>

<div class={$$restProps.class} bind:this={container}>
    <Label {label} {id} />

    <Textfield {disabled} variant="outlined" {...props} bind:this={input} {type} {value} on:input={update} {label} />
    {#if type == 'url' && message != ''}
        <InfoMessage>
            {message}
        </InfoMessage>
    {:else if type == 'url' && message == ''}
        <InfoMessage>
            <strong>Si va a cargar un archivo/documento/soporte tenga en cuenta lo siguiente:</strong>
            <ul>
                <li>1. Si tiene cuenta @sena.edu.co cargue el archivo en <a href="https://sena4-my.sharepoint.com/" class="underline" target="_blank">https://sena4-my.sharepoint.com/</a> y si es @misena.edu.co en <a href="https://drive.google.com" class="underline" target="_blank">https://drive.google.com</a></li>
                <li>2. De clic en la opción Share o Compartir y copie el enlace que se genera (Debe revisar que el enlace sea público)</li>
                <li>3. Asegúrese que el enlace empiece con http:// o https://</li>
            </ul>
        </InfoMessage>
    {/if}
    {#if error}
        <InputError message={error} />
    {/if}
</div>
