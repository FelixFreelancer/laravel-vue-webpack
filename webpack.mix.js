let mix = require('laravel-mix');

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

mix.styles([
    'resources/assets/css/admin/vendor/bootstrap.css',
    'resources/assets/css/admin/style.css',
], 'public/css/admin/metronic.css');

mix.less('resources/assets/css/admin/vendor/croppie.less', 'public/css/admin/vendor/croppie.css');
mix.scripts([
    'resources/assets/js/admin/vendor/jquery.js',
    'resources/assets/js/admin/metronic.js'
], 'public/js/admin/metronic.js');
mix.scripts('resources/assets/js/admin/dashboard.js', 'public/js/admin/dashboard.js');
mix.scripts([
    'resources/assets/js/admin/vendor/croppie.min.js',
    'resources/assets/js/admin/vendor/ss-crop.js'
], 'public/js/admin/vendor/ss-crop.js');

mix.copy('resources/assets/img', 'public/img');
mix.copy('resources/assets/css/admin/fonts', 'public/css/admin/fonts');
mix.version();