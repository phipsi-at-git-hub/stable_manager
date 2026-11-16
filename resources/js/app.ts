import { createApp, h, type DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createVuetify } from 'vuetify';

import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';

const vuetify = createVuetify({
    theme: {
        defaultTheme: 'stableManagerLight',
        themes: {
            stableManagerLight: {
                dark: false,
                colors: {
                    primary: '#345B4A',
                    secondary: '#718579',
                    background: '#F7F8F6',
                    surface: '#FFFFFF',
                    error: '#B3261E',
                    success: '#397653',
                    warning: '#B7791F',
                },
            },
        },
    },
});

const pages = import.meta.glob<{ default: DefineComponent }>(
    './Modules/**/*.vue',
    {
        eager: true,
    },
);

createInertiaApp({
    resolve: (name) => {
        const path = `./Modules/${name}.vue`;
        const page = pages[path];

        if (!page) {
            throw new Error(`Inertia page not found: ${path}`);
        }

        return page.default;
    },

    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(App, props),
        })
            .use(plugin)
            .use(vuetify)
            .mount(el);
    },
});
