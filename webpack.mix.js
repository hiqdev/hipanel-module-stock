const mix = require("laravel-mix");

mix.js("src/assets/Mobile/index.js", "src/assets/Mobile/dist/mobile-app.js").vue({version: 3});
