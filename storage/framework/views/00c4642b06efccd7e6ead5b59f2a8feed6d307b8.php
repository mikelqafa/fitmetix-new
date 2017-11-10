<?php if(Auth::guest()): ?>
    <nav class="navbar socialite navbar-default no-bg guest-nav hidden-sm hidden-xs">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand socialite" href="<?php echo e(url('/')); ?>">
                    <img class="socialite-logo" src="<?php echo url('setting/'.Setting::get('logo')); ?>"
                         alt="<?php echo e(Setting::get('site_name')); ?>" title="<?php echo e(Setting::get('site_name')); ?>">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-4">
                <form class="navbar-form navbar-left form-left" role="search">
                    <div class="input-group no-margin">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                        </span>
                        <input type="text" id="navbar-search" data-url="<?php echo e(URL::to('api/v1/timelines')); ?>"
                               class="form-control"
                               placeholder="<?php echo e(trans('messages.search_placeholder')); ?>">
                    </div>
                </form>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <form method="POST" class="login-form navbar-form navbar-right"
                          action="<?php echo e(url('/login')); ?>">
                        <?php echo e(csrf_field()); ?>

                        <fieldset
                                class="form-group mail-form <?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                            <?php echo e(Form::text('email', NULL, ['class' => 'form-control', 'id' => 'email', 'placeholder'=> trans('auth.enter_email_or_username')])); ?>

                        </fieldset>
                        <fieldset class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                            <?php echo e(Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder'=> trans('auth.password')])); ?>

                            <ul class="list-inline">
                                <li>
                                    <a href="<?php echo e(url('/register')); ?>" class="forgot-password"><i
                                                class="fa fa-user-plus"></i> Create new Account</a>
                                </li>
                                <li>
                                    <a href="<?php echo e(url('/password/reset')); ?>" class="forgot-password"><i
                                                class="fa fa-refresh"></i> Forgot your password</a>
                                </li>
                            </ul>
                        </fieldset>
                        <?php echo e(Form::button( trans('common.signin') , ['type' => 'submit','class' => 'btn btn-success btn-submit'])); ?>

                    </form>
                </div>
            </div><!-- /.navbar-collapse -->
        </div>
    </nav>
<?php else: ?>
    <nav class="navbar socialite navbar-default no-bg hidden-sm hidden-xs">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand socialite" href="<?php echo e(url('/')); ?>">
                    <img class="socialite-logo" src="<?php echo url('setting/'.Setting::get('logo')); ?>"
                         alt="<?php echo e(Setting::get('site_name')); ?>" title="<?php echo e(Setting::get('site_name')); ?>">
                </a>
            </div>
            <div class="navbar-collapse" id="bs-example-navbar-collapse-4">
                <form class="navbar-form navbar-left form-left" role="search">
                    <div class="input-group no-margin">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                        </span>
                        <input type="text" id="navbar-search" data-url="<?php echo e(URL::to('api/v1/timelines')); ?>"
                               class="form-control"
                               placeholder="<?php echo e(trans('messages.search_placeholder')); ?>">
                    </div>
                </form>
                <ul class="nav fm-nav navbar-nav hidden-sm hidden-xs navbar-right" id="navbar-right" v-cloak>
                    <li class="has-hover-effect fm-nav__item">
                        <a href="<?php echo e(url(Auth::user()->username.'/create-event')); ?>" class="fm-nav__item">
                            <i class="fa fa-plus"></i>
                            <b>Inspire</b>
                        </a>
                    </li>
                    <li class="has-hover-effect fm-nav__item">
                        <a href="<?php echo e(url('events')); ?>">
                            <b>Events</b>
                        </a>
                    </li>
                    <li class="has-hover-effect dropdown message hidden-sm hidden-xs notification">
                        <a href="#" data-toggle="dropdown" @click.prevent="showNotifications"
                           class="dropdown-toggle" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <div class="icon icon-like"></div>
                            <span class="unread-notification" v-bind:class="{ 'is-visible': isShowUN }"></span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-header">
                                <span class="side-left"><?php echo e(trans('common.notifications')); ?></span>
                                <a v-if="unreadNotifications > 0" class="side-right" href="#"
                                   @click.prevent="markNotificationsRead"><?php echo e(trans('messages.mark_all_read')); ?></a>
                                <div class="clearfix"></div>
                            </div>
                            <?php if(Auth::user()->notifications()->count() > 0): ?>
                                <ul class="list-unstyled dropdown-messages-list scrollable"
                                    data-type="notifications">
                                    <li class="inbox-message"
                                        v-bind:class="[ !notification.seen ? 'active' : '' ]"
                                        v-for="notification in notifications.data">
                                        
                                        
                                        <a href="<?php echo e(url(Auth::user()->username.'/notification/')); ?>">
                                            <div class="media">
                                                <div class="media-left">
                                                    <img class="media-object img-icon"
                                                         v-bind:src="notification.notified_from.avatar"
                                                         alt="images">
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">
                                                        <span class="notification-text"> {{ notification.description }} </span>
                                            <span class="message-time">
                                                <span class="notification-type"><i class="fa fa-user"
                                                                                   aria-hidden="true"></i></span>
                                                <time class="timeago"
                                                      :datetime="notification.created_at+ '00:00'"
                                                      :title="notification.created_at + '00:00'">
                                                    {{ notification.created_at }}+00:00
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
                            <?php else: ?>
                                <div class="no-messages">
                                    <i class="fa fa-bell-slash-o" aria-hidden="true"></i>
                                    <p><?php echo e(trans('messages.no_notifications')); ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="dropdown-menu-footer"><br>
                                <a href="<?php echo e(url('allnotifications')); ?>"><?php echo e(trans('common.see_all')); ?></a>
                            </div>
                        </div>
                    </li>
                    
                    <li class="has-hover-effect dropdown message largescreen-message">
                        <a href="#" data-toggle="dropdown" v-on:click="showConversations" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="icon icon-chat"></span>
                            <span class="unread-notification" v-bind:class="{ 'is-visible': isShowUCM }"></span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-header">
                                <span class="side-left"><?php echo e(trans('common.messages')); ?></span>
                                <div class="clearfix"></div>
                            </div>
                            <div class="no-messages hidden">
                                <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                <p><?php echo e(trans('messages.no_messages')); ?></p>
                            </div>
                            <ul class="list-unstyled dropdown-messages-list scrollable"
                                data-type="messages">
                                <li class="inbox-message" v-for="conversation in conversations.data">
                                    <a href="#" :data-user-id="conversation.user.id" onclick="chatBoxes.sendMessageOnClick(this)">
                                        <div class="media">
                                            <div class="media-left">
                                                <img class="media-object img-icon"
                                                     v-bind:src="conversation.user.avatar" alt="images">
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">
                                                    <span class="message-heading">{{ conversation.user.name }}</span>
                                                    <span class="online-status hidden"></span>
                                                    <time class="timeago message-time"
                                                          :datetime="conversation.lastMessage.created_at + '00:00'"
                                                          :title="conversation.lastMessage.created_at + '00:00'">
                                                        {{ conversation.lastMessage.created_at }}+00:00
                                                </h4>
                                                <p class="message-text">
                                                    {{ conversation.lastMessage.body }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li v-if="conversationsLoading" class="dropdown-loading">
                                    <i class="fa fa-spin fa-spinner"></i>
                                </li>
                            </ul>
                            <div class="dropdown-menu-footer">
                                <a href="<?php echo e(url('messages')); ?>"><?php echo e(trans('common.see_all')); ?></a>
                            </div>
                        </div>
                    </li>

                    <li class="has-hover-effect user-image socialite fm-nav__item">
                        <a href="<?php echo e(url(Auth::user()->username)); ?>" class="user-image-wrapper">
                            <img src="<?php echo e(asset('images/default.png')); ?>" style="max-width: 100%" alt="<?php echo e(Auth::user()->name); ?>"
                                 class="img-radius img-30" title="<?php echo e(Auth::user()->name); ?>">
                            <span class="user-name hidden"><?php echo e(Auth::user()->name); ?></span>
                        </a>
                    </li>
                    <li class="dropdown message fm-nav__item--no-padding vert-has">
                        <a href="<?php echo e(url(Auth::user()->username)); ?>" class="dropdown-toggle"
                           data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <div class="icon icon-options"></div>
                        </a>
                        <ul data-width="3" class="dropdown-menu">
                            <?php if(Auth::user()->hasRole('admin')): ?>
                                <li class="<?php echo e(Request::segment(1) == 'admin' ? 'active' : ''); ?>">
                                    <a href="<?php echo e(url('admin')); ?>">
                                        <i class="fa fa-user-secret" aria-hidden="true"></i><?php echo e(trans('common.admin')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>

                            <li class="<?php echo e((Request::segment(1) == Auth::user()->username && Request::segment(2) == '') ? 'active' : ''); ?>">
                                <a href="<?php echo e(url(Auth::user()->username)); ?>"><i class="fa fa-user"
                                                                               aria-hidden="true"></i><?php echo e(trans('common.my_profile')); ?>

                                </a></li>

                            <li class="<?php echo e(Request::segment(2) == 'albums' ? 'active' : ''); ?>"><a
                                        href="<?php echo e(url(Auth::user()->username.'/albums')); ?>"><i
                                            class="fa fa-image"
                                            aria-hidden="true"></i><?php echo e(trans('common.my_albums')); ?></a>
                            </li>

                            <li class="<?php echo e(Request::segment(3) == 'events' ? 'active' : ''); ?>"><a
                                        href="<?php echo e(url(Auth::user()->username.'/events')); ?>"><i
                                            class="fa fa-calendar"
                                            aria-hidden="true"></i><?php echo e(trans('common.my_events')); ?></a>
                            </li>

                            <li class="<?php echo e(Request::segment(3) == 'general' ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/'.Auth::user()->username.'/settings/general')); ?>">
                                    <i class="fa fa-cog" aria-hidden="true"></i><?php echo e(trans('common.settings')); ?>

                                </a>
                            </li>

                            <li>
                                <form action="<?php echo e(url('/logout')); ?>" method="post">
                                    <?php echo e(csrf_field()); ?>


                                    <button type="submit" class="btn-logout">
                                        <i class="fa fa-unlock" aria-hidden="true"></i><?php echo e(trans('common.logout')); ?>

                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>