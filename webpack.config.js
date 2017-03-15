let path = require('path'),
    webpack = require('webpack'),
    fs = require('fs'),

    HtmlWebpackPlugin = require('html-webpack-plugin'),
    // CleanWebpackPlugin = require('clean-webpack-plugin'),
    ExtractTextPlugin = require('extract-text-webpack-plugin'),

    autoprefixer = require('autoprefixer')({
        // browsers: 'last 2 versions',
        // browsers: 'last 2 versions, safari 8, android 4',
        browsers: ['last 2 versions', 'safari 8', 'android 4'],
        remove: false
    }),
    // precss = require('precss'),

    validate = require('webpack-validator'),
    pkg = require('./package.json');

const PATHS = {
    app: path.join(__dirname, 'src', 'main.js'),
    build: __dirname
};

const _path = (p) => path.join(__dirname, p);

const stylusLoaders = [
    'style-loader',
    'css-loader?sourceMap',
    'postcss-loader',
    'stylus-loader' //?outputStyle=expanded&includePaths[]=' + path.resolve('./node_modules/compass-mixins/lib')
];

let plugins = [];

let config = {
    entry: {
        main: PATHS.app
    },
    resolve: {
        alias: {
            // jquery is NOT a peer dependency in jquery.inputmask so a alias
            // is used here to force jquery.inputmask to use your jquery
            // version
            // 'jquery': path.join(__dirname, 'node_modules/jquery/dist/jquery'),

            'jquery': _path('node_modules/jquery/dist/jquery'),
            'inputmask.dependencyLib': _path('node_modules/jquery.inputmask/dist/inputmask/inputmask.dependencyLib.jquery'),
            'inputmask' : _path('node_modules/jquery.inputmask/dist/inputmask/inputmask'),
            'jquery.inputmask': _path('node_modules/jquery.inputmask/dist/inputmask/jquery.inputmask'),
            'inputmask.date.extension': _path('node_modules/jquery.inputmask/dist/inputmask/inputmask.date.extensions'),
            'inputmask.numeric.extension': _path('node_modules/jquery.inputmask/dist/inputmask/inputmask.numeric.extensions')

            // Switch dependency lib accordingly (this one uses jquery)
            // 'inputmask.dependencyLib': path.join(__dirname, 'node_modules/jquery.inputmask/dist/inputmask/inputmask.dependencyLib.jquery'),
            // Core library (order of these aliases shouldn't matter FYI)
            // 'inputmask' : path.join(__dirname, 'node_modules/jquery.inputmask/dist/inputmask/inputmask'),
            // Allows use of jquery input mask via jquery chaining api/$('selector').inputmask(...)
            // 'jquery.inputmask': path.join(__dirname, 'node_modules/jquery.inputmask/dist/inputmask/jquery.inputmask'),
            // Add extensions following the pattern below remember to import them as necessary in your .js files
            // 'inputmask.numeric.extensions': path.join(__dirname, 'node_modules/jquery.inputmask/dist/inputmask/inputmask.numeric.extensions')
        }
    },
    module: {
        loaders: [{
            test: /\.js$/,
            loader: 'imports?jQuery=jquery'
        }, {
            test: /\.styl$/,
            loaders: stylusLoaders
        }, {
            test: /\.(woff|woff2|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
            loader: 'file-loader?name=fonts/[name].[ext]'
        }, {
            test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
            loaders: ['svg-url?limit=16384&name=img/svg/[name].[ext]', 'svgo']
        }, {
            test: /\.(png|jpg)$/,
            //loaders: ['url?limit=16384&name=img/[name].[ext]', 'img?optimizationLevel=7&progressive=true']
            loaders: ['url?limit=8192&name=img/[name].[ext]', 'img?optimizationLevel=7&progressive=true']
        }]
    },
    output: {
        publicPath: '/',
        path: PATHS.build,
        filename: '[name].js'
    },
    postcss: function () {
        return [autoprefixer];
        // return [autoprefixer, precss];
    }
    // stylus: {
    //     preferPathResolver: 'webpack'
    // }
};

let htmlFiles = fs.readdirSync(path.join(__dirname, 'src', 'components', '_pages')).filter(function (file) {
    return file.indexOf('.html') > -1;
});

switch (process.env.npm_lifecycle_event) {
    /*case 'watch':
        config.module.loaders.unshift({
            test: /\.jsx?$/,
            exclude: /node_modules/,
            loader: 'babel',
            query: {
                presets: ['es2015']
            }
        });

        config.output.filename = 'js/[name].[chunkhash].js';
        config.output.chunkFilename = 'js/[chunkhash].js';

        config.entry.vendor = Object.keys(pkg.dependencies);

        plugins.push(new webpack.optimize.CommonsChunkPlugin({
            names: ['vendor'/!*, 'manifest'*!/],
            minChunks: 2
        }));

        plugins.push(new webpack.optimize.UglifyJsPlugin({
            output: {
                comments: false
            },
            compress: {
                warnings: false,
                screw_ie8: true
            }
        }));

        config.module.loaders.filter(loader =>
            loader.loaders && loader.loaders.find(name => /css/.test(name.split('?')[0]))
        ).forEach(loader => {
            const [first, ...rest] = loader.loaders;
            loader.loader = ExtractTextPlugin.extract(first, rest.join('!'));
            delete loader.loaders;
        });

        plugins.push(new ExtractTextPlugin('css/[name].css', {
            allChunks: true
        }));

        htmlFiles.forEach(function (file) {
            plugins.push(new HtmlWebpackPlugin({
                filename: path.join(__dirname, '_html', file),
                template: path.join(__dirname, 'src', 'components', '_pages', file)
            }));
        });

        break;*/

    case 'build':
        config.module.loaders.unshift({
            test: /\.jsx?$/,
            exclude: /node_modules/,
            loader: 'babel',
            query: {
                presets: ['es2015']
            }
        });

        process.env.NODE_ENV = 'production';

        config.output.filename = 'js/[name].js';
        config.output.chunkFilename = 'js/[chunkhash].js';

        plugins.push(new webpack.DefinePlugin({
            'process.env.NODE_ENV': 'production'
        }));

        config.entry.vendor = Object.keys(pkg.dependencies);

        plugins.push(new webpack.optimize.CommonsChunkPlugin({
            names: ['vendor'/*, 'manifest'*/]
        }));

        plugins.push(new webpack.optimize.OccurenceOrderPlugin(true));
        plugins.push(new webpack.optimize.DedupePlugin());

        plugins.push(new webpack.optimize.UglifyJsPlugin({
            output: {
                comments: false
            },
            compress: {
                warnings: false,
                screw_ie8: true
            }
        }));

        config.module.loaders.filter(loader =>
            loader.loaders && loader.loaders.find(name => /css/.test(name.split('?')[0]))
        ).forEach(loader => {
            const [first, ...rest] = loader.loaders;
            loader.loader = ExtractTextPlugin.extract(first, rest.join('!'));
            delete loader.loaders;
        });

        plugins.push(new ExtractTextPlugin('css/[name].css', {
            allChunks: true
        }));

        htmlFiles.forEach(function (file) {
            plugins.push(new HtmlWebpackPlugin({
                filename: path.join(__dirname, '_html', file),
                template: path.join(__dirname, 'src', 'components', '_pages', file)
            }));
        });

        break;

    default:
        let host = '192.168.1.36',
            port = 3000,
            url = `http://${host}:${port}/`;

        config.module.loaders.unshift({
            test: /\.jsx?$/,
            exclude: /node_modules/,
            loaders: ['babel']
        });

        config.devtool = 'source-map';
        config.output.publicPath = url;

        config.devServer = {
            historyApiFallback: true,
            hot: true,
            inline: true,
            stats: 'errors-only',
            //stats: { colors: true },
            host: host,
            port: port,
            publicPath: config.output.publicPath
        };

        plugins.push(new ExtractTextPlugin('[name].css', {
            allChunks: true
        }));

        plugins.push(new webpack.HotModuleReplacementPlugin());

        config.entry['webpack-dev-server'] = `webpack-dev-server/client/?${url}`;
        config.entry['webpack/hot/dev-server'] = 'webpack/hot/dev-server';

        htmlFiles.forEach(function (file) {
            plugins.push(new HtmlWebpackPlugin({
                filename: path.join(file),
                template: path.join(__dirname, 'src', 'components', '_pages', file)
            }));
        });

        break;
}

config.plugins = plugins;

module.exports = validate(config);