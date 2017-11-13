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

mix.sass('resources/assets/sass/login.scss', 'public/css/login.css')
// mix.sass('resources/assets/sass/bootstrap.scss', 'public/css/bootstrap.css')
mix.js(['resources/assets/js/main.js'], 'public/js/notification.js');
mix.browserSync({
      proxy: 'http://localhost/fitmetix/public/'
});
return
mix.combine([
  'resources/assets/js/bundle/jquery.min.js',
  'resources/assets/js/bundle/jquery.form.js',
  'resources/assets/js/bundle/bootstrap.min.js',
  'resources/assets/js/bundle/selectize.min.js',
  'resources/assets/js/bundle/emojify.min.js',
  'resources/assets/js/bundle/mention.js',
  'resources/assets/js/bundle/playSound.js',
  'resources/assets/js/bundle/snackbar.js',
  'resources/assets/js/bundle/jquery.jscroll.js',
  'resources/assets/js/bundle/selectize.min.js',
  'resources/assets/js/bundle/snackbar.js',
  'resources/assets/js/bundle/jquery.mCustomScrollbar.concat.min.js'
], 'public/js/bundle.js')
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


