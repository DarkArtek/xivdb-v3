let Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/assets/')
    .setPublicPath('/assets')
    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/app', './assets/css/app.scss')
    .enableSassLoader(function(options) {}, {
        resolveUrlLoader: false
    })
;

let config = Encore.getWebpackConfig();
config.output.library = 'XIV';
config.output.libraryExport = "default";
config.output.libraryTarget = 'var';
module.exports = config;
