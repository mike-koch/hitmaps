const RollbarSourceMapPlugin = require('rollbar-sourcemap-webpack-plugin');

module.exports = {
  pluginOptions: {
    i18n: {
      locale: 'en-US',
      fallbackLocale: 'en-US',
      localeDir: 'locales',
      enableInSFC: false
    }
  },
  configureWebpack: {
    plugins: [
      new RollbarSourceMapPlugin({
        accessToken: '78c7dc0eff9f44a8a460396dd6ba4dd1',
        version: 'version-1',
        publicPath: 'https://www.hitmaps.com'
      })
    ]
  }
}
