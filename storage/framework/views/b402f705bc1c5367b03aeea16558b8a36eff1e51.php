<!-- <div class="main-content"> -->	
<div class="panel panel-default">
	<div class="panel-heading no-bg panel-settings">
		<?php if($group_id != null): ?>
		<h3 class="panel-title"><?php echo e(trans('common.create_event_in')); ?> <?php echo $timeline_name; ?></h3>
		<?php else: ?>
		<h3 class="panel-title"><?php echo e(trans('common.create_event')); ?></h3>
		<?php endif; ?>						
	</div>
	<div class="panel-body nopadding">  
		<?php if((env('GOOGLE_MAPS_API_KEY') == NULL)): ?>
		<div class="col-md-12">
			<div class="alert alert-warning">
				<i class="fa fa-warning"></i> Please add Google maps API key in "Environment settings" available in admin panel to create an event as it needs location to be added.
			</div>
		</div>

		<?php else: ?>
		<div class="socialite-form">
			<?php if(session()->has('message')): ?>
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php echo e(session()->get('message')); ?>

			</div>
			<?php endif; ?>                         
			<form class="margin-right" method="POST" action="<?php echo e(url('/'.$username.'/create-event/')); ?>">
				<?php echo e(csrf_field()); ?>


				<fieldset class="form-group required <?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
					<div class="row">
						<div class="col-md-6">
							<?php echo e(Form::label('name', trans('common.name_of_your_event'), ['class' => 'control-label'])); ?>

							<?php echo e(Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('common.name_of_your_event')])); ?>

							<?php if($errors->has('name')): ?>
								<span class="help-block">
									<?php echo e($errors->first('name')); ?>

								</span>
							<?php endif; ?>
						</div>
						<div class="col-md-6">
							<?php echo e(Form::label('gender', 'Gender: ', ['class' => 'control-label'])); ?>

							<?php echo e(Form::select('gender', array('' => trans('admin.please_select'), 'male' => 'Male', 'female' => 'female', 'all' => 'All'), 'all' ,array('class' => 'form-control'))); ?>

							<?php if($errors->has('name')): ?>
								<span class="help-block">
									<?php echo e($errors->first('name')); ?>

								</span>
							<?php endif; ?>
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group required <?php echo e($errors->has('type') ? ' has-error' : ''); ?>">
					<div class="row">
						<div class="col-md-6">
							<?php echo e(Form::label('type', trans('common.type'), ['class' => 'control-label'])); ?>

							<?php echo e(Form::select('type', array('' => trans('admin.please_select'), 'private' => trans('common.private'), 'public' => trans('common.public')), null ,array('class' => 'form-control'))); ?>

							<?php if($errors->has('type')): ?>
								<span class="help-block">
									<?php echo e($errors->first('type')); ?>

								</span>
							<?php endif; ?>
						</div>
						<div class="col-md-6">
							
							<label for="user_limit">User Limit: <small>(Provide <code>0</code> for Unlimited User)</small></label>
							<?php echo e(Form::number('user_limit', old('user_limit'), ['class' => 'form-control', 'placeholder' => 'Max. user (Provide 0 for unlimited)'])); ?>

							<?php if($errors->has('type')): ?>
								<span class="help-block">
									<?php echo e($errors->first('type')); ?>

								</span>
							<?php endif; ?>
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group required <?php echo e($errors->has('location') || $errors->has('price') ? ' has-error' : ''); ?>">
					<div class="row">
						<div class="col-md-6">
							<?php echo e(Form::label('location', trans('common.location'))); ?>

							<?php echo e(Form::text('location', old('location'), ['class' => 'form-control', 'id' => 'location-input', 'autocomplete' => 'off','placeholder' => trans('common.enter_location'), 'onKeyPress' => "return initMap(event)" ])); ?>

							<?php if($errors->has('location')): ?>
								<span class="help-block">
									<?php echo e($errors->first('location')); ?>

								</span>
							<?php endif; ?>
						</div>
						<div class="col-md-6">
							<label for="price">Price: <small>(Provide <code>0</code> for FREE Event)</small></label>
							<?php echo e(Form::text('price', old('price'), ['class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off','placeholder' => 'Price' ])); ?>

							<?php if($errors->has('price')): ?>
								<span class="help-block">
									<?php echo e($errors->first('price')); ?>

								</span>
							<?php endif; ?>
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group required <?php echo e($errors->has('start_date') || $errors->has('end_date') ? ' has-error' : ''); ?>">
					<div class="row">
						<div class="col-md-6">
							<?php echo e(Form::label('start_date', trans('admin.start_date'), ['class' => 'control-label'])); ?>


							<div class="input-group date form_datetime">												

								<input type="text" class="form-control" name="start_date" placeholder="01/01/1970" value="<?php echo e(old('start_date')); ?>">

								<span class="input-group-addon addon-right calendar-addon">
									<span class="fa fa-calendar"></span>
								</span>

								
							</div>
							<?php if($errors->has('start_date')): ?>
							<span class="help-block">
								<?php echo e($errors->first('start_date')); ?>

							</span>
							<?php endif; ?>
						</div>
						<div class="col-md-6">
							<?php echo e(Form::label('end_date', trans('admin.end_date'), ['class' => 'control-label'])); ?>

							<div class="input-group date form_datetime">

								<input value="<?php echo e(old('end_date')); ?>" type="text" class="form-control" name="end_date" placeholder="01/01/1970">

								<span class="input-group-addon addon-right calendar-addon">
									<span class="fa fa-calendar"></span>
								</span>
								
								
							</div>
							<?php if($errors->has('end_date')): ?>
							<span class="help-block">
								<?php echo e($errors->first('end_date')); ?>

							</span>
							<?php endif; ?>
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group">
					<?php echo e(Form::label('about', trans('common.about'), ['class' => 'control-label'])); ?>							
					<?php echo e(Form::textarea('about', old('about'), ['class' => 'form-control','placeholder' => trans('common.about')])); ?>									
				</fieldset>

				<fieldset class="form-group">
					<?php echo e(Form::label('focus', 'Focus: ', ['class' => 'control-label'])); ?>

					<?php echo e(Form::radio('focus', 'training', true)); ?> Training
                    <?php echo e(Form::radio('focus', 'motivation')); ?> Motivation
                    <?php echo e(Form::radio('focus', 'learning')); ?> Learning (mix)
				</fieldset>

				<fieldset class="form-group">
					<?php echo e(Form::label('frequency', 'Frequency: ', ['class' => 'control-label'])); ?>

					<?php echo e(Form::radio('frequency', 'once', true)); ?> Once
					<?php echo e(Form::radio('frequency', 'daily')); ?> Daily
					<?php echo e(Form::radio('frequency', 'weekly')); ?> Weekly
					<?php echo e(Form::radio('frequency', 'monthly')); ?> Monthly
				</fieldset>

				<?php echo Form::hidden('group_id', $group_id); ?>		

				<div class="pull-right">
					<?php if($group_id != null): ?>
					<a href="<?php echo url($username); ?>" class="btn btn-default">Cancel</a>								    
					<?php else: ?>
					<a href="<?php echo url($username.'/events'); ?>" class="btn btn-default">Cancel</a>								    
					<?php endif; ?>									
					<?php echo e(Form::submit(trans('common.create_event'), ['class' => 'btn btn-success'])); ?>

				</div>
				<div class="clearfix"></div>

			</form>
		</div>
		<?php endif; ?>
	</div><!-- /panel-body -->
	
</div>			
<!-- </div> -->

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(env('GOOGLE_MAPS_API_KEY')); ?>&libraries=places&callback=initMap"
async defer></script>

<script>
	function initMap(event) 
	{    
		var key;  
		var map = new google.maps.Map(document.getElementById('location-input'), {
		});

		var input = /** @type  {!HTMLInputElement} */(
			document.getElementById('location-input'));        

		if(window.event)
		{
			key = window.event.keyCode; 

		}
		else 
		{
			if(event)
				key = event.which;      
		}       

		if(key == 13){       
    //do nothing 
    return false;       
    //otherwise 
} else { 
	var autocomplete = new google.maps.places.Autocomplete(input);  
	autocomplete.bindTo('bounds', map);

    //continue as normal (allow the key press for keys other than "enter") 
    return true; 
} 
}

$(".form_datetime").datetimepicker({
	format: "mm/dd/yyyy H P",
	autoclose: true,
	minView: 1,
	startView: "decade",
	showMeridian: true
});
</script>