<<<<<<< HEAD
// Mengonfigurasi aset Laravel, Tailwind, dan server pengembangan Vite.
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
=======
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
>>>>>>> 2719a8768e6bc6c45f29cf2c011aecb1c6686891
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
<<<<<<< HEAD
            input: ['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'],
            refresh: true,
=======
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
>>>>>>> 2719a8768e6bc6c45f29cf2c011aecb1c6686891
        }),
        tailwindcss(),
    ],
    server: {
<<<<<<< HEAD
        host: '127.0.0.1',
        port: 3000,
=======
>>>>>>> 2719a8768e6bc6c45f29cf2c011aecb1c6686891
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
