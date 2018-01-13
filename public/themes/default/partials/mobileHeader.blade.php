<div class="ft-header hidden-md hidden-lg">
    <form class="ft-header-nav">
        @if(Auth::user())
            <a class="ft-header-nav__item fm-nav__item {{ Request::is('/') ? 'is-active' : '' }}" href="{{ url('/') }}">
                <div class="navicon hidden-active" data-icon="f" style="font-size: 22px"></div>
                <div class="navicon visible-active" data-icon="e" style="font-size: 22px"></div>
            </a>
            <a href="{{ url(Auth::user()->username.'/events') }}" class="has-hover-effect ft-header-nav__item fm-nav__item {{ (Request::is(Auth::user()->username.'/events') ? 'is-active' : '') }}">
                <span>
                    <img src="{{asset('images/Run.svg')}}" class="svg-object hidden-active" type="image/svg+xml" style="height: 30px"/>
                    <img src="{{asset('images/RunBlack.svg')}}" class="svg-object visible-active" type="image/svg+xml" style="height: 30px"/>
                </span>
            </a>
            <a id="ft-mobile-nt" class="ft-header-nav__item pos-rel {{ Request::is('allnotifications') ? 'is-active' : '' }}" href="{!! url('allnotifications') !!}">
                <div class="navicon icon-like hidden-active" data-icon="d" ></div>
                <div class="navicon icon-like visible-active" data-icon="c" ></div>
                <span class="unread-notification is-shown-un"></span>
            </a>
            <a class="ft-header-nav__item pos-rel {{ Request::is('conversation') ? 'is-active' : '' }}" href="{{url('conversation')}}">
                <div class="navicon icon-chat hidden-active" data-icon="b" ></div>
                <div class="navicon icon-chat visible-active" data-icon="a" ></div>
                <span class="unread-notification is-shown-msg"></span>
            </a>
            <a class="ft-header-nav__item {{ Request::is('search') ? 'is-active' : '' }}" href="{!! url('search') !!}">
                <div class="icon icon-font-match icon-search"></div>
            </a>
            <form action="{{ url('/logout') }}" method="post" class=" ft-header-nav__item">
                <a class="ft-header-nav__item--user-img toggleSlide ft-header-nav__item pos-rel" href="javascript:;">
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
<style>
    div.navicon{
        line-height: 24px;
    }
    div[data-icon]:before{
        font-size:inherit;
    }
    .ft-header div.navicon, .ft-header .icon-font-match{
        font-size:20px;
        margin-top: 2px;
    }
</style>