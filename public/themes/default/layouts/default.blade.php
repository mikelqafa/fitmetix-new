<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="{!! csrf_token() !!}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height"/>
    <meta name="keywords" content="{{ Setting::get('meta_keywords') }}">
    <meta name="description" content="{{ Setting::get('meta_description') }}">
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{!! url('setting/'.Setting::get('favicon')) !!}">
    <meta content="{{ url('/') }}" property="og:url"/>
    <meta content="{!! url('setting/'.Setting::get('logo')) !!}" property="og:image"/>
    <meta content="{{ Setting::get('meta_description') }}" property="og:description"/>
    <meta content="{{ Setting::get('site_name') }}" property="og:title"/>
    <meta content="website" property="og:type"/>
    <meta content="{{ Setting::get('site_name') }}" property="og:site_name"/>
    <title>{{ Theme::get('title') }}</title>
    <link href="{{ asset('fitmetixfont/font.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ Theme::asset()->url('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="{{ asset('css/prakash.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/zippy.css') }}" rel="stylesheet">
    <script type="text/javascript">
        function SP_source() {
            return "{{ url('/') }}/";
        }
        var base_url = "{{ url('/') }}/";
        var theme_url = "{!! Theme::asset()->url('') !!}";
        var current_username = "{{ Auth::user()->username }}";
    </script>
    @if(Setting::get('google_analytics') != NULL)
        {!! Setting::get('google_analytics') !!}
    @endif
    <style>
        .navbar.socialite{
            z-index: 10 !important;
        }
        .ft-dialog {
            background-color: rgba(0,0,0,.5);
            bottom: 0;
            justify-content: space-between;
            left: 0;
            overflow-y: auto;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 12;
            display: none;
        }
        .ft-dialog--open {
            display: block;
        }
        .ft-dialog__inner-layer {
            bottom: 0;
            left: 0;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 11
        }
        .ft-dialog__wrapper{
            padding: 0 40px;
            position: relative;
            pointer-events: none;
            -webkit-transform: translate3d(0,0,0);
            transform: translate3d(0,0,0);
            display: flex;
            min-height: 100%;
            overflow: auto;
            width: auto;
            z-index: 11;
        }
        .ft-dialog__surface {
            align-items: center;
            margin: auto;
            max-width: 935px;
            pointer-events: auto;
            width: 100%;
        }
        .ft-dialog-option{
            width: 100%;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .ft-dialog-option__item {
            background: #fff;
            border: none;
            color: #262626;
            cursor: pointer;
            font-size: 16px;
            font-weight: 400;
            line-height: 50px;
            margin: 0;
            overflow: hidden;
            border-radius: 0;
            padding: 0 16px;
            text-align: center;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
            max-width: 510px;
            border-bottom: 1px solid #dbdbdb;
        }
        .ft-dialog-option__item:hover,
        .ft-dialog-option__item:focus,
        .ft-dialog-option__item:active{
            background-color: #efefef;
        }
        .ft-dialog__btn{
            position: fixed;
            top:15px;
            right:15px;
            font-size: 24px;
            z-index: 14;
            color: #FFF;
            cursor: pointer;
        }
        .ft-dialog__btn:hover,
        .ft-dialog__btn:focus {
            color: #FFF;
        }
        .ft-dialog__btn .icon-close {
            font-size: 36px;
        }
    </style>
    <style>
        .meta-font {
            font-size: 12px;
        }
        .layout-m-l-0 {
            margin-left: 7px;
        }
        a {
            color: #333;
        }
        .ft-custom .no-float {
            float: none !important;
            clear: both;
        }
        .fm-nav__item {
            line-height: 60px;
            padding: 0 16px;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            background-color: rgba(0,0,0,0);
            transition: all 0.2s ease;
        }
        .ft-menu .ft-menu__item {
            flex-direction: row;
            height: 40px;
            align-content: center;
            justify-content: flex-start;
            line-height: 40px;
            text-align: left;
            border-radius: 0;
            margin: auto 0;
            font-size: 15px;
            font-weight: 400;
            padding: 0 15px !important;
            text-transform: capitalize;
            width: 100%;
            position: relative;
        }
        .ft-menu__item.btn {
            background-color: transparent;
        }
        .ft-menu__item.btn:hover,
        .ft-menu__item.btn:focus {
            background-color: #f5f5f5;
            color: #262626;
        }

        .ft-menu__item i {
            position: absolute;
            top:0;
            left:5px;
            transform: translate(50%, 50%);
            height: 24px;
            width: 24px;
            margin-top: -6px;
            margin-left: -8px;
            line-height: 24px;
            text-align: center;
        }

        .ft-menu .ft-menu__item--icon {
            padding-left: 40px !important;
        }
        .fm-nav .has-hover-effect:hover,
        .fm-nav .has-hover-effect.open  {
            background-color: rgba(0,0,0,0.03) !important;
        }
        .footer-description {
            padding-top: 0 !important;
        }

        .panel-post .panel-body .text-wrapper p {
            color: #000 !important;
        }

        @media ( max-width: 500px) {
            .post-image-holder.single-image {
                margin-left: -15px;
                margin-right: -15px
            }
        }

        .panel-post .panel-heading .post-author .user-avatar img {
            border-radius: 50%;
        }

        .nav > li > a > img {
            border-radius: 50%;
        }

        @media (max-width: 660px) {
            .timeline-cover-section .timeline-cover img {
                width: 100% !important;
            }
        }

        @media (max-width: 1660px) {
            .chat-list .left-sidebar.socialite {
                margin-right: -240px !important;
                display: none;
            }
        }

        .actions-count {
            text-align: center !important;
        }

        @media (max-width: 768px) {
            .nav-justified > li {
                display: table-cell;
                width: 1%;
            }

            .nav-justified > li > a {
                border-bottom: 1px solid #ddd !important;
                border-radius: 4px 4px 0 0 !important;
                margin-bottom: 0 !important;
            }
        }

        .navbar-collapse.collapse.in {
            height: 100vh !important;
        }
    </style>
    <style>
        .navbar.socialite {
            border-bottom: 1px solid #1e7c82;
        }

        .panel-default .panel-heading.no-bg {
            border: none !important;
        }

        .panel-post .panel-body {
            border-right: none;
            border-left: none;
        }

        .panel-post .panel-footer.socialite {
            border: none !important;
        }

        .panel-post .panel-body .text-wrapper .post-image-holder {
            margin-top: 0;
        }

        .panel-post .panel-body {
            padding-bottom: 15px;
            padding-top: 0;
        }

        .md-layout-spacer {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .md-layout-flex {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1
        }

        @media screen\0 {
            .md-layout-flex .flex {
                -webkit-box-flex: 1;
                -ms-flex: 1 1 0%;
                flex: 1 1 0%
            }
        }

        .md-layout-flex--grow {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 100%;
            flex: 1 1 100%
        }

        .md-layout-flex--initial {
            -webkit-box-flex: 0;
            -ms-flex: 0 1 auto;
            flex: 0 1 auto
        }

        .md-layout-flex--auto {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto
        }

        .md-layout-flex--none {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 auto;
            flex: 0 0 auto
        }

        .md-layout-flex--noshrink {
            -webkit-box-flex: 1;
            -ms-flex: 1 0 auto;
            flex: 1 0 auto
        }

        .md-layout-flex--nogrow {
            -webkit-box-flex: 0;
            -ms-flex: 0 1 auto;
            flex: 0 1 auto
        }

        .md-align {
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start;
            -ms-flex-line-pack: stretch;
            align-content: stretch;
            -webkit-box-align: stretch;
            -ms-flex-align: stretch;
            align-items: stretch;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .md-align--start, .md-align--start-center, .md-align--start-end, .md-align--start-start, .md-align--start-stretch {
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: start
        }

        .md-align--center, .md-align--center-center, .md-align--center-end, .md-align--center-start, .md-align--center-stretch {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .md-align--end, .md-align--end-center, .md-align--end-end, .md-align--end-start, .md-align--end-stretch {
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end
        }

        .md-align--space-around, .md-align--space-around-center, .md-align--space-around-end, .md-align--space-around-start, .md-align--space-around-stretch {
            -ms-flex-pack: distribute;
            justify-content: space-around
        }

        .md-align--space-between, .md-align--space-between-center, .md-align--space-between-end, .md-align--space-between-start, .md-align--space-between-stretch {
            -ms-flex-pack: justify;
            -webkit-box-pack: justify;
            justify-content: space-between
        }

        .md-align--center-start, .md-align--end-start, .md-align--space-around-start, .md-align--space-between-start, .md-align--start-start {
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            -ms-flex-line-pack: start;
            align-content: flex-start
        }

        .md-align--center-center, .md-align--end-center, .md-align--space-around-center, .md-align--space-between-center, .md-align--start-center {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-line-pack: center;
            align-content: center;
            max-width: 100%
        }

        .md-align--center-end, .md-align--end-end, .md-align--space-around-end, .md-align--space-between-end, .md-align--start-end {
            -webkit-box-align: end;
            -ms-flex-align: end;
            align-items: flex-end;
            -ms-flex-line-pack: end;
            align-content: flex-end
        }

        .md-align--center-stretch, .md-align--end-stretch, .md-align--space-around-stretch, .md-align--space-between-stretch, .md-align--start-stretch {
            -webkit-box-align: stretch;
            -ms-flex-align: stretch;
            align-items: stretch;
            -ms-flex-line-pack: stretch;
            align-content: stretch
        }

        .md-align--self-stretch {
            -ms-flex-item-align: stretch;
            -ms-grid-row-align: stretch;
            align-self: stretch;
            -webkit-align-self: stretch
        }

        .md-align--self-end {
            -ms-flex-item-align: end;
            align-self: flex-end;
            -webkit-align-self: flex-end
        }

        .md-align--self-start {
            -ms-flex-item-align: start;
            align-self: flex-start;
            -webkit-align-self: flex-start
        }

        .md-layout {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .md-layout--column {
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column
        }

        .md-layout--row {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row
        }

        .md-layout--wrap {
            -ms-flex-wrap: wrap;
            flex-wrap: wrap
        }

        .md-layout--nowrap {
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap
        }

        .md-layout--fill {
            margin: 0;
            width: 100%;
            min-height: 100%;
            height: 100%
        }
    </style>
    <style>
        .user-avatar.lg-loadable {
            height: 40px;
            width: 40px;
        }

        .user-meta-info {
            height: 8px;
            width: 100px;
            margin: 4px 8px;
        }

        .user-meta-info--sm {
            margin-top: 4px;
            width: 70px;
        }

        .lg-loadable--text {
            height: 10px;
            margin-bottom: 16px;
            width: 300px;
            min-width: 80%;
            max-width: 90%;
        }

        .lg-loadable--text--lg {
            width: 280px;
            min-width: 90%;
        }

        .lg-loadable--text--sm {
            width: 280px;
            min-width: 60%;
        }

        @-webkit-keyframes loading-color-pulse {
            0% {
                background-color: #e3e3e3
            }

            50% {
                background-color: #f3f3f3
            }

            to {
                background-color: #e3e3e3
            }
        }

        @keyframes loading-color-pulse {
            0% {
                background-color: #e3e3e3
            }

            50% {
                background-color: #f3f3f3
            }

            to {
                background-color: #e3e3e3
            }
        }

        .lg-loading-skeleton {
            pointer-events: none !important;
            -webkit-transition-duration: 500ms;
            transition-duration: 500ms;
            -webkit-transition-property: background-color, border-color, color, cursor, margin-bottom, visibility;
            transition-property: background-color, border-color, color, cursor, margin-bottom, visibility;
            -webkit-transition-timing-function: ease;
            transition-timing-function: ease
        }

        .lg-loading-skeleton .lg-loadable {
            -webkit-animation-name: loading-color-pulse;
            animation-name: loading-color-pulse;
            -webkit-animation-duration: 1500ms;
            animation-duration: 1500ms;
            -webkit-animation-timing-function: ease-in-out;
            animation-timing-function: ease-in-out;
            -webkit-animation-iteration-count: infinite;
            animation-iteration-count: infinite;
            -webkit-animation-play-state: running;
            animation-play-state: running;
            background-color: #e3e3e3;
            background-image: none !important;
            border-color: transparent !important;
            color: transparent !important;
            cursor: progress !important
        }

        .lg-loading-skeleton .lg-loadable * {
            visibility: hidden !important
        }

        .lg-loading-skeleton .lg-invisible-load {
            visibility: hidden !important
        }

    </style>
</head>
<body @if(Setting::get('enable_rtl') == 'on') class="direction-rtl" @endif>

<div class="padding-10">
    {!! Theme::partial('header') !!}
    {!! Theme::partial('mobileHeader') !!}
</div>
<div class="main-content">
    {!! Theme::content() !!}
    <app-dialog-option></app-dialog-option>
</div>

{!! Theme::partial('right-sidebar') !!}

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
<script src="{{ asset('js/bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@if (in_array(!Route::currentRouteName(), ['contact', 'login', 'register']))
    <script src="{{ asset('js/notification.js') }}" type="text/javascript"></script>
@endif
<form action="{{ url('/logout') }}" method="post" style="opacity:.1;position:fixed; bottom: 0; left: 0; z-index: 11">
    {{ csrf_field() }}

    <button type="submit" class="btn-logout">
        <i class="fa fa-unlock" aria-hidden="true"></i>{{ trans('common.logout') }}
    </button>
</form>
</body>
</html>
