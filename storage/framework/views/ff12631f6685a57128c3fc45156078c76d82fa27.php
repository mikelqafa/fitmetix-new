<!-- Modal starts here-->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel">
    <div class="modal-dialog modal-likes" role="document">
        <div class="modal-content">
        	<i class="fa fa-spinner fa-spin"></i>
        </div>
    </div>
</div>
<div class="footer-description">
	<div class="footer__wrapper">
		<div class="socialite-terms text-center">
			<?php if(Auth::check()): ?>
				
			<?php else: ?>
				<a href="<?php echo e(url('login')); ?>"><?php echo e(trans('auth.login')); ?></a>
				<a href="<?php echo e(url('register')); ?>"><?php echo e(trans('auth.register')); ?></a>
			<?php endif; ?>
			<?php $__currentLoopData = App\StaticPage::active(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staticpage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<a href="<?php echo e(url('page/'.$staticpage->slug)); ?>"><?php echo e($staticpage->title); ?></a>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
		<div class="ft-copyright text-center">
			<?php echo e(trans('common.copyright')); ?> &copy; <?php echo e(date('Y')); ?> <?php echo e(Setting::get('site_name')); ?>. <?php echo e(trans('common.all_rights_reserved')); ?>

		</div>
		<div class="multi-lang">
			<a href="#" class="multi-lang__item cover-bg"  style="background-image: url(<?php echo e(asset('images/sp.png')); ?>)"></a>
			<a href="#" class="multi-lang__item cover-bg"  style="background-image: url(<?php echo e(asset('images/en.png')); ?>)"></a>
			<a href="#" class="multi-lang__option cover-bg" style="background-image: url(<?php echo e(asset('images/en.png')); ?>)"></a>
		</div>
	</div>
</div>


