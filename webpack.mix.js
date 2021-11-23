const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.copy('resources/assets/js/addons', 'public/js')
    .sass('resources/assets/scss/app.scss', 'css')
    .sass('resources/assets/scss/customer.scss', 'css')
    .js('resources/assets/js/app.js', 'js').vue()
    .copy('resources/assets/js/modules', 'public/js/modules')
    .copy('resources/assets/images', 'public/images')
    .copy('resources/assets/css', 'public/css')
    .copy('resources/assets/external-css', 'public/css')
    .setPublicPath('public');

