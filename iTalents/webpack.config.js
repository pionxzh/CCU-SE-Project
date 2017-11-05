const webpack = require('webpack')
const path = require('path')

module.exports = {
    entry: path.join(__dirname, 'resources/assets/js/main.js'),
    output: {
        filename: 'main.js',
        path: __dirname + '/public/js'
    },
	module: {
        rules: [
            {
                test: /\.(js|vue)$/,
                enforce: 'pre',
                loader: 'eslint-loader',
                include: path.join(__dirname, 'resources'),
                options: {
                    formatter: require('eslint-friendly-formatter')
                }
            }, {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                        i18n: '@kazupon/vue-i18n-loader'
                    }
                }
            }, {
                test: /\.styl$/,
                loader: 'style-loader!css-loader!stylus-loader'
            }
        ]
    },
    plugins: [
        new webpack.ProvidePlugin({
            jQuery: "jquery",
            $: "jquery"
        })
  ]
}