<!-- main-section -->
	<!-- <div class="main-content"> -->
		<div class="container">
			<div class="row">
				<div class="visible-lg col-lg-2">
					<?php echo Theme::partial('home-leftbar',compact('trending_tags')); ?>


					<div class="btn-group-vertical">
					    <button type="button" class="btn btn-warning btn-xs switch-language" data-language="en">English</button>
					    <button type="button" class="btn btn-warning btn-xs switch-language" data-language="de">German</button>
					    <button type="button" class="btn btn-warning btn-xs switch-language" data-language="it">Italian</button>
					    <button type="button" class="btn btn-warning btn-xs switch-language" data-language="fr">French</button>
					    <button type="button" class="btn btn-warning btn-xs switch-language" data-language="es">Spanish</button>
					    <button type="button" class="btn btn-warning btn-xs switch-language" data-language="tr">Turkish</button>
					 </div> 
				</div>
              
                <div class="col-md-7 col-lg-6">
			   		<?php if(Session::has('message')): ?>
				        <div class="alert alert-<?php echo e(Session::get('status')); ?>" role="alert">
				            <?php echo Session::get('message'); ?>

				        </div>
				    <?php endif; ?>

					<?php if(isset($active_announcement)): ?>
						<div class="announcement alert alert-info">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<h3><?php echo e($active_announcement->title); ?></h3>
							<p><?php echo e($active_announcement->description); ?></p>
						</div>
					<?php endif; ?>
					
					<?php if($mode != "eventlist"): ?>
						<?php echo Theme::partial('create-post',compact('timeline','user_post')); ?>

						<div class="timeline-posts">
                            <div>
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

				<div class="col-md-5 col-lg-4">
					<?php echo Theme::partial('home-rightbar',compact('suggested_users', 'suggested_groups', 'suggested_pages')); ?>

				</div>
			</div>
		</div>
	<!-- </div> -->
<!-- /main-section -->