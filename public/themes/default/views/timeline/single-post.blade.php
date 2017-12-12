<!-- main-section -->
	<!-- <div class="main-content"> -->
		<div class="container">
			<div class="row">
				<div class="visible-lg col-lg-2">
					{!! Theme::partial('home-leftbar',compact('trending_tags')) !!}
				</div>

                <div class="col-md-7 col-lg-6">

					<div class="timeline-posts">
						@if($mode == 'posts')
							<input type="hidden" id="post-id" value="{{$post->id}}">
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
								</app-post>
								<div id="scroll-bt"></div>
							</div>
						@elseif($mode == 'notifications')
							{!! Theme::partial('allnotifications',compact('notifications')) !!}
						@endif
					</div>
				</div><!-- /col-md-6 -->

				<div class="col-md-5 col-lg-4">
					{!! Theme::partial('home-rightbar',compact('suggested_users', 'suggested_groups', 'suggested_pages')) !!}
				</div>
			</div>
		</div>
	<!-- </div> -->
<!-- /main-section -->