import path from 'path';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    build: {
        sourcemap: true,
    },
    resolve: {
        alias: {
            // DataTables (and other deps) use page's global jQuery, not a bundled copy
            jquery: path.resolve(__dirname, 'resources/js/jquery-shim.js'),
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: 'myapp.test',
        port: 5173,
        hmr: { host: 'myapp.test' },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
