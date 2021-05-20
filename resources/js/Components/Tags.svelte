<script>
    import Tagify from '@yaireo/tagify'
    import { onMount } from 'svelte'

    import '@yaireo/tagify/dist/tagify.css'
    import InputError from '@/Components/InputError'

    export let id
    export let tags
    export let error

    let input, tagify

    onMount(() => {
        ;(input = document.querySelector('.tagify-input')), (tagify = new Tagify(input, { placeholder: 'Separados por coma' }))
        // listen to any keystrokes which modify tagify's input

        tagify.on('add', onAddRemove).on('remove', onAddRemove)
    })

    function onAddRemove() {
        tags = input.value
    }
</script>

<input type="text" {id} value={tags} class="tagify-input bg-white w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200" />
<InputError message={error} />

<style>
    :global(.tagify__input::before) {
        line-height: initial;
    }
</style>
