import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.jsx'
            ],
            refresh: true,
        }),
        react(),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
        assetsDir: 'assets',
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
    server: {
        hmr: {
            protocol: 'wss',
            host: 'laquevapizza.onrender.com',
            clientPort: 443,
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    // Configuración para producción
    base: process.env.NODE_ENV === 'production' ? '/build/' : '/',
});
