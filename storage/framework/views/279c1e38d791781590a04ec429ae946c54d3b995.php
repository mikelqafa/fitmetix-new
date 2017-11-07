<style type="text/css">
    .calendar {
        text-align: center;
    }
    .calendar header {
        position: relative;
    }
    .calendar h2 {
        text-transform: uppercase;
    }
    .calendar .current-day {
        background: #30435b;
        color: #FFF !important;
    }
    .calendar .event {
        cursor: pointer;
        position: relative;
    }
    .calendar .event:after {
        background: #30435b;
        border-radius: 50%;
        bottom: 1px;
        display: block;
        content: '';
        height: 8px;
        left: 50%;
        margin: -4px 0 0 -4px;
        position: absolute;
        width: 8px;
    }
    .event.current-day:after {
        background: #f9f9f9;
    }
    .btn-prev,
    .btn-next {
        border: 2px solid #cbd1d2;
        border-radius: 50%;
        color: #cbd1d2;
        height: 32px;
        font-size: 22px;
        line-height: 28px;
        margin: -16px;
        position: absolute;
        top: 50%;
        width: 32px;
    }
    .btn-prev:hover,
    .btn-next:hover {
        background: #cbd1d2;
        color: #f9f9f9;
    }
    .btn-prev {
        left: 30px;
    }
    .btn-next {
        right: 35px;
    }
    .list {
        margin-top: 20px;
    }
    .close {
        color: #A4AAAB;
        margin-top: -15px;
        margin-right: 10px;
        float: right;
    }
    .day-event {
        background-color: #F2F2F2 ;
        width: 100%;
        padding-bottom: 0px;
        margin-bottom: 50px;
        display:none;
    }
    .day-event p{
        padding-left: 20px;
        padding-right: 20px;
        padding-bottom: 20px;
    }
    .day-event span{
        font-size: 12px;
    }
    .day-event button {
        position: relative;
        vertical-align: top;
        width: 100%;
        height: 50px;
        padding: 0;
        font-size: 18px;fitmet
        color: white;
        text-align: center;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        background: #30435b;
        border: 0;
        border-bottom: 2px solid #30435b;
        cursor: pointer;
        -webkit-box-shadow: inset 0 -2px #30435b;
        box-shadow: inset 0 -2px #30435b;
    }
    @media (max-width: 768px) {
        .nav-justified > li {
            display: table-cell;
            width: 1%;
        }
        .nav-justified > li > a  {
            border-bottom: 1px solid #ddd !important;
            border-radius: 4px 4px 0 0 !important;
            margin-bottom: 0 !important;
        }
    }
    @media (max-width: 660px) {
        .timeline-cover-section .timeline-cover {
            max-height: 140px !important;
        }
    }
    @media (max-width: 660px) {
        .timeline-cover-section .timeline-cover {
            min-height: 105px !important;
        }
    }
</style>

<!-- main-section -->
	<div class="container-fluid section-container <?php if($timeline->hide_cover): ?> no-cover <?php endif; ?>">
		<div class="row">
            <div class="col-md-3 hidden-sm hidden-xs">
                <div class="panel panel-default">
                    <div class="panel-heading">Left Side of Profile</div>
                    <div class="panel-body">
                        <p>Left Side of Body</p>
                    </div>
                </div>
            </div>
			<div class="col-md-6">
				<?php if($timeline->type == "user"): ?>
					<?php echo Theme::partial('user-header',compact('user','timeline','liked_pages','joined_groups','followRequests','following_count','followers_count','follow_confirm','user_post','joined_groups_count','guest_events')); ?>

                    <br>
                    <p>
                        <?php echo ($user->about != NULL) ? $user->about : trans('messages.no_description'); ?>

                    </p>
                    <ul class="list-inline list-unstyled social-links-list">
                        <?php if($user->facebook_link != NULL): ?>
                            <li>
                                <a target="_blank" href="<?php echo e($user->facebook_link); ?>" class="btn btn-facebook"><i class="fa fa-facebook"></i></a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->twitter_link != NULL): ?>
                            <li>
                                <a target="_blank" href="<?php echo e($user->twitter_link); ?>" class="btn btn-twitter"><i class="fa fa-twitter"></i></a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->dribbble_link != NULL): ?>
                            <li>
                                <a target="_blank" href="<?php echo e($user->dribbble_link); ?>" class="btn btn-dribbble"><i class="fa fa-dribbble"></i></a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->youtube_link != NULL): ?>
                            <li>
                                <a target="_blank" href="<?php echo e($user->youtube_link); ?>" class="btn btn-youtube"><i class="fa fa-youtube"></i></a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->instagram_link != NULL): ?>
                            <li>
                                <a target="_blank" href="<?php echo e($user->instagram_link); ?>" class="btn btn-instagram"><i class="fa fa-instagram"></i></a>
                            </li>
                        <?php endif; ?>
                        <?php if($user->linkedin_link != NULL): ?>
                            <li>
                                <a target="_blank" href="<?php echo e($user->linkedin_link); ?>" class="btn btn-linkedin"><i class="fa fa-linkedin"></i></a>
                            </li>
                        <?php endif; ?>
                    </ul>
				<?php elseif($timeline->type == "page"): ?>
					<?php echo Theme::partial('page-header',compact('page','timeline')); ?>

				<?php elseif($timeline->type == "group"): ?>
					<?php echo Theme::partial('group-header',compact('timeline','group')); ?>

				<?php elseif($timeline->type == "event"): ?>
					<?php echo Theme::partial('event-header',compact('event','timeline')); ?>

				<?php endif; ?>
			</div>
			<div class="col-md-3 hidden-sm hidden-xs">
                <div class="panel panel-default">
                    <div class="panel-heading">Event</div>
                    <div class="panel-body">
                        <div class="calendar hidden-print">
                            <header>
                                <h2 class="month"><?php echo isset($monthNames[$cMonth-1]) ? $monthNames[$cMonth-1] : 'Undefined'; ?></h2>
                                <a class="btn-prev fa fa-angle-left" href="<?php echo e(url($timeline->username.'?month='.$prev_month.'&year='.$prev_year)); ?>"></a> <a class="btn-next fa fa-angle-right" href="<?php echo e(url($timeline->username.'?month='.$next_month.'&year='.$next_year)); ?>"></a>
                            </header>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-condensed">
                                    <thead>
                                    <tr>
                                        <td>Mon</td>
                                        <td>Tue</td>
                                        <td>Wed</td>
                                        <td>Thu</td>
                                        <td>Fri</td>
                                        <td>Sat</td>
                                        <td>Sun</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                                    $maxday = date("t",$timestamp);
                                    $thismonth = getdate ($timestamp);
                                    $startday = $thismonth['wday'];
                                    for ($i=0; $i<($maxday+$startday); $i++) {
                                        if(($i % 7) == 0 ) { echo "<tr>"; }
                                        if($i < $startday) { echo "<td></td>"; }
                                        else {
                                            if(date('d') == ($i - $startday + 1) && date('m') == $cMonth) {
                                                if(in_array(($i - $startday + 1), $event_date)) {
                                                    echo "<td date-day='".($i - $startday + 1)."' date-month='".$cMonth."' class='current-day event'>".($i - $startday + 1)."</td>";
                                                } else {
                                                    echo "<td date-day='".($i - $startday + 1)."' date-month='".$cMonth."' class='current-day'>".($i - $startday + 1)."</td>";
                                                }
                                            } else {
                                                if(in_array(($i - $startday + 1), $event_date)) {
                                                    echo "<td date-day='".($i - $startday + 1)."' date-month='".$cMonth."' class='event'>".($i - $startday + 1) . "</td>";
                                                } else {
                                                    echo "<td date-day='".($i - $startday + 1)."' date-month='".$cMonth."'>".($i - $startday + 1)."</td>";
                                                }
                                            }
                                        }
                                        if(($i % 7) == 6 ) { echo "</tr>"; }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div class="row">
            <div class="col-md-3">
                <?php if($timeline->type == "user"): ?>
                    <?php echo Theme::partial('user-leftbar',compact('timeline','user','follow_user_status','own_pages','own_groups','user_events')); ?>

                <?php elseif($timeline->type == "page"): ?>
                    <?php echo Theme::partial('page-leftbar',compact('timeline','page','page_members')); ?>

                <?php elseif($timeline->type == "group"): ?>
                    <?php echo Theme::partial('group-leftbar',compact('timeline','group','group_members','group_events','ongoing_events','upcoming_events')); ?>

                <?php elseif($timeline->type == "event"): ?>
                    <?php echo Theme::partial('event-leftbar',compact('event','timeline')); ?>

                <?php endif; ?>
            </div>

			<div class="col-md-6">
                <div class="row">
                    <ul class="nav nav-justified" style="border-top: 1px solid #333;border-bottom: 1px solid #333;">
                        <li><a style="color:#000" href="<?php echo e(url($timeline->username)); ?>">Posts</a></li>
                        <li><a style="color: #000;" href="<?php echo e(url($timeline->username.'/albums')); ?>" class="">Gallery</a></li>
                        <li><a style="color: #000;" href="<?php echo e(url($timeline->username.'/events')); ?>" class="">Events</a></li>
                    </ul>
                </div>
				<div class="row">
					<div class="timeline">
                        <?php if($timeline->type == "user" && $timeline_post == true): ?>
                            <?php echo Theme::partial('create-post',compact('timeline','user_post')); ?>

                        <?php elseif($timeline->type == "page"): ?>
                            <?php if(($page->timeline_post_privacy == "only_admins" && $page->is_admin(Auth::user()->id)) || ($page->timeline_post_privacy == "everyone")): ?>
                                <?php echo Theme::partial('create-post',compact('timeline','user_post')); ?>

                            <?php elseif($page->timeline_post_privacy == "everyone"): ?>
                                <?php echo Theme::partial('create-post',compact('timeline','user_post')); ?>

                            <?php endif; ?>
                        <?php elseif($timeline->type == "group"): ?>
                            <?php if(($group->post_privacy == "only_admins" && $group->is_admin(Auth::user()->id))|| ($group->post_privacy == "members" && Auth::user()->get_group($group->id) == 'approved') || $group->post_privacy == "everyone"): ?>
                                <?php echo Theme::partial('create-post',compact('timeline','user_post','username')); ?>

                            <?php endif; ?>
                        <?php elseif($timeline->type == "event"): ?>
                            <?php if(($event->timeline_post_privacy == 'only_admins' && $event->is_eventadmin(Auth::user()->id, $event->id)) || ($event->timeline_post_privacy == 'only_guests' && Auth::user()->get_eventuser($event->id))): ?>
                                <?php echo Theme::partial('create-post',compact('timeline','user_post')); ?>

                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="timeline-posts">
                            <?php if($user_post == "user" || $user_post == "page" || $user_post == "group"): ?>
                                <?php if(count($posts) > 0): ?>
                                    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo Theme::partial('post',compact('post','timeline','next_page_url','user')); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="no-posts alert alert-warning"><?php echo e(trans('messages.no_posts')); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if($user_post == "event"): ?>
                                <?php if($event->type == 'private' && Auth::user()->get_eventuser($event->id) || $event->type == 'public'): ?>
                                    <?php if(count($posts) > 0): ?>
                                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo Theme::partial('post',compact('post','timeline','next_page_url','user')); ?>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <div class="no-posts alert alert-warning"><?php echo e(trans('messages.no_posts')); ?></div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="no-posts alert alert-warning"><?php echo e(trans('messages.private_posts')); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
				</div><!-- /row -->
			</div><!-- /col-md-10 -->

			<div class="col-md-3 hidden-sm hidden-xs">
				<div class="calendar hidden-print">
                    <div class="list">
                        <?php echo $event_list; ?>

                    </div>
                </div>
				
			</div>

		</div><!-- /row -->
	</div>
<script>
    var calendar = {

        init: function() {

            var d = new Date();
            var strDate = d.getFullYear() + "/" + (d.getMonth() + 1) + "/" + d.getDate();
            var monthNumber = d.getMonth() + 1;

            $('tbody td').on('click', function(e) {
                if ($(this).hasClass('event')) {
                    $('tbody td').removeClass('active');
                    $(this).addClass('active');
                } else {
                    $('tbody td').removeClass('active');
                }
            });

            $('tbody td').on('click', function(e) {
                $('.day-event').hide('fast');
                var monthEvent = $(this).attr('date-month');
                var dayEvent = $(this).attr('date-day');
                console.log(monthEvent+" "+dayEvent);
                $('.day-event[date-day="' + dayEvent + '"]').show('fast');
            });

            $('.print-btn').click(function() {
                window.print();
            });
        }
    };

    $(document).ready(function() {

        calendar.init();

    });
</script>