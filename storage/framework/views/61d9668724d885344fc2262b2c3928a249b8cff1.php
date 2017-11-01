<div class="panel panel-default">
	<div class="panel-heading no-bg panel-settings">
		<h3 class="panel-title">
			<?php echo e(trans('common.manage_events')); ?>

		</h3>
		<div class="col-md-offset-9">
			<?php echo e(Form::label('sort by', 'Sort by:')); ?>

			<?php echo Form::select('manage_users', array('name_asc' => trans('admin.name_asc'), 'name_desc' => trans('admin.name_desc'), 'private' => trans('common.private'), 'public' => trans('common.public')), Request::get('sort'), ['class' => 'form-control eventsort']); ?>

		</div>		
	</div>
	
		
	<div class="panel-body nopadding">
		<ul class="nav nav-pills heading-list">			
			<li class="active"><a href="#ongoing" data-toggle="pill" class="header-text"><?php echo e(trans('common.ongoing')); ?><span><?php echo e(count($ongoning_events)); ?></span></a></li>
			<li class="divider">&nbsp;</li>
			<li class=""><a href="#upcoming" data-toggle="pill" class="header-text"><?php echo e(trans('common.upcoming')); ?><span><?php echo e(count($upcoming_events)); ?></span></a></li>
			<li class="divider">&nbsp;</li>
			<li class=""><a href="#expired" data-toggle="pill" class="header-text"><?php echo e(trans('common.expired')); ?><span><?php echo e(count($expired_events)); ?></span></a></li>
		</ul>

		<div class="tab-content nopadding">
    		<div id="ongoing" class="tab-pane fade active in">
	    		<div class="table-responsive manage-table">
	        		<table class="table apps-table">
	      				<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
						<?php if(count($ongoning_events) > 0): ?>
							
						<thead>
							<tr>
								<th>&nbsp;</th>
								<th><?php echo e(trans('admin.id')); ?></th> 
								<th><?php echo e(trans('auth.name')); ?></th>
								<th><?php echo e(trans('common.type')); ?></th>
								<th><?php echo e(trans('common.guests')); ?></th> 
								<th><?php echo e(trans('admin.options')); ?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php $__currentLoopData = $ongoning_events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ongoning): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td>&nbsp;</td>	
									<td><?php echo e($ongoning->event->id); ?></td>
									<td><a href="#">
										<img src="<?php echo e($ongoning->event->user->picture); ?>" alt="<?php echo e($ongoning->event->timeline->name); ?>" title="<?php echo e($ongoning->event->timeline->name); ?>"></a><a href="<?php echo e(url($ongoning->event->timeline->username)); ?>"> <?php echo e($ongoning->event->timeline->name); ?>

										</a>
									</td> 
									<td><span class="label label-default"><?php echo e($ongoning->event->type); ?></span></td>
									<td><?php echo e($ongoning->event->users->count()); ?></td> 
									<td>
										<ul class="list-inline">
											<li><a href="<?php echo e(url('admin/events/'.$ongoning->event->timeline->username.'/edit')); ?>"><span class="pencil-icon bg-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></a></li>
											<li><a href="<?php echo e(url('admin/events/'.$ongoning->event->id.'/delete')); ?>" onclick="return confirm('<?php echo e(trans("messages.are_you_sure")); ?>')"><span class="trash-icon bg-danger"><i class="fa fa-trash text-danger" aria-hidden="true"></i></span></a></li>
										</ul>
									</td>
									<td>&nbsp;</td> 
								</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</tbody>
							
						<?php else: ?>
							<div class="alert alert-warning"><?php echo e(trans('messages.no_events')); ?></div>
						<?php endif; ?>
	        		</table>
	        		<div class="pagination-holder groupnation">
	        			<?php echo e($ongoning_events->render()); ?>

	        		</div>
	        	</div>
    		</div>
<!-- End of ongoing tab-->
			<div id="upcoming" class="tab-pane fade">
				<div class="table-responsive manage-table">
					<table class="table apps-table">         
						<?php if(count($upcoming_events) > 0): ?>
							
								<thead>
									<tr>
										<th>&nbsp;</th>
										<th><?php echo e(trans('admin.id')); ?></th> 
										<th><?php echo e(trans('auth.name')); ?></th>
										<th><?php echo e(trans('common.type')); ?></th>
										<th><?php echo e(trans('common.guests')); ?></th> 
										<th><?php echo e(trans('admin.options')); ?></th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<?php $__currentLoopData = $upcoming_events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $upcoming): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<tr>
										<td>&nbsp;</td>	
										<td><?php echo e($upcoming->event->id); ?></td>
										<td><a href="#">
											<img src="<?php echo e($upcoming->event->user->picture); ?>" alt="<?php echo e($upcoming->event->timeline->name); ?>" title="<?php echo e($upcoming->event->timeline->name); ?>"></a><a href="<?php echo e(url($upcoming->event->timeline->username)); ?>"> <?php echo e($upcoming->event->timeline->name); ?>

											</a>
										</td> 
										<td><span class="label label-default"><?php echo e($upcoming->event->type); ?></span></td>
										<td><?php echo e($upcoming->event->users->count()); ?></td> 
										<td>
											<ul class="list-inline">
												<li><a href="<?php echo e(url('admin/events/'.$upcoming->event->timeline->username.'/edit')); ?>"><span class="pencil-icon bg-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></a></li>
												<li><a href="<?php echo e(url('admin/events/'.$upcoming->event->id.'/delete')); ?>" onclick="return confirm('<?php echo e(trans("messages.are_you_sure")); ?>')"><span class="trash-icon bg-danger"><i class="fa fa-trash text-danger" aria-hidden="true"></i></span></a></li>
											</ul>
										</td>
										<td>&nbsp;</td> 
									</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</tbody>
						<?php else: ?>
							<div class="alert alert-warning"><?php echo e(trans('messages.no_events')); ?></div>
						<?php endif; ?>
	        		</table>
	        		<div class="pagination-holder groupnation">
	        			<?php echo e($upcoming_events->render()); ?>

	        		</div>
				</div>
    		</div>
<!-- End of upcoming tab-->
		<div id="expired" class="tab-pane fade">
			<div class="table-responsive manage-table">
				<table class="table apps-table">         
					<?php if(count($expired_events) > 0): ?>

					<thead>
						<tr>
							<th>&nbsp;</th>
							<th><?php echo e(trans('admin.id')); ?></th> 
							<th><?php echo e(trans('auth.name')); ?></th>
							<th><?php echo e(trans('common.type')); ?></th>
							<th><?php echo e(trans('common.guests')); ?></th> 
							<th><?php echo e(trans('admin.options')); ?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php $__currentLoopData = $expired_events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expired): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td>&nbsp;</td>	
								<td><?php echo e($expired->event->id); ?></td>
								<td><a href="#">
									<img src="<?php echo e($expired->event->user->picture); ?>" alt="<?php echo e($expired->event->timeline->name); ?>" title="<?php echo e($expired->event->timeline->name); ?>"></a><a href="<?php echo e(url($expired->event->timeline->username)); ?>"> <?php echo e($expired->event->timeline->name); ?>

									</a>
								</td> 
								<td><span class="label label-default"><?php echo e($expired->event->type); ?></span></td>
								<td><?php echo e($expired->event->users->count()); ?></td> 
								<td>
									<ul class="list-inline">
										<li><a href="<?php echo e(url('admin/events/'.$expired->event->timeline->username.'/edit')); ?>"><span class="pencil-icon bg-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></a></li>
										<li><a href="<?php echo e(url('admin/events/'.$expired->event->id.'/delete')); ?>" onclick="return confirm('<?php echo e(trans("messages.are_you_sure")); ?>')"><span class="trash-icon bg-danger"><i class="fa fa-trash text-danger" aria-hidden="true"></i></span></a></li>
									</ul>
								</td>
								<td>&nbsp;</td> 
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
				<?php else: ?>
				<div class="alert alert-warning"><?php echo e(trans('messages.no_events')); ?></div>
				<?php endif; ?>
			</table>
			<div class="pagination-holder groupnation">
				<?php echo e($expired_events->render()); ?>

			</div>
			</div>
		</div>
<!-- End of upcoming tab-->	
		</div>
	</div>
</div>
