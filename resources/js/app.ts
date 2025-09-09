// resources/js/app.ts
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { route as ziggyRoute } from 'ziggy-js';
import { Ziggy } from './ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // ✅ Global $route function
        app.config.globalProperties.$route = (name: string, params?: Record<string, any>, absolute?: boolean) => {
            return ziggyRoute(name, params, absolute, Ziggy);
        };

        app.use(plugin).mount(el);
    },
    progress: { color: '#4B5563' },
});
