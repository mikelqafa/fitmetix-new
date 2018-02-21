<div class="ft-header hidden-md hidden-lg" style="border-bottom: 1px solid #333">
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
                    @if(Auth::user()->timeline->avatar != null)
                        <div class="user-avatar" style="background-image: url('{{ url('user/avatar/100_'.Auth::user()->timeline->avatar->source) }}')"></div>
                    @else 
                        <div class="user-avatar" style="background-image: url('{{ url('user/avatar/100_default-male-avatar.png')  }}')"></div>
                    @endif
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
            <li class="list-group-item" style="padding-left: 20px"><img src="{{ asset('images/left-arrow.png') }}" alt="" class="toggleSlide"></li>
            <li class="list-group-item text-first-up">
                <a href="{{ url(Auth::user()->username) }}">
                    <img src="{{ url(Auth::user()->avatar) }}" class="user-avatar" style="border-radius: 0;">
                    &nbsp; {{ trans('common.my_profile') }}</a>
            </li>
            <li class="list-group-item">
                <a href="{{ url(Auth::user()->username.'/create-event') }}"><i class="fa fa-plus"></i> {{ trans('common.inspire') }}</a>
            </li>
            <li class="list-group-item">
                <a href="{{ url('/'.Auth::user()->username.'/settings/general') }}">{{ trans('common.settings') }}</a>
            </li>
            <li class="list-group-item">
                <a href="{{ url('/'.Auth::user()->username.'/settings/privacy') }}">{{ trans('common.privacy') }}</a>
            </li>
            <li class="list-group-item">
                <a href="{{ url('/terms') }}">
                    {{ trans('common.t_c') }}
                </a>
            </li>
{{--             <li class="list-group-item">
                <a href="javascript:;" onclick="FacebookInviteFriends()">
                    {{ trans('common.invite_friends') }}
                </a>
            </li> --}}
            @if(Auth::user()->custom_option1 == 'scout')
                <li class="list-group-item">
                    <a href="{{ url('/'.Auth::user()->username.'/settings/affliates') }}">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>{{ trans('common.affiliates') }}
                    </a>
                </li>
            @endif
            <li class="list-group-item">
                <form action="{{ url('/logout') }}" method="post">
                        <button type="submit" class="btn btn-link" style="padding-left:0;color: #10413E;font-weight: 600;">
                            {{ trans('common.logout') }}
                        </button>
                        {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
</div>
<div class="md-menu__backdrop"></div>

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