<div class="right-side-section">
	
	<div class="panel panel-default">
		<div class="panel-heading no-bg">
			<h3 class="panel-title">
				<?php echo e(trans('common.suggested_people')); ?>

			</h3>
		</div>
		<div class="panel-body">
			<!-- widget holder starts here -->
			<div class="user-follow socialite">
				<!-- Each user is represented with media block -->
				<?php if($suggested_users != ""): ?>

				<?php $__currentLoopData = $suggested_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $suggested_user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

				<div class="media">
					<div class="media-left badge-verification">
						<a href="<?php echo e(url($suggested_user->username)); ?>">
							<img src="<?php echo e($suggested_user->avatar); ?>" class="img-icon" alt="<?php echo e($suggested_user->name); ?>" title="<?php echo e($suggested_user->name); ?>">
							<?php if($suggested_user->verified): ?>
							<span class="verified-badge bg-success verified-medium">
								<i class="fa fa-check"></i>
							</span>
							<?php endif; ?>
						</a>
					</div>
					<div class="media-body socialte-timeline follow-links">
						<h4 class="media-heading"><a href="<?php echo e(url($suggested_user->username)); ?>"><?php echo e($suggested_user->name); ?> </a>
							<span class="text-muted"><?php echo e('@'.$suggested_user->username); ?></span>
						</h4>
							<div class="btn-follow">
								<a href="#" class="btn btn-default follow-user follow" data-timeline-id="<?php echo e($suggested_user->timeline->id); ?>"> <i class="fa fa-heart"></i> <?php echo e(trans('common.follow')); ?></a>
							</div>
							<div class="btn-follow hidden">
								<a href="#" class="btn btn-success follow-user unfollow" data-timeline-id="<?php echo e($suggested_user->timeline->id); ?>"><i class="fa fa-check"></i> <?php echo e(trans('common.following')); ?></a>
							</div>
						</div>
					</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php else: ?>
					<div class="alert alert-warning">
						<?php echo e(trans('messages.no_suggested_users')); ?>

					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

			<?php if(Setting::get('home_ad') != NULL): ?>
			<div id="link_other" class="post-filters">
				<?php echo htmlspecialchars_decode(Setting::get('home_ad')); ?>

			</div>
			<?php endif; ?>
		</div>