import { createApp, DefineComponent, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

const pages = import.meta.glob<{ default: DefineComponent }>(
    './Modules/**/*.vue',
    {
        eager: true,
    }
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
            .mount(el);
    },
});
