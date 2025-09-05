const webpack = require('webpack');
const path    = require('path');
const minicss = require('mini-css-extract-plugin');

module.exports = {
    mode: 'development',
    resolve: {
        fallback: {
            'path': require.resolve('path-browserify'),
        },
    },
    entry: {
        'assets/css/editor-style': path.resolve('src', 'scss/editor-style.scss'),
		'assets/css/editor-panels': path.resolve('src', 'scss/editor-panels.scss'),
        'assets/css/custom-layouts': path.resolve('src', 'scss/custom-layouts.scss'),
        'custom-layouts': path.resolve('src', 'js/custom-layouts.js'),
        'unlimited-sidebars': path.resolve('src', 'js/unlimited-sidebars.js'),
    },
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: 'assets/js/[name].js',
    },
    plugins: [
        new webpack.ProvidePlugin({
            process: 'process/browser',
        }),
        new minicss({
            filename: '[name].css',
        }),
    ],
    module: {
        rules: [
            {
                test: /\.scss$/,
                exclude: /node_modules/,
                use: [
                    minicss.loader, // creates style nodes from JS strings
                    'css-loader', // translates CSS into CommonJS
                    'sass-loader', // compiles Sass to CSS, using Node Sass by default
                ],
            },
            {
                test: /\.js?$/,
                exclude: /node_modules/,
                use: 'babel-loader'
            },
            {
                test: /\.(jpe?g|png|gif|webp)$/i,
                include: path.resolve(__dirname, 'src/images'),
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: 'assets/images/',
                        publicPath: '../assets/images/',
                    },
                }],
            },
        ],
    },
}
