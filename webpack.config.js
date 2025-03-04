const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')

    .addEntry('app', './assets/app.js')
<<<<<<< HEAD

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
=======
    .enableStimulusBridge('./assets/controllers.json')
    .splitEntryChunks()
>>>>>>> dfa94d0090eea8f5598197e859ef1ddb4569f4ea
    .enableSingleRuntimeChunk()

    // enables Sass/SCSS support
    .enableSassLoader()

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
<<<<<<< HEAD
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    .enableIntegrityHashes(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();
=======
    .enableVersioning(Encore.isProduction());

module.exports = Encore.getWebpackConfig();
>>>>>>> dfa94d0090eea8f5598197e859ef1ddb4569f4ea
