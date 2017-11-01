<!-- <div class="main-content"> -->
			<!-- List of user events-->
				<div class="post-filters pages-groups">
					
					<div class="panel panel-default">
					<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
						<div class="panel-heading no-bg panel-settings">						
							<div class="side-right">
								<a href="<?php echo e(url(Auth::user()->username.'/create-event')); ?>" class="btn btn-success"><?php echo e(trans('common.create_event')); ?></a>
							</div>
							<h3 class="panel-title">
								<?php echo e(trans('messages.events-manage')); ?>

							</h3>
						</div>
						<div class="panel-body">
							<?php if(count($user_events)): ?>

							<ul class="list-group page-likes">
								<?php $__currentLoopData = $user_events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<li class="list-group-item deleteevent">
									<div class="connect-list">
										<div class="connect-link side-left">
											
											<a href="<?php echo e(url($user_event->timeline->username)); ?>">
												<img src=" <?php if(Auth::user()->timeline->avatar): ?> <?php echo e(url('user/avatar/'.Auth::user()->timeline->avatar->source)); ?> <?php else: ?> <?php echo e(url('group/avatar/default-group-avatar.png')); ?> <?php endif; ?>" alt="<?php echo e($user_event->timeline->name); ?>" title="<?php echo e($user_event->timeline->name); ?>"><?php echo e($user_event->timeline->name); ?>

											</a>
											<span class="label label-default"><?php echo e($user_event->type); ?></span>
										</div>
										<div class="side-right">
											<a href="" class="side-right delete-event delete_event" data-eventdelete-id="<?php echo e($user_event->id); ?>"><i class="fa fa-close text-danger"></i></a>
										</div>
										<div class="clearfix"></div>
									</div><!-- /connect-list -->
								</li>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</ul>
							<?php else: ?>
							<div class="alert alert-warning">
								<?php echo e(trans('messages.no_events')); ?>

							</div>
							<?php endif; ?>
						</div>
					</div>

				</div><!-- /panel -->
<!-- </div> --><!-- /main-content -->