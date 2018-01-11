<div class="ft-header hidden-md hidden-lg">
    <form class="ft-header-nav">
        @if(Auth::user())
            <a class="ft-header-nav__item {{ Request::is('/') ? 'is-active' : '' }}" href="{{ url('/') }}">
                <div class="icon" data-icon="n"></div>
            </a>
            <a href="{{ url(Auth::user()->username.'/events') }}" class="has-hover-effect ft-header-nav__item fm-nav__item {{ (Request::is(Auth::user()->username.'/events') ? 'is-active' : '') }}">
                <object data="{{asset('images/Run.svg')}}" class="svg-object hidden-active" type="image/svg+xml" style="height: 34px">
                </object>
                <object data="{{asset('images/RunBlack.svg')}}" class="svg-object visible-active" type="image/svg+xml" style="height: 34px">
                </object>
            </a>
            <a id="ft-mobile-nt" class="ft-header-nav__item pos-rel {{ Request::is('allnotifications') ? 'is-active' : '' }}" href="{!! url('allnotifications') !!}">
                <div class="icon icon icon-like"></div>
                <span class="unread-notification is-shown-un"></span>
            </a>
            <a class="ft-header-nav__item pos-rel {{ Request::is('conversation') ? 'is-active' : '' }}" href="{{url('conversation')}}">
                <i class="icon icon-chat"></i>
                <span class="unread-notification" v-bind:class="{ 'is-visible': isShowUCM }"></span>
            </a>
            <a class="ft-header-nav__item" href="{!! url('search') !!}">
                <div class="icon icon-search"></div>
            </a>
            <form action="{{ url('/logout') }}" method="post" class="ft-header-nav__item">
                <a class="ft-header-nav__item--user-img toggleSlide" href="javascript:;">
                    <div class="user-avatar" style="background-image: url('{{url(Auth::user()->avatar)}}')"></div>
                </a>
            </form>
        @else
            <div class="ft-header-nav__item"></div>
            <div class="ft-header-nav__item"></div>
            <div class="ft-header-nav__item"></div>
        @endif
    </div>
</div>

<div class="hidden-lg hidden-md mobile-menu-slide">
    <div>
        <ul class="list-group list-group--ft" style="font-weight: 600;">
            <li class="list-group-item"><img src="{{ asset('images/left-arrow.png') }}" alt="" class="toggleSlide"></li>
            <li class="list-group-item">
                <a href="{{ url(Auth::user()->username) }}"><img src="{{ url(Auth::user()->avatar) }}" class="user-avatar" style="border-radius: 0;"> &nbsp; My Profile</a>
            </li>
            <li class="list-group-item">
                <a href="{{ url('/'.Auth::user()->username.'/settings/general') }}">General Settings</a>
            </li>
            <li class="list-group-item">
                <a href="{{ url('/'.Auth::user()->username.'/settings/privacy') }}">Privacy Settings</a>
            </li>
            <li class="list-group-item">
                <a href="{{ url('/'.Auth::user()->username.'/settings/general') }}">Email Notifications</a>
            </li>
            <li class="list-group-item">
                <a href="javascript:;">Deactivate Account</a>
            </li>
        </ul>
    </div>
</div>