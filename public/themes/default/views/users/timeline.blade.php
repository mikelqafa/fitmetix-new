<style type="text/css">
    .calendar {
        text-align: center;
    }
    .calendar header {
        position: relative;
    }
    .calendar h2 {
        text-transform: uppercase;
    }
    .calendar .current-day {
        background: #30435b;
        color: #FFF !important;
    }
    .calendar .event {
        cursor: pointer;
        position: relative;
    }
    .calendar .event:after {
        background: #30435b;
        border-radius: 50%;
        bottom: 1px;
        display: block;
        content: '';
        height: 8px;
        left: 50%;
        margin: -4px 0 0 -4px;
        position: absolute;
        width: 8px;
    }
    .event.current-day:after {
        background: #f9f9f9;
    }
    .btn-prev,
    .btn-next {
        border: 2px solid #cbd1d2;
        border-radius: 50%;
        color: #cbd1d2;
        height: 32px;
        font-size: 22px;
        line-height: 28px;
        margin: -16px;
        position: absolute;
        top: 50%;
        width: 32px;
    }
    .btn-prev:hover,
    .btn-next:hover {
        background: #cbd1d2;
        color: #f9f9f9;
    }
    .btn-prev {
        left: 30px;
    }
    .btn-next {
        right: 35px;
    }
    .list {
        margin-top: 20px;
    }
    .close {
        color: #A4AAAB;
        margin-top: -15px;
        margin-right: 10px;
        float: right;
    }
    .day-event {
        background-color: #F2F2F2 ;
        width: 100%;
        padding-bottom: 0px;
        margin-bottom: 50px;
        display:none;
    }
    .day-event p{
        padding-left: 20px;
        padding-right: 20px;
        padding-bottom: 20px;
    }
    .day-event span{
        font-size: 12px;
    }
    .day-event button {
        position: relative;
        vertical-align: top;
        width: 100%;
        height: 50px;
        padding: 0;
        font-size: 18px;fitmet
        color: white;
        text-align: center;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        background: #30435b;
        border: 0;
        border-bottom: 2px solid #30435b;
        cursor: pointer;
        -webkit-box-shadow: inset 0 -2px #30435b;
        box-shadow: inset 0 -2px #30435b;
    }
    @media (max-width: 768px) {
        .nav-justified > li {
            display: table-cell;
            width: 1%;
        }
        .nav-justified > li > a  {
            border-bottom: 1px solid #ddd !important;
            border-radius: 4px 4px 0 0 !important;
            margin-bottom: 0 !important;
        }
    }
    @media (max-width: 660px) {
        .timeline-cover-section .timeline-cover {
            max-height: 140px !important;
        }
    }
    @media (max-width: 660px) {
        .timeline-cover-section .timeline-cover {
            min-height: 105px !important;
        }
    }
</style>
<link href="{{ asset('css/theme1.css') }}" rel="stylesheet">
<!-- main-section -->
	<div class="container-fluid section-container @if($timeline->hide_cover) no-cover @endif">
		<div class="row">
            <div class="visible-lg col-md-2 col-lg-2"></div>
			<div class="col-md-6 col-lg-6">
				@if($timeline->type == "user")
					{!! Theme::partial('user-header',compact('user','timeline','liked_pages','joined_groups','followRequests','following_count','followers_count','follow_confirm','user_post','joined_groups_count','guest_events')) !!}
                    <br>
                    <p>
                        {{--{!! ($user->about != NULL) ? userAbout($user->about) : trans('messages.no_description') !!}--}}
                    </p>
                    <ul class="list-inline list-unstyled social-links-list">
                        @if($user->facebook_link != NULL)
                            <li>
                                <a target="_blank" href="{{ $user->facebook_link }}" class="btn btn-facebook"><i class="fa fa-facebook"></i></a>
                            </li>
                        @endif
                        @if($user->twitter_link != NULL)
                            <li>
                                <a target="_blank" href="{{ $user->twitter_link }}" class="btn btn-twitter"><i class="fa fa-twitter"></i></a>
                            </li>
                        @endif
                        @if($user->dribbble_link != NULL)
                            <li>
                                <a target="_blank" href="{{ $user->dribbble_link }}" class="btn btn-dribbble"><i class="fa fa-dribbble"></i></a>
                            </li>
                        @endif
                        @if($user->youtube_link != NULL)
                            <li>
                                <a target="_blank" href="{{ $user->youtube_link }}" class="btn btn-youtube"><i class="fa fa-youtube"></i></a>
                            </li>
                        @endif
                        @if($user->instagram_link != NULL)
                            <li>
                                <a target="_blank" href="{{ $user->instagram_link }}" class="btn btn-instagram"><i class="fa fa-instagram"></i></a>
                            </li>
                        @endif
                        @if($user->linkedin_link != NULL)
                            <li>
                                <a target="_blank" href="{{ $user->linkedin_link }}" class="btn btn-linkedin"><i class="fa fa-linkedin"></i></a>
                            </li>
                        @endif
                    </ul>
				@elseif($timeline->type == "page")
					{!! Theme::partial('page-header',compact('page','timeline')) !!}
				@elseif($timeline->type == "group")
					{!! Theme::partial('group-header',compact('timeline','group')) !!}
				@elseif($timeline->type == "event")
					{!! Theme::partial('event-header',compact('event','timeline')) !!}
				@endif
                <ul class="nav nav-justified" style="border-top: 1px solid #333;border-bottom: 1px solid #333;">
                    <li><a style="color:#000" href="{{ url($timeline->username) }}">Posts</a></li>
                    <li><a style="color: #000;" href="{{ url($timeline->username.'/albums') }}" class="">Gallery</a></li>
                    <li><a style="color: #000;" href="{{ url($timeline->username.'/events') }}" class="">Events</a></li>
                </ul>
			</div>
			<div class="col-md-4 col-lg-4 hidden-sm hidden-xs">
                <div id="caleandar"></div>
			</div>
		</div>
		<div class="row">
            <div class="visible-lg col-md-2 col-lg-2"></div>
            <div class="col-md-6 col-lg-6">
                <div class="row">
                </div>
				<div class="row">
					<div class="timeline">
                        @if($timeline->type == "user" && $timeline_post == true)
                            {!! Theme::partial('create-post',compact('timeline','user_post')) !!}
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

                        <div class="timeline-posts">
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
			</div>
			<div class="col-md-4 col-lg-4 hidden-sm hidden-xs">
				<div class="calendar hidden-print">
                    <div class="list">
                        {{-- {!! $event_list !!} --}}
                    </div>
                </div>
				{{--{!! Theme::partial('timeline-rightbar') !!}--}}
			</div>
		</div>
	</div>