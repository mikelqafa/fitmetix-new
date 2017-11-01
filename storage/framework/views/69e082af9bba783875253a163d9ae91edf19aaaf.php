<?php if(
        ($timeline->type == 'user' && $timeline->id == Auth::user()->timeline_id) ||
        ($timeline->type == 'page' && $timeline->page->is_admin(Auth::user()->id) == true) ||
        ($timeline->type == 'group' && $timeline->groups->is_admin(Auth::user()->id) == true)
    ): ?>
<br>

<?php endif; ?>






<form class="change-avatar-form hidden" action="<?php echo e(url('ajax/change-avatar')); ?>" method="post" enctype="multipart/form-data">
	<input name="timeline_id" value="<?php echo e($timeline->id); ?>" type="hidden">
	<input name="timeline_type" value="<?php echo e($timeline->type); ?>" type="hidden">
	<input class="change-avatar-input hidden" accept="image/jpeg,image/png" type="file" name="change_avatar" >
</form>

<!-- Change cover form -->
<form class="change-cover-form hidden" action="<?php echo e(url('ajax/change-cover')); ?>" method="post" enctype="multipart/form-data">
	<input name="timeline_id" value="<?php echo e($timeline->id); ?>" type="hidden">
	<input name="timeline_type" value="<?php echo e($timeline->type); ?>" type="hidden">
	<input class="change-cover-input hidden" accept="image/jpeg,image/png" type="file" name="change_cover" >
</form>



<?php if(Setting::get('timeline_ad') != NULL): ?>
<div id="link_other" class="post-filters">
	<?php echo htmlspecialchars_decode(Setting::get('timeline_ad')); ?>

</div>
<?php endif; ?>
















