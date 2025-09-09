// vite.config.mjs
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                // Oculta warnings deprecados que vienen de dependencias (node_modules)
                quietDeps: true,
                // Silencia los avisos que hoy te “ensucian” el build (incluye tus .scss):
                silenceDeprecations: [
                    'import',
                    'legacy-js-api',
                    'global-builtin',
                    'color-functions',
                ],
            },
        },
    },
});
