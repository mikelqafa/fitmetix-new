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