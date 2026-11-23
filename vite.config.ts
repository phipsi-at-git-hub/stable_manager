import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
import { fileURLToPath, URL } from 'node:url'; 

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app/app.ts',
            ],
            refresh: true,
        }),
        vue(),
        vuetify({
            autoImport: true,
        }),
    ],
    resolve: {
        /*
        alias: {
            '@Core': fileURLToPath(
                new URL('./resources/js/app/Core'), 
            ), 
            '@Modules': fileURLToPath(
                new URL('./resources/js/app/Modules'), 
            ), 
            '@Shared': fileURLToPath(
                new URL('./resources/js/app/Shared'), 
            ), 
        }
        */
        alias: [
            {
                find: /^@Core/,
                replacement: fileURLToPath(
                    new URL('./resources/js/app/Core', import.meta.url), 
                ), 
            }, 
            {
                find: /^@Modules/,
                replacement: fileURLToPath(
                    new URL('./resources/js/app/Modules', import.meta.url), 
                ), 
            }, 
            {
                find: /^@Shared/,
                replacement: fileURLToPath(
                    new URL('./resources/js/app/Shared', import.meta.url), 
                ), 
            }, 
        ], 
    }, 
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
    },
});
