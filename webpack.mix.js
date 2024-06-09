const mix = require('laravel-mix');
require('mix-tailwindcss');
const tailwindcss = require("tailwindcss");

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
    .sass('resources/assets/scss/pdf/invoice.scss', 'css')
    .sass('resources/assets/scss/pdf/itinerary.scss', 'css')
    .sass('resources/assets/scss/admin/occupancy.scss', 'css/admin')
    .sass('resources/assets/scss/customer_portal/occupancy.scss', 'css/customer')
    .postCss('resources/assets/css/tailwind.css', 'public/css', [tailwindcss(),])
    .js('resources/assets/js/app.js', 'js')
    .js('resources/assets/js/bootstrap.js', 'js')
    .ts('resources/assets/typescript/occupancy.ts', 'js')
    .copy('resources/assets/js/admin', 'public/js/admin')
    .copy('resources/assets/js/ckeditor', 'public/js/ckeditor')
    .copy('resources/assets/js/modules', 'public/js/modules')
    .copy('resources/assets/images', 'public/images')
    .copy('resources/assets/css/preprocessed', 'public/css')
    .copy('resources/assets/external-css', 'public/css')
    .setPublicPath('public');

