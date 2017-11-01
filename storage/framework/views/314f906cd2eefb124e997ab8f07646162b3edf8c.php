<div class="user-profile-buttons">
	<div class="row join-links">	
		<?php if($event->type == 'public' && $event->isExpire($event->id)): ?>
			<?php if(!$event->users->contains(Auth::user()->id)): ?>
			<div class="col-md-12 col-xs-6 col-sm-6">
				<a href="#" class="btn btn-options btn-block join-guest btn-default join" data-timeline-id="<?php echo e($timeline->id); ?>">
					<i class="fa fa-heart"></i> <?php echo e(trans('common.want_to_go')); ?>

				</a>
			</div>

			<div class="col-md-12 col-xs-6 col-sm-6 hidden">
				<a href="#" class="btn btn-options btn-block btn-success join-guest joined" data-timeline-id="<?php echo e($timeline->id); ?>">
					<i class="fa fa-check"></i>  <?php echo e(trans('common.iam_going')); ?>

				</a>
			</div>
			<?php else: ?>
			<div class="col-md-12 col-xs-6 col-sm-6 hidden">
				<a href="#" class="btn btn-options btn-block join-guest btn-default join " data-timeline-id="<?php echo e($timeline->id); ?>">
					<i class="fa fa-heart"></i>  <?php echo e(trans('common.want_to_go')); ?>

				</a>
			</div>
				<?php if(!$event->is_eventadmin(Auth::user()->id, $event->id)): ?>  
					<div class="col-md-12 col-xs-6 col-sm-6">
						<a href="#" class="btn btn-options btn-block btn-success join-guest joined" data-timeline-id="<?php echo e($timeline->id); ?>">
							<i class="fa fa-check"></i>  <?php echo e(trans('common.iam_going')); ?>

						</a>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if($event->is_eventadmin(Auth::user()->id, $event->id)): ?>
			<div class="col-md-12 col-sm-6 col-xs-6">
				<a href="<?php echo e(url('/'.$timeline->username.'/event-settings/general')); ?>" class="btn btn-options btn-block btn-default"><i class="fa fa-gear"></i> 
					<?php echo e(trans('common.settings')); ?>

				</a>
			</div>
		<?php endif; ?>
	</div>
</div>

	<!-- Change cover form -->
	<form class="change-cover-form hidden" action="<?php echo e(url('ajax/change-cover')); ?>" method="post" enctype="multipart/form-data">
		<input name="timeline_id" value="<?php echo e($timeline->id); ?>" type="hidden">
		<input name="timeline_type" value="<?php echo e($timeline->type); ?>" type="hidden">
		<input class="change-cover-input hidden" accept="image/jpeg,image/png" type="file" name="change_cover" >
	</form>

	<div class="user-bio-block">

		<div class="bio-header"><?php echo e(trans('common.about')); ?></div>
		<div class="bio-description">
			<?php echo e(($timeline['about'] != NULL) ? $timeline['about'] : trans('messages.no_description')); ?>

		</div>
		<ul class="bio-list list-unstyled">			
			<li>
				<?php if($event->type == 'public'): ?>
					<i class="fa fa-unlock"></i>
				<?php else: ?>
					<i class="fa fa-lock"></i>
				<?php endif; ?>
				<span><?php echo e($event->type.' '.trans('common.event')); ?></span>
			</li>
			<li>
				<i class="fa fa-user" aria-hidden="true"></i><span> <?php echo e(trans('common.hosted_by')); ?> <a href="<?php echo e(url('/'.$event->hostedByUsername($event->user_id))); ?>"><?php echo e($event->hostedByName($event->user_id)); ?></a> </span>
			</li>

			<?php if( $event->gender != null ): ?>
				<li>
					<i class="fa fa-genderless"></i> <b>Gender: </b> <?php echo ucfirst($event->gender); ?>

				</li>
			<?php endif; ?>

			<?php if( $event->price != null ): ?>
				<li>
					<i class="fa fa-money"></i> <b>Price: </b> <?php echo $event->price; ?>

				</li>
			<?php endif; ?>

				<li>
					<i class="fa fa-fire"></i> <b>Focus: </b> <?php echo ucfirst($event->focus); ?>

				</li>
				<li>
					<i class="fa fa-calendar"></i> <b>Frequency: </b> <?php echo ucfirst($event->frequency); ?>

				</li>


			<?php if($event->location != null): ?>
				<li>
					<i class="fa fa-map-marker" aria-hidden="true"></i><span><?php echo e($event->location); ?></span>
				</li>
			<?php endif; ?>

			<?php if($event->start_date != null): ?>
				<li>
					<i class="fa fa-calendar"></i><span><?php echo e(trans('common.starts_on')); ?></span>: <?php echo e(date("Y:m:d H:i", strtotime($event->start_date))); ?>

				</li>
			<?php endif; ?>

			<?php if($event->end_date != null): ?>
				<li>
					<i class="fa fa-calendar"></i><span><?php echo e(trans('common.ends_on')); ?></span>: <?php echo e(date("Y:m:d H:i", strtotime($event->end_date))); ?>

				</li>
			<?php endif; ?>
			<?php if($event->group_id != null): ?>
				<li>
					<i class="fa fa-users" aria-hidden="true"></i><span><?php echo e(trans('common.group')); ?></span>: <?php echo e($event->group->name); ?>

				</li>
			<?php endif; ?>
		</ul>
	</div>
<?php if((env('GOOGLE_MAPS_API_KEY') != NULL)): ?>
	<iframe style="height:100%;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=<?php echo $event->location; ?>&key=<?php echo e(env('GOOGLE_MAPS_API_KEY')); ?>"></iframe>
<?php endif; ?>