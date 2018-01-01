<!-- main-section -->
	<!-- <div class="main-content"> -->
		<div class="container">
			<div class="row">
				<div class="visible-lg col-lg-2 hide-on-other">
					{!! Theme::partial('home-leftbar',compact('trending_tags')) !!}
				</div>
              
                <div class="col-md-7 col-lg-6 full-wdith">
			   		@if (Session::has('message'))
				        <div class="alert alert-{{ Session::get('status') }}" role="alert">
				            {!! Session::get('message') !!}
				        </div>
				    @endif

					@if(isset($active_announcement))
						<div class="announcement alert alert-info">
							<a href="javascript:;" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<h3>{{ $active_announcement->title }}</h3>
							<p>{{ $active_announcement->description }}</p>
						</div>
					@endif
					
					@if($mode != "eventlist")
						{!! Theme::partial('create-post',compact('timeline','user_post')) !!}
						<div class="timeline-posts">
                            <div id="app-timeline">
								<input type="hidden" id="newPostId">
								@if(isset($location))
									<input type="hidden" id="postByLocation" value="{{$location}}">
								@endif
								@if(isset($hashtag))
									<input type="hidden" id="postByHashTag" value="{{$hashtag}}">
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
						</div>
					@else
						{!! Theme::partial('eventslist',compact('user_events','username','event_tags')) !!}
					@endif
				</div>

				@if(Request::is('/'))
					<div class="hide-on-other col-lg-4">
						<div class="widget-events widget-left-panel" id="single-event-view">
							<single-event-view></single-event-view>
							<suggestion-user></suggestion-user>
						</div>
					</div>
				@endif
			</div>
		</div>
	<!-- </div> -->
<!-- /main-section -->