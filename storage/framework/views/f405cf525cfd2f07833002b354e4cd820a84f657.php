<!-- main-section -->
	<!-- <div class="main-content"> -->
		<div class="container">
			<div class="row">
				<div class="visible-lg col-lg-2">

				</div>
              
                <div class="col-md-7 col-lg-6">
			   		<?php if(Session::has('message')): ?>
				        <div class="alert alert-<?php echo e(Session::get('status')); ?>" role="alert">
				            <?php echo Session::get('message'); ?>

				        </div>
				    <?php endif; ?>

					<?php if(isset($active_announcement)): ?>
						<div class="announcement alert alert-info">
							<a href="javascript:;" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<h3><?php echo e($active_announcement->title); ?></h3>
							<p><?php echo e($active_announcement->description); ?></p>
						</div>
					<?php endif; ?>
					
					<?php if($mode != "eventlist"): ?>
						<?php echo Theme::partial('create-post',compact('timeline','user_post')); ?>

						<div id="app-event-calendar">
							<app-event-calendar></app-event-calendar>
						</div>
						<div class="timeline-posts">
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
							<?php if($posts->count() > 0): ?>
								<?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php else: ?>
								<div class="no-posts alert alert-warning"><?php echo e(trans('common.no_posts')); ?></div>
							<?php endif; ?>
						</div>
					<?php else: ?>
						<?php echo Theme::partial('eventslist',compact('user_events','username')); ?>

					<?php endif; ?>
				</div><!-- /col-md-6 -->

			</div>
		</div>
	<!-- </div> -->
<!-- /main-section -->