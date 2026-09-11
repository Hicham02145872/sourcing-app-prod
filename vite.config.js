import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const hmrHost = process.env.VITE_HMR_HOST;

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: hmrHost
        ? {
              host: '0.0.0.0',
              hmr: { host: hmrHost },
          }
        : undefined,
});
