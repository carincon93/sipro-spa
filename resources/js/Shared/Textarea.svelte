<script>
    import fitTextarea from 'fit-textarea'
    import InputError from '@/Shared/InputError'
    import Textfield from '@smui/textfield'
    import CharacterCounter from '@smui/textfield/character-counter'
    import { onMount } from 'svelte'

    export let error
    export let id
    export let value
    export let label
    export let disabled
    export let sinContador = false
    export let maxlength = 2000

    let container

    $: props = {
        ...$$restProps,
        class: `w-full block bg-white ${$$restProps.class || ''}`,
    }

    onMount(() => {
        fitTextarea.watch(container.querySelector('textarea'))
        container.querySelector('textarea').setAttribute('id', id)
    })
</script>

<div bind:this={container}>
    {#if sinContador == true}
        <Textfield {disabled} textarea bind:value {label} {...props} />
    {:else}
        <Textfield {disabled} textarea input$maxlength={maxlength} bind:value {label} {...props}>
            <CharacterCounter slot="internalCounter">0 / {maxlength}</CharacterCounter>
        </Textfield>
    {/if}
</div>

{#if error}
    <InputError message={error} />
{/if}
