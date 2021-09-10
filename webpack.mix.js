const mix = require('laravel-mix')
const path = require('path')

mix
  .js('resources/js/main.js', 'public/js')
  .vue({ version: 3 })
  .postCss('resources/css/app.css', 'public/css', [require('tailwindcss')])
  .webpackConfig({
    resolve: {
      modules: [
        'node_modules',
        path.resolve(__dirname, 'vendor/laravel/spark/resources/js'),
      ],
      extensions: ['vue', 'js', 'jsx', 'ts', 'tsx'],
      alias: {
        '@': path.resolve(__dirname, 'resources/js/'),
      },
    },
  })
