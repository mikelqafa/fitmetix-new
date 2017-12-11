<div class="icon icon-like"></div>
<span class="unread-notification" v-bind:class="{ 'is-visible': isShowUN }"></span>
<div class="dropdown-menu">
    <div class="dropdown-menu-header">
        <span class="side-left">{{ trans('common.notifications') }}</span>
        <a v-if="unreadNotifications > 0" class="side-right" href="#"
           @click.prevent="markNotificationsRead">{{ trans('messages.mark_all_read') }}</a>
        <div class="clearfix"></div>
    </div>
    @if(Auth::user()->notifications()->count() > 0)
        <ul class="list-unstyled dropdown-messages-list scrollable"
            data-type="notifications">
            <li class="inbox-message"
                v-bind:class="[ !notification.seen ? 'active' : '' ]"
                v-for="notification in notifications.data">
                {{--TODO--}}
                {{--<a href="{{ url(Auth::user()->username.'/notification/') }}/@{{ notification.id }}">--}}
                <a href="{{ url(Auth::user()->username.'/notification/') }}">
                    <div class="media">
                        <div class="media-left">
                            <img class="media-object img-icon"
                                 v-bind:src="notification.notified_from.avatar"
                                 alt="images">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <span class="notification-text"> @{{ notification.description }} </span>
                                            <span class="message-time">
                                                <span class="notification-type"><i class="fa fa-user"
                                                                                   aria-hidden="true"></i></span>
                                                <time class="timeago"
                                                      :datetime="notification.created_at+ '00:00'"
                                                      :title="notification.created_at + '00:00'">
                                                    @{{ notification.created_at }}+00:00
                                                </time>
                                            </span>
                            </h4>
                        </div>
                    </div>
                </a>
            </li>
            <li v-if="notificationsLoading" class="dropdown-loading">
                <i class="fa fa-spin fa-spinner"></i>
            </li>
        </ul>
    @else
        <div class="no-messages">
            <i class="fa fa-bell-slash-o" aria-hidden="true"></i>
            <p>{{ trans('messages.no_notifications') }}</p>
        </div>
    @endif
    <div class="dropdown-menu-footer"><br>
        <a href="{{ url('allnotifications') }}">{{ trans('common.see_all') }}</a>
    </div>
</div>


{{--new one --}}

<div class="dropdown-menu">
    <div class="dropdown-menu-header">
        <span class="side-left">{{ ntCommonMessages }}</span>
        <div class="clearfix"></div>
    </div>
    <div class="no-messages hidden">
        <i class="fa fa-commenting-o" aria-hidden="true"></i>
        <p>{{ ntMessageNo }}</p>
    </div>
    <ul class="list-unstyled dropdown-messages-list scrollable" data-type="messages">
        <li class="inbox-message" v-for="conversation in conversations.data">
            <a href="#" :data-user-id="conversation.user.id" onclick="chatBoxes.sendMessageOnClick(this)">
                <div class="media">
                    <div class="media-left">
                        <img class="media-object img-icon" v-bind:src="conversation.user.avatar" alt="images">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <span class="message-heading">@{{ conversation.user.name }}</span>
                            <span class="online-status hidden"></span>
                            <!--TODO timeago -->
                        </h4>
                        <p class="message-text">
                            <!--@{{ conversation.lastMessage.body }}-->
                        </p>
                    </div>
                </div>
            </a>
        </li>
        <li v-if="conversationsLoading" class="dropdown-loading">
            <div class="loader">
                <div class="spinner spinner--small"></div>
            </div>
        </li>
    </ul>
    <div class="dropdown-menu-footer">
        <a href="url.messages">{{ ntSeeAll }}</a>
    </div>
</div>


<div class="panel-heading no-bg">
    <div class="post-author">
        <div class="post-options">
            <ul class="list-inline no-margin">
                <li class="dropdown"><a href="#" class="dropdown-togle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="icon icon-options"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="main-link">
                            <a href="#" data-post-id="108" class="notify-user unnotify">
                                <i class="fa  fa-bell-slash" aria-hidden="true"></i>Stop notifications
                                <span class="small-text">You will not be notified</span>
                            </a>
                        </li>
                        <li class="main-link hidden">
                            <a href="#" data-post-id="108" class="notify-user notify">
                                <i class="fa fa-bell" aria-hidden="true"></i>Get notifications
                                <span class="small-text">You will be notified for likes,comments and shares</span>
                            </a>
                        </li>

                        <li class="main-link">
                            <a href="#" data-post-id="108" class="edit-post">
                                <i class="fa fa-edit" aria-hidden="true"></i>Edit
                                <span class="small-text">You can edit your post</span>
                            </a>
                        </li>

                        <li class="main-link">
                            <a href="#" class="delete-post" data-post-id="108">
                                <i class="icon icon-delete" aria-hidden="true"></i>Delete
                                <span class="small-text">This post will be deleted</span>
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li class="main-link">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Flocalhost%2Ffitmetix%2Fpublic%2Fshare-post%2F108" class="fb-xfbml-parse-ignore" target="_blank">
                                <i class="fa fa-facebook-square"></i>Facebook Share
                            </a>
                        </li>

                        <li class="main-link">
                            <a href="https://twitter.com/intent/tweet?text=http://localhost/fitmetix/public/share-post/108" target="_blank">
                                <i class="fa fa-twitter-square"></i>Twitter Tweet
                            </a>
                        </li>

                        <li class="main-link">
                            <a href="#" data-toggle="modal" data-target="#myModal">
                                <i class="icon icon-share-alt"></i>Embed post
                            </a>
                        </li>

                    </ul>

                </li>

            </ul>
        </div>
        <div class="user-avatar">
            <a href="http://localhost/fitmetix/public/mikele"><img src="http://localhost/fitmetix/public/user/avatar/2017-10-22-14-07-04athletebookprofilepage.png" alt="Mikel" title="Mikel"></a>
        </div>
        <div class="user-post-details">
            <ul class="list-unstyled no-margin">
                <li>
                    <a href="http://localhost/fitmetix/public/mikele" title="@mikele" data-toggle="tooltip" data-placement="top" class="user-name user ft-user-name">
                        mikele
                    </a>
                    <div class="small-text">
                    </div>
                </li>
                <li>

                    <time class="post-time timeago" datetime="2017-11-17 12:55:13+00:00" title="2017-11-17 12:55:13+00:00">
                        2017-11-17 12:55:13+00:00
                    </time>


                    at <span class="post-place">
              <a target="_blank" href="http://localhost/fitmetix/public/get-location/Kolkata, West Bengal, India">
                  <i class="fa fa-map-marker"></i> Kolkata, West Bengal, India
              </a>
              </span></li>
            </ul>
        </div>
    </div>
</div>




<template v-if="postItem !== null">
    <image-theater-surface  :post-index="theaterPostItem.postIndex" :post-img="postItem.images" :post-index="theaterPostItem.postIndex"></image-theater-surface>
</template>

computed: {
...mapGetters({
theaterPostItem: 'theaterPostItem'
}),
postItem: function () {
return this.theaterPostItem.postIndex !== undefined ? this.$store.state.postItemList[this.theaterPostItem.postIndex] : null
}
}


<image-theater-surface   :post-index="theaterPostItem.postIndex"></image-theater-surface>

<template v-if="isMultiple">
            <swiper :options="swiperOption" class="deal-card-slider">
                <swiper-slide :key="imageIndex" v-for="(image, imageIndex) in images">
                    <a href="javascript:;" :key="imageIndex" class="item__background"
                       :style="{ backgroundImage: 'url(' + image + ')' }"></a>
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
            </swiper>
        </template>
        <template v-else="">
            <div class="image-responsive item__background--post img-viewer" v-for="(image, imageIndex) in images"
                 :style="{ backgroundImage: 'url(' + image + ')', height: '250px' }"></div>
        </template>



<html lang="es"><head><style type="text/css">.pac-container{background-color:#fff;position:absolute!important;z-index:1000;border-radius:2px;border-top:1px solid #d9d9d9;font-family:Arial,sans-serif;box-shadow:0 2px 6px rgba(0,0,0,0.3);-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;overflow:hidden}.pac-logo:after{content:"";padding:1px 1px 1px 0;height:16px;text-align:right;display:block;background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3.png);background-position:right;background-repeat:no-repeat;background-size:120px 14px}.hdpi.pac-logo:after{background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3_hdpi.png)}.pac-item{cursor:default;padding:0 4px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;line-height:30px;text-align:left;border-top:1px solid #e6e6e6;font-size:11px;color:#999}.pac-item:hover{background-color:#fafafa}.pac-item-selected,.pac-item-selected:hover{background-color:#ebf2fe}.pac-matched{font-weight:700}.pac-item-query{font-size:13px;padding-right:3px;color:#000}.pac-icon{width:15px;height:20px;margin-right:7px;margin-top:6px;display:inline-block;vertical-align:top;background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/autocomplete-icons.png);background-size:34px}.hdpi .pac-icon{background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/autocomplete-icons_hdpi.png)}.pac-icon-search{background-position:-1px -1px}.pac-item-selected .pac-icon-search{background-position:-18px -1px}.pac-icon-marker{background-position:-1px -161px}.pac-item-selected .pac-icon-marker{background-position:-18px -161px}.pac-placeholder{color:gray}
    </style><style type="text/css">.gm-style-pbc{transition:opacity ease-in-out;background-color:rgba(0,0,0,0.45);text-align:center}.gm-style-pbt{font-size:22px;color:white;font-family:Roboto,Arial,sans-serif;position:relative;margin:0;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%)}
    </style>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="7vtbOkv6q5q16wL6wTCbPzPVt4Lhly4UOpVimcjv">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">


    <!-- Add to home screen for Android and modern mobile browsers -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#1E7C82">

    <!-- Add to home screen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="apple-mobile-web-app-title" content="Fitmetix">
    <link rel="apple-touch-icon" href="/setting/logo.png">
    <!-- Add to home screen for Windows -->
    <meta name="msapplication-TileImage" content="/setting/logo.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">


    <meta name="keywords" content="fitmetix">
    <meta name="description" content="Fitmetix is the FIRST Social networking script developed for Health!">
    <meta name="_token" content="7vtbOkv6q5q16wL6wTCbPzPVt4Lhly4UOpVimcjv">
    <link rel="icon" type="image/x-icon" href="http://localhost/fitmetix/public/setting/favicon.jpg">
    <meta content="http://localhost/fitmetix/public" property="og:url">
    <meta content="http://localhost/fitmetix/public/setting/logo.png" property="og:image">
    <meta content="Fitmetix is the FIRST Social networking script developed for Health!" property="og:description">
    <meta content="Fitmetix" property="og:title">
    <meta content="website" property="og:type">
    <meta content="Fitmetix" property="og:site_name">
    <title>Mikel | Fitmetix | Fitmetix Network</title>
    <link href="http://localhost/fitmetix/public/fitmetixfont/font.css" rel="stylesheet">
    <link href="http://localhost/fitmetix/public/css/bootstrap.css" rel="stylesheet">
    <link href="http://localhost/fitmetix/public/themes/default/assets/css/custom.css" rel="stylesheet">
    <link href="http://localhost/fitmetix/public/css/swiper.css" rel="stylesheet">
    <link href="http://localhost/fitmetix/public/css/dialog.css" rel="stylesheet">
    <link href="http://localhost/fitmetix/public/css/snackbar.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="http://localhost/fitmetix/public/css/prakash.css" rel="stylesheet">
    <link href="http://localhost/fitmetix/public/css/app.css" rel="stylesheet">
    <link href="http://localhost/fitmetix/public/css/zippy.css" rel="stylesheet">
    <script type="text/javascript">
        function SP_source() {
            return "http://localhost/fitmetix/public/";
        }
        var base_url = "http://localhost/fitmetix/public/";
        var asset_url = "http://localhost/fitmetix/storage/";
        var theme_url = "http://localhost/fitmetix/public/themes/default/assets/";
        var current_username = "mikele";
    </script>
    <style>
        .ft-btn-primary, .ft-btn-primary:hover, .ft-btn-primary:focus {
            background-color: #1E7C82;
            color: #fff;
        }
        .timeline-posts__item,.panel-create {
            max-width: 600px;
            margin-left:auto;
            margin-right:auto;
        }
        body.is-dialog-open {
            padding-right: 0 !important;
        }
        .blueimp-gallery > .slides > .slide-loading {
            background: url(http://localhost/fitmetix/public/imgaes/loading.gif) center no-repeat;
            background-size: 64px 64px;
        }
        .form-left .form-control {
            min-width: 0;
        }
        .selectize-input {
            min-width: 200px;
        }
        .btn.switch-language {
            display: none !important;
        }
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

        @media  screen\0 {
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

        @keyframes  loading-color-pulse {
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
    <style>
        body.is-dialog-open{
            transition-property: none !important;
        }
        .md-dialog--md .md-dialog__surface{
            max-width: 600px;
            width: 90%;
        }
        .md-dialog__surface{
            margin-top: 16px;
            margin-bottom: 16px;
        }
        .md-dialog--md .md-dialog__body--scrollable{
            max-height: 240px;
        }
        .md-dialog{
            overflow-y: auto;
        }
        .md-dialog__wrapper {
            position: relative;
            min-height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            flex-direction: column;
        }
    </style>
    <style type="text/css">/**
 * Swiper 3.4.2
 * Most modern mobile touch slider and framework with hardware accelerated transitions
 *
 * http://www.idangero.us/swiper/
 *
 * Copyright 2017, Vladimir Kharlampidi
 * The iDangero.us
 * http://www.idangero.us/
 *
 * Licensed under MIT
 *
 * Released on: March 10, 2017
 */
        .swiper-container {
            margin-left: auto;
            margin-right: auto;
            position: relative;
            overflow: hidden;
            /* Fix of Webkit flickering */
            z-index: 1;
        }
        .swiper-container-no-flexbox .swiper-slide {
            float: left;
        }
        .swiper-container-vertical > .swiper-wrapper {
            -webkit-box-orient: vertical;
            -moz-box-orient: vertical;
            -ms-flex-direction: column;
            -webkit-flex-direction: column;
            flex-direction: column;
        }
        .swiper-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            z-index: 1;
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-transition-property: -webkit-transform;
            -moz-transition-property: -moz-transform;
            -o-transition-property: -o-transform;
            -ms-transition-property: -ms-transform;
            transition-property: transform;
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
        }
        .swiper-container-android .swiper-slide,
        .swiper-wrapper {
            -webkit-transform: translate3d(0px, 0, 0);
            -moz-transform: translate3d(0px, 0, 0);
            -o-transform: translate(0px, 0px);
            -ms-transform: translate3d(0px, 0, 0);
            transform: translate3d(0px, 0, 0);
        }
        .swiper-container-multirow > .swiper-wrapper {
            -webkit-box-lines: multiple;
            -moz-box-lines: multiple;
            -ms-flex-wrap: wrap;
            -webkit-flex-wrap: wrap;
            flex-wrap: wrap;
        }
        .swiper-container-free-mode > .swiper-wrapper {
            -webkit-transition-timing-function: ease-out;
            -moz-transition-timing-function: ease-out;
            -ms-transition-timing-function: ease-out;
            -o-transition-timing-function: ease-out;
            transition-timing-function: ease-out;
            margin: 0 auto;
        }
        .swiper-slide {
            -webkit-flex-shrink: 0;
            -ms-flex: 0 0 auto;
            flex-shrink: 0;
            width: 100%;
            height: 100%;
            position: relative;
        }
        /* Auto Height */
        .swiper-container-autoheight,
        .swiper-container-autoheight .swiper-slide {
            height: auto;
        }
        .swiper-container-autoheight .swiper-wrapper {
            -webkit-box-align: start;
            -ms-flex-align: start;
            -webkit-align-items: flex-start;
            align-items: flex-start;
            -webkit-transition-property: -webkit-transform, height;
            -moz-transition-property: -moz-transform;
            -o-transition-property: -o-transform;
            -ms-transition-property: -ms-transform;
            transition-property: transform, height;
        }
        /* a11y */
        .swiper-container .swiper-notification {
            position: absolute;
            left: 0;
            top: 0;
            pointer-events: none;
            opacity: 0;
            z-index: -1000;
        }
        /* IE10 Windows Phone 8 Fixes */
        .swiper-wp8-horizontal {
            -ms-touch-action: pan-y;
            touch-action: pan-y;
        }
        .swiper-wp8-vertical {
            -ms-touch-action: pan-x;
            touch-action: pan-x;
        }
        /* Arrows */
        .swiper-button-prev,
        .swiper-button-next {
            position: absolute;
            top: 50%;
            width: 27px;
            height: 44px;
            margin-top: -22px;
            z-index: 10;
            cursor: pointer;
            -moz-background-size: 27px 44px;
            -webkit-background-size: 27px 44px;
            background-size: 27px 44px;
            background-position: center;
            background-repeat: no-repeat;
        }
        .swiper-button-prev.swiper-button-disabled,
        .swiper-button-next.swiper-button-disabled {
            opacity: 0.35;
            cursor: auto;
            pointer-events: none;
        }
        .swiper-button-prev,
        .swiper-container-rtl .swiper-button-next {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M0%2C22L22%2C0l2.1%2C2.1L4.2%2C22l19.9%2C19.9L22%2C44L0%2C22L0%2C22L0%2C22z'%20fill%3D'%23007aff'%2F%3E%3C%2Fsvg%3E");
            left: 10px;
            right: auto;
        }
        .swiper-button-prev.swiper-button-black,
        .swiper-container-rtl .swiper-button-next.swiper-button-black {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M0%2C22L22%2C0l2.1%2C2.1L4.2%2C22l19.9%2C19.9L22%2C44L0%2C22L0%2C22L0%2C22z'%20fill%3D'%23000000'%2F%3E%3C%2Fsvg%3E");
        }
        .swiper-button-prev.swiper-button-white,
        .swiper-container-rtl .swiper-button-next.swiper-button-white {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M0%2C22L22%2C0l2.1%2C2.1L4.2%2C22l19.9%2C19.9L22%2C44L0%2C22L0%2C22L0%2C22z'%20fill%3D'%23ffffff'%2F%3E%3C%2Fsvg%3E");
        }
        .swiper-button-next,
        .swiper-container-rtl .swiper-button-prev {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M27%2C22L27%2C22L5%2C44l-2.1-2.1L22.8%2C22L2.9%2C2.1L5%2C0L27%2C22L27%2C22z'%20fill%3D'%23007aff'%2F%3E%3C%2Fsvg%3E");
            right: 10px;
            left: auto;
        }
        .swiper-button-next.swiper-button-black,
        .swiper-container-rtl .swiper-button-prev.swiper-button-black {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M27%2C22L27%2C22L5%2C44l-2.1-2.1L22.8%2C22L2.9%2C2.1L5%2C0L27%2C22L27%2C22z'%20fill%3D'%23000000'%2F%3E%3C%2Fsvg%3E");
        }
        .swiper-button-next.swiper-button-white,
        .swiper-container-rtl .swiper-button-prev.swiper-button-white {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M27%2C22L27%2C22L5%2C44l-2.1-2.1L22.8%2C22L2.9%2C2.1L5%2C0L27%2C22L27%2C22z'%20fill%3D'%23ffffff'%2F%3E%3C%2Fsvg%3E");
        }
        /* Pagination Styles */
        .swiper-pagination {
            position: absolute;
            text-align: center;
            -webkit-transition: 300ms;
            -moz-transition: 300ms;
            -o-transition: 300ms;
            transition: 300ms;
            -webkit-transform: translate3d(0, 0, 0);
            -ms-transform: translate3d(0, 0, 0);
            -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            z-index: 10;
        }
        .swiper-pagination.swiper-pagination-hidden {
            opacity: 0;
        }
        /* Common Styles */
        .swiper-pagination-fraction,
        .swiper-pagination-custom,
        .swiper-container-horizontal > .swiper-pagination-bullets {
            bottom: 10px;
            left: 0;
            width: 100%;
        }
        /* Bullets */
        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            display: inline-block;
            border-radius: 100%;
            background: #000;
            opacity: 0.2;
        }
        button.swiper-pagination-bullet {
            border: none;
            margin: 0;
            padding: 0;
            box-shadow: none;
            -moz-appearance: none;
            -ms-appearance: none;
            -webkit-appearance: none;
            appearance: none;
        }
        .swiper-pagination-clickable .swiper-pagination-bullet {
            cursor: pointer;
        }
        .swiper-pagination-white .swiper-pagination-bullet {
            background: #fff;
        }
        .swiper-pagination-bullet-active {
            opacity: 1;
            background: #007aff;
        }
        .swiper-pagination-white .swiper-pagination-bullet-active {
            background: #fff;
        }
        .swiper-pagination-black .swiper-pagination-bullet-active {
            background: #000;
        }
        .swiper-container-vertical > .swiper-pagination-bullets {
            right: 10px;
            top: 50%;
            -webkit-transform: translate3d(0px, -50%, 0);
            -moz-transform: translate3d(0px, -50%, 0);
            -o-transform: translate(0px, -50%);
            -ms-transform: translate3d(0px, -50%, 0);
            transform: translate3d(0px, -50%, 0);
        }
        .swiper-container-vertical > .swiper-pagination-bullets .swiper-pagination-bullet {
            margin: 5px 0;
            display: block;
        }
        .swiper-container-horizontal > .swiper-pagination-bullets .swiper-pagination-bullet {
            margin: 0 5px;
        }
        /* Progress */
        .swiper-pagination-progress {
            background: rgba(0, 0, 0, 0.25);
            position: absolute;
        }
        .swiper-pagination-progress .swiper-pagination-progressbar {
            background: #007aff;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            -webkit-transform: scale(0);
            -ms-transform: scale(0);
            -o-transform: scale(0);
            transform: scale(0);
            -webkit-transform-origin: left top;
            -moz-transform-origin: left top;
            -ms-transform-origin: left top;
            -o-transform-origin: left top;
            transform-origin: left top;
        }
        .swiper-container-rtl .swiper-pagination-progress .swiper-pagination-progressbar {
            -webkit-transform-origin: right top;
            -moz-transform-origin: right top;
            -ms-transform-origin: right top;
            -o-transform-origin: right top;
            transform-origin: right top;
        }
        .swiper-container-horizontal > .swiper-pagination-progress {
            width: 100%;
            height: 4px;
            left: 0;
            top: 0;
        }
        .swiper-container-vertical > .swiper-pagination-progress {
            width: 4px;
            height: 100%;
            left: 0;
            top: 0;
        }
        .swiper-pagination-progress.swiper-pagination-white {
            background: rgba(255, 255, 255, 0.5);
        }
        .swiper-pagination-progress.swiper-pagination-white .swiper-pagination-progressbar {
            background: #fff;
        }
        .swiper-pagination-progress.swiper-pagination-black .swiper-pagination-progressbar {
            background: #000;
        }
        /* 3D Container */
        .swiper-container-3d {
            -webkit-perspective: 1200px;
            -moz-perspective: 1200px;
            -o-perspective: 1200px;
            perspective: 1200px;
        }
        .swiper-container-3d .swiper-wrapper,
        .swiper-container-3d .swiper-slide,
        .swiper-container-3d .swiper-slide-shadow-left,
        .swiper-container-3d .swiper-slide-shadow-right,
        .swiper-container-3d .swiper-slide-shadow-top,
        .swiper-container-3d .swiper-slide-shadow-bottom,
        .swiper-container-3d .swiper-cube-shadow {
            -webkit-transform-style: preserve-3d;
            -moz-transform-style: preserve-3d;
            -ms-transform-style: preserve-3d;
            transform-style: preserve-3d;
        }
        .swiper-container-3d .swiper-slide-shadow-left,
        .swiper-container-3d .swiper-slide-shadow-right,
        .swiper-container-3d .swiper-slide-shadow-top,
        .swiper-container-3d .swiper-slide-shadow-bottom {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 10;
        }
        .swiper-container-3d .swiper-slide-shadow-left {
            background-image: -webkit-gradient(linear, left top, right top, from(rgba(0, 0, 0, 0.5)), to(rgba(0, 0, 0, 0)));
            /* Safari 4+, Chrome */
            background-image: -webkit-linear-gradient(right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Chrome 10+, Safari 5.1+, iOS 5+ */
            background-image: -moz-linear-gradient(right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Firefox 3.6-15 */
            background-image: -o-linear-gradient(right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Opera 11.10-12.00 */
            background-image: linear-gradient(to left, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Firefox 16+, IE10, Opera 12.50+ */
        }
        .swiper-container-3d .swiper-slide-shadow-right {
            background-image: -webkit-gradient(linear, right top, left top, from(rgba(0, 0, 0, 0.5)), to(rgba(0, 0, 0, 0)));
            /* Safari 4+, Chrome */
            background-image: -webkit-linear-gradient(left, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Chrome 10+, Safari 5.1+, iOS 5+ */
            background-image: -moz-linear-gradient(left, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Firefox 3.6-15 */
            background-image: -o-linear-gradient(left, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Opera 11.10-12.00 */
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Firefox 16+, IE10, Opera 12.50+ */
        }
        .swiper-container-3d .swiper-slide-shadow-top {
            background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, 0.5)), to(rgba(0, 0, 0, 0)));
            /* Safari 4+, Chrome */
            background-image: -webkit-linear-gradient(bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Chrome 10+, Safari 5.1+, iOS 5+ */
            background-image: -moz-linear-gradient(bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Firefox 3.6-15 */
            background-image: -o-linear-gradient(bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Opera 11.10-12.00 */
            background-image: linear-gradient(to top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Firefox 16+, IE10, Opera 12.50+ */
        }
        .swiper-container-3d .swiper-slide-shadow-bottom {
            background-image: -webkit-gradient(linear, left bottom, left top, from(rgba(0, 0, 0, 0.5)), to(rgba(0, 0, 0, 0)));
            /* Safari 4+, Chrome */
            background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Chrome 10+, Safari 5.1+, iOS 5+ */
            background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Firefox 3.6-15 */
            background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Opera 11.10-12.00 */
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0));
            /* Firefox 16+, IE10, Opera 12.50+ */
        }
        /* Coverflow */
        .swiper-container-coverflow .swiper-wrapper,
        .swiper-container-flip .swiper-wrapper {
            /* Windows 8 IE 10 fix */
            -ms-perspective: 1200px;
        }
        /* Cube + Flip */
        .swiper-container-cube,
        .swiper-container-flip {
            overflow: visible;
        }
        .swiper-container-cube .swiper-slide,
        .swiper-container-flip .swiper-slide {
            pointer-events: none;
            -webkit-backface-visibility: hidden;
            -moz-backface-visibility: hidden;
            -ms-backface-visibility: hidden;
            backface-visibility: hidden;
            z-index: 1;
        }
        .swiper-container-cube .swiper-slide .swiper-slide,
        .swiper-container-flip .swiper-slide .swiper-slide {
            pointer-events: none;
        }
        .swiper-container-cube .swiper-slide-active,
        .swiper-container-flip .swiper-slide-active,
        .swiper-container-cube .swiper-slide-active .swiper-slide-active,
        .swiper-container-flip .swiper-slide-active .swiper-slide-active {
            pointer-events: auto;
        }
        .swiper-container-cube .swiper-slide-shadow-top,
        .swiper-container-flip .swiper-slide-shadow-top,
        .swiper-container-cube .swiper-slide-shadow-bottom,
        .swiper-container-flip .swiper-slide-shadow-bottom,
        .swiper-container-cube .swiper-slide-shadow-left,
        .swiper-container-flip .swiper-slide-shadow-left,
        .swiper-container-cube .swiper-slide-shadow-right,
        .swiper-container-flip .swiper-slide-shadow-right {
            z-index: 0;
            -webkit-backface-visibility: hidden;
            -moz-backface-visibility: hidden;
            -ms-backface-visibility: hidden;
            backface-visibility: hidden;
        }
        /* Cube */
        .swiper-container-cube .swiper-slide {
            visibility: hidden;
            -webkit-transform-origin: 0 0;
            -moz-transform-origin: 0 0;
            -ms-transform-origin: 0 0;
            transform-origin: 0 0;
            width: 100%;
            height: 100%;
        }
        .swiper-container-cube.swiper-container-rtl .swiper-slide {
            -webkit-transform-origin: 100% 0;
            -moz-transform-origin: 100% 0;
            -ms-transform-origin: 100% 0;
            transform-origin: 100% 0;
        }
        .swiper-container-cube .swiper-slide-active,
        .swiper-container-cube .swiper-slide-next,
        .swiper-container-cube .swiper-slide-prev,
        .swiper-container-cube .swiper-slide-next + .swiper-slide {
            pointer-events: auto;
            visibility: visible;
        }
        .swiper-container-cube .swiper-cube-shadow {
            position: absolute;
            left: 0;
            bottom: 0px;
            width: 100%;
            height: 100%;
            background: #000;
            opacity: 0.6;
            -webkit-filter: blur(50px);
            filter: blur(50px);
            z-index: 0;
        }
        /* Fade */
        .swiper-container-fade.swiper-container-free-mode .swiper-slide {
            -webkit-transition-timing-function: ease-out;
            -moz-transition-timing-function: ease-out;
            -ms-transition-timing-function: ease-out;
            -o-transition-timing-function: ease-out;
            transition-timing-function: ease-out;
        }
        .swiper-container-fade .swiper-slide {
            pointer-events: none;
            -webkit-transition-property: opacity;
            -moz-transition-property: opacity;
            -o-transition-property: opacity;
            transition-property: opacity;
        }
        .swiper-container-fade .swiper-slide .swiper-slide {
            pointer-events: none;
        }
        .swiper-container-fade .swiper-slide-active,
        .swiper-container-fade .swiper-slide-active .swiper-slide-active {
            pointer-events: auto;
        }
        .swiper-zoom-container {
            width: 100%;
            height: 100%;
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -moz-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -moz-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
            text-align: center;
        }
        .swiper-zoom-container > img,
        .swiper-zoom-container > svg,
        .swiper-zoom-container > canvas {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        /* Scrollbar */
        .swiper-scrollbar {
            border-radius: 10px;
            position: relative;
            -ms-touch-action: none;
            background: rgba(0, 0, 0, 0.1);
        }
        .swiper-container-horizontal > .swiper-scrollbar {
            position: absolute;
            left: 1%;
            bottom: 3px;
            z-index: 50;
            height: 5px;
            width: 98%;
        }
        .swiper-container-vertical > .swiper-scrollbar {
            position: absolute;
            right: 3px;
            top: 1%;
            z-index: 50;
            width: 5px;
            height: 98%;
        }
        .swiper-scrollbar-drag {
            height: 100%;
            width: 100%;
            position: relative;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            left: 0;
            top: 0;
        }
        .swiper-scrollbar-cursor-drag {
            cursor: move;
        }
        /* Preloader */
        .swiper-lazy-preloader {
            width: 42px;
            height: 42px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -21px;
            margin-top: -21px;
            z-index: 10;
            -webkit-transform-origin: 50%;
            -moz-transform-origin: 50%;
            transform-origin: 50%;
            -webkit-animation: swiper-preloader-spin 1s steps(12, end) infinite;
            -moz-animation: swiper-preloader-spin 1s steps(12, end) infinite;
            animation: swiper-preloader-spin 1s steps(12, end) infinite;
        }
        .swiper-lazy-preloader:after {
            display: block;
            content: "";
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20viewBox%3D'0%200%20120%20120'%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20xmlns%3Axlink%3D'http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink'%3E%3Cdefs%3E%3Cline%20id%3D'l'%20x1%3D'60'%20x2%3D'60'%20y1%3D'7'%20y2%3D'27'%20stroke%3D'%236c6c6c'%20stroke-width%3D'11'%20stroke-linecap%3D'round'%2F%3E%3C%2Fdefs%3E%3Cg%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(30%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(60%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(90%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(120%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(150%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.37'%20transform%3D'rotate(180%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.46'%20transform%3D'rotate(210%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.56'%20transform%3D'rotate(240%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.66'%20transform%3D'rotate(270%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.75'%20transform%3D'rotate(300%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.85'%20transform%3D'rotate(330%2060%2C60)'%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E");
            background-position: 50%;
            -webkit-background-size: 100%;
            background-size: 100%;
            background-repeat: no-repeat;
        }
        .swiper-lazy-preloader-white:after {
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20viewBox%3D'0%200%20120%20120'%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20xmlns%3Axlink%3D'http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink'%3E%3Cdefs%3E%3Cline%20id%3D'l'%20x1%3D'60'%20x2%3D'60'%20y1%3D'7'%20y2%3D'27'%20stroke%3D'%23fff'%20stroke-width%3D'11'%20stroke-linecap%3D'round'%2F%3E%3C%2Fdefs%3E%3Cg%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(30%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(60%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(90%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(120%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.27'%20transform%3D'rotate(150%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.37'%20transform%3D'rotate(180%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.46'%20transform%3D'rotate(210%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.56'%20transform%3D'rotate(240%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.66'%20transform%3D'rotate(270%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.75'%20transform%3D'rotate(300%2060%2C60)'%2F%3E%3Cuse%20xlink%3Ahref%3D'%23l'%20opacity%3D'.85'%20transform%3D'rotate(330%2060%2C60)'%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E");
        }
        @-webkit-keyframes swiper-preloader-spin {
            100% {
                -webkit-transform: rotate(360deg);
            }
        }
        @keyframes swiper-preloader-spin {
            100% {
                transform: rotate(360deg);
            }
        }
    </style><style type="text/css">
        .user-avatar {
            overflow: hidden;
        }
    </style><style type="text/css">
        .text-wrapper + .post-image--wrapper {
            margin-top: 15px;
        }
        @media screen  and (min-width: 768px){
            .md-dialog--theater {
                overflow-x: auto;
            }
            .md-dialog--theater .md-dialog__surface {
                max-width: 100%;
                min-width: 900px;
            }
            .md-dialog--theater .md-dialog__body {
                width: 1020px;
                max-width: 100%;
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                min-height: 520px;
                height: 90vh;
                max-height: 636px;
            }
            .md-dialog--theater .md-dialog__wrapper {
                justify-content: center;
            }
            .md-dialog--theater .md-dialog__surface .img-viewer,
            .md-dialog--theater .md-dialog__surface .post-image--wrapper {
                height: 100%;
            }
            .md-dialog--theater .md-dialog__surface .img-viewer {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .md-dialog--theater .md-dialog__surface .img-responsive{
                max-height: 100%;
            }
            .md-dialog--theater .stage {
                width: 660px;
            }
            .md-dialog--dark .stage{
                background-color: #000;
                display: flex;
                justify-content: center;
                flex-shrink: 0;
                align-items: center;
            }
            .stage-photo-sidebar {
                flex-grow: 1;
                flex-shrink: 1;
                max-height: 570px;
                overflow-y: auto;
                overflow-x: hidden;
            }
            .stage-photo-sidebar .text-wrapper {
                padding: 0 15px;
            }
        }
        .md-dialog__header.panel-post{
            padding-left:0;
            padding-right: 0;
        }
        .md-dialog__header .panel-heading {
            border-left: none !important;
            border-right:none !important;
        }
        .md-button {
            background: transparent;
            border: none;
            border-radius: 2px;
            color: rgba(0,0,0,.84);
            position: relative;
            height: 36px;
            min-width: 64px;
            padding: 0 16px;
            display: inline-block;
            font-family: Roboto,sans-serif;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0;
            overflow: hidden;
            will-change: box-shadow;
            transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);
            -webkit-transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);
            outline: none;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            line-height: 36px;
            vertical-align: middle;
        }
        .md-button--icon{
            border-radius: 50%;
            font-size: 24px;
            height: 32px;
            margin-left: 0;
            margin-right: 0;
            min-width: 32px;
            width: 32px;
            padding: 0;
            overflow: hidden;
            line-height: normal;
        }
        .md-button--icon .icon {
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-12px,-12px);
            transform: translate(-12px,-12px);
            line-height: 24px;
            width: 24px;
            font-size: 24px;
        }
        .md-dialog__header .post-options {
            display: none;
        }
        .md-dialog--theater .md-dialog__shadow {
            background-color: rgba(0,0,0,.7);
        }
        .md-dialog--theater .md-dialog__body {
            padding-left: 0;
            padding-right: 0;
            margin-top: 0;
            padding-bottom: 0;
        }
        .md-dialog--theater .md-dialog__header {
            padding-bottom: 0;
            padding-top: 0;
        }
        @media screen and (max-width: 767px) {
            .md-dialog__header--xs{
                display: flex !important;
                flex-direction: row;
                flex-wrap: nowrap;
            }
            .md-dialog--theater .md-dialog__surface {
                height: 100vh;
                color: #fff;
            }
            .md-dialog--theater .md-dialog__body {
                justify-content: center;
                align-items: center;
                display: flex;
            }
            .md-dialog--dark .md-dialog__surface{
                background-color: #0A0A0A;
            }
            .md-dialog--dark .panel-heading.no-bg{
                background-color: transparent !important;
                border-top:none !important;
                border-bottom:none !important;
            }
            .md-dialog--dark .ft-socialite{
                background-color: transparent;
            }
            .md-dialog--dark .ft-expression{
                color: #fff;
            }
        }
    </style><style type="text/css">
        .md-dialog--who-likes .md-dialog__body {
            min-height: 216px;
            padding-right: 0;
            padding-left: 0;
            padding-top: 0;
            margin-top: 0;
        }
        .md-dialog--who-likes.md-dialog--open {
            z-index: 28;
        }
        .md-list--likes {
            background-color: transparent !important;
        }
        .md-dialog--who-likes .md-dialog__surface {
            width: 440px;
        }
        .md-dialog--who-likes .loading-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 192px;
            margin-top: -24px;
        }
        .md-dialog--who-likes .md-dialog__header {
            padding-top: 8px;
            padding-bottom: 8px;
        }
    </style><style type="text/css">
        .font-large {
            font-size: 24px;
        }
        .loader {
            position: relative;
            width:24px;
            height: 24px;
        }
        .spinner {
            border: 2px solid #333;
            border-right-color: transparent;
            border-radius: 50%;
            height: 16px;
            left: 50%;
            position: absolute;
            top: 50%;
            width: 16px;
            animation: spinner 1s linear 0s infinite;
        }
        .spinner--small {
            width: 12px;
            height: 12px;
        }
        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style><style type="text/css">
        .panel-default .sub-meta-info {
            color: rgba(0,0,0,.54)
        }
        .ft-btn--icon {
            height: 24px;
            width: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style><style type="text/css">
        .item__background--post{
            width: 100%;
            background-size: cover;
            background-position: center;
        }
        .img-viewer{
            cursor: pointer;
        }
        .fkd-slider-wrapper {
            min-height: 300px;
            position: relative;
        }
        .item__background--home-slider{
            max-height: 300px;
        }
        @media screen and (max-width: 599px){
            .loading-state-wrapper{
                height: auto;
                min-height: 200px;
                width: 100%;
            }
            .loading-state-relative{
                width: 100%;
            }
            .fkd-slider-wrapper{
                min-height: 180px;
            }
            .item__background{
                background-size: cover;
            }
            .swiper-slide .item__background {
                max-height: 200px;
                min-height: 180px;
            }
            .panel-post .panel-body .post-image--wrapper{
                margin-left:-15px;
                margin-right:-15px;
            }
        }
        .component-loading-state {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
        }
        .item__background {
            height: 360px;
            width: 100vw;
            position: relative;
            background-size: auto 100%;
            background-position: center;
            display: block;
        }
        .swiper-pagination-bullet-active{
            background-color: #1E7C82;
        }
        .swiper-button-prev, .swiper-container-rtl .swiper-button-next{
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 27 44'><path d='M0,22L22,0l2.1,2.1L4.2,22l19.9,19.9L22,44L0,22L0,22L0,22z' fill='#81C784'/></svg>");
            transform: scale(.7);
        }
        .swiper-button-next, .swiper-container-rtl .swiper-button-prev{
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 27 44'><path d='M27,22L27,22L5,44l-2.1-2.1L22.8,22L2.9,2.1L5,0L27,22L27,22z' fill='#81C784'/></svg>");
            transform: scale(.7);
        }
    </style><style type="text/css">
        .icon {
            pointer-events: none;
        }
        .text-center.ft-menu__item {
            text-align: center !important;
        }
        .zippy {
            height: 0;
        }
        .zippy.zippy--open,
        .zippy.zippy--animating {
            height: auto;
        }
        .comment-textfield{
            position: relative;
        }
        .comment-textfield .loading-wrapper {
            position: absolute;
            top:0;
            left:0;
            height: 100%;
            width: 100%;
            display: none;
        }
        .comment-textfield.is-loading {
            pointer-events: none;
            cursor: wait;
        }
        .comment-textfield .ft-loading {
            background-color: rgba(0,0,0,.12);
        }
        .comment-textfield.is-loading .loading-wrapper {
            display: block;
        }
        /*.comment-list-action{
            max-height: 320px;
            overflow-y: auto;
        }*/
        .ft-loading{
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
            height: 48px;
            background-color: #FAFBFC;
        }
        .ft-loading__dot{
            background: #333;
            display: block;
            height: 8px;
            float: left;
            margin: 0 2px;
            opacity: 0;
            width: 8px;
            border-radius: 50%;
            animation: dot 1s cubic-bezier(0.77, 0, 0.175, 1) 0s infinite;
        }
        .ft-loading .ft-loading__dot:nth-child(1) {
            animation-delay: 0.15s;
        }
        .ft-loading .ft-loading__dot:nth-child(2) {
            animation-delay: 0.3s;
        }
        .ft-loading .ft-loading__dot:nth-child(3) {
            animation-delay: 0.45s;
        }
        @keyframes dot {
            0% {
                opacity: 0;
            }
            25% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }
        .comment-textfield .form-control{
            height: 48px;
            border-top: none;
            border-left:none;
            border-right:none;
            border-radius: 0;
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 15px;
            background-color: #FAFBFC;
        }
        .md-list__item-icon {
            margin-top: 2px;
            height: 32px;
            width: 32px;
            align-self: flex-start;
            margin-right: 16px;
            flex-shrink: 0;
        }
        .user-avatar {
            background-size: cover;
        }
        .md-list {
            background-color: #FAFBFC;
            padding: 4px 0;
            width: 100%;
            display: block;
            list-style: none;
        }
        .md-list .md-list__item {
            border-bottom: 1px solid rgba(0,0,0,.12);
        }
        .md-list .md-list__item:last-child {
            border-bottom: none
        }
        .md-list--dense .md-list__item {
            min-height: 32px;
            font-size: 14px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            font-weight: 400;
            color: rgba(0,0,0,.87);
            letter-spacing: .04em;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            cursor: default;
            overflow: hidden;
            line-height: 1;
            padding-left: 16px;
            padding-top:5px;
            padding-bottom:5px;
        }
        .md-list__item-content {
            flex: 1 1 100%;
            display: flex;
            position: relative;
            flex-direction: row;
            flex-wrap: nowrap;
            line-height: 20px;
            justify-content: space-between;
            padding-right: 16px;
        }
        .md-list__item-text-body{
            line-height: 18px;
            color: rgba(0,0,0,.54);
            padding: 0;
            font-size: 12px;
        }
        .md-list__item-secondary{
            display: flex;
            margin-left: 8px;
        }
        .md-list__item-secondary-action {
            height: 20px;
            width: 20px;
            padding: 2px;
            text-align: center;
            font-size: 11px;
            color: #333;
        }
        .md-list__item-secondary-action.ft-expression{
            height: 20px;
            width: 20px;
        }
        .md-list__item-secondary-action .icon{
            font-size: 1.4rem;
        }
        .ft-socialite {
            background-color: #fff;
            padding: 7.5px 15px;
            padding-bottom: 2px;
            border: none;
        }
        .ft-expression {
            display: flex;
            height: 32px;
            width: 36px;
            text-align: center;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        .ft-expression .hidden-default {
            display: none;
        }
        .ft-expression--liked,
        .ft-expression--liked:focus,
        .ft-expression--liked:hover {
            color: #EB5757;
        }
        .ft-expression--liked .hidden-default {
            display: block;
        }
        .ft-expression--liked .visible-default {
            display: none;
        }
        .ft-expression i {
            font-size: 22px;
        }
        .ft-comment {
            flex-wrap: wrap;
            align-items: center;
        }
        .ft-comment__item {
            display: flex;
        }
        .ft-comment__item--grow {
            flex-grow: 1;
        }
        .ft-expression--meta {
            font-size: 11px;
            height: 24px;
            min-width: 24px;
            width: auto;
            padding: 0 4px;
            line-height: 24px;
        }
        .ft-expression--meta .icon {
            font-size: 12px;
            margin-top: 4px;
        }
        .ft-expression--meta-text {
            margin-left: 2px;
        }
        @media screen and (max-width: 599px) {
            .main-content > .container > .row > .col-lg-6 {
                padding-left: 0;
                padding-right: 0;
            }
        }
    </style><script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/31/0/common.js"></script><script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/31/0/map.js"></script><script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/31/0/util.js"></script><script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/31/0/controls.js"></script><script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/31/0/places_impl.js"></script><script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/31/0/stats.js"></script><style type="text/css">.gm-style {
            font: 400 11px Roboto, Arial, sans-serif;
            text-decoration: none;
        }
        .gm-style img { max-width: none; }</style><script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/31/0/onion.js"></script></head>
<body>

<div class="padding-10">
    <nav class="navbar ft-custom socialite navbar-default no-bg hidden-sm hidden-xs">
        <div class="container md-layout md-layout--row" style="max-width: 960px;">
            <div class="no-float navbar-header">
                <a class="navbar-brand socialite" href="http://localhost/fitmetix/public">
                    <img class="socialite-logo" src="http://localhost/fitmetix/public/setting/logo.png" alt="Fitmetix" title="Fitmetix">
                </a>
            </div>
            <div class="md-layout-spacer"></div>
            <form class="no-float navbar-form navbar-left form-left" role="search">
                <div class="input-group no-margin">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                        </span>
                    <input type="text" id="navbar-search" style="width: 200px; display: none;" data-url="http://localhost/fitmetix/public/api/v1/timelines" class="form-control selectized" placeholder="Search" tabindex="-1" value=""><div class="selectize-control form-control multi" style="width: 200px;"><div class="selectize-input items not-full"><input type="text" autocomplete="off" tabindex="" placeholder="Search" style="width: 48px;"></div><div class="selectize-dropdown multi form-control" style="display: none; width: 200px; top: 30px; left: 0px;"><div class="selectize-dropdown-content"></div></div></div>
                </div>
            </form>
            <div class="md-layout-spacer"></div>
            <div class="nav no-float md-layout fm-nav navbar-nav hidden-sm hidden-xs" id="navbar-right">
                <a href="http://localhost/fitmetix/public/mikele/create-event" class="has-hover-effect fm-nav__item">
                    <span>
                        <i class="fa fa-plus"></i> Inspire
                    </span>
                </a>
                <a href="http://localhost/fitmetix/public/mikele/events" class="has-hover-effect fm-nav__item">
                    Events
                </a>
                <div id="app-notification"><div><div class="pos-rel"><a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="has-hover-effect fm-nav__item dropdown font-large hidden-sm hidden-xs"><span class="icon icon-chat"></span> <span class="unread-notification"></span></a> <ul data-width="3" class="dropdown-menu dropdown-menu ft-menu"><li class="dropdown-menu-header ft-menu__item"><span class="side-left">Messages</span></li> <!----> <li class="inbox-message"><a href="#" data-user-id="26" onclick="chatBoxes.sendMessageOnClick(this)"><div class="media"><div class="media-left"><img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="images" class="media-object img-icon"></div> <div class="media-body"><h4 class="media-heading"><span class="message-heading">@Prakash</span> <span class="online-status hidden"></span></h4> <p class="message-text"></p></div></div></a></li><li class="inbox-message"><a href="#" data-user-id="14" onclick="chatBoxes.sendMessageOnClick(this)"><div class="media"><div class="media-left"><img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="images" class="media-object img-icon"></div> <div class="media-body"><h4 class="media-heading"><span class="message-heading">@Alpesh Harsoda</span> <span class="online-status hidden"></span></h4> <p class="message-text"></p></div></div></a></li><li class="inbox-message"><a href="#" data-user-id="22" onclick="chatBoxes.sendMessageOnClick(this)"><div class="media"><div class="media-left"><img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="images" class="media-object img-icon"></div> <div class="media-body"><h4 class="media-heading"><span class="message-heading">@hamed</span> <span class="online-status hidden"></span></h4> <p class="message-text"></p></div></div></a></li><li class="inbox-message"><a href="#" data-user-id="1" onclick="chatBoxes.sendMessageOnClick(this)"><div class="media"><div class="media-left"><img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="images" class="media-object img-icon"></div> <div class="media-body"><h4 class="media-heading"><span class="message-heading">@Admin</span> <span class="online-status hidden"></span></h4> <p class="message-text"></p></div></div></a></li><li class="inbox-message"><a href="#" data-user-id="9" onclick="chatBoxes.sendMessageOnClick(this)"><div class="media"><div class="media-left"><img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="images" class="media-object img-icon"></div> <div class="media-body"><h4 class="media-heading"><span class="message-heading">@waqas raza</span> <span class="online-status hidden"></span></h4> <p class="message-text"></p></div></div></a></li><li class="inbox-message"><a href="#" data-user-id="2" onclick="chatBoxes.sendMessageOnClick(this)"><div class="media"><div class="media-left"><img src="http://localhost/fitmetix/public/user/avatar/default-male-avatar.png" alt="images" class="media-object img-icon"></div> <div class="media-body"><h4 class="media-heading"><span class="message-heading">@Freelancer</span> <span class="online-status hidden"></span></h4> <p class="message-text"></p></div></div></a></li> <li class="dropdown-menu-footer ft-menu__item ft-menu__item--icon"><a href="http://localhost/fitmetix/public/messages">See all</a></li></ul></div></div></div>

                <input type="hidden" name="nt-count" value="52">
                <input type="hidden" name="nt-common-messages" value="Messages">
                <input type="hidden" name="nt-common-see_all" value="See all">
                <input type="hidden" name="nt-no_messages" value="You don't have any messages">
                <input type="hidden" name="see-all-messages" value="http://localhost/fitmetix/public/messages">
                <a href="http://localhost/fitmetix/public/mikele" class="has-hover-effect fm-nav__item user-image socialite fm-nav__item">
                    <span class="user-image-wrapper">
                        <img src="http://localhost/fitmetix/public/images/default.png" style="max-width: 100%" alt="Mikel" class="img-radius img-30" title="Mikel">
                        <span class="user-name hidden">Mikel</span>
                    </span>
                </a>

                <div class="dropdown message vert-has">
                    <a href="http://localhost/fitmetix/public/mikele" class="has-hover-effect fm-nav__item fm-nav__item--no-padding dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <div class="icon icon-options"></div>
                    </a>
                    <ul data-width="3" class="dropdown-menu ft-menu" style="left: -123px;border-top: none">
                        <li class="">
                            <a href="http://localhost/fitmetix/public/admin" class="ft-menu__item  ft-menu__item--icon">
                                <i class="fa fa-user-secret" aria-hidden="true"></i>Admin
                            </a>
                        </li>

                        <li class="">
                            <a href="http://localhost/fitmetix/public/mikele" class="ft-menu__item ft-menu__item--icon">
                                <i class="fa fa-user" aria-hidden="true"></i>my profile
                            </a>
                        </li>

                        <li class="">
                            <a href="http://localhost/fitmetix/public/mikele/albums" class="ft-menu__item ft-menu__item--icon">
                                <i class="fa fa-image" aria-hidden="true"></i>My Albums
                            </a>
                        </li>

                        <li class="">
                            <a class="ft-menu__item ft-menu__item--icon" href="http://localhost/fitmetix/public/mikele/events"><i class="fa fa-calendar" aria-hidden="true"></i>My events</a>
                        </li>

                        <li class="">
                            <a class="ft-menu__item ft-menu__item--icon" href="http://localhost/fitmetix/public/mikele/settings/general">
                                <i class="fa fa-cog" aria-hidden="true"></i>settings
                            </a>
                        </li>

                        <li>
                            <form action="http://localhost/fitmetix/public/logout" method="post">
                                <input type="hidden" name="_token" value="7vtbOkv6q5q16wL6wTCbPzPVt4Lhly4UOpVimcjv">

                                <button type="submit" class="ft-menu__item ft-menu__item--icon btn btn-logout">
                                    <i class="fa fa-unlock" aria-hidden="true"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="ft-header hidden-md hidden-lg">
        <div class="ft-header-nav">
            <a class="ft-header-nav__item is-active" href="http://localhost/fitmetix/public">
                <div class="icon" data-icon="n"></div>
            </a>
            <a class="ft-header-nav__item " style="padding: 0" href="http://localhost/fitmetix/public/mikele/create-event">
                <div class="icon icon-eventpage" style="font-size: 50px; line-height: 45px"></div>
            </a>
            <a class="ft-header-nav__item pos-rel " href="http://localhost/fitmetix/public/notifications">
                <div class="icon icon icon-like"></div>
                <span class="unread-notification is-visible" v-bind:class="{ 'is-visible': isShowUN }"></span>
            </a>
            <a class="ft-header-nav__item pos-rel" href="http://localhost/fitmetix/public/messages">
                <i class="icon icon-chat"></i>
                <span class="unread-notification is-visible" v-bind:class="{ 'is-visible': isShowUCM }"></span>
            </a>
            <a class="ft-header-nav__item" href="http://localhost/fitmetix/public/messages" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample">
                <div class="icon icon-search"></div>
            </a>
            <div class="dropdown ft-header-nav__item pos-rel">
                <a class="dropdown-toggle ft-header-nav__item--user-img" data-toggle="dropdown" @click.prevent="showNotifications" role="button" href="javascript:;" aria-haspopup="true" aria-expanded="false">
                    <div class="user-avatar" style="background-image: url(//localhost:3000/fitmetix/public/user/avatar/2017-10-22-14-07-04athletebookprofilepage.png)"></div>
                </a><ul style="left: auto; right: 0;" data-width="3" class="ft-menu dropdown-menu"><a class="dropdown-toggle ft-header-nav__item--user-img" data-toggle="dropdown" @click.prevent="showNotifications" role="button" href="javascript:;" aria-haspopup="true" aria-expanded="false">
                    </a><li class=""><a class="dropdown-toggle ft-header-nav__item--user-img" data-toggle="dropdown" @click.prevent="showNotifications" role="button" href="javascript:;" aria-haspopup="true" aria-expanded="false">
                        </a><a href="http://localhost/fitmetix/public/mikele/create-event" class="ft-menu__item  ft-menu__item--icon">
                            <i class="icon icon-add"></i> Inspire
                        </a>
                    </li>
                    <li class="">
                        <a href="http://localhost/fitmetix/public/mikele" class="ft-menu__item  ft-menu__item--icon">
                            <i class="icon icon-participant"></i> my profile
                        </a>
                    </li>
                    <li class="">
                        <a href="http://localhost/fitmetix/public/mikele/settings/general" class="ft-menu__item ft-menu__item--icon">
                            <i class="icon icon-settings-o"></i> settings
                        </a>
                    </li>
                    <li>
                        <form action="http://localhost/fitmetix/public/logout" method="post" style="height: 40px">
                            <input type="hidden" name="_token" value="7vtbOkv6q5q16wL6wTCbPzPVt4Lhly4UOpVimcjv">

                            <button type="submit" class="ft-menu__item ft-menu__item--icon btn btn-logout">
                                <i class="fa fa-unlock" aria-hidden="true"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>

            </div>
        </div>
    </div>


</div>
<div class="main-content">
    <!-- main-section -->
    <!-- <div class="main-content"> -->
    <div class="container">
        <div class="row">
            <div class="visible-lg col-lg-2">

            </div>

            <div class="col-md-7 col-lg-6">


                <form action="http://localhost/fitmetix/public" method="post" class="create-post-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="7vtbOkv6q5q16wL6wTCbPzPVt4Lhly4UOpVimcjv">

                    <div class="panel panel-default panel-create"> <!-- panel-create -->
                        <div class="panel-heading">
                            <div class="heading-text">
                                What's going on?
                            </div>
                        </div>
                        <div class="panel-body">
                            <textarea name="description" class="form-control createpost-form comment" cols="30" rows="3" id="createPost" placeholder="Write something.....#hashtags @mentions"></textarea>


                            <div class="user-tags-added" style="display:none">
                                &nbsp; -- with
                                <div class="user-tag-names">

                                </div>
                            </div>
                            <div class="user-tags-addon post-addon" style="display: none">
                                <span class="post-addon-icon"><i class="fa fa-user-plus"></i></span>
                                <div class="form-group">
                                    <input type="text" id="userTags" class="form-control user-tags youtube-text selectized" placeholder="Who are you with?" autocomplete="off" value="" tabindex="-1" style="display: none;"><div class="selectize-control form-control user-tags youtube-text multi plugin-remove_button"><div class="selectize-input items not-full"><input type="text" autocomplete="off" tabindex="" placeholder="Who are you with?" style="width: 112px;"></div><div class="selectize-dropdown multi form-control user-tags youtube-text plugin-remove_button" style="display: none; width: 100px; top: 0px; left: 0px;"><div class="selectize-dropdown-content"></div></div></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="users-results-wrapper"></div>
                            <div class="youtube-iframe"></div>

                            <div class="video-addon post-addon" style="display: none">
                                <span class="post-addon-icon"><i class="fa fa-film"></i></span>
                                <div class="form-group">
                                    <input type="text" name="youtubeText" id="youtubeText" class="form-control youtube-text" placeholder="What are you watching?" value="">
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div class="location-addon post-addon" style="display: none">
                                <span class="post-addon-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                <div class="form-group">
                                    <input type="text" name="location" id="pac-input" class="form-control" placeholder="Where are you?" autocomplete="off" value="" onkeypress="return initMap(event)" style="position: relative; overflow: hidden;"><div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="emoticons-wrapper  post-addon" style="display:none">

                            </div>
                            <div class="images-selected post-images-selected" style="display:none">
                                <span>3</span> photo(s) selected
                            </div>
                            <div class="images-selected post-video-selected" style="display:none">
                                <span>3</span>
                            </div>
                            <!-- Hidden elements  -->
                            <input type="hidden" name="timeline_id" value="11">
                            <input type="hidden" name="youtube_title" value="">
                            <input type="hidden" name="youtube_video_id" value="">
                            <input type="hidden" name="locatio" value="">
                            <input type="hidden" name="soundcloud_id" value="">
                            <input type="hidden" name="user_tags" value="">
                            <input type="hidden" name="soundcloud_title" value="">
                            <input type="file" class="post-images-upload hidden" multiple="multiple" accept="image/jpeg,image/png,image/gif" name="post_images_upload[]" id="post_images_upload[]">
                            <input type="file" class="post-video-upload hidden" accept="video/mp4" name="post_video_upload">
                            <div id="post-image-holder"></div>
                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            <ul class="list-inline left-list">
                                <li><a href="#" id="addUserTags"><i class="fa fa-user-plus"></i></a></li>
                                <li><a href="#" id="imageUpload"><i class="icon icon-photo"></i></a></li>

                                <li><a href="#" id="videoUpload"><i class="icon icon-youtube"></i></a></li>
                                <li><a href="#" id="locationUpload"><i class="fa fa-map-marker"></i></a></li>
                                <li><a href="#" id="emoticons"><i class="fa fa-smile-o"></i></a></li>
                            </ul>
                            <ul class="list-inline right-list">

                                <li><button type="submit" class="btn btn-submit ft-btn-primary">post</button></li>
                            </ul>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </form>




                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_vuWi_hzMDDeenNYwaNAj0PHzzS2GAx8&amp;libraries=places&amp;callback=initMap" async="" defer=""></script>

                <script>
                    function initMap(event)
                    {
                        var key;
                        var map = new google.maps.Map(document.getElementById('pac-input'), {
                        });

                        var input = /** @type  {!HTMLInputElement} */(
                                document.getElementById('pac-input'));

                        if(window.event)
                        {
                            key = window.event.keyCode;

                        }
                        else
                        {
                            if(event)
                                key = event.which;
                        }

                        if(key == 13){
                            //do nothing
                            return false;
                            //otherwise
                        } else {
                            var autocomplete = new google.maps.places.Autocomplete(input);
                            autocomplete.bindTo('bounds', map);

                            //continue as normal (allow the key press for keys other than "enter")
                            return true;
                        }
                    }
                </script>





                <div class="timeline-posts">
                    <div id="app-timeline"><input type="hidden" id="newPostId"> <div id="post-option-dialog" class="ft-dialog"><div class="ft-dialog__inner-layer"></div> <a href="javascript:;" class="ft-dialog__btn"><i class="icon icon-close"></i></a> <div class="ft-dialog__wrapper"><div class="ft-dialog__surface"><div class="ft-dialog-option"><a href="javascript:;" data-value="post" class="btn ft-dialog-option__item">
                                            Go to post
                                        </a> <a href="javascript:;" data-value="embed" class="btn ft-dialog-option__item">
                                            Embed
                                        </a> <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item">
                                            Cancel
                                        </a></div></div></div></div> <div id="comment-option-dialog" class="ft-dialog"><div class="ft-dialog__inner-layer"></div> <a href="javascript:;" class="ft-dialog__btn"><i class="icon icon-close"></i></a> <div class="ft-dialog__wrapper"><div class="ft-dialog__surface"><div class="ft-dialog-option"><a href="javascript:;" data-value="post" class="btn ft-dialog-option__item">
                                            Go to post
                                        </a> <a href="javascript:;" data-value="embed" class="btn ft-dialog-option__item">
                                            Embed
                                        </a> <a href="javascript:;" data-value="cancel" class="btn ft-dialog-option__item">
                                            Cancel
                                        </a></div></div></div></div> <div><div id="post-image-theater-dialog" class="md-dialog md-dialog--dark md-dialog--theater md-dialog--full-screen"><div class="md-dialog__wrapper"><div class="md-dialog__shadow"></div> <div class="md-dialog__surface"><header class="md-dialog__header panel-post hidden md-dialog__header--xs visible-xs"><!----> <a href="javascript:;" data-action="dismissive" class="md-button md-button--icon md-dialog__header-action-dismissive" style="margin-right: 15px;"><i class="icon icon-close"></i></a></header> <div class="md-dialog__body"><div class="stage"><!----></div> <div class="stage-photo-sidebar"><header class="panel-post hidden-xs"><!----></header> <!----></div></div> <footer class="md-dialog__footer  hidden visible-xs"><!----></footer></div></div></div> <div id="post-who-likes-dialog" class="md-dialog md-dialog--who-likes md-dialog--full-screen"><div class="md-dialog__wrapper"><div class="md-dialog__shadow"></div> <div class="md-dialog__surface"><header class="md-dialog__header panel-post"><div class="md-layout-spacer"></div> <a href="javascript:;" data-action="dismissive" class="md-button md-button--icon md-dialog__header-action-dismissive" style="margin-right: 15px;"><i class="icon icon-close"></i></a></header> <div class="md-dialog__body md-dialog__body--scrollable"><div class="loading-wrapper"><div class="ft-loading" style="background-color: transparent;"><span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span></div></div></div></div></div></div> <!---->  <div id="ft-post186" class="panel panel-default timeline-posts__item panel-post"><div class="panel-heading no-bg"><div class="post-author"><div class="post-options"><a href="javascript:;" class="ft-btn--icon"><i class="icon icon-options"></i></a></div> <a href="http://localhost/fitmetix/public/mikele" title="@mikele" class="user-avatar" style="background-image: url(&quot;http://localhost/fitmetix/public/images/default.png&quot;);"><img src="http://localhost/fitmetix/public/images/default.png" class="hidden" title="Mikel"></a> <div class="user-post-details"><div class="no-margin"><div class="meta-font"><a href="http://localhost/fitmetix/public/mikele" data-toggle="tooltip" data-placement="top" title="@mikele" data-original-title="@mikele" class="user-name user ft-user-name">
                                                        Mikel
                                                    </a> <div class="small-text"></div></div> <div class="md-layout meta-font"><div class="ft-timeago sub-meta-info"><time datetime="Thu Nov 23 2017 09:29:55 GMT+0530 (IST)" title="23/11/2017, 09:29:55" class="timeago">1 d</time></div> <!----></div></div></div></div></div> <div class="panel-body"><div class="text-wrapper"><div>Under the username is the approximation of how long ago the post was written. For simplicity in our
                                            platform we just want to use the terms:</div></div> <!----> <div class="post-image--wrapper"><div class="image-responsive item__background--post img-viewer"><img src="http://localhost/fitmetix/storage/uploads/users/gallery/2017-11-23-09-29-55IMG_20170417_095452643.jpg" class="img-responsive"></div></div> <!----></div> <div style="width: 100%;"><div class="panel-footer ft-socialite meta-font"><div class="ft-comment md-layout md-layout--row"><div class="ft-comment__item md-layout md-layout--row"><a href="javascript:;" class="ft-expression ft-expression--likes ft-expression--liked"><i class="icon icon-like visible-default"></i> <i class="icon icon-liked hidden-default"></i></a> <a href="javascript:;" class="ft-expression ft-expression--comment ft-expression--liked"><i class="icon icon-comment"></i></a></div> <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow"><a href="javascript:;" class="ft-expression ft-expression--meta" style=""><span class="icon icon-liked visible-default"></span> <span class="ft-expression--meta-text">
                    1
                </span></a> <a href="javascript:;" class="ft-expression  ft-expression--meta" style=""><span class="icon icon-commentcount"></span> <span class="ft-expression--meta-text">
                    18
                </span></a></div> <div class="ft-comment__item"><a href="javascript:;" class="ft-expression"><i class="icon icon-share"></i></a></div></div></div> <section id="10comment-expand-186" class="zippy suggestion-list-expand"><div class="zippy__wrapper" style="margin-top: -48px;"><div class="ft-loading"><span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span></div></div></section></div></div><div id="ft-post185" class="panel panel-default timeline-posts__item panel-post"><div class="panel-heading no-bg"><div class="post-author"><div class="post-options"><a href="javascript:;" class="ft-btn--icon"><i class="icon icon-options"></i></a></div> <a href="http://localhost/fitmetix/public/mikele" title="@mikele" class="user-avatar" style="background-image: url(&quot;http://localhost/fitmetix/public/images/default.png&quot;);"><img src="http://localhost/fitmetix/public/images/default.png" class="hidden" title="Mikel"></a> <div class="user-post-details"><div class="no-margin"><div class="meta-font"><a href="http://localhost/fitmetix/public/mikele" data-toggle="tooltip" data-placement="top" title="@mikele" data-original-title="@mikele" class="user-name user ft-user-name">
                                                        Mikel
                                                    </a> <div class="small-text"></div></div> <div class="md-layout meta-font"><div class="ft-timeago sub-meta-info"><time datetime="Thu Nov 23 2017 09:28:49 GMT+0530 (IST)" title="23/11/2017, 09:28:49" class="timeago">1 d</time></div> <!----></div></div></div></div></div> <div class="panel-body"><div class="text-wrapper"><div>first pelase</div></div> <!----> <!----> <!----></div> <div style="width: 100%;"><div class="panel-footer ft-socialite meta-font"><div class="ft-comment md-layout md-layout--row"><div class="ft-comment__item md-layout md-layout--row"><a href="javascript:;" class="ft-expression ft-expression--likes"><i class="icon icon-like visible-default"></i> <i class="icon icon-liked hidden-default"></i></a> <a href="javascript:;" class="ft-expression ft-expression--comment ft-expression--liked"><i class="icon icon-comment"></i></a></div> <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow"><a href="javascript:;" class="ft-expression ft-expression--meta" style="display: none;"><span class="icon icon-liked visible-default"></span> <span class="ft-expression--meta-text">
                    0
                </span></a> <a href="javascript:;" class="ft-expression  ft-expression--meta" style=""><span class="icon icon-commentcount"></span> <span class="ft-expression--meta-text">
                    14
                </span></a></div> <div class="ft-comment__item"><a href="javascript:;" class="ft-expression"><i class="icon icon-share"></i></a></div></div></div> <section id="1comment-expand-185" class="zippy suggestion-list-expand"><div class="zippy__wrapper" style="margin-top: -48px;"><div class="ft-loading"><span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span></div></div></section></div></div><div id="ft-post184" class="panel panel-default timeline-posts__item panel-post"><div class="panel-heading no-bg"><div class="post-author"><div class="post-options"><a href="javascript:;" class="ft-btn--icon"><i class="icon icon-options"></i></a></div> <a href="http://localhost/fitmetix/public/mikele" title="@mikele" class="user-avatar" style="background-image: url(&quot;http://localhost/fitmetix/public/images/default.png&quot;);"><img src="http://localhost/fitmetix/public/images/default.png" class="hidden" title="Mikel"></a> <div class="user-post-details"><div class="no-margin"><div class="meta-font"><a href="http://localhost/fitmetix/public/mikele" data-toggle="tooltip" data-placement="top" title="@mikele" data-original-title="@mikele" class="user-name user ft-user-name">
                                                        Mikel
                                                    </a> <div class="small-text"></div></div> <div class="md-layout meta-font"><div class="ft-timeago sub-meta-info"><time datetime="Thu Nov 23 2017 09:22:17 GMT+0530 (IST)" title="23/11/2017, 09:22:17" class="timeago">1 d</time></div> <!----></div></div></div></div></div> <div class="panel-body"><div class="text-wrapper"><div>The button “more…” is for pagination of text in a post, for likes etc. If a post is longer than 150
                                            characters it will show this button ‘more..’. When user clicks on that it will show the complete post.
                                            Put a maximum limit of 600 characters for a post
                                        </div></div> <!----> <!----> <!----></div> <div style="width: 100%;"><div class="panel-footer ft-socialite meta-font"><div class="ft-comment md-layout md-layout--row"><div class="ft-comment__item md-layout md-layout--row"><a href="javascript:;" class="ft-expression ft-expression--likes"><i class="icon icon-like visible-default"></i> <i class="icon icon-liked hidden-default"></i></a> <a href="javascript:;" class="ft-expression ft-expression--comment ft-expression--liked"><i class="icon icon-comment"></i></a></div> <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow"><a href="javascript:;" class="ft-expression ft-expression--meta" style="display: none;"><span class="icon icon-liked visible-default"></span> <span class="ft-expression--meta-text">
                    0
                </span></a> <a href="javascript:;" class="ft-expression  ft-expression--meta" style=""><span class="icon icon-commentcount"></span> <span class="ft-expression--meta-text">
                    2
                </span></a></div> <div class="ft-comment__item"><a href="javascript:;" class="ft-expression"><i class="icon icon-share"></i></a></div></div></div> <section id="3comment-expand-184" class="zippy suggestion-list-expand"><div class="zippy__wrapper" style="margin-top: -48px;"><div class="ft-loading"><span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span></div></div></section></div></div><div id="ft-post183" class="panel panel-default timeline-posts__item panel-post"><div class="panel-heading no-bg"><div class="post-author"><div class="post-options"><a href="javascript:;" class="ft-btn--icon"><i class="icon icon-options"></i></a></div> <a href="http://localhost/fitmetix/public/mikele" title="@mikele" class="user-avatar" style="background-image: url(&quot;http://localhost/fitmetix/public/images/default.png&quot;);"><img src="http://localhost/fitmetix/public/images/default.png" class="hidden" title="Mikel"></a> <div class="user-post-details"><div class="no-margin"><div class="meta-font"><a href="http://localhost/fitmetix/public/mikele" data-toggle="tooltip" data-placement="top" title="@mikele" data-original-title="@mikele" class="user-name user ft-user-name">
                                                        Mikel
                                                    </a> <div class="small-text"></div></div> <div class="md-layout meta-font"><div class="ft-timeago sub-meta-info"><time datetime="Thu Nov 23 2017 09:20:42 GMT+0530 (IST)" title="23/11/2017, 09:20:42" class="timeago">1 d</time></div> <!----></div></div></div></div></div> <div class="panel-body"><div class="text-wrapper"><div>The button “more…” is for pagination of text in a post, for likes etc. If a post is longer than 150
                                            characters it will show this button ‘more..’. When user clicks on that it will show the complete post.
                                            Put a maximum limit of 600 characters for a post</div></div> <!----> <div class="post-image--wrapper"><div class="image-responsive item__background--post img-viewer"><img src="http://localhost/fitmetix/storage/uploads/users/gallery/2017-11-23-09-20-42IMG_20170320_194105268.jpg" class="img-responsive"></div></div> <!----></div> <div style="width: 100%;"><div class="panel-footer ft-socialite meta-font"><div class="ft-comment md-layout md-layout--row"><div class="ft-comment__item md-layout md-layout--row"><a href="javascript:;" class="ft-expression ft-expression--likes"><i class="icon icon-like visible-default"></i> <i class="icon icon-liked hidden-default"></i></a> <a href="javascript:;" class="ft-expression ft-expression--comment"><i class="icon icon-comment"></i></a></div> <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow"><a href="javascript:;" class="ft-expression ft-expression--meta" style="display: none;"><span class="icon icon-liked visible-default"></span> <span class="ft-expression--meta-text">
                    0
                </span></a> <a href="javascript:;" class="ft-expression  ft-expression--meta" style="display: none;"><span class="icon icon-commentcount"></span> <span class="ft-expression--meta-text">
                    0
                </span></a></div> <div class="ft-comment__item"><a href="javascript:;" class="ft-expression"><i class="icon icon-share"></i></a></div></div></div> <section id="4comment-expand-183" class="zippy suggestion-list-expand"><div class="zippy__wrapper" style="margin-top: -48px;"><div class="ft-loading"><span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span></div></div></section></div></div><div id="ft-post182" class="panel panel-default timeline-posts__item panel-post"><div class="panel-heading no-bg"><div class="post-author"><div class="post-options"><a href="javascript:;" class="ft-btn--icon"><i class="icon icon-options"></i></a></div> <a href="http://localhost/fitmetix/public/mikele" title="@mikele" class="user-avatar" style="background-image: url(&quot;http://localhost/fitmetix/public/images/default.png&quot;);"><img src="http://localhost/fitmetix/public/images/default.png" class="hidden" title="Mikel"></a> <div class="user-post-details"><div class="no-margin"><div class="meta-font"><a href="http://localhost/fitmetix/public/mikele" data-toggle="tooltip" data-placement="top" title="@mikele" data-original-title="@mikele" class="user-name user ft-user-name">
                                                        Mikel
                                                    </a> <div class="small-text"></div></div> <div class="md-layout meta-font"><div class="ft-timeago sub-meta-info"><time datetime="Thu Nov 23 2017 09:16:22 GMT+0530 (IST)" title="23/11/2017, 09:16:22" class="timeago">1 d</time></div> <!----></div></div></div></div></div> <div class="panel-body"><div class="text-wrapper"><div>In the middle of post is the count of the likes and comments for that post (always must be centered,
                                            not like in current version of platform). When user clicks on the icon for likes it shows all the likes
                                            (of course paginated) and when user clicks on the button for comments, it shows all the comments
                                            (paginated, 20 each loading time). Until the user clicks on those icons there are no comments or
                                            likes shown for a post, only the number.

                                        </div></div> <!----> <div class="post-image--wrapper"><div class="image-responsive item__background--post img-viewer"><img src="http://localhost/fitmetix/storage/uploads/users/gallery/2017-11-23-09-16-22IMG_20170301_095548610.jpg" class="img-responsive"></div></div> <!----></div> <div style="width: 100%;"><div class="panel-footer ft-socialite meta-font"><div class="ft-comment md-layout md-layout--row"><div class="ft-comment__item md-layout md-layout--row"><a href="javascript:;" class="ft-expression ft-expression--likes ft-expression--liked"><i class="icon icon-like visible-default"></i> <i class="icon icon-liked hidden-default"></i></a> <a href="javascript:;" class="ft-expression ft-expression--comment ft-expression--liked"><i class="icon icon-comment"></i></a></div> <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow"><a href="javascript:;" class="ft-expression ft-expression--meta" style=""><span class="icon icon-liked visible-default"></span> <span class="ft-expression--meta-text">
                    1
                </span></a> <a href="javascript:;" class="ft-expression  ft-expression--meta" style=""><span class="icon icon-commentcount"></span> <span class="ft-expression--meta-text">
                    1
                </span></a></div> <div class="ft-comment__item"><a href="javascript:;" class="ft-expression"><i class="icon icon-share"></i></a></div></div></div> <section id="6comment-expand-182" class="zippy suggestion-list-expand"><div class="zippy__wrapper" style="margin-top: -48px;"><div class="ft-loading"><span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span></div></div></section></div></div><div id="ft-post181" class="panel panel-default timeline-posts__item panel-post"><div class="panel-heading no-bg"><div class="post-author"><div class="post-options"><a href="javascript:;" class="ft-btn--icon"><i class="icon icon-options"></i></a></div> <a href="http://localhost/fitmetix/public/mikele" title="@mikele" class="user-avatar" style="background-image: url(&quot;http://localhost/fitmetix/public/images/default.png&quot;);"><img src="http://localhost/fitmetix/public/images/default.png" class="hidden" title="Mikel"></a> <div class="user-post-details"><div class="no-margin"><div class="meta-font"><a href="http://localhost/fitmetix/public/mikele" data-toggle="tooltip" data-placement="top" title="@mikele" data-original-title="@mikele" class="user-name user ft-user-name">
                                                        Mikel
                                                    </a> <div class="small-text"></div></div> <div class="md-layout meta-font"><div class="ft-timeago sub-meta-info"><time datetime="Thu Nov 23 2017 09:15:27 GMT+0530 (IST)" title="23/11/2017, 09:15:27" class="timeago">1 d</time></div> <!----></div></div></div></div></div> <div class="panel-body"><div class="text-wrapper"><div>In the middle of post is the count of the likes and comments for that post (always must be centered,
                                            not like in current version of platform). When user clicks on the icon for likes it shows all the likes
                                            (of course paginated) and when user clicks on the button for comments, it shows all the comments
                                            (paginated, 20 each loading time). Until the user clicks on those icons there are no comments or
                                            likes shown for a post, only the number.

                                        </div></div> <!----> <!----> <!----></div> <div style="width: 100%;"><div class="panel-footer ft-socialite meta-font"><div class="ft-comment md-layout md-layout--row"><div class="ft-comment__item md-layout md-layout--row"><a href="javascript:;" class="ft-expression ft-expression--likes"><i class="icon icon-like visible-default"></i> <i class="icon icon-liked hidden-default"></i></a> <a href="javascript:;" class="ft-expression ft-expression--comment"><i class="icon icon-comment"></i></a></div> <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow"><a href="javascript:;" class="ft-expression ft-expression--meta" style="display: none;"><span class="icon icon-liked visible-default"></span> <span class="ft-expression--meta-text">
                    0
                </span></a> <a href="javascript:;" class="ft-expression  ft-expression--meta" style="display: none;"><span class="icon icon-commentcount"></span> <span class="ft-expression--meta-text">
                    0
                </span></a></div> <div class="ft-comment__item"><a href="javascript:;" class="ft-expression"><i class="icon icon-share"></i></a></div></div></div> <section id="2comment-expand-181" class="zippy suggestion-list-expand"><div class="zippy__wrapper" style="margin-top: -48px;"><div class="ft-loading"><span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span></div></div></section></div></div><div id="ft-post180" class="panel panel-default timeline-posts__item panel-post"><div class="panel-heading no-bg"><div class="post-author"><div class="post-options"><a href="javascript:;" class="ft-btn--icon"><i class="icon icon-options"></i></a></div> <a href="http://localhost/fitmetix/public/mikele" title="@mikele" class="user-avatar" style="background-image: url(&quot;http://localhost/fitmetix/public/images/default.png&quot;);"><img src="http://localhost/fitmetix/public/images/default.png" class="hidden" title="Mikel"></a> <div class="user-post-details"><div class="no-margin"><div class="meta-font"><a href="http://localhost/fitmetix/public/mikele" data-toggle="tooltip" data-placement="top" title="@mikele" data-original-title="@mikele" class="user-name user ft-user-name">
                                                        Mikel
                                                    </a> <div class="small-text"></div></div> <div class="md-layout meta-font"><div class="ft-timeago sub-meta-info"><time datetime="Thu Nov 23 2017 09:13:03 GMT+0530 (IST)" title="23/11/2017, 09:13:03" class="timeago">1 d</time></div> <!----></div></div></div></div></div> <div class="panel-body"><div class="text-wrapper"><div>The icons are on the same level and change color when they have been used for a particular post.
                                            When user likes the post, the heart icon switches color from <a class="hashtag" href="http://localhost/fitmetix/public/?hashtag=333">#333</a> to <a class="hashtag" href="http://localhost/fitmetix/public/?hashtag=EB5757">#EB5757</a> and becomes filled.
                                        </div></div> <!----> <div class="post-image--wrapper"><div class="image-responsive item__background--post img-viewer"><img src="http://localhost/fitmetix/storage/uploads/users/gallery/2017-11-23-09-13-03431171_353752254635572_1535486756_n.jpg" class="img-responsive"></div></div> <!----></div> <div style="width: 100%;"><div class="panel-footer ft-socialite meta-font"><div class="ft-comment md-layout md-layout--row"><div class="ft-comment__item md-layout md-layout--row"><a href="javascript:;" class="ft-expression ft-expression--likes"><i class="icon icon-like visible-default"></i> <i class="icon icon-liked hidden-default"></i></a> <a href="javascript:;" class="ft-expression ft-expression--comment"><i class="icon icon-comment"></i></a></div> <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow"><a href="javascript:;" class="ft-expression ft-expression--meta" style="display: none;"><span class="icon icon-liked visible-default"></span> <span class="ft-expression--meta-text">
                    0
                </span></a> <a href="javascript:;" class="ft-expression  ft-expression--meta" style="display: none;"><span class="icon icon-commentcount"></span> <span class="ft-expression--meta-text">
                    0
                </span></a></div> <div class="ft-comment__item"><a href="javascript:;" class="ft-expression"><i class="icon icon-share"></i></a></div></div></div> <section id="4comment-expand-180" class="zippy suggestion-list-expand"><div class="zippy__wrapper" style="margin-top: -48px;"><div class="ft-loading"><span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span></div></div></section></div></div><div id="ft-post179" class="panel panel-default timeline-posts__item panel-post"><div class="panel-heading no-bg"><div class="post-author"><div class="post-options"><a href="javascript:;" class="ft-btn--icon"><i class="icon icon-options"></i></a></div> <a href="http://localhost/fitmetix/public/mikele" title="@mikele" class="user-avatar" style="background-image: url(&quot;http://localhost/fitmetix/public/images/default.png&quot;);"><img src="http://localhost/fitmetix/public/images/default.png" class="hidden" title="Mikel"></a> <div class="user-post-details"><div class="no-margin"><div class="meta-font"><a href="http://localhost/fitmetix/public/mikele" data-toggle="tooltip" data-placement="top" title="@mikele" data-original-title="@mikele" class="user-name user ft-user-name">
                                                        Mikel
                                                    </a> <div class="small-text"></div></div> <div class="md-layout meta-font"><div class="ft-timeago sub-meta-info"><time datetime="Wed Nov 22 2017 15:40:50 GMT+0530 (IST)" title="22/11/2017, 15:40:50" class="timeago">2 d</time></div> <!----></div></div></div></div></div> <div class="panel-body"><div class="text-wrapper"><div>The post can show the location where the photo was taken or post was made. The user can choose to
                                            tag this when they are writing the post. This is optional. If user does not add location then nothing
                                            shows there, only approximate time of the post</div></div> <!----> <div class="post-image--wrapper"><div class="image-responsive item__background--post img-viewer"><img src="http://localhost/fitmetix/storage/uploads/users/gallery/2017-11-22-15-40-50556990_480468835314065_1258221623_n.jpg" class="img-responsive"></div></div> <!----></div> <div style="width: 100%;"><div class="panel-footer ft-socialite meta-font"><div class="ft-comment md-layout md-layout--row"><div class="ft-comment__item md-layout md-layout--row"><a href="javascript:;" class="ft-expression ft-expression--likes ft-expression--liked"><i class="icon icon-like visible-default"></i> <i class="icon icon-liked hidden-default"></i></a> <a href="javascript:;" class="ft-expression ft-expression--comment ft-expression--liked"><i class="icon icon-comment"></i></a></div> <div class="ft-comment__item md-align md-align--center-center ft-comment__item--grow"><a href="javascript:;" class="ft-expression ft-expression--meta" style=""><span class="icon icon-liked visible-default"></span> <span class="ft-expression--meta-text">
                    1
                </span></a> <a href="javascript:;" class="ft-expression  ft-expression--meta" style=""><span class="icon icon-commentcount"></span> <span class="ft-expression--meta-text">
                    3
                </span></a></div> <div class="ft-comment__item"><a href="javascript:;" class="ft-expression"><i class="icon icon-share"></i></a></div></div></div> <section id="9comment-expand-179" class="zippy suggestion-list-expand"><div class="zippy__wrapper" style="margin-top: -48px;"><div class="ft-loading"><span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span> <span class="ft-loading__dot"></span></div></div></section></div></div> <!----> <!----></div> <div id="scroll-bt"></div></div>










                </div>
            </div><!-- /col-md-6 -->

        </div>
    </div>
    <!-- </div> -->
    <!-- /main-section -->
    <app-dialog-option></app-dialog-option>
</div>

<!-- Modal starts here-->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel">
    <div class="modal-dialog modal-likes" role="document">
        <div class="modal-content">
            <i class="fa fa-spinner fa-spin"></i>
        </div>
    </div>
</div>
<div class="footer-description">
    <div class="footer__wrapper">
        <div class="socialite-terms text-center">

        </div>
        <div class="ft-copyright text-center">
            Copyright © 2017 Fitmetix. All Rights Reserved
        </div>
        <div class="multi-lang">
            <input type="hidden" name="_token" value="7vtbOkv6q5q16wL6wTCbPzPVt4Lhly4UOpVimcjv">
            <a href="javascript:;" class="multi-lang__item cover-bg switch-language" data-language="es" style="background-image: url(http://localhost/fitmetix/public/images/sp.png)"></a>
            <a href="javascript:;" class="multi-lang__item cover-bg switch-language" data-language="en" style="background-image: url(http://localhost/fitmetix/public/images/en.png)"></a>
        </div>
        <div class="multi-lang multi-lang--mobile">
            <a href="javascript:;" class="multi-lang__option cover-bg switch-language" data-language="es" style="background-image: url(http://localhost/fitmetix/public/images/sp.png)"></a>
            <a href="javascript:;" class="lang__option multi-lang__option cover-bg switch-language" data-language="en" style="background-image: url(http://localhost/fitmetix/public/images/en.png)"></a>
        </div>
    </div>
</div>




<script>
    // Pusher.logToConsole = true;
    var pusherConfig = {
        token: "7vtbOkv6q5q16wL6wTCbPzPVt4Lhly4UOpVimcjv",
        PUSHER_KEY: "085500f73a0ef80c8b8a"
    };
</script>
<script src="http://localhost/fitmetix/public/js/bundle.js" type="text/javascript"></script>
<script src="http://localhost/fitmetix/public/js/dialog.js" type="text/javascript"></script>
<script src="http://localhost/fitmetix/public/js/app.js" type="text/javascript"></script>
<script src="http://localhost/fitmetix/public/js/notification.js" type="text/javascript"></script>
<form action="http://localhost/fitmetix/public/logout" method="post" style="opacity:.1;position:fixed; bottom: 0; left: 0; z-index: 11">
    <input type="hidden" name="_token" value="7vtbOkv6q5q16wL6wTCbPzPVt4Lhly4UOpVimcjv">

    <button type="submit" class="btn-logout">
        <i class="fa fa-unlock" aria-hidden="true"></i>Logout
    </button>
</form>


<div style="position: absolute; left: 0px; top: -2px; height: 1px; overflow: hidden; visibility: hidden; width: 1px;"><span style="position: absolute; font-size: 300px; width: auto; height: auto; margin: 0px; padding: 0px; font-family: Roboto, Arial, sans-serif;">BESbswy</span></div><div class="pac-container pac-logo" style="display: none; width: 0px; position: absolute; left: 0px; top: 0px;"></div></body></html>


<script>
    var Calendar = function(model, options, date){
        // Default Values
        this.Options = {
            Color: '',
            LinkColor: '',
            NavShow: true,
            NavVertical: false,
            NavLocation: '',
            DateTimeShow: true,
            DateTimeFormat: 'mmm, yyyy',
            DatetimeLocation: '',
            EventClick: '',
            EventTargetWholeDay: false,
            DisabledDays: [],
            ModelChange: model
        };
        // Overwriting default values
        for(var key in options){
            this.Options[key] = typeof options[key]=='string'?options[key].toLowerCase():options[key];
        }

        model?this.Model=model:this.Model={};
        this.Today = new Date();

        this.Selected = this.Today
        this.Today.Month = this.Today.getMonth();
        this.Today.Year = this.Today.getFullYear();
        if(date){this.Selected = date}
        this.Selected.Month = this.Selected.getMonth();
        this.Selected.Year = this.Selected.getFullYear();

        this.Selected.Days = new Date(this.Selected.Year, (this.Selected.Month + 1), 0).getDate();
        this.Selected.FirstDay = new Date(this.Selected.Year, (this.Selected.Month), 1).getDay();
        this.Selected.LastDay = new Date(this.Selected.Year, (this.Selected.Month + 1), 0).getDay();

        this.Prev = new Date(this.Selected.Year, (this.Selected.Month - 1), 1);
        if(this.Selected.Month==0){this.Prev = new Date(this.Selected.Year-1, 11, 1);}
        this.Prev.Days = new Date(this.Prev.getFullYear(), (this.Prev.getMonth() + 1), 0).getDate();
    };

    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
</script>



<div id="user-login" class="md-menu-wrapper hidden" data-position="top right" data-location="top" data-width="3">
    <div class="md-menu">
        <a class="md-menu__item md-button" data-value="login" href="javascript:;">
            <span>Login</span>
        </a>
        <li class="inbox-message" v-for="conversation in conversations.data">
            <a href="javascript:;" class="md-menu__item md-button" :data-user-id="conversation.user.id" onclick="chatBoxes.sendMessageOnClick(this)">
                <div class="media">
                    <div class="media-left">
                        <img class="media-object img-icon" v-bind:src="conversation.user.avatar" alt="images">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <span class="message-heading">@{{ conversation.user.name }}</span>
                            <span class="online-status hidden"></span>
                            <!--TODO timeago -->
                        </h4>
                        <p class="message-text">
                            <!--@{{ conversation.lastMessage.body }}-->
                        </p>
                    </div>
                </div>
            </a>
        </li>
    </div>
</div>


<ul class="dropdown-menu dropdown-menu ft-menu" data-width="3">
    <li class="dropdown-menu-header ft-menu__item">
        <span class="side-left">{{ ntCommonMessages }}</span>
    </li>
    <li class="no-messages ft-menu__item ft-menu__item--icon" v-if="noMessage">
        <i class="fa fa-commenting-o"></i>
        <p>{{ ntMessageNo }}</p>
    </li>
    <li class="inbox-message" v-for="conversation in conversations.data">
        <a href="javascript:;" :data-user-id="conversation.user.id" onclick="chatBoxes.sendMessageOnClick(this)">
            <div class="media">
                <div class="media-left">
                    <img class="media-object img-icon" v-bind:src="conversation.user.avatar" alt="images">
                </div>
                <div class="media-body">
                    <h4 class="media-heading">
                        <span class="message-heading">@{{ conversation.user.name }}</span>
                        <span class="online-status hidden"></span>
                        <!--TODO timeago -->
                    </h4>
                    <p class="message-text">
                        <!--@{{ conversation.lastMessage.body }}-->
                    </p>
                </div>
            </div>
        </a>
    </li>
    <li class="dropdown-menu-footer ft-menu__item ft-menu__item--icon">
        <a :href="ntSeeAllLink">{{ ntSeeAll }}</a>
    </li>
</ul>