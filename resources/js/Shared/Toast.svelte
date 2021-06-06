<script>
    import { page } from '@inertiajs/inertia-svelte'
    import { _ } from 'svelte-i18n'
    import Snackbar, { Actions, Label } from '@smui/snackbar'
    import IconButton from '@smui/icon-button'

    let snackbar
    let text = ''

    let errorsLength = Object.keys($page.props.errors).length

    if ($page.props.flash.success) {
        text = $_($page.props.flash.success)
    } else if ($page.props.flash.error) {
        text = $page.props.flash.error
    } else if (errorsLength === 1) {
        text = $_('There is one form error.')
    } else if (errorsLength > 0) {
        text = $_('There are form errors.', { values: { count: errorsLength } })
    }

    if (text && !snackbar.isOpen()) {
        snackbar.open()
    }

    $: console.log($page.props.errors)
</script>

<Snackbar bind:this={snackbar} labelText={text} timeoutMs={4000}>
    <Label />
    <Actions>
        <IconButton class="material-icons" title="Dismiss">close</IconButton>
    </Actions>
</Snackbar>
