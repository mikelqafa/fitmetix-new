<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf_token" content="{!! csrf_token() !!}"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <meta name="keywords" content="{{ Setting::get('meta_keywords') }}">
        <meta name="description" content="{{ Setting::get('meta_description') }}">
        <link rel="icon" type="image/x-icon" href="{!! url('setting/'.Setting::get('favicon')) !!}">

        <title>{{ Theme::get('title') }}</title>

        <link href="{{ Theme::asset()->url('css/flag-icon.css') }}" rel="stylesheet">

        <link href="{{ Theme::asset()->url('css/custom.css') }}" rel="stylesheet">
        {{--{!! Theme::asset()->styles() !!}--}}


        <link href="{{ asset('fitmetixfont/font.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/swiper.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/drawer.css') }}">
        <link href="{{ asset('css/dialog.css') }}" rel="stylesheet">
        <link href="{{ asset('css/snackbar.css') }}" rel="stylesheet">
        <link href="{{asset('vendor/datepicker.css')}}" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/zippy.css') }}" rel="stylesheet">
        <link href="{{ asset('css/menu.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">


        <script type="text/javascript">
        function SP_source() {
          return "{{ url('/') }}/";
        }
        var base_url = "{{ url('/') }}/";
        var asset_url = "{{env('STORAGE_URL')}}";
        var theme_url = "{!! Theme::asset()->url('') !!}";
        var current_username = "{{ Auth::user()->username }}";
        var user_id = "{{ Auth::user()->id }}";

        </script>
        <script src="{{ asset('js/bundle.js') }}" type="text/javascript"></script>
        {{--{!! Theme::asset()->scripts() !!}--}}
        @if(Setting::get('google_analytics') != NULL)
            {!! Setting::get('google_analytics') !!}
        @endif
    </head>
    <body @if(Setting::get('enable_rtl') == 'on') class="direction-rtl" @endif>
    {!! Theme::partial('header') !!}
    {!! Theme::partial('mobileHeader') !!}

        <div class="page-wrapper page-wrapper--admin">
            <div class="container">
                <div class="row">

                    <div class="col-md-3">
                        {!! Theme::partial('admin-leftbar') !!}
                    </div>

                    <div class="col-md-9">
                        {!! Theme::content() !!}
                    </div>

                </div><!-- /row -->
            </div>
        </div><!-- /amin-content -->
        
        {!! Theme::partial('footer') !!}
        <script>
            var pusherConfig = {
                token: "{{ csrf_token() }}",
                PUSHER_KEY: "{{ config('broadcasting.connections.pusher.key') }}"
            };
       </script>
    <script src="{{ asset('js/dialog.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/drawer.js') }}"></script>
    <script src="{{ asset('vendor/datepicker.js') }}" type="text/javascript"></script>
    @if (in_array(!Route::currentRouteName(), ['contact', 'login', 'register']))
        <script src="{{ asset('js/notification.js') }}" type="text/javascript"></script>
        <script src="{{asset('vendor/bootstrap-duration-picker.js')}}" type="text/javascript"></script>
        <script type="text/javascript" src="{{asset('js/caleandar.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/event.js')}}"></script>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '217416572121069',
                    xfbml      : true,
                    version    : 'v2.10',
                    cookie:true,
                    status:true
                });
                FB.AppEvents.logPageView();
            };
            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    @endif
    @yield('js')

        <script type="text/javascript">
            $('#scout_form').on('submit', function (ev) {
              alert("working")
              ev.preventDefault();
              var post_url = 'register';
              var formData = {
                'email' : $('input[name=email]').val(),
                'username' : $('input[name=username]').val(),
                'birthday' : $('input[name=birthday]').val(),
                'gender' : $('#gender').val(),
                'password' : $('input[name=password]').val(),
                '_token': $('input[name=_token]').val()
              };
              var submitBtn = $('#submit');
              submitBtn.prop('disabled',true);
              $.ajax({
                url : post_url,
                type: "post",
                data: formData
              }).done(function(e){ 
                
                if(e.status == 200) {
                  location.reload();
                } else {
                  console.log(e.err_result)
                  var c = 0;
                  $.each(e.err_result, function( index, value ) {
                    var config = {
                      messageText:  value,
                      alignCenter: false,
                      autoClose: true
                    }
                    setTimeout(function(){
                      window.materialSnackBar(config)
                    }, 2000*c)
                    c++;
                  });
                }
              }).always(function(e){
                submitBtn.prop('disabled',false)
              }).fail(function(e){
                var config = {
                  messageText:  'Authentication failed. Please try again!',
                  alignCenter: false,
                  autoClose: true
                }
                window.materialSnackBar(config)
              })
            })
        </script>

        {!! Theme::asset()->container('footer')->scripts() !!}
    </body>
</html>
