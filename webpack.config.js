/**
 * webpack.config.js
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.ibitux.com/license license
 * @version XXX
 * @link http://www.ibitux.com
 */

const argv = require('yargs').argv;
const webpack = require('webpack');
const path = require('path');
const fs = require('fs');
const AssetsPlugin = require('assets-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');
const Dotenv = require('dotenv');

const prodFlag = process.env.NODE_ENV === 'production';

let confPath = './webpack-yii2.json';
if (argv.env && argv.env.config) {
  confPath = path.join(__dirname, argv.env.config, 'webpack-yii2.json');
}
if (!fs.existsSync(confPath)) {
  throw `Error: file "${confPath}" not found.`;
}

const config = require(confPath);
if (argv.env && argv.env.config) {
  config.sourceDir = path.relative(__dirname, argv.env.config);
}

// read env file
Dotenv.config();

const entryParams = prodFlag ? [] : [
  `webpack-dev-server/client?${process.env.NODE_DEV_HOST}:${process.env.NODE_DEV_PORT}`,
  'webpack/hot/only-dev-server',
];

const entries = {};
Object.keys(config.entry).forEach((key) => {
  entries[key] = [...entryParams, ...config.entry[key]];
});

const plugins = [
  new webpack.HotModuleReplacementPlugin(),
  new webpack.EnvironmentPlugin({
    NODE_ENV: 'development', // use 'development' unless process.env.NODE_ENV is defined
    DEBUG: false,
  }),
  new webpack.DefinePlugin(prodFlag ? {
    __REACT_DEVTOOLS_GLOBAL_HOOK__: '({})',
  } : {}),
  new MiniCssExtractPlugin({
    filename: prodFlag ? `../${config.assets.styles}/[name].[hash:6].css` : `${config.assets.styles}/[name].css`,
    allChunks: true,
  }),
  new CleanWebpackPlugin([`${config.subDirectories.dist}/js`, `${config.subDirectories.dist}/css`], {
    root: path.resolve(__dirname, config.sourceDir),
    verbose: true,
    dry: false,
    exclude: [],
  }),
  new AssetsPlugin({
    prettyPrint: true,
    filename: config.catalog,
    path: path.join(__dirname, config.sourceDir),
    processOutput(assets) {
      // fix path
      for (const i in assets) {
        if (assets.hasOwnProperty(i)) {
          for (const j in assets[i]) {
            if (assets[i].hasOwnProperty(j)) {
              if (prodFlag && !(assets[i][j] instanceof Array)) {
                assets[i][j] = path.join(config.assets.scripts, assets[i][j]).replace('\\', '/');
              }
            }
          }
        }
      }
      // drop empty assets (gz)
      const validAssets = Object.keys(assets)
        .filter(key => key !== '')
        .reduce((obj, key) => {
          obj[key] = assets[key];
          return obj;
        }, {});
      return JSON.stringify(validAssets, null, this.prettyPrint ? 2 : null);
    },
  }),
];

module.exports = {
  entry: entries,
  context: path.resolve(__dirname, config.sourceDir, config.subDirectories.sources),
  output: {
    path: path.resolve(__dirname, config.sourceDir, config.subDirectories.dist, config.assets.scripts),
    // dev server is not using upper path, so for dev env adding js manually in filename
    filename: prodFlag ? '[name].[hash:6].js' : `${config.assets.scripts}/[name].js`,
    publicPath: prodFlag ? '' : `${process.env.NODE_DEV_HOST}:${process.env.NODE_DEV_PORT}/`,
  },
  devServer: {
    headers: { 'Access-Control-Allow-Origin': '*' },
    hot: true,
    compress: true,
    inline: true,
    port: process.env.NODE_DEV_PORT,
    overlay: { errors: true, warnings: true },
  },
  plugins: prodFlag ? [...plugins,
    new OptimizeCssAssetsPlugin({
      cssProcessorOptions: { discardComments: { removeAll: true } },
    }),
    new CompressionPlugin({
      asset: '[path].gz[query]',
      algorithm: 'gzip',
      test: /\.js$|\.css$|\.html$|\.eot?.+$|\.ttf?.+$|\.woff?.+$|\.svg?.+$/,
      threshold: 10240,
      minRatio: 0.8,
    }),
  ] : plugins,
  externals: config.externals,
  optimization: {
    runtimeChunk: {
      name: 'manifest',
    },
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        loaders: ['babel-loader'],
      },
      {
        test: /\.(ttf|eot|svg|woff|woff2)(\?[a-z0-9]+)?$/,
        loader: 'file-loader?name=../[path][name].[ext]',
      },
      {
        test: /\.(jpg|png|gif)$/,
        loader: 'file-loader?name=../[path][name].[ext]',
      },
      {
        test: /\.less$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'less-loader',
        ],
      },
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
        ],
      },
    ],
  },
  resolve: {
    alias: config.alias,
    extensions: ['.js', '.jsx', '.less', '.css'],
  },
  devtool: 'source-map',
  target: 'web',
};
