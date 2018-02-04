<link href="{{ asset('css/theme1.css') }}" rel="stylesheet">
<link href="{{ asset('css/timeline-event.css') }}" rel="stylesheet">
<!-- main-section -->
    <div class="container container--standard section-container @if($timeline->hide_cover) no-cover @endif">
        <div class="row">
            <div class="md-layout md-align md-layout--wrap md-align--start-start layout-timeline">
                <div class="md-col layout-timeline__post layout-m-t-1">
                    {!! Theme::partial('timeline-header',compact('timeline','user_post')) !!}
                    <div class="options" style="text-align: center;display: none;">
                        @if($timeline->user->settings()->post_privacy == 'everyone' || (Auth::user()->id == $timeline->user->id))
                            <div class="ft-user-info md-layout md-layout--row md-align md-align--space-around show-more">
                                <div class="ft-user-info__item">
                                    <div class="ft-icon">
                                        Events
                                    </div>
                                    <a class="info btn" href="{{ url($timeline->username.'/event') }}">
                                        {{ count($user_events) }}
                                    </a>
                                </div>
                                <div class="ft-user-info__item">
                                    <div class="ft-icon">
                                        Posts
                                    </div>
                                    <a class="info btn" href="{{ url($timeline->username) }}#post-feed">
                                        {{ count($posts) }}
                                    </a>
                                </div>
                                <div class="ft-user-info__item">
                                    <div class="ft-icon">
                                        Follows
                                    </div>
                                    <a href="javascript:;" class="info btn" onclick="$('#user-who-following--dialog').MaterialDialog('show')">
                                        {{ $following_count }}
                                    </a>
                                </div>
                                <div class="ft-user-info__item">
                                    <div class="ft-icon">
                                        Followers
                                    </div>
                                    <a href="javascript:;" class="info btn" onclick="$('#user-who-follow--dialog').MaterialDialog('show')">
                                        {{ $followers_count }}
                                    </a>
                                </div>
                                @if(Auth::user()->id == $timeline->user->id)
                                    <div class="ft-user-info__item hidden">
                                        <div class="ft-icon">
                                            Saved
                                        </div>
                                        <div class="info">
                                            {{ count($timeline->user->postsSaved()) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div id="user-follow-view">
                                <input type="hidden" id="follow-userid" value="{{$timeline->user->id}}">
                                <input type="hidden" id="follow-username" value="{{$timeline->username}}">
                                <user-follow-list></user-follow-list>
                                <user-following-list></user-following-list>
                            </div>
                        @endif
                        @if(Auth::user()->id == $timeline->user->id)
                            <a class="btn btn-default" href="{{ url($timeline->username.'/settings') }}">Settings</a>
                        @elseif(Auth::user()->following->contains($timeline->user->id))
                                @if($block_text == 'Unblock')
                                    <button class="btn btn-default" onclick="unblock()">{{ $block_text }}</button>
                                @else
                                    <button class="btn btn-default" onclick="$('#profile-option-dialog').MaterialDialog('show')">{{ $block_text }}</button>
                                @endif
                            <button class="btn btn-default" onclick="$('#profile-option-dialog').MaterialDialog('show')">Report</button>
                        @else
                            @if($block_text == 'Unblock')
                                <button class="btn btn-default" onclick="unblock()">{{ $block_text }}</button>
                            @else
                                <button class="btn btn-default" onclick="$('#profile-option-dialog').MaterialDialog('show')">{{ $block_text }}</button>
                            @endif
                            <button class="btn btn-default" onclick="$('#profile-option-dialog').MaterialDialog('show')">Report</button>
                        @endif
                    </div>
                    
                    @if(($timeline->user->instagram_link != '') || ($timeline->user->facebook_link != ''))
                        <div class="md-layout md-layout--row">
                            @if($timeline->user->instagram_link != '')
                                <a style="display: block;height: 32px; width: 32px" href="{{ $timeline->user->instagram_link }}" target="_blank" rel="noopener"><i class="icon icon-insta" style="font-size: 32px"></i></a>
                            @endif
                            @if($timeline->user->facebook_link != '')
                                <a style="display: block;height: 32px; width: 32px" href="{{ $timeline->user->facebook_link }}" target="_blank" rel="noopener"><i class="icon icon-fcb" style="font-size: 32px"></i></a>
                            @endif
                        </div>
                    @endif
                    <div class="ft-header-hashtag">
                        <ul class="nav nav-justified" >
                            <li class="active is-active"><a href="{{ url($timeline->username) }}">Posts</a></li>
                            <li><a href="{{ url($timeline->username.'/gallery') }}" class="">Gallery</a></li>
                            <li><a href="{{ url($timeline->username.'/event') }}" class="">Events</a></li>
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
                        <div class="timeline-posts timeline-posts--user" id="post-feed">
                            @if(($timeline->user->settings()->post_privacy == 'everyone') || Auth::user()->id == $timeline->user->id || ($follow_user_status == 'approved'))
                                <input type="hidden" id="timeline_username" value="{{$username}}" />
                                <div id="app-timeline">
                                    <input type="hidden" id="newPostId">
                                    @if(Auth::user()->id != $timeline->user->id)
                                        <input type="hidden" id="username" value="{{$timeline->username}}">
                                        <input type="hidden" id="timeline_id" value="{{$timeline->id}}">
                                        <app-profile-option></app-profile-option>
                                    @endif
                                    @if(Auth::user()->id == $timeline->user->id)
                                        <app-picture-option></app-picture-option>
                                    @endif
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
                            @else
                                <div class="text-center">
                                        <h2>This Account is Private</h2>
                                        <p>Follow to see their photos and videos.</p>
                                    </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="md-col layout-timeline__left">
                    <div id="caleandar" style="width: 374px;margin-top: 1px"></div>
                </div>
            </div>
        </div>
    </div>