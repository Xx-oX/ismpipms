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

mix.js([
    'resources/js/app.js'
], 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .webpackConfig(require('./webpack.config'));

mix.scripts([
    'resources/js/plugin/jquery.min.js',
    'resources/js/plugin/jquery.dataTables.min.js',
], 'public/js/vendor.js');

mix.styles([
    'resources/css/jquery.dataTables.css',
], 'public/css/vendor.css');

mix.browserSync({
    proxy: 'whatever.test',
    open: false,
});

if (mix.inProduction()) {
    mix.version();
}
