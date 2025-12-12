import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import ToastService from 'primevue/toastservice';
import { updateTheme } from './composables/useAppearance';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        const initialPreference = (props.initialPage.props as any)?.preference || null;
        const savedAppearance = typeof window !== 'undefined' ? localStorage.getItem('appearance') : null;
        const appearance = (initialPreference?.theme as string) || savedAppearance || 'system';
        updateTheme(appearance as any);
        if (initialPreference?.theme && typeof window !== 'undefined') {
            localStorage.setItem('appearance', initialPreference.theme);
        }

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                },
                ripple: true,
            })
            .use(ToastService)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
