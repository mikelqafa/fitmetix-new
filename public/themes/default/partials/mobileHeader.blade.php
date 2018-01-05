<div class="ft-header hidden-md hidden-lg">
    <div class="ft-header-nav">
        @if(Auth::user())
            <a class="ft-header-nav__item {{ Request::is(Auth::user()->username.'/create-event') ? 'is-active' : '' }}" href="{{ url('/') }}">
                <div class="icon" data-icon="n"></div>
            </a>
            <a href="{{ url(Auth::user()->username.'/events') }}" class="has-hover-effect fm-nav__item {{ (Request::is(Auth::user()->username.'/events') ? 'is-active' : '') }}">
                <div class="icon icon-eventpage" style="font-size: 50px; line-height: 45px"></div>
            </a>
            <a id="ft-mobile-nt" class="ft-header-nav__item pos-rel {{ Request::is('allnotifications') ? 'is-active' : '' }}" href="{!! url('allnotifications') !!}">
                <div class="icon icon icon-like"></div>
                <span class="unread-notification is-shown-un"></span>
            </a>
            <a class="ft-header-nav__item pos-rel" href="{{url('messages')}}">
                <i class="icon icon-chat"></i>
                <span class="unread-notification" v-bind:class="{ 'is-visible': isShowUCM }"></span>
            </a>
            <a class="ft-header-nav__item" href="{!! url('search') !!}">
                <div class="icon icon-search"></div>
            </a>
            <form action="{{ url('/logout') }}" method="post" class="ft-header-nav__item">
                <a class="dropdown-toggle ft-header-nav__item--user-img" data-toggle="dropdown" @click.prevent="showNotifications" role="button" href="javascript:;" aria-haspopup="true"
                   aria-expanded="false">
                    <div class="user-avatar" style="background-image: url('{{url(Auth::user()->avatar)}}')"></div>
                    <ul style="left: auto; right: 0;" data-width="3" class="ft-menu dropdown-menu">
                        <li class="{{ (Request::segment(1) == Auth::user()->username && Request::segment(2) == '') ? 'active' : '' }}">
                            <a href="{{ url(Auth::user()->username.'/create-event') }}" class="ft-menu__item  ft-menu__item--icon">
                                <i class="icon icon-add"></i> Inspire
                            </a>
                        </li>
                        <li class="{{ (Request::segment(1) == Auth::user()->username && Request::segment(2) == '') ? 'active' : '' }}">
                            <a href="{{ url(Auth::user()->username) }}" class="ft-menu__item  ft-menu__item--icon">
                                <i class="icon icon-participant"></i> {{ trans('common.my_profile') }}
                            </a>
                        </li>
                        <li class="{{ Request::segment(3) == 'general' ? 'active' : '' }}">
                            <a href="{{ url('/'.Auth::user()->username.'/settings/general') }}" class="ft-menu__item ft-menu__item--icon">
                                <i class="icon icon-settings-o"></i> {{ trans('common.settings') }}
                            </a>
                        </li>
                        <li style="height: 40px">
                            <form action="{{ url('/logout') }}" method="post" style="height: 40px">
                                <button type="submit" class="ft-menu__item ft-menu__item--icon btn btn-logout" style="margin-top: -20px">
                                    <i class="fa fa-unlock" aria-hidden="true"></i>{{ trans('common.logout') }}
                                </button>
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </a>
            </div>
        @else
            <div class="ft-header-nav__item"></div>
            <div class="ft-header-nav__item"></div>
            <div class="ft-header-nav__item"></div>
        @endif
    </div>
</div>

