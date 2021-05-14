<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'

    export let errors

    $: $title = 'Crear sector productivo'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    // prettier-ignore
    let canIndexSectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.index') == 'sectores-productivos.index'
    // prettier-ignore
    let canShowSectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.show') == 'sectores-productivos.show'
    // prettier-ignore
    let canCreateSectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.create') == 'sectores-productivos.create'
    // prettier-ignore
    let canEditSectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.edit') == 'sectores-productivos.edit'
    // prettier-ignore
    let canDestroySectoresProductivos = authUser.can.find((element) => element == 'sectores-productivos.destroy') == 'sectores-productivos.destroy'

    let sending = false
    let form = useForm({
        nombre: '',
    })

    function submit() {
        if (canCreateSectoresProductivos || isSuperAdmin) {
            $form.post(route('sectores-productivos.store'), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1>
                    {#if canIndexSectoresProductivos || canCreateSectoresProductivos || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('sectores-productivos.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            Sectores productivos
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canCreateSectoresProductivos || isSuperAdmin
                    ? undefined
                    : true}
            >
                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="nombre"
                        value="Nombre"
                    />
                    <Input
                        id="nombre"
                        type="text"
                        class="mt-1 block w-full"
                        bind:value={$form.nombre}
                        error={errors.nombre}
                        required
                    />
                </div>
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canCreateSectoresProductivos || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear sector productivo
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
