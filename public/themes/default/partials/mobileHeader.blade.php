<div class="ft-header hidden-md hidden-lg">
    <div class="ft-header-nav">
        @if(Auth::user())
            <a class="ft-header-nav__item {{ Request::is('/') ? 'is-active' : '' }}" href="{{ url('/') }}">
                <div class="icon" data-icon="n"></div>
            </a>
            <a class="ft-header-nav__item {{ Request::is(Auth::user()->username.'/create-event') ? 'is-active' : '' }}" style="padding: 0" href="{!! url(Auth::user()->username.'/create-event') !!}">
                <div class="icon icon-eventpage" style="font-size: 50px; line-height: 45px"></div>
            </a>
            <a class="ft-header-nav__item pos-rel {{ Request::is('messages') ? 'is-active' : '' }}" href="{!! url('notifications') !!}">
                <div class="icon icon icon-like"></div>
                <span class="unread-notification is-visible" v-bind:class="{ 'is-visible': isShowUN }"></span>
            </a>
            <a class="ft-header-nav__item pos-rel" href="{!! url('messages') !!}">
                <i class="icon icon-chat"></i>
                <span class="unread-notification is-visible" v-bind:class="{ 'is-visible': isShowUCM }"></span>
            </a>
            <a class="ft-header-nav__item" href="{!! url('messages') !!}" data-toggle="collapse" href="#bs-example-navbar-collapse-4" aria-expanded="false" aria-controls="collapseExample">
                <div class="icon icon-search"></div>
            </a>
            <div class="dropdown ft-header-nav__item pos-rel">
                <a class="dropdown-toggle ft-header-nav__item--user-img" data-toggle="dropdown" @click.prevent="showNotifications" role="button" href="javascript:;" aria-haspopup="true"
                   aria-expanded="false">
                    <div class="user-avatar" style="background-image: url(//localhost:3000/fitmetix/public/user/avatar/2017-10-22-14-07-04athletebookprofilepage.png)"></div>
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
                        <li>
                            <form action="{{ url('/logout') }}" method="post" style="height: 40px">
                            {{ csrf_field() }}

                                <button type="submit" class="ft-menu__item ft-menu__item--icon btn btn-logout">
                                    <i class="fa fa-unlock" aria-hidden="true"></i>{{ trans('common.logout') }}
                                </button>
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

