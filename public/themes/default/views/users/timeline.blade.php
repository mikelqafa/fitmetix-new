<link href="{{ asset('css/theme1.css') }}" rel="stylesheet">
<link href="{{ asset('css/timeline-event.css') }}" rel="stylesheet">
<!-- main-section -->
	<div class="container container--standard section-container @if($timeline->hide_cover) no-cover @endif">
        <div class="row">
            <div class="md-layout md-algin md-layout--wrap md-align--start-start layout-timeline">
                <div class="md-col layout-timeline__post layout-m-t-1">
                    <div class="timeline-cover-section">
                        <div class="timeline-cover">
                            <img src=" @if($timeline->cover_id) {{ url('user/cover/'.$timeline->cover->source) }} @else {{ url('user/cover/default-cover-user.png') }} @endif" alt="{{ $timeline->name }}" title="{{ $timeline->name }}">
                            @if($timeline->id == Auth::user()->timeline_id)
                                <a href="javascript:;" class="btn btn-camera-cover change-cover"><i class="fa fa-camera" aria-hidden="true"></i><span class="change-cover-text">{{ trans('common.change_cover') }}</span></a>
                            @endif
                            <div class="user-cover-progress hidden">
                            </div>
                        </div>
                        <div class="timeline-user-info-wrapper">
                            <div class="timeline-user-avtar">
                                <img src="{{ $timeline->user->avatar }}" alt="{{ $timeline->name }}" class="img-circle" title="{{ $timeline->name }}">
                                @if($timeline->id == Auth::user()->timeline_id)
                                    <div class="chang-user-avatar">
                                        <a href="#" class="btn btn-camera change-avatar"><i class="fa fa-camera" aria-hidden="true"></i><span class="avatar-text">{{ trans('common.update_profile') }}<span>{{ trans('common.picture') }}</span></span></a>
                                    </div>
                                @endif
                                <div class="user-avatar-progress hidden">
                                </div>
                            </div>
                            <div class="user-timeline-name">
                                <a href="{{ url($timeline->username) }}" class="username__link">{{$timeline->username }}</a>
                                <div class="name">{{$timeline->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="layout-m-t-2 timeline-option layout-m-b-2 md-layout md-layout--row md-align md-align--space-between-center">
                        <div class="timeline-option__item">
                            <a href="#" class="ft-btn ft-btn--icon">
                                <i class="icon icon-chat"></i>
                            </a>
                        </div>
                        <button class="btn ft-btn-primary ft-btn-primary--outline">Follow</button>
                        <div class="timeline-option__item">
                            <a href="#" class="ft-btn ft-btn--icon">
                                <i class="icon icon-options"></i>
                            </a>
                        </div>
                    </div>
                    <div class="timeline-user-desc">
                        {{$timeline->about}}
                    </div>
                    <div class="ft-header-hashtag">
                        <ul class="nav nav-justified" >
                            <li class="active is-active"><a href="{{ url($timeline->username) }}">Posts</a></li>
                            <li><a href="{{ url($timeline->username.'/albums') }}" class="">Gallery</a></li>
                            <li><a href="{{ url($timeline->username.'/events') }}" class="">Events</a></li>
                        </ul>
                    </div>
                    <div class="timeline timeline-posts--user">
                        @if($timeline->type == "user" && $timeline_post == true)
                            @if($timeline->username == Auth::user()->username)
                                {!! Theme::partial('create-post',compact('timeline','user_post')) !!}
                            @endif
                        @elseif($timeline->type == "page")
                            @if(($page->timeline_post_privacy == "only_admins" && $page->is_admin(Auth::user()->id)) || ($page->timeline_post_privacy == "everyone"))
                                {!! Theme::partial('create-post',compact('timeline','user_post')) !!}
                            @elseif($page->timeline_post_privacy == "everyone")
                                {!! Theme::partial('create-post',compact('timeline','user_post')) !!}
                            @endif
                        @elseif($timeline->type == "group")
                            @if(($group->post_privacy == "only_admins" && $group->is_admin(Auth::user()->id))|| ($group->post_privacy == "members" && Auth::user()->get_group($group->id) == 'approved') || $group->post_privacy == "everyone")
                                {!! Theme::partial('create-post',compact('timeline','user_post','username')) !!}
                            @endif
                        @elseif($timeline->type == "event")
                            @if(($event->timeline_post_privacy == 'only_admins' && $event->is_eventadmin(Auth::user()->id, $event->id)) || ($event->timeline_post_privacy == 'only_guests' && Auth::user()->get_eventuser($event->id)))
                                {!! Theme::partial('create-post',compact('timeline','user_post')) !!}
                            @endif
                        @endif
                        {{--<div class="timeline-posts">
                            @if($user_post == "user" || $user_post == "page" || $user_post == "group")
                                @if(count($posts) > 0)
                                    @foreach($posts as $post)
                                        {!! Theme::partial('post',compact('post','timeline','next_page_url','user')) !!}
                                    @endforeach
                                @else
                                    <div class="no-posts alert alert-warning">{{ trans('messages.no_posts') }}</div>
                                @endif
                            @endif

                            @if($user_post == "event")
                                @if($event->type == 'private' && Auth::user()->get_eventuser($event->id) || $event->type == 'public')
                                    @if(count($posts) > 0)
                                        @foreach($posts as $post)
                                            {!! Theme::partial('post',compact('post','timeline','next_page_url','user')) !!}
                                        @endforeach
                                    @else
                                        <div class="no-posts alert alert-warning">{{ trans('messages.no_posts') }}</div>
                                    @endif
                                @else
                                    <div class="no-posts alert alert-warning">{{ trans('messages.private_posts') }}</div>
                                @endif
                            @endif
                        </div>--}}

                        <div class="timeline-posts timeline-posts--user">
                            <input type="hidden" id="timeline_username" value="{{$username}}" />
                            <div id="app-timeline">
                                <input type="hidden" id="newPostId">
                                <app-post-option></app-post-option>
                                <app-comment-option></app-comment-option>
                                <app-post>
                                    <div class="lg-loading-skeleton panel panel-default timeline-posts__item panel-post">
                                        <div class="panel-heading no-bg post-avatar md-layout md-layout--row">
                                            <div class="user-avatar lg-loadable"></div>
                                            <div class="md-layout md-layout--column">
                                                <div class="user-meta-info lg-loadable"></div>
                                                <div class="user-meta-info lg-loadable user-meta-info--sm"></div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="lg-loadable lg-loadable--text"></div>
                                            <div class="lg-loadable lg-loadable--text--lg lg-loadable--text"></div>
                                            <div class="lg-loadable lg-loadable--text--sm lg-loadable--text"></div>
                                        </div>
                                    </div>
                                    <div class="lg-loading-skeleton panel panel-default timeline-posts__item panel-post">
                                        <div class="panel-heading no-bg post-avatar md-layout md-layout--row">
                                            <div class="user-avatar lg-loadable"></div>
                                            <div class="md-layout md-layout--column">
                                                <div class="user-meta-info lg-loadable"></div>
                                                <div class="user-meta-info lg-loadable user-meta-info--sm"></div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="lg-loadable lg-loadable--text"></div>
                                            <div class="lg-loadable lg-loadable--text--lg lg-loadable--text"></div>
                                            <div class="lg-loadable lg-loadable--text--sm lg-loadable--text"></div>
                                        </div>
                                    </div>
                                </app-post>
                                <div id="scroll-bt"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md-col layout-timeline__left">
                    <div id="caleandar" style="width: 374px;margin-top: 1px"></div>
                </div>
            </div>
        </div>
	</div>