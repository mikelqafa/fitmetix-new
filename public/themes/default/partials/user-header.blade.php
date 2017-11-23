<div class="row">
	<div style="margin-bottom:50px" class="timeline-cover-section">
		<div class="timeline-cover">
			<img src=" @if($timeline->cover_id) {{ url('user/cover/'.$timeline->cover->source) }} @else {{ url('user/cover/default-cover-user.png') }} @endif" alt="{{ $timeline->name }}" title="{{ $timeline->name }}">
			@if($timeline->id == Auth::user()->timeline_id)
				<a href="javascript:;" class="btn btn-camera-cover change-cover"><i class="fa fa-camera" aria-hidden="true"></i><span class="change-cover-text">{{ trans('common.change_cover') }}</span></a>
			@endif
			<div class="user-cover-progress hidden">

			</div>
			<!-- <div class="cover-bottom">
		</div> -->
			<div class="user-timeline-name">
				<a href="{{ url($timeline->username) }}">{{ $timeline->name }}</a>
				{!! verifiedBadge($timeline) !!}
			</div>
		</div>
		<div class="timeline-list">
			<ul class="list-inline pagelike-links">
				@if($user_post == true)
					<li class="{{ Request::segment(2) == 'posts' ? 'active' : '' }}"><a href="{{ url($timeline->username.'/posts') }}" ><span class="top-list">{{ count($timeline->posts()->where('active', 1)->get()) }} {{ trans('common.posts') }}</span></a></li>
				@else
					<li class="{{ Request::segment(2) == 'posts' ? 'active' : '' }}"><a href="#"><span class="top-list">{{ count($timeline->posts()->where('active', 1)->get()) }} {{ trans('common.posts') }}</span></a></li>
				@endif
				<li class="{{ Request::segment(2) == 'following' ? 'active' : '' }} smallscreen-report"><a href="{{ url($timeline->username.'/following') }}" ><span class="top-list">{{ $following_count }} {{ trans('common.following') }}</span></a></li>
				<li class="{{ Request::segment(2) == 'followers' ? 'active' : '' }} smallscreen-report"><a href="{{ url($timeline->username.'/followers') }}" ><span class="top-list">{{ $followers_count }}  {{ trans('common.followers') }}</span></a></li>
				<li class="dropdown largescreen-report"><a href="#" class=" dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="top-list"> <i class="fa fa-ellipsis-h"></i></span></a>
					<ul class="dropdown-menu  report-dropdown">
						@if(!$user->timeline->albums->isEmpty())
							<li class=""><a href="{{ url($timeline->username.'/albums') }}" > {{ trans('common.photos') }} </a></li>
						@endif
						<li class="{{ Request::segment(2) == 'following' ? 'active' : '' }}">
							<a href="{{ url($timeline->username.'/following') }}" ><span class="top-list">{{ $following_count }} {{ trans('common.following') }}</span>
							</a>
						</li>
						<li class="{{ Request::segment(2) == 'followers' ? 'active' : '' }}">
							<a href="{{ url($timeline->username.'/followers') }}" ><span class="top-list">{{ $followers_count }}  {{ trans('common.followers') }}</span>
							</a>
						</li>
						@if($follow_confirm == "yes" && $timeline->id == Auth::user()->timeline_id)
							<li class="{{ Request::segment(2) == 'follow-requests' ? 'active' : '' }}">
								<a href="{{ url($timeline->username.'/follow-requests') }}" ><span class="top-list">{{count($followRequests)}} {{ trans('common.follow_requests') }}</span>
								</a>
							</li>
						@endif
						<li class="{{ Request::segment(2) == 'event-guests' ? 'active' : '' }}">
							<a href="{{ url($timeline->username.'/event-guests') }}" ><span class="top-list">{{ count($guest_events) }}  {{ trans('common.guest-event') }}</span>
							</a>
						</li>
						{{--
                                            <li><a href="{{ url('/'.Auth::user()->username.'/settings/general') }}" class="btn btn-profile"><i class="fa fa-pencil-square-o"></i><span class="top-list">Edit Profile</span></a></li>
                        --}}

					</ul>
				</li>
				@if(Auth::user()->username != $timeline->username)
					@if(!$timeline->reports->contains(Auth::user()->id))
						<li class="pull-right">
							<a href="#" class="page-report report" data-timeline-id="{{ $timeline->id }}"> <i class="fa fa-flag" aria-hidden="true"></i> {{ trans('common.report') }}
							</a>
						</li>
						<li class="hidden pull-right">
							<a href="#" class="page-report reported" data-timeline-id="{{ $timeline->id }}"> <i class="fa fa-flag" aria-hidden="true"></i>	{{ trans('common.reported') }}
							</a>
						</li>
					@else
						<li class="hidden pull-right">
							<a href="#" class="page-report report" data-timeline-id="{{ $timeline->id }}"> <i class="fa fa-flag" aria-hidden="true"></i> {{ trans('common.report') }}
							</a>
						</li>
						<li class="pull-right">
							<a href="#" class="page-report reported" data-timeline-id="{{ $timeline->id }}"> <i class="fa fa-flag" aria-hidden="true"></i>	{{ trans('common.reported') }}
							</a>
						</li>
					@endif
				@endif
				@if(Auth::user()->username != $timeline->username)
					@if(!$timeline->reports->contains(Auth::user()->id))
						<li class="smallscreen-report"><a href="#" class="page-report report" data-timeline-id="{{ $timeline->id }}">{{ trans('common.report') }}</a></li>
						<li class="hidden smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="{{ $timeline->id }}">{{ trans('common.reported') }}</a></li>
					@else
						<li class="hidden smallscreen-report"><a href="#" class="page-report report" data-timeline-id="{{ $timeline->id }}">{{ trans('common.report') }}</a></li>
						<li class="smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="{{ $timeline->id }}">{{ trans('common.reported') }}</a></li>
					@endif
				@endif
			</ul>

			<div class="status-button">
				<a href="#" class="btn btn-status">{{ trans('common.status') }}</a>
			</div>
			<div class="timeline-user-avtar" style="background-color: transparent !important;border: none !important;">
				<img src="{{ $timeline->user->avatar }}" alt="{{ $timeline->name }}" class="img-circle" title="{{ $timeline->name }}">
				@if($timeline->id == Auth::user()->timeline_id)
					<div class="chang-user-avatar">
						<a href="#" class="btn btn-camera change-avatar"><i class="fa fa-camera" aria-hidden="true"></i><span class="avatar-text">{{ trans('common.update_profile') }}<span>{{ trans('common.picture') }}</span></span></a>
					</div>
				@endif
				<div class="user-avatar-progress hidden">
				</div>
			</div><!-- /timeline-user-avatar -->

		</div><!-- /timeline-list -->
	</div><!-- timeline-cover-section -->
</div>
<div class="user-profile-buttons">
	<div class="row follow-links pagelike-links">
		@if(Auth::user()->username != $timeline->username)
            <?php
            $user_follow ="";
            $confirm_follow ="";
            $message_privacy ="";
            $othersSettings = $user->getOthersSettings($timeline->username);
            if($othersSettings) {
                if ($othersSettings->follow_privacy == "only_follow") {
                    $user_follow = "only_follow";
                } elseif ($othersSettings->follow_privacy == "everyone") {
                    $user_follow = "everyone";
                }
                if ($othersSettings->confirm_follow == "yes") {
                    $confirm_follow = "yes";
                } elseif ($othersSettings->confirm_follow == "no") {
                    $confirm_follow = "no";
                }
                if ($othersSettings->message_privacy == "only_follow") {
                    $message_privacy = "only_follow";
                } elseif ($othersSettings->message_privacy == "everyone") {
                    $message_privacy = "everyone";
                }
            }
            ?>
			@if($confirm_follow == "no")
				@if(($user->followers->contains(Auth::user()->id) && $user_follow == "only_follow") || ($user_follow == "everyone"))
					@if(!$user->followers->contains(Auth::user()->id))
						@if($message_privacy == "everyone")
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user btn-default follow" data-timeline-id="{{ $timeline->id }}">
									<i class="fa fa-heart"></i> {{ trans('common.follow') }}
								</a>
							</div>
						@else
							<div class="col-md-12 col-sm-6 col-xs-6">
								<a href="#" class="btn btn-options btn-block follow-user btn-default follow" data-timeline-id="{{ $timeline->id }}">
									<i class="fa fa-heart"></i> {{ trans('common.follow') }}
								</a>
							</div>
						@endif
						@if($message_privacy == "everyone")
							<div class="col-md-6 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="{{ $timeline->id }}">
									<i class="fa fa-check"></i> {{ trans('common.following') }}
								</a>
							</div>
						@else
							<div class="col-md-12 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="{{ $timeline->id }}">
									<i class="fa fa-check"></i> {{ trans('common.following') }}
								</a>
							</div>
						@endif
					@else
						<div class="col-md-6 col-sm-6 col-xs-6 hidden">
							<a href="#" class="btn btn-options btn-block follow-user btn-default follow " data-timeline-id="{{ $timeline->id }}">
								<i class="fa fa-heart"></i> {{ trans('common.follow') }}
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6 left-col">
							<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="{{ $timeline->id }}">	<i class="fa fa-check"></i> {{ trans('common.following') }}
							</a>
						</div>
					@endif
				@elseif(($user->following->contains(Auth::user()->id) && $user_follow == "only_follow") || ($user_follow == "everyone"))

					@if(!$user->followers->contains(Auth::user()->id))
						@if($message_privacy == "everyone")
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user btn-default follow" data-timeline-id="{{ $timeline->id }}">
									<i class="fa fa-heart"></i> {{ trans('common.follow') }}
								</a>
							</div>
						@else
							<div class="col-md-12 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user btn-default follow" data-timeline-id="{{ $timeline->id }}">
									<i class="fa fa-heart"></i> {{ trans('common.follow') }}
								</a>
							</div>
						@endif

						@if($message_privacy == "everyone")
							<div class="col-md-6 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="{{ $timeline->id }}">
									<i class="fa fa-check"></i> {{ trans('common.following') }}
								</a>
							</div>
						@else
							<div class="col-md-12 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="{{ $timeline->id }}">
									<i class="fa fa-check"></i> {{ trans('common.following') }}
								</a>
							</div>
						@endif

					@else
						<div class="col-md-6 col-sm-6 col-xs-6 hidden">
							<a href="#" class="btn btn-options btn-block follow-user btn-default follow " data-timeline-id="{{ $timeline->id }}">
								<i class="fa fa-heart"></i> {{ trans('common.follow') }}
							</a>
						</div>

						<div class="col-md-6 col-sm-6 col-xs-6 left-col">
							<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="{{ $timeline->id }}">	<i class="fa fa-heart"></i> {{ trans('common.following') }}
							</a>
						</div>
					@endif
				@endif	<!-- End of [if-3]-->

			@elseif($confirm_follow == "yes")
			<!-- This [if-4] is for checking usersettings follow_privacy showing follow/following || message button -->
				@if(($user->followers->contains(Auth::user()->id) && $user_follow == "only_follow") || ($user_follow == "everyone"))
					@if(!$user->followers->contains(Auth::user()->id))
						@if($message_privacy == "everyone")
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block btn-default follow-user-confirm follow" data-timeline-id="{{ $timeline->id }}-{{ $follow_user_status }}">
									<i class="fa fa-heart"></i> {{ trans('common.follow') }}
								</a>
							</div>
						@else
							<div class="col-md-12 col-sm-6 col-xs-6">
								<a href="#" class="btn btn-options btn-block btn-default follow-user-confirm follow" data-timeline-id="{{ $timeline->id }}-{{ $follow_user_status }}">
									<i class="fa fa-heart"></i> {{ trans('common.follow') }}
								</a>
							</div>
						@endif

						@if($message_privacy == "everyone")
							<div class="col-md-6 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user-confirm btn-warning followrequest" data-timeline-id="{{ $timeline->id }}-{{ $follow_user_status }}">
									<i class="fa fa-check"></i> {{ trans('common.requested') }}
								</a>
							</div>
						@else
							<div class="col-md-12 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user-confirm btn-warning followrequest" data-timeline-id="{{ $timeline->id }}-{{ $follow_user_status }}">
									<i class="fa fa-check"></i> {{ trans('common.requested') }}
								</a>
							</div>
						@endif
					@else
						<div class="col-md-6 col-sm-6 col-xs-6 hidden">
							<a href="#" class="btn btn-options btn-block btn-default follow-user-confirm  follow " data-timeline-id="{{ $timeline->id }}-{{ $follow_user_status }}">
								<i class="fa fa-heart"></i> {{ trans('common.follow') }}
							</a>
						</div>

						@if($follow_user_status == "pending")
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user-confirm btn-warning followrequest" data-timeline-id="{{ $timeline->id }}-{{ $follow_user_status }}">
									<i class="fa fa-check"></i> {{ trans('common.requested') }}
								</a>
							</div>
						@endif
						@if($follow_user_status == "approved")
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user-confirm btn-primary unfollow" data-timeline-id="{{ $timeline->id }}-{{ $follow_user_status }}">
									<i class="fa fa-check"></i> {{ trans('common.following') }}
								</a>
							</div>
						@endif
					@endif
				@endif	<!-- End of [if-4]-->
			@endif	<!-- End of [if-2]-->
			@if(($user->followers->contains(Auth::user()->id) && $message_privacy == "only_follow") || ($message_privacy == "everyone"))
				<div class="col-md-6 col-sm-6 col-xs-6 right-col">
					<a href="#" class="btn btn-options btn-block btn-default" onClick="chatBoxes.sendMessage({{ $timeline->user->id }})">
						<i class="fa fa-inbox"></i> {{ trans('common.message') }}
					</a>
				</div>
			@endif
		@else
	@endif <!-- End of [if-1]-->

	</div>
</div>
<script type="text/javascript">
	@if($timeline->background_id != NULL)
		$('body')
			.css('background-image', "url({{ url('/wallpaper/'.$timeline->wallpaper->source) }})")
			.css('background-attachment', 'fixed');
	@endif
</script>