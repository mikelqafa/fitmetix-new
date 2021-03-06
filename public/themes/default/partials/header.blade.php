@if(Auth::guest())
    <nav class="navbar socialite navbar-default no-bg hidden-sm hidden-xs">
        <div class="container">
            <div class="md-header__row layout-p-l-0 layout-p-r-0">
                <div class="navbar-header">
                    <a class="navbar-brand socialite" href="{{ url('/') }}">
                        <img class="socialite-logo" src="{!! url('images/logo.png') !!}"
                             alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}">
                    </a>
                </div>
            </div>
        </div>
    </nav>
@else
    <nav class="navbar ft-custom socialite navbar-default no-bg hidden-sm hidden-xs">
        <div class="container md-layout md-layout--row" style="max-width: 1024px;">
            <div class="no-float navbar-header">
                <a class="navbar-brand socialite" href="{{ url('/') }}">
                    <img class="socialite-logo" src="{!! url('images/logo.png') !!}"
                         alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}">
                </a>
            </div>
            <div class="md-layout-spacer"></div>

            <div class="no-float ft-search--navbar navbar-form navbar-left form-left" id="app-search-desktop">
                <app-search-desktop></app-search-desktop>
            </div>
            <div class="md-layout-spacer"></div>
            <div class="nav no-float md-layout fm-nav navbar-nav hidden-sm hidden-xs" id="navbar-right">
                <a href="{{ url(Auth::user()->username.'/create-event') }}" class="has-hover-effect fm-nav__item {{ Request::path() == Auth::user()->username.'/create-event' ? 'is-active' : '' }}">
                    <span>
                        <i class="fa fa-plus"></i> {{ trans('common.inspire') }}
                    </span>
                </a>
                <a href="{{ url(Auth::user()->username.'/events') }}" class="has-hover-effect fm-nav__item {{ (Request::is(Auth::user()->username.'/events') ? 'is-active' : '') }}">
                    {{ trans('common.events') }}
                </a>
                <div id="app-notification">
                    <app-notification>
                        <a href="javascript:;" style="height: 60px" class="has-hover-effect fm-nav__item dropdown message hidden-sm hidden-xs">
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
                {{--{{ Auth::user() }}--}}
                <a href="{{ url(Auth::user()->username) }}" class="has-hover-effect fm-nav__item user-image socialite fm-nav__item">

                    @if(Auth::user()->timeline->avatar != null)
                        <span class="user-image-wrapper" style="background-image: url('{{ url('user/avatar/100_'.Auth::user()->timeline->avatar->source) }}')">
                        </span>
                    @else
                        <span class="user-image-wrapper" style="background-image: url('{{ url('user/avatar/100_default-male-avatar.png')  }}')">
                        </span>
                    @endif
                </a>
                <input type="hidden" id="auth-user-avatar" value="{{url(Auth::user()->avatar)}}">
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

                        <li class="{{ Request::segment(2) == 'general' ? 'active' : '' }}">
                            <a class="ft-menu__item ft-menu__item--icon" href="{{ url('/'.Auth::user()->username.'/settings/general') }}">
                                <i class="fa fa-cog" aria-hidden="true"></i>{{ trans('common.settings') }}
                            </a>
                        </li>
                            <li class="{{ Request::segment(3) == 'general' ? 'active' : '' }}">
                                <a class="ft-menu__item ft-menu__item--icon" href="{{ url('/'.Auth::user()->username.'/settings/privacy') }}">
                                    <i class="fa fa-user-secret" aria-hidden="true"></i>{{ trans('common.privacy') }}
                                </a>
                            </li>
                            @if(Auth::user()->custom_option1 == 'scout')
                                <li class="{{ Request::segment(4) == 'general' ? 'active' : '' }}">
                                    <a class="ft-menu__item ft-menu__item--icon" href="{{ url('/'.Auth::user()->username.'/settings/affliates') }}">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>{{ trans('common.affiliates') }}
                                    </a>
                                </li>
                            @endif
                            <li class="">
                                <div class="ft-menu__item ft-menu__item--icon">
                                    <i class="" aria-hidden="true">
                                        <img class="img-responsive" src="{{asset('images/drop.png')}}" style="max-width: 18px">
                                    </i>
                                    <div class="md-layout md-layout--row">
                                        <div class="color-picker" data-color="#f9fafc" title="Change Theme"></div>
                                        <div class="color-picker" data-color="#ffffff" title="Change Theme"></div>
                                        <div class="color-picker" data-color="#fbf1f4" title="Change Theme"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="{{ Request::segment(3) == 'general' ? 'active' : '' }}">
                                <a class="ft-menu__item ft-menu__item--icon" href="javascript:;" onclick="FacebookInviteFriends()">
                                    <i class="" aria-hidden="true">
                                        <img class="img-responsive" src="{{asset('images/invitation.png')}}" style="max-width: 18px">
                                    </i>
                                    {{ trans('common.invite_friends') }}
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
    <div id="app-send-direct">
        <app-send-direct></app-send-direct>
    </div>
@endif