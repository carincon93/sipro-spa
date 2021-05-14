require('./bootstrap')

// Import modules...
import { addMessages, init } from 'svelte-i18n'
import { App } from '@inertiajs/inertia-svelte'
import { InertiaProgress } from '@inertiajs/progress'

import es from '../lang/es.json'

addMessages('es', es)

init({
    initialLocale: 'es',
})

const el = document.getElementById('app')

new App({
    target: el,
    props: {
        initialPage: JSON.parse(el.dataset.page),
        resolveComponent: (name) => require(`./Pages/${name}.svelte`),
    },
})

InertiaProgress.init({ color: '#4B5563' })
