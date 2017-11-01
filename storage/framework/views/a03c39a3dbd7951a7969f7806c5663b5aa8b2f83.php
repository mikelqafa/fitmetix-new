<!-- main-section -->
<!-- <div class="main-content"> -->
<div class="container-fluid">
    <div class="row">
        <div class="visible-lg col-lg-2">
            <?php echo Theme::partial('home-leftbar',compact('trending_tags')); ?>

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
                    <?php if($posts->count() > 0): ?>
                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <ul class="list-unstyled no-margin">
                            <li class="list-group-item deleteevent">
                                <div class="connect-list">
                                    <div class="connect-link side-left">
                                        <div class="media">
                                            <div class="pull-left">
                                                <a href="<?php echo e(url($post->timeline->username)); ?>">
                                                    <img style="width:48px;height:48px;" src="<?php echo !is_null($post->timeline->cover) ? url('event/cover/'.$post->timeline->cover->source) : url('group/avatar/default-group-avatar.png'); ?>" alt="Event Image" class="img-responsive">
                                                </a>
                                            </div>
                                            <div class="media-body" style="vertical-align:middle;">
                                                <a style="font-size:18px;color: #5f5d5d !important;font-family: 'Source Sans Pro', sans-serif;" href="<?php echo e(url($post->timeline->username)); ?>">
                                                    <?php echo e($post->timeline->name); ?> <small style="font-size:9px;"><i class="fa fa-external-link"></i></small>
                                                    <br>
                                                    <small><time class="post-time timeago" datetime="<?php echo e($post->created_at); ?>+00:00" title="<?php echo e($post->created_at); ?>+00:00"><?php echo e($post->created_at); ?>+00:00</time></small> <a target="_blank" href="<?php echo e(url('/get-location/'.$post->location)); ?>">
                                                        <i class="fa fa-map-marker"></i> <?php echo e($post->location); ?>

                                                    </a>
                                                </a>
                                            </div>
                                        </div>
                                        <hr>
                                        Starts: <b><?php echo e(\Carbon\Carbon::createFromTimestamp(strtotime($post->start_date))->diffForHumans().' ('.(date('d-M-Y H:i', strtotime($post->start_date))).')'); ?></b> until <b><?php echo e(\Carbon\Carbon::createFromTimestamp(strtotime($post->end_date))->diffForHumans().' ('.(date('d-M-Y H:i', strtotime($post->end_date))).')'); ?></b>
                                        <hr>
                                        <a href="<?php echo e(url($post->timeline->username)); ?>" class="btn btn-default"> Details</a>
                                        <a href="<?php echo e(url($post->timeline->username)); ?>" class="pull-right btn btn-default"> Register </a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
                        </ul>
                            <br>
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