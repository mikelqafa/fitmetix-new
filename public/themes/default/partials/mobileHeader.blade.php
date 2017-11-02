<div class="ft-header hidden-md hidden-lg">
    <div class="ft-header-nav">
        @if(Auth::user())
            <a class="ft-header-nav__item {{ Request::is('/') ? 'is-active' : '' }}" href="{{ url('/') }}">
                <div class="icon" data-icon="n"></div>
            </a>
            <a class="ft-header-nav__item {{ Request::is(Auth::user()->username.'/create-event') ? 'is-active' : '' }}" style="padding: 0" href="{!! url(Auth::user()->username.'/create-event') !!}">
                <div class="icon icon-eventpage" style="font-size: 50px; line-height: 45px"></div>
            </a>
            <a class="ft-header-nav__item dropdown" data-toggle="dropdown" href="{!! url('notifications') !!}" class="dropdown-toggle" role="button" aria-haspopup="true"
               aria-expanded="false">
                <div class="icon icon icon-like"></div>
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
                                <a href="{{ url(Auth::user()->username.'/notification/') }}/@{{ notification.id }}">
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
                                                      datetime="@{{ notification.created_at }}+00:00"
                                                      title="@{{ notification.created_at }}+00:00">
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
            </a>
            <a class="ft-header-nav__item" href="{!! url('messages') !!}">
                <div class="icon icon-chat"></div>
            </a>
            <a class="ft-header-nav__item" href="{!! url('messages') !!}" data-toggle="collapse" href="#bs-example-navbar-collapse-4" aria-expanded="false" aria-controls="collapseExample">
                <div class="icon icon-search"></div>
            </a>
            <div class="dropdown ft-header-nav__item pos-rel">
                <a class="dropdown-toggle ft-header-nav__item--user-img" data-toggle="dropdown" @click.prevent="showNotifications" role="button" aria-haspopup="true"
                   aria-expanded="false">
                    <div class="user-avatar" style="background-image: url(//localhost:3000/fitmetix/public/user/avatar/2017-10-22-14-07-04athletebookprofilepage.png)"></div>
                    <ul style="left: auto; right: 0;" class="dropdown-menu">
                        <li class="{{ (Request::segment(1) == Auth::user()->username && Request::segment(2) == '') ? 'active' : '' }}">
                            <a href="{{ url(Auth::user()->username.'/create-event') }}">
                                <i class="fa fa-plus"></i> Inspire
                            </a>
                        </li>
                        <li class="{{ (Request::segment(1) == Auth::user()->username && Request::segment(2) == '') ? 'active' : '' }}">
                            <a href="{{ url(Auth::user()->username) }}">
                                <i class="icon icon-user"></i> {{ trans('common.my_profile') }}
                            </a>
                        </li>
                        <li class="{{ Request::segment(3) == 'general' ? 'active' : '' }}"><a
                                    href="{{ url('/'.Auth::user()->username.'/settings/general') }}">
                                <i class="icon icon-settings"></i> {{ trans('common.settings') }}</a>
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

