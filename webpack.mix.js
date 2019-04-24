let mix = require('laravel-mix');

mix.setPublicPath('./assets/build');

mix.js('assets/src/js/public.js', 'js')
    .sass('assets/src/scss/public.scss', 'css');

if (mix.inProduction()) {
    mix.sourceMaps();
}