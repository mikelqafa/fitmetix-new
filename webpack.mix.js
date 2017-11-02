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

/*mix.sass('resources/assets/sass/bootstrap.scss', 'public/css/bootstrap.css')
    .combine([
      'public/themes/default/assets/css/bootstrap.css',
      'public/themes/default/assets/css/animate.css',
      'public/themes/default/assets/css/font-awesome.min.css',
      'public/themes/default/assets/css/datepicker.css',
      'public/themes/default/assets/css/bootstrap-datetimepicker.css',
      'public/themes/default/assets/css/jquery-confirm.min.css',
      'public/themes/default/assets/css/selectize.css',
      'public/themes/default/assets/css/selectize.bootstrap3.css',
      'public/themes/default/assets/css/emojify.css',
      'public/themes/default/assets/css/jquery.mCustomScrollbar.css',
      'public/themes/default/assets/css/lightbox.min.css',
      'public/themes/default/assets/css/lightgallery.css',
      'public/themes/default/assets/css/main.css'
    ], 'public/css/style.css')*/
mix.sass('resources/assets/sass/app.scss', 'public/css/app.css');
mix.disableNotifications();
mix.browserSync({
  proxy: 'http://localhost/fitmetix/public/'
});