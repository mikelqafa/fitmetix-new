<!DOCTYPE html>
<html>
<head>

    <meta charset="utf8mb4">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="{!! csrf_token() !!}"/>
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#1e7c82">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <meta name="theme-color" content="#1e7c82">
    <meta name="keywords" content="{{ Setting::get('meta_keywords') }}">
    <meta name="description" content="{{ Setting::get('meta_description') }}">
    <meta name="_token" content="{{ csrf_token() }}">

    <meta content="{{ Theme::get('meta_url') }}" property="og:url"/>
    <meta content="217416572121069" property="fb:app_id"/>
    <meta content="{{ Theme::get('meta_image') }}" property="og:image"/>
    <meta content="{{ Theme::get('meta_description') }}" property="og:description"/>
    <meta content="{{ Theme::get('meta_site_title') }}" property="og:title"/>
    <meta content="website" property="og:type"/>
    <meta content="Fitmetix" property="og:site_name"/>

    <title>{{ Theme::get('title') }}</title>
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
        function getLang() {
            return "{{ config('app.locale') }}";
        }
        var base_url = "{{ url('/') }}/";
        var asset_url = "{{env('STORAGE_URL')}}";
        var theme_url = "{!! Theme::asset()->url('') !!}";
        var current_username = "{{ Auth::user()->username }}";
        var user_id = "{{ Auth::user()->id }}";
        var user_gender = "{{ Auth::user()->gender }}";
    </script>
    <script src="{{ asset('js/bundle.js') }}" type="text/javascript"></script>
    @if(Setting::get('google_analytics') != NULL)
        {!! Setting::get('google_analytics') !!}
    @endif
    <style>
        .blueimp-gallery > .slides > .slide-loading {
            background: url({{asset('imgaes/loading.gif')}}) center no-repeat;
            background-size: 64px 64px;
        }
    </style>
</head>
<body data-theme="{{ Session::get('color_code') }}" id="fitmetix-app" @if(Setting::get('enable_rtl') == 'on') class="direction-rtl" @endif>
<audio style="display:none;" id="message-audio" controls="controls">
    <source type="audio/mpeg" src="{{asset('themes/default/assets/sounds/message.mp3')}}">
    <source type="audio/ogg" src="{{asset('themes/default/assets/sounds/message.ogg')}}">
    <embed hidden="true" autostart="true" loop="false" src="{{asset('themes/default/assets/sounds/notification.mp3')}}" />
</audio>
<button id="message-audio-btn" class="hidden"></button>
<button id="notification-audio-btn" class="hidden"></button>
<audio style="display:none;" id="notification-audio" controls="controls">
    <source type="audio/mpeg" src="{{asset('themes/default/assets/sounds/notification.mp3')}}">
    <source type="audio/ogg" src="{{asset('themes/default/assets/sounds/notification.ogg')}}">
    <embed hidden="true" autostart="true" loop="false" src="{{asset('themes/default/assets/sounds/notification.mp3')}}" />
</audio>
<script>
    var msgX_ = document.getElementById("message-audio");
    var notX_ = document.getElementById("notification-audio");
    msgX_.volume = 0;
    notX_.volume = 0;
</script>

<div id="app-alert" class="md-dialog md-dialog--center md-dialog--confirm" aria-hidden="false">
    <div class="md-dialog__wrapper">
        <div class="md-dialog__shadow"></div>
        <div class="md-dialog__surface">
            <div class="md-dialog__body">
                <div class="app-alert__text">

                </div>
            </div>
            <footer class="md-dialog__footer">
                <button data-action="affirmative" class="md-dialog__action md-button md-button--compact">OK
                </button>
            </footer>
        </div>
    </div>
</div>

<div class="padding-10">
    {!! Theme::partial('header') !!}
    {!! Theme::partial('mobileHeader') !!}
</div>
<div class="main-content">
    {!! Theme::content() !!}
    <app-dialog-option></app-dialog-option>
</div>

{!! Theme::partial('footer') !!}

<script>
    @if(Config::get('app.debug'))
    // Pusher.logToConsole = true;
            @endif
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
<script type="text/javascript">
    $(function() {
        $('#drawer-1').MaterialDrawer({
            show: false,
            permanent: true
        });
        $('.ft-card_drawer-trigger').click(function(e) {
            if(!$('body').hasClass('is-drawer-open')) {
                $('#drawer-1').MaterialDrawer('toggle');
                $('#drawer-1').find('.ft-card').addClass('hidden');
                $('#drawer-1').find( '.ft-card[data-index="'+ $(this).attr('data-index') +'"]').removeClass('hidden')
                $('.dropdown-toggle').dropdown()
            } else {
                e.preventDefault();
                $('#drawer-1').find('.ft-card').addClass('hidden');
                $('#drawer-1').find( '.ft-card[data-index="'+ $(this).attr('data-index') +'"]').removeClass('hidden')
            }
        })

        $('#duration').durationPicker();
        
    })
</script>
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
                autoLogAppEvents: true,
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
<script>
    function FacebookInviteFriends() {
        FB.ui({
            display: 'iframe',
            method: 'send',
            link: 'http://www.fitmetix.com'
        },function(response) {
            console.log('facebook', response)
        });
    }
</script>
</body>
</html>
