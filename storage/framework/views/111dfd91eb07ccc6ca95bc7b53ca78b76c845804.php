<!-- main-section -->	
<div class="container">
	<div class="row">              
		<div class="col-md-8 col-lg-8">
			<div class="panel panel-default">
				<div class="panel-heading no-bg panel-settings">
					<h3 class="panel-title"><?php echo e(trans('common.create_album')); ?></h3>
				</div>

				<div class="panel-body nopadding">  
					<div class="socialite-form">
						<?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>                         
						<form class="margin-right" method="POST" action="<?php echo e(url('/'.Auth::user()->username.'/album/create')); ?>" files="true" enctype="multipart/form-data">
							<?php echo e(csrf_field()); ?>


							<div class="row">
								<div class="col-md-6">
									<fieldset class="form-group required <?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
										<?php echo e(Form::label('name', trans('auth.name'), ['class' => 'control-label'])); ?>

										<?php echo e(Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('common.name_of_the_album')])); ?>

										<?php if($errors->has('name')): ?>
											<span class="help-block">
												<?php echo e($errors->first('name')); ?>

											</span>
										<?php endif; ?>
									</fieldset>
								</div>
								<div class="col-md-6">
									<fieldset class="form-group required <?php echo e($errors->has('privacy') ? ' has-error' : ''); ?>">
										<?php echo e(Form::label('privacy', trans('common.privacy_type'), ['class' => 'control-label'])); ?>

										<?php echo e(Form::select('privacy', array('' => trans('admin.please_select'), 'private' => trans('common.private'), 'public' => trans('common.public')), 'public' ,array('class' => 'form-control'))); ?>

										<?php if($errors->has('privacy')): ?>
											<span class="help-block">
												<?php echo e($errors->first('privacy')); ?>

											</span>
										<?php endif; ?>
									</fieldset>
								</div>
							</div>

							<fieldset class="form-group">
								<?php echo e(Form::label('about', trans('common.about'), ['class' => 'control-label'])); ?>

								<?php echo e(Form::textarea('about', old('about'), ['class' => 'form-control', 'placeholder' => trans('messages.create_album_placeholder'), 'rows' => '4', 'cols' => '20'])); ?>

							</fieldset>

							<fieldset class="form-group">
								<?php echo e(Form::label('album_photos', trans('common.upload_photos'), ['class' => 'control-label'])); ?>

								<?php echo e(Form::file('album_photos[]', ['multiple' => 'multiple', 'accept' =>  'image/jpeg,image/png,image/gif'])); ?>

							</fieldset>

							<fieldset class="form-group">
								<div class="pull-right">
									<a href="#" class="add-youtube-input"><?php echo e('+ '.trans('common.one_more')); ?></a>
								</div>
								<?php echo e(Form::label('album_videos[]', trans('common.youtube_links'), ['class' => 'control-label'])); ?>

								<div class="youtube-videos">
									<?php echo e(Form::text('album_videos[]', null, ['class' => 'form-control youtube_link', 'placeholder' => trans('common.copy_paste_youtube_link')] )); ?>

								</div>
							</fieldset>
							
							<fieldset class="form-group">
								<div class="pull-right">
									<?php echo e(Form::submit(trans('common.create_album'), ['class' => 'btn btn-success'])); ?>

								</div>
							</fieldset>

						</form>
					</div><!-- /Fitmetix-form -->
				</div>
			</div><!-- /panel -->		
		</div><!-- /col-md-8 -->

		<div class="col-md-4 col-lg-4">
			<?php echo Theme::partial('home-rightbar',compact('suggested_users','suggested_groups','suggested_pages','timeline')); ?>

		</div>
	</div>
</div>	
<!-- /main-section -->