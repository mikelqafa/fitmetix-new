const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js(['resources/assets/js/webpack/notification.js'], 'public/js/notification.js');

// mix.sass('resources/assets/sass/bootstrap.scss', 'public/css/bootstrap.css')
return
mix.sass('resources/assets/sass/app.scss', 'public/css/style.css')
    .combine([
      'resources/assets/sass/style/animate.css',
      'resources/assets/sass/style/font-awesome.min.css',
      'resources/assets/sass/style/datepicker.css',
      'resources/assets/sass/style/bootstrap-datetimepicker.css',
      'resources/assets/sass/style/jquery-confirm.min.css',
      'resources/assets/sass/style/selectize.css',
      'resources/assets/sass/style/selectize.bootstrap3.css',
      'resources/assets/sass/style/emojify.css',
      'resources/assets/sass/style/jquery.mCustomScrollbar.css',
      'resources/assets/sass/style/lightbox.min.css',
      'resources/assets/sass/style/lightgallery.css',
      'resources/assets/sass/style/main.css',
      'public/css/style.css'
    ], 'public/css/app.css')

mix.disableNotifications();

mix.browserSync({
  proxy: 'http://localhost/fitmetix/public/'
});
