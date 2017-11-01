<div class="timeline-cover-section">
	<div class="timeline-cover">
		<img src=" <?php if($timeline->cover_id): ?> <?php echo e(url('event/cover/'.$timeline->cover->source)); ?> <?php else: ?> <?php echo e(url('event/cover/default-cover-event.png')); ?> <?php endif; ?>" alt="<?php echo e($timeline->name); ?>" title="<?php echo e($timeline->name); ?>">
		<?php if($timeline->event->is_eventadmin(Auth::user()->id, $event->id)): ?>
		<a href="#" class="btn btn-camera-cover change-cover"><i class="fa fa-camera" aria-hidden="true"></i><span class="change-cover-text"><?php echo e(trans('common.change_cover')); ?></span></a>
		<?php endif; ?>
		<div class="user-cover-progress hidden"></div>
		<div class="user-timeline-name event">		
			<a href="<?php echo e(url($timeline->username)); ?>"><?php echo e($timeline->name); ?></a>
			<?php echo verifiedBadge($timeline); ?>		
		</div>		
	</div>

	<div class="timeline-list event">
		<?php if($event->type == 'private' && Auth::user()->get_eventuser($event->id) || $event->type == 'public'): ?>
			<ul class="list-inline pagelike-links">				
				<?php if(($event->is_eventadmin(Auth::user()->id, $event->id) && $event->invite_privacy == 'only_admins')  && $event->isExpire($event->id) || ($event->invite_privacy == 'only_guests' && Auth::user()->get_eventuser($event->id) && $event->isExpire($event->id))): ?>
					<li class="<?php echo e(Request::segment(2) == 'add-eventmembers' ? 'active' : ''); ?>">
						<a href="<?php echo e(url($timeline->username.'/add-eventmembers')); ?>" ><span class="top-list"> <?php echo e(trans('common.invitemembers')); ?></span></a>
					</li>	
				<?php endif; ?>
				
				<li class="<?php echo e(Request::segment(2) == 'eventguests' ? 'active' : ''); ?>"><a href="<?php echo e(url($timeline->username.'/eventguests/')); ?>">
					<span class="top-list">
						<?php echo e($event->guests($event->user_id) != false ? count($event->guests($event->user_id)) : 0); ?> <?php echo e(trans('common.guests')); ?>

					</span>
					</a>
				</li>		
				
				<li class="<?php echo e(Request::segment(2) == 'event-posts' ? 'active' : ''); ?>">
					<a href="<?php echo e(url($timeline->username.'/event-posts')); ?>">
						<span class="top-list"><?php echo e(count($timeline->posts()->where('active', 1)->get())); ?> <?php echo e(trans('common.posts')); ?></span>
					</a>
				</li>

				<li class="<?php echo e(Request::segment(2) == 'reviews' ? 'active' : ''); ?>">
					<a href="<?php echo e(url($timeline->username.'/reviews')); ?>">
						<span class="top-list"><i class="fa fa-star" style="vertical-align: baseline;"></i> Reviews</span>
					</a>
				</li>

				<?php if(!$timeline->event->is_eventadmin(Auth::user()->id, $event->id)): ?>
				<li class="dropdown largescreen-report"><a href="#" class=" dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="top-list"> <i class="fa fa-ellipsis-h"></i></span></a>
					<ul class="dropdown-menu  report-dropdown">
						
						<li class="">
							<a href="#" class="save-timeline" data-timeline-id="<?php echo e($timeline->id); ?>">
								<span class="top-list"><i class="fa fa-save"></i>
								<?php if($timeline->usersSaved()->where('user_id',Auth::user()->id)->get()->isEmpty()): ?>
									<?php echo e(trans('common.save')); ?>

								<?php else: ?>
									<?php echo e(trans('common.unsave')); ?>

								<?php endif; ?>
								</span>
							</a>
						</li>
					</ul>
				</li>
				<?php endif; ?>
				<?php if($timeline->event->is_eventadmin(Auth::user()->id, $event->id)): ?>
					<li class="pull-right">
						<a href="#" class="event-report eventdelete text-danger" data-event-id="<?php echo e($event->id); ?>-<?php echo e(Auth::user()->username); ?>"> <i class="fa fa-trash" aria-hidden="true"></i>
							<?php echo e(trans('common.delete')); ?>

						</a>
					</li>						
				<?php endif; ?>
			</ul>
		<?php endif; ?>

		<div class="status-button">
			<a href="#" class="btn btn-status"><?php echo e(trans('common.status')); ?></a>
		</div>

		<div class="event-avatar">
			<div class="event-date">
					<?php echo e(date("d", strtotime($event->start_date))); ?>

			</div>			
			<div class="event-month">
				<?php echo e(date("M", strtotime($event->start_date))); ?>

			</div>
		</div>

	</div>
</div>
