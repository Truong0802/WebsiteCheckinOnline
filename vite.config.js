import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// Build SCSS (Bootstrap) + JS cho giao diện Blade; output vào public/build.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
