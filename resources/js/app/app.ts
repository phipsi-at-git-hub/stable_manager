import { createApp, h, type DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

import { createStableManagerVuetify } from './Core/UI/Vuetify';

import {
    createAppI18n, 
    type LocalizationPayload, 
} from './Core/Localization';

import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import '../../css/app.css';

const vuetify = createStableManagerVuetify();

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
        const localization = props.initialPage.props.localization as LocalizationPayload; 
        const i18n = createAppI18n(localization); 

        createApp({
            render: () => h(App, props),
        })
            .use(plugin)
            .use(vuetify)
            .use(i18n)
            .mount(el);
    },
});
