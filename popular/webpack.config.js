/**
 * EVOICE WEBPACK CONFIGURATION
 */

const path    = require('path');
const webpack = require('webpack');

const devMode = process.env.NODE_ENV !== 'production';
const CleanWebpackPlugin   = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// The path(s) that should be cleaned
let pathsToClean = ['dist'];

// The clean options to use
let cleanOptions = { verbose: true };

const config = {
    entry: {
        popular:  './src/js/popular.js'
    },
    output: {
        filename: 'js/[name].min.js',
        path: path.resolve(__dirname, 'dist')
    },
    watch: true,
    module: {
        rules: [
            // CSS
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            },

            // JS
            {
                test: /\.js$/,
                enforce: 'pre',
                exclude: /(node_modules|bower_components)/,
                loader: 'eslint-loader',
                options: {
                    failOnError: true
                }
            },

            // Images
            {
                test: /\.(png|jpg|gif|svg)$/,
                use: [
                    {
                        loader: 'file-loader',
						options: {
                            // Images larger than 10 KB won’t be inlined
                            // limit: 3 * 1024,
							name: '[name].[ext]',
                            outputPath: 'images/',
							publicPath: '../../images'
                        }
                    }
                ]
            },

            // Exposes jQuery for use outside Webpack build
            {
                test: require.resolve('jquery'),
                use: [
                    {
                        loader: 'expose-loader',
                        options: 'jQuery'
                    },
                    {
                        loader: 'expose-loader',
                        options: '$'
                    }
                ]
            }
        ]
    },
    node: {
       fs: 'empty',
       child_process: 'empty'
    },
    resolve: {
        alias: {
            'echo': 'echo-js/dist/echo.min'
        }
    },
    plugins: [
        // For Mini CSS support
        new webpack.LoaderOptionsPlugin({ options: {} }),

        // jQuery
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        }),

        // Optimisation
        new webpack.optimize.ModuleConcatenationPlugin(),

        // CSS
        new MiniCssExtractPlugin({
            // Options similar to the same options in webpackOptions.output
            // both options are optional
            filename: 'css/[name].min.css',
            chunkFilename: 'css/[id].min.css'
        }),

        // Clean up
        new CleanWebpackPlugin(pathsToClean, cleanOptions)
    ]
};

module.exports = config;
