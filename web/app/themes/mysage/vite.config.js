import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    base: '/wp-content/themes/sage/public/build/',
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/editor.css',
                'resources/js/editor.js',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                // {
                //     src: 'resources/lang/*.json', // Copy all JSON translation files
                //     dest: 'lang' // Save them in the build directory
                // },
                {
                    src: 'resources/magiczoomplus/magiczoomplus.css',
                    dest: 'assets'
                },
                {
                    src: 'resources/magiczoomplus/magiczoomplus.js',
                    dest: 'assets'
                },
                {
                    src: 'resources/mobiscrolljs/css/mobiscroll.javascript.min.css',
                    dest: 'assets'
                },
                {
                    src: 'resources/mobiscrolljs/js/mobiscroll.javascript.min.js',
                    dest: 'assets'
                },

            ]
        }),

        wordpressPlugin(),

        // Generate the theme.json file in the public/build/assets directory
        // based on the Tailwind config and the theme.json file from base theme folder
        wordpressThemeJson({
          disableTailwindColors: false,
          disableTailwindFonts: false,
          disableTailwindFontSizes: false,
        }),
    ],
    resolve: {
        alias: {
            '@scripts': '/resources/js',
            '@styles': '/resources/css',
            '@fonts': '/resources/fonts',
            '@images': '/resources/images',
        },
    },
    server: {
      host: '0.0.0.0',
      port: 5173,
      hmr: { host: '127.0.0.1', },
    },
});
