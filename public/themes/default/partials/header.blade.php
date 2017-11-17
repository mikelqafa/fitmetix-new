@if(Auth::guest())
    <nav class="navbar socialite navbar-default no-bg hidden-sm hidden-xs">
        <div class="container">
            <div class="md-header__row layout-p-l-0 layout-p-r-0">
                <div class="navbar-header">
                    <a class="navbar-brand socialite" href="{{ url('/') }}">
                        <img class="socialite-logo" src="{!! url('setting/'.Setting::get('logo')) !!}"
                             alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}">
                    </a>
                </div>
            </div>
        </div>
    </nav>
@else
    <nav class="navbar ft-custom socialite navbar-default no-bg hidden-sm hidden-xs">
        <div class="container md-layout md-layout--row" style="max-width: 960px;">
            <div class="no-float navbar-header">
                <a class="navbar-brand socialite" href="{{ url('/') }}">
                    <img class="socialite-logo" src="{!! url('setting/'.Setting::get('logo')) !!}"
                         alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}">
                </a>
            </div>
            <div class="md-layout-spacer"></div>
            <form class="no-float navbar-form navbar-left form-left" role="search">
                <div class="input-group no-margin">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                        </span>
                    <input type="text" id="navbar-search" style="width: 200px" data-url="{{ URL::to('api/v1/timelines') }}"
                           class="form-control"
                           placeholder="Search">
                </div>
            </form>
            <div class="md-layout-spacer"></div>
            <div class="nav no-float md-layout fm-nav navbar-nav hidden-sm hidden-xs" id="navbar-right">
                <a href="{{ url(Auth::user()->username.'/create-event') }}" class="has-hover-effect fm-nav__item">
                    <span>
                        <i class="fa fa-plus"></i> Inspire
                    </span>
                </a>
                <a href="{{ url('events') }}" class="has-hover-effect fm-nav__item">
                    Events
                </a>
                <div id="app-notification">
                    <app-notification>
                        <a href="javascript:;" data-toggle="dropdown" @click.prevent="showNotifications" class="has-hover-effect fm-nav__item dropdown message hidden-sm hidden-xs">
                            <div class="icon icon-like"></div>
                        </a>
                    </app-notification>
                </div>
                {{--TODO working on message notification--}}
                <input type="hidden" name="nt-count" value="{{Auth::user()->notifications()->count()}}">
                <input type="hidden" name="nt-common-messages" value="{{ trans('common.messages') }}">
                <input type="hidden" name="nt-common-see_all" value="{{ trans('common.see_all') }}">
                <input type="hidden" name="nt-no_messages" value="{{ trans('messages.no_messages') }}">
                <input type="hidden"  name="see-all-messages" value="{{ url('messages') }}">
                <a href="{{ url(Auth::user()->username) }}" class="has-hover-effect fm-nav__item user-image socialite fm-nav__item">
                    <span class="user-image-wrapper">
                        <img src="{{asset('images/default.png')}}" style="max-width: 100%" alt="{{ Auth::user()->name }}"
                             class="img-radius img-30" title="{{ Auth::user()->name }}">
                        <span class="user-name hidden">{{ Auth::user()->name }}</span>
                    </span>
                </a>

                <div class="dropdown message vert-has">
                    <a href="{{ url(Auth::user()->username) }}" class="has-hover-effect fm-nav__item fm-nav__item--no-padding dropdown-toggle"
                       data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">
                        <div class="icon icon-options"></div>
                    </a>
                    <ul data-width="3" class="dropdown-menu ft-menu" style="left: -123px;border-top: none">
                        @if(Auth::user()->hasRole('admin'))
                            <li class="{{ Request::segment(1) == 'admin' ? 'active' : '' }}">
                                <a href="{{ url('admin') }}" class="ft-menu__item  ft-menu__item--icon">
                                    <i class="fa fa-user-secret" aria-hidden="true"></i>{{ trans('common.admin') }}
                                </a>
                            </li>
                        @endif

                        <li class="{{ (Request::segment(1) == Auth::user()->username && Request::segment(2) == '') ? 'active' : '' }}">
                            <a href="{{ url(Auth::user()->username) }}" class="ft-menu__item ft-menu__item--icon">
                                <i class="fa fa-user" aria-hidden="true"></i>{{ trans('common.my_profile') }}
                            </a>
                        </li>

                        <li class="{{ Request::segment(2) == 'albums' ? 'active' : '' }}">
                            <a  href="{{ url(Auth::user()->username.'/albums') }}" class="ft-menu__item ft-menu__item--icon">
                                <i class="fa fa-image" aria-hidden="true"></i>{{ trans('common.my_albums') }}
                            </a>
                        </li>

                        <li class="{{ Request::segment(3) == 'events' ? 'active' : '' }}">
                            <a class="ft-menu__item ft-menu__item--icon"
                                    href="{{ url(Auth::user()->username.'/events') }}"><i
                                        class="fa fa-calendar"
                                        aria-hidden="true"></i>{{ trans('common.my_events') }}</a>
                        </li>

                        <li class="{{ Request::segment(3) == 'general' ? 'active' : '' }}">
                            <a class="ft-menu__item ft-menu__item--icon" href="{{ url('/'.Auth::user()->username.'/settings/general') }}">
                                <i class="fa fa-cog" aria-hidden="true"></i>{{ trans('common.settings') }}
                            </a>
                        </li>

                        <li>
                            <form action="{{ url('/logout') }}" method="post">
                                {{ csrf_field() }}

                                <button type="submit" class="ft-menu__item ft-menu__item--icon btn btn-logout">
                                    <i class="fa fa-unlock" aria-hidden="true"></i>{{ trans('common.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
@endif