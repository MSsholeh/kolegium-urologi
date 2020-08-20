const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/assets/js')
    .sass('resources/assets/sass/app.scss', 'public/assets/css');

// Pages JS
mix.js('resources/metronic/src/assets/js/pages/login.js', 'public/assets/js/pages/login.js');

// Page Sass
mix.sass('resources/metronic/src/assets/sass/pages/support-center/faq-2.scss', 'public/assets/css/pages/support-center/faq.css');

// Copy images folder into laravel public folder
mix.copyDirectory('resources/metronic/src/assets/media', 'public/assets/media');

/**
 * plugins specific issue workaround for webpack
 * @see https://github.com/morrisjs/morris.js/issues/697
 * @see https://stackoverflow.com/questions/33998262/jquery-ui-and-webpack-how-to-manage-it-into-module
 */
mix.webpackConfig({
    resolve: {
        alias: {
            'morris.js': 'morris.js/morris.js',
            'jquery-ui': 'jquery-ui',
        },
    },
});
