<div class="row">
	<div style="margin-bottom:50px" class="timeline-cover-section">
		<div class="timeline-cover">
			<img src=" <?php if($timeline->cover_id): ?> <?php echo e(url('user/cover/'.$timeline->cover->source)); ?> <?php else: ?> <?php echo e(url('user/cover/default-cover-user.png')); ?> <?php endif; ?>" alt="<?php echo e($timeline->name); ?>" title="<?php echo e($timeline->name); ?>">
			<?php if($timeline->id == Auth::user()->timeline_id): ?>
				<a href="#" class="btn btn-camera-cover change-cover"><i class="fa fa-camera" aria-hidden="true"></i><span class="change-cover-text"><?php echo e(trans('common.change_cover')); ?></span></a>
			<?php endif; ?>
			<div class="user-cover-progress hidden">

			</div>
			<!-- <div class="cover-bottom">
		</div> -->
			<div class="user-timeline-name">
				<a href="<?php echo e(url($timeline->username)); ?>"><?php echo e($timeline->name); ?></a>
				<?php echo verifiedBadge($timeline); ?>

			</div>
		</div>
		<div class="timeline-list">
			<ul class="list-inline pagelike-links">
				<?php if($user_post == true): ?>
					<li class="<?php echo e(Request::segment(2) == 'posts' ? 'active' : ''); ?>"><a href="<?php echo e(url($timeline->username.'/posts')); ?>" ><span class="top-list"><?php echo e(count($timeline->posts()->where('active', 1)->get())); ?> <?php echo e(trans('common.posts')); ?></span></a></li>
				<?php else: ?>
					<li class="<?php echo e(Request::segment(2) == 'posts' ? 'active' : ''); ?>"><a href="#"><span class="top-list"><?php echo e(count($timeline->posts()->where('active', 1)->get())); ?> <?php echo e(trans('common.posts')); ?></span></a></li>
				<?php endif; ?>
				<li class="<?php echo e(Request::segment(2) == 'following' ? 'active' : ''); ?> smallscreen-report"><a href="<?php echo e(url($timeline->username.'/following')); ?>" ><span class="top-list"><?php echo e($following_count); ?> <?php echo e(trans('common.following')); ?></span></a></li>
				<li class="<?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?> smallscreen-report"><a href="<?php echo e(url($timeline->username.'/followers')); ?>" ><span class="top-list"><?php echo e($followers_count); ?>  <?php echo e(trans('common.followers')); ?></span></a></li>
				<li class="dropdown largescreen-report"><a href="#" class=" dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="top-list"> <i class="fa fa-ellipsis-h"></i></span></a>
					<ul class="dropdown-menu  report-dropdown">
						<?php if(!$user->timeline->albums->isEmpty()): ?>
							<li class=""><a href="<?php echo e(url($timeline->username.'/albums')); ?>" > <?php echo e(trans('common.photos')); ?> </a></li>
						<?php endif; ?>
						<li class="<?php echo e(Request::segment(2) == 'following' ? 'active' : ''); ?>">
							<a href="<?php echo e(url($timeline->username.'/following')); ?>" ><span class="top-list"><?php echo e($following_count); ?> <?php echo e(trans('common.following')); ?></span>
							</a>
						</li>
						<li class="<?php echo e(Request::segment(2) == 'followers' ? 'active' : ''); ?>">
							<a href="<?php echo e(url($timeline->username.'/followers')); ?>" ><span class="top-list"><?php echo e($followers_count); ?>  <?php echo e(trans('common.followers')); ?></span>
							</a>
						</li>
						<?php if($follow_confirm == "yes" && $timeline->id == Auth::user()->timeline_id): ?>
							<li class="<?php echo e(Request::segment(2) == 'follow-requests' ? 'active' : ''); ?>">
								<a href="<?php echo e(url($timeline->username.'/follow-requests')); ?>" ><span class="top-list"><?php echo e(count($followRequests)); ?> <?php echo e(trans('common.follow_requests')); ?></span>
								</a>
							</li>
						<?php endif; ?>
						<li class="<?php echo e(Request::segment(2) == 'event-guests' ? 'active' : ''); ?>">
							<a href="<?php echo e(url($timeline->username.'/event-guests')); ?>" ><span class="top-list"><?php echo e(count($guest_events)); ?>  <?php echo e(trans('common.guest-event')); ?></span>
							</a>
						</li>
						

					</ul>
				</li>
				<?php if(Auth::user()->username != $timeline->username): ?>
					<?php if(!$timeline->reports->contains(Auth::user()->id)): ?>
						<li class="pull-right">
							<a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i> <?php echo e(trans('common.report')); ?>

							</a>
						</li>
						<li class="hidden pull-right">
							<a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i>	<?php echo e(trans('common.reported')); ?>

							</a>
						</li>
					<?php else: ?>
						<li class="hidden pull-right">
							<a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i> <?php echo e(trans('common.report')); ?>

							</a>
						</li>
						<li class="pull-right">
							<a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"> <i class="fa fa-flag" aria-hidden="true"></i>	<?php echo e(trans('common.reported')); ?>

							</a>
						</li>
					<?php endif; ?>
				<?php endif; ?>
				<?php if(Auth::user()->username != $timeline->username): ?>
					<?php if(!$timeline->reports->contains(Auth::user()->id)): ?>
						<li class="smallscreen-report"><a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.report')); ?></a></li>
						<li class="hidden smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.reported')); ?></a></li>
					<?php else: ?>
						<li class="hidden smallscreen-report"><a href="#" class="page-report report" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.report')); ?></a></li>
						<li class="smallscreen-report"><a href="#" class="page-report reported" data-timeline-id="<?php echo e($timeline->id); ?>"><?php echo e(trans('common.reported')); ?></a></li>
					<?php endif; ?>
				<?php endif; ?>
			</ul>

			<div class="status-button">
				<a href="#" class="btn btn-status"><?php echo e(trans('common.status')); ?></a>
			</div>
			<div class="timeline-user-avtar" style="background-color: transparent !important;border: none !important;">
				<img src="<?php echo e($timeline->user->avatar); ?>" alt="<?php echo e($timeline->name); ?>" class="img-circle" title="<?php echo e($timeline->name); ?>">
				<?php if($timeline->id == Auth::user()->timeline_id): ?>
					<div class="chang-user-avatar">
						<a href="#" class="btn btn-camera change-avatar"><i class="fa fa-camera" aria-hidden="true"></i><span class="avatar-text"><?php echo e(trans('common.update_profile')); ?><span><?php echo e(trans('common.picture')); ?></span></span></a>
					</div>
				<?php endif; ?>
				<div class="user-avatar-progress hidden">
				</div>
			</div><!-- /timeline-user-avatar -->

		</div><!-- /timeline-list -->
	</div><!-- timeline-cover-section -->
</div>
<div class="user-profile-buttons">
	<div class="row follow-links pagelike-links">
		<?php if(Auth::user()->username != $timeline->username): ?>
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
			<?php if($confirm_follow == "no"): ?>
				<?php if(($user->followers->contains(Auth::user()->id) && $user_follow == "only_follow") || ($user_follow == "everyone")): ?>
					<?php if(!$user->followers->contains(Auth::user()->id)): ?>
						<?php if($message_privacy == "everyone"): ?>
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user btn-default follow" data-timeline-id="<?php echo e($timeline->id); ?>">
									<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

								</a>
							</div>
						<?php else: ?>
							<div class="col-md-12 col-sm-6 col-xs-6">
								<a href="#" class="btn btn-options btn-block follow-user btn-default follow" data-timeline-id="<?php echo e($timeline->id); ?>">
									<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

								</a>
							</div>
						<?php endif; ?>
						<?php if($message_privacy == "everyone"): ?>
							<div class="col-md-6 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="<?php echo e($timeline->id); ?>">
									<i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?>

								</a>
							</div>
						<?php else: ?>
							<div class="col-md-12 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="<?php echo e($timeline->id); ?>">
									<i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?>

								</a>
							</div>
						<?php endif; ?>
					<?php else: ?>
						<div class="col-md-6 col-sm-6 col-xs-6 hidden">
							<a href="#" class="btn btn-options btn-block follow-user btn-default follow " data-timeline-id="<?php echo e($timeline->id); ?>">
								<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6 left-col">
							<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="<?php echo e($timeline->id); ?>">	<i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?>

							</a>
						</div>
					<?php endif; ?>
				<?php elseif(($user->following->contains(Auth::user()->id) && $user_follow == "only_follow") || ($user_follow == "everyone")): ?>

					<?php if(!$user->followers->contains(Auth::user()->id)): ?>
						<?php if($message_privacy == "everyone"): ?>
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user btn-default follow" data-timeline-id="<?php echo e($timeline->id); ?>">
									<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

								</a>
							</div>
						<?php else: ?>
							<div class="col-md-12 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user btn-default follow" data-timeline-id="<?php echo e($timeline->id); ?>">
									<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

								</a>
							</div>
						<?php endif; ?>

						<?php if($message_privacy == "everyone"): ?>
							<div class="col-md-6 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="<?php echo e($timeline->id); ?>">
									<i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?>

								</a>
							</div>
						<?php else: ?>
							<div class="col-md-12 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="<?php echo e($timeline->id); ?>">
									<i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?>

								</a>
							</div>
						<?php endif; ?>

					<?php else: ?>
						<div class="col-md-6 col-sm-6 col-xs-6 hidden">
							<a href="#" class="btn btn-options btn-block follow-user btn-default follow " data-timeline-id="<?php echo e($timeline->id); ?>">
								<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

							</a>
						</div>

						<div class="col-md-6 col-sm-6 col-xs-6 left-col">
							<a href="#" class="btn btn-options btn-block follow-user btn-success unfollow" data-timeline-id="<?php echo e($timeline->id); ?>">	<i class="fa fa-heart"></i> <?php echo e(trans('common.following')); ?>

							</a>
						</div>
					<?php endif; ?>
				<?php endif; ?>	<!-- End of [if-3]-->

			<?php elseif($confirm_follow == "yes"): ?>
			<!-- This [if-4] is for checking usersettings follow_privacy showing follow/following || message button -->
				<?php if(($user->followers->contains(Auth::user()->id) && $user_follow == "only_follow") || ($user_follow == "everyone")): ?>
					<?php if(!$user->followers->contains(Auth::user()->id)): ?>
						<?php if($message_privacy == "everyone"): ?>
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block btn-default follow-user-confirm follow" data-timeline-id="<?php echo e($timeline->id); ?>-<?php echo e($follow_user_status); ?>">
									<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

								</a>
							</div>
						<?php else: ?>
							<div class="col-md-12 col-sm-6 col-xs-6">
								<a href="#" class="btn btn-options btn-block btn-default follow-user-confirm follow" data-timeline-id="<?php echo e($timeline->id); ?>-<?php echo e($follow_user_status); ?>">
									<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

								</a>
							</div>
						<?php endif; ?>

						<?php if($message_privacy == "everyone"): ?>
							<div class="col-md-6 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user-confirm btn-warning followrequest" data-timeline-id="<?php echo e($timeline->id); ?>-<?php echo e($follow_user_status); ?>">
									<i class="fa fa-check"></i> <?php echo e(trans('common.requested')); ?>

								</a>
							</div>
						<?php else: ?>
							<div class="col-md-12 col-sm-6 col-xs-6 hidden">
								<a href="#" class="btn btn-options btn-block follow-user-confirm btn-warning followrequest" data-timeline-id="<?php echo e($timeline->id); ?>-<?php echo e($follow_user_status); ?>">
									<i class="fa fa-check"></i> <?php echo e(trans('common.requested')); ?>

								</a>
							</div>
						<?php endif; ?>
					<?php else: ?>
						<div class="col-md-6 col-sm-6 col-xs-6 hidden">
							<a href="#" class="btn btn-options btn-block btn-default follow-user-confirm  follow " data-timeline-id="<?php echo e($timeline->id); ?>-<?php echo e($follow_user_status); ?>">
								<i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?>

							</a>
						</div>

						<?php if($follow_user_status == "pending"): ?>
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user-confirm btn-warning followrequest" data-timeline-id="<?php echo e($timeline->id); ?>-<?php echo e($follow_user_status); ?>">
									<i class="fa fa-check"></i> <?php echo e(trans('common.requested')); ?>

								</a>
							</div>
						<?php endif; ?>
						<?php if($follow_user_status == "approved"): ?>
							<div class="col-md-6 col-sm-6 col-xs-6 left-col">
								<a href="#" class="btn btn-options btn-block follow-user-confirm btn-primary unfollow" data-timeline-id="<?php echo e($timeline->id); ?>-<?php echo e($follow_user_status); ?>">
									<i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?>

								</a>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>	<!-- End of [if-4]-->
			<?php endif; ?>	<!-- End of [if-2]-->
			<?php if(($user->followers->contains(Auth::user()->id) && $message_privacy == "only_follow") || ($message_privacy == "everyone")): ?>
				<div class="col-md-6 col-sm-6 col-xs-6 right-col">
					<a href="#" class="btn btn-options btn-block btn-default" onClick="chatBoxes.sendMessage(<?php echo e($timeline->user->id); ?>)">
						<i class="fa fa-inbox"></i> <?php echo e(trans('common.message')); ?>

					</a>
				</div>
			<?php endif; ?>
		<?php else: ?>
	<?php endif; ?> <!-- End of [if-1]-->

	</div>
</div>
<script type="text/javascript">
	<?php if($timeline->background_id != NULL): ?>
		$('body')
			.css('background-image', "url(<?php echo e(url('/wallpaper/'.$timeline->wallpaper->source)); ?>)")
			.css('background-attachment', 'fixed');
	<?php endif; ?>
</script>