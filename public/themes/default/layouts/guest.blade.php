<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf_token" content="{!! csrf_token() !!}"/>
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height"/>
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon-32x32.png') }}/">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon-16x16.png') }}">
        <link rel="manifest" href="{{asset('manifest.json') }}">
        <link rel="mask-icon" href="{{asset('safari-pinned-tab.svg') }}" color="#1e7c82">
        <link rel="icon" type="image/x-icon" href="{!! asset('favicon.ico') !!}">

        <meta property="og:image" content="{{ url('setting/logo.jpg') }}" />
        <meta property="og:title" content="{{ Setting::get('site_title') }}" />
        <meta property="og:type" content="Social Network" />
        <meta name="keywords" content="{{ Setting::get('meta_keywords') }}">
        <meta name="description" content="{{ Setting::get('meta_description') }}">
        <link rel="icon" type="image/x-icon" href="{!! url('setting/'.Setting::get('favicon')) !!}">
        <title>{{ Theme::get('title') }}</title>
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('fitmetixfont/font.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <link href="{{ asset('css/snackbar.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Finger+Paint" rel="stylesheet">
        <style>
            .md-button--icon {
                width: 32px;
                height: 32px
                display: inline-block;
            }
            .btn {
                max-height: 36px;
            }
        </style>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
        function SP_source() {
          return "{{ url('/') }}/";
        }
        var base_url = "{{ url('/') }}/";
        var theme_url = "{!! Theme::asset()->url('') !!}";
        </script>
        @if(Setting::get('google_analytics') != NULL)
            {!! Setting::get('google_analytics') !!}
        @endif
    </head>
    <body @if(Setting::get('enable_rtl') == 'on') class="direction-rtl" @endif>
        {!! Theme::content() !!}
        {!! Theme::partial('footer') !!}
        <script src="{{ asset('js/bundle.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/login.js') }}" type="text/javascript"></script>
        <link href="{{asset('js/owl/dist/assets/owl.carousel.min.css')}}" rel="stylesheet">
        <link href="{{asset('js/owl/dist/assets/owl.theme.default.css')}}" rel="stylesheet">
        <script src="{{asset('js/owl/dist/owl.carousel.min.js')}}" type="text/javascript"></script>
        <script src="{{ asset('vendor/datepicker.js') }}" type="text/javascript"></script>
        <link href="{{asset('vendor/datepicker.css')}}" rel="stylesheet">
        <script>
            $(document).ready(function () {
                $('#slider-owl').owlCarousel({
                    loop:true,
                    margin:0,
                    nav:false,
                    items: 1,
                    dots: false,
                    autoplay:true,
                    animateOut: 'fadeOut'
                });

                $( "#datepicker1" ).datepicker({
                    format: 'mm/dd/yyyy',
                    ignoreReadonly: true,
                    allowInputToggle: true
                });
                $( "#datepicker2" ).datepicker({
                    format: 'mm/dd/yyyy',
                    ignoreReadonly: true,
                    allowInputToggle: true
                });
            })
        </script>
    </body>
</html>
