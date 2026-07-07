import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
import { fileURLToPath, URL } from 'url';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/src/main.ts'],
            refresh: true,
        }),
        vue({
            template: {
                compilerOptions: {
                    isCustomElement: (tag) => ['v-list-recognize-title'].includes(tag)
                },
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            }
        }),
        vuetify({
            autoImport: true
        })
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js/src', import.meta.url)),
            'vue-i18n': 'vue-i18n/dist/vue-i18n.esm-bundler.js'
        }
    },
    css: {
        preprocessorOptions: {
            scss: {}
        }
    },
    build: {
        chunkSizeWarningLimit: 1024 * 1024
    },
    optimizeDeps: {

        entries: ['./resources/js/src/**/*.vue']
    },
    server: {
        watch: {
            ignored: ['**/png/**']
        }
    }
});
