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
				<a href="<?php echo e(url('terms')); ?>"><?php echo e(trans('Terms')); ?></a>
			
				<a href="<?php echo e(url('help')); ?>"><?php echo e(trans('Help')); ?></a>
			<?php endif; ?>
		</div>
			<div class="ft-copyright text-center">
			<?php echo e(trans('common.copyright')); ?> &copy; <?php echo e(date('Y')); ?> <?php echo e(Setting::get('site_name')); ?>. <?php echo e(trans('common.all_rights_reserved')); ?>

		</div>
		<div class="multi-lang">
			<?php echo e(csrf_field()); ?>

			<a href="javascript:;" class="multi-lang__item cover-bg switch-language" data-language='es'   style="background-image: url(<?php echo e(asset('images/sp.png')); ?>)"></a>
			<a href="javascript:;" class="multi-lang__item cover-bg switch-language" data-language='en'  style="background-image: url(<?php echo e(asset('images/en.png')); ?>)"></a>
		</div>
		<div class="multi-lang multi-lang--mobile">
			<a href="javascript:;" class="multi-lang__option cover-bg switch-language" data-language='es'   style="background-image: url(<?php echo e(asset('images/sp.png')); ?>)"></a>
			<a href="javascript:;" class="lang__option multi-lang__option cover-bg switch-language" data-language='en'  style="background-image: url(<?php echo e(asset('images/en.png')); ?>)"></a>
		</div>
	</div>
</div>


