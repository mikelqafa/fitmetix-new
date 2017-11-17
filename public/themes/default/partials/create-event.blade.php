<!-- <div class="main-content"> -->	
<div class="panel panel-default">
	<div class="panel-heading no-bg panel-settings">
		@if($group_id != null)
		<h3 class="panel-title">{{ trans('common.create_event_in') }} {!! $timeline_name !!}</h3>
		@else
		<h3 class="panel-title">{{ trans('common.create_event') }}</h3>
		@endif						
	</div>
	<div class="panel-body nopadding">
		@if( env('GOOGLE_MAPS_API_KEY') == NULL)
		<div class="col-md-12">
			<div class="alert alert-warning">
				<i class="fa fa-warning"></i> Please add Google maps API key in "Environment settings" available in admin panel to create an event as it needs location to be added.
				}
			</div>
		</div>

		@else
		<div class="socialite-form">
			@if(session()->has('message'))
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				{{ session()->get('message') }}
			</div>
			@endif                         
			<form class="margin-right" method="POST" action="{{ url('/'.$username.'/create-event/') }}">
				{{ csrf_field() }}

				<fieldset class="form-group required {{ $errors->has('name') ? ' has-error' : '' }}">
					<label>Upload Cover</label>
					<input type="file" name="event_images_upload" class="form-control">
					<div class="row">
						<div class="col-md-6">
							{{ Form::label('name', trans('common.name_of_your_event'), ['class' => 'control-label']) }}
							{{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('common.name_of_your_event')]) }}
							@if ($errors->has('name'))
								<span class="help-block">
									{{ $errors->first('name') }}
								</span>
							@endif
						</div>
						<div class="col-md-6">
							{{ Form::label('gender', 'Gender: ', ['class' => 'control-label']) }}
							{{ Form::select('gender', array('' => trans('admin.please_select'), 'male' => 'Male', 'female' => 'female', 'all' => 'All'), 'all' ,array('class' => 'form-control')) }}
							@if ($errors->has('name'))
								<span class="help-block">
									{{ $errors->first('name') }}
								</span>
							@endif
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group required {{ $errors->has('type') ? ' has-error' : '' }}">
					<div class="row">
						<div class="col-md-6">
							{{ Form::label('type', trans('common.type'), ['class' => 'control-label']) }}
							{{ Form::select('type', array('' => trans('admin.please_select'), 'private' => trans('common.private'), 'public' => trans('common.public')), null ,array('class' => 'form-control')) }}
							@if ($errors->has('type'))
								<span class="help-block">
									{{ $errors->first('type') }}
								</span>
							@endif
						</div>
						<div class="col-md-6">
							{{--{{ Form::label('user_limit', 'User Liimt', ['class' => 'control-label']) }}--}}
							<label for="user_limit">User Limit: <small>(Provide <code>0</code> for Unlimited User)</small></label>
							{{ Form::number('user_limit', old('user_limit'), ['class' => 'form-control', 'placeholder' => 'Max. user (Provide 0 for unlimited)']) }}
							@if ($errors->has('type'))
								<span class="help-block">
									{{ $errors->first('type') }}
								</span>
							@endif
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group required {{ $errors->has('location') || $errors->has('price') ? ' has-error' : '' }}">
					<div class="row">
						<div class="col-md-6">
							{{ Form::label('location', trans('common.location')) }}
							{{ Form::text('location', old('location'), ['class' => 'form-control', 'id' => 'location-input', 'autocomplete' => 'off','placeholder' => trans('common.enter_location'), 'onKeyPress' => "return initMap(event)" ]) }}
							@if ($errors->has('location'))
								<span class="help-block">
									{{ $errors->first('location') }}
								</span>
							@endif
						</div>
						<div class="col-md-6">
							<label for="price">Price: <small>(Provide <code>0</code> for FREE Event)</small></label>
							{{ Form::text('price', old('price'), ['class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off','placeholder' => 'Price' ]) }}
							@if ($errors->has('price'))
								<span class="help-block">
									{{ $errors->first('price') }}
								</span>
							@endif
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group required {{ $errors->has('start_date') || $errors->has('end_date') ? ' has-error' : '' }}">
					<div class="row">
						<div class="col-md-6">
							{{ Form::label('start_date', trans('admin.start_date'), ['class' => 'control-label']) }}

							<div class="input-group date form_datetime">												

								<input type="text" class="form-control" name="start_date" placeholder="01/01/1970" value="{{ old('start_date') }}">

								<span class="input-group-addon addon-right calendar-addon">
									<span class="fa fa-calendar"></span>
								</span>

								{{-- <span class="input-group-addon addon-right angle-addon">
									<span class="fa fa-angle-down"></span>
								</span> --}}
							</div>
							@if ($errors->has('start_date'))
							<span class="help-block">
								{{ $errors->first('start_date') }}
							</span>
							@endif
						</div>
						<div class="col-md-6">
							{{ Form::label('end_date', trans('admin.end_date'), ['class' => 'control-label']) }}
							<div class="input-group date form_datetime">

								<input value="{{ old('end_date') }}" type="text" class="form-control" name="end_date" placeholder="01/01/1970">

								<span class="input-group-addon addon-right calendar-addon">
									<span class="fa fa-calendar"></span>
								</span>
								
								{{-- <span class="input-group-addon addon-right angle-addon">
									<span class="fa fa-angle-down"></span>
								</span> --}}
							</div>
							@if ($errors->has('end_date'))
							<span class="help-block">
								{{ $errors->first('end_date') }}
							</span>
							@endif
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group">
					{{ Form::label('about', trans('common.about'), ['class' => 'control-label']) }}							
					{{ Form::textarea('about', old('about'), ['class' => 'form-control','placeholder' => trans('common.about')]) }}									
				</fieldset>

				<fieldset class="form-group">
					{{ Form::label('focus', 'Focus: ', ['class' => 'control-label']) }}
					{{ Form::radio('focus', 'training', true) }} Training
                    {{ Form::radio('focus', 'motivation') }} Motivation
                    {{ Form::radio('focus', 'learning') }} Learning (mix)
				</fieldset>

				<fieldset class="form-group">
					{{ Form::label('frequency', 'Frequency: ', ['class' => 'control-label']) }}
					{{ Form::radio('frequency', 'once', true) }} Once
					{{ Form::radio('frequency', 'daily') }} Daily
					{{ Form::radio('frequency', 'weekly') }} Weekly
					{{ Form::radio('frequency', 'monthly') }} Monthly
				</fieldset>

				{!! Form::hidden('group_id', $group_id) !!}		

				<div class="pull-right">
					@if($group_id != null)
					<a href="{!! url($username) !!}" class="btn btn-default">Cancel</a>								    
					@else
					<a href="{!! url($username.'/events') !!}" class="btn btn-default">Cancel</a>								    
					@endif									
					{{ Form::submit(trans('common.create_event'), ['class' => 'btn btn-success']) }}
				</div>
				<div class="clearfix"></div>

			</form>
		</div>
		@endif
	</div><!-- /panel-body -->
	
</div>			
<!-- </div> -->

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
async defer></script>

<script>
	function initMap(event) 
	{    
		var key;  
		var map = new google.maps.Map(document.getElementById('location-input'), {
		});

		var input = /** @type {!HTMLInputElement} */(
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