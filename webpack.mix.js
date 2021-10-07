const mix = require('laravel-mix')

if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: "inline-source-map"
    });
}

mix.webpackConfig({
    devtool: "inline-source-map"
});

mix.js('resources/js/index.js', 'public/js')
    .less('resources/less/app.less', 'public/css')
    .react()
    .version()

if (!mix.inProduction()) {
    mix.sourceMaps()
}



