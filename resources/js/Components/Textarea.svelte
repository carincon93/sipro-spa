<script>
    import fitTextarea from 'fit-textarea'
    import InputError from '@/Components/InputError'
    import Textarea from '@smui/textfield'
    import CharacterCounter from '@smui/textfield/character-counter/index'
    import { onMount } from 'svelte'

    export let error
    export let id
    export let value
    export let label
    export let maxlength = 2000

    let container

    $: props = {
        ...$$restProps,
        class: 'w-full block bg-white',
    }

    function update(event) {
        value = event.target.value
    }

    onMount(() => {
        fitTextarea.watch(container.querySelector('textarea'))
    })
</script>

<div bind:this={container}>
    <Textarea textarea input$maxlength={maxlength} bind:value {label} {...props} {id} on:input={update}>
        <CharacterCounter slot="internalCounter">0 / {maxlength}</CharacterCounter>
    </Textarea>
</div>

{#if error}
    <InputError message={error} />
{/if}
