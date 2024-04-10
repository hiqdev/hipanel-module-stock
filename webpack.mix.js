const mix = require("laravel-mix");
const { VantResolver } = require("unplugin-vue-components/resolvers");
const ComponentsPlugin = require("unplugin-vue-components/webpack");
const path = require("path");

mix.webpackConfig({
    resolve: {
      alias: {
        "@": path.resolve(__dirname, "src/assets/Mobile"),
      },
    },
    plugins: [
      ComponentsPlugin({
        resolvers: [VantResolver()],
      }),
    ],
  })
  .js("src/assets/Mobile/index.js", "src/assets/Mobile/dist/mobile-app.js")
  .sourceMaps()
  .version()
  .setPublicPath('src/assets/Mobile/dist')
  .vue({ version: 3 });
