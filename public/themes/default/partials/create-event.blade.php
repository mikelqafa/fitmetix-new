<style type="text/css">
::-webkit-input-placeholder {
   text-align: center;
}

:-moz-placeholder { /* Firefox 18- */
   text-align: center;  
}

::-moz-placeholder {  /* Firefox 19+ */
   text-align: center;  
}

:-ms-input-placeholder {  
   text-align: center; 
}

.bdp-input {
    border-radius: 2px;
    padding: 0 3px;
    border: 1px solid rgba(34, 36, 38, .15);
    cursor: pointer;
}

.bdp-input.disabled {
    color: #AAA;
    cursor: default;
}

.bdp-popover {
    min-width: 110px;
}

.bdp-popover input {
    display: inline;
    margin-bottom: 3px;
    width: 60px;
}

.bdp-block {
    display: inline-block;
    line-height: 1;
    text-align: center;
    padding: 5px 3px;
}

.bdp-label {
    font-size: 70%;
}
	.event_images_upload{
		visibility: hidden;
		width: 0;
		height: 0;
	}
	.event_images_upload--label {
		height: 312px;
		width: 100%;
		display: block;
		position: relative;
		background-position: center;
		background-repeat: no-repeat;
		background-size: 200px auto;
		cursor: pointer;
	}
	.event_images_upload--label.image-added {
		background-image: none;
	}
	#event_images_upload--image {
		width: 100%;
		overflow: hidden;
		height: 100%;
		position: absolute;
		top: 0;
		left: 0;
	}
	#event_images_upload--image img{
		max-width: 100%;
		display: block;
		max-height: 100%;
	}
	#event_images_upload--image .event-remove-thumb {
		position: absolute;
		top:15px;
		right: 15px;
	}
</style>
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
					<label class="event_images_upload--label" for="event_images_upload" style="background-image: url({{url('images/no-image.png')}})">
						<input id="event_images_upload" type="file" name="event_images_upload" class="event_images_upload form-control">
						<i class="hidden icon icon-add"></i>
						<div id="event_images_upload--image"></div>
					</label>

					<br/>
					{{-- {{ Form::label('name', trans('common.name_of_your_event'), ['class' => 'control-label']) }} --}}
					{{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('common.name_of_your_event'),'maxlength'=>30]) }}
					@if ($errors->has('name'))
						<span class="help-block">
							{{ $errors->first('name') }}
						</span>
					@endif
					<br/>	
					<div class="row">
						<div class="col-md-4">
							{{-- {{ Form::label('type', trans('common.privacy'), ['class' => 'control-label']) }} --}}
							{{ Form::select('type', array('' => trans('common.privacy'), 'private' => trans('common.private'), 'public' => trans('common.public')), null ,array('class' => 'form-control')) }}
							@if ($errors->has('type'))
								<span class="help-block">
									{{ $errors->first('type') }}
								</span>
							@endif
						</div>
						<div class="col-md-4">
							<fieldset class="form-group">
								{{-- {{ Form::label('frequency', 'Frequency: ', ['class' => 'control-label']) }} --}}
								{{ Form::select('frequency', array('' => 'Frequency', 'once' => 'Once', 'daily' => 'Daily', 'weekly'=>'Weekly','monthly'=>'Monthly'), null ,array('class' => 'form-control')) }}
							
							</fieldset>
						</div>
						<div class="col-md-4">
							{{-- {{ Form::label('gender', 'Gender: ', ['class' => 'control-label']) }} --}}
							{{ Form::select('gender', array('' => trans('common.gender'), 'male' => 'Male', 'female' => 'female', 'all' => 'All'), null ,array('class' => 'form-control')) }}
							@if ($errors->has('name'))
								<span class="help-block">
									{{ $errors->first('name') }}
								</span>
							@endif
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group required {{ $errors->has('type') ? ' has-error' : '' }}">
				    {{-- {{ Form::label('location', trans('common.location')) }} --}}
					{{ Form::text('location', old('location'), ['class' => 'form-control', 'id' => 'location-input', 'autocomplete' => 'off','placeholder' => trans('common.enter_location'), 'onKeyPress' => "return initMap(event)" ]) }}
					@if ($errors->has('location'))
						<span class="help-block">
							{{ $errors->first('location') }}
						</span>
					@endif	
				</fieldset>

				<fieldset class="form-group required {{ $errors->has('location') || $errors->has('price') ? ' has-error' : '' }}">
					<div class="row">
						<div class="col-md-6">
							{{--{{ Form::label('user_limit', 'User Liimt', ['class' => 'control-label']) }}--}}
							{{-- <label for="user_limit">User Limit: </label> --}}
							{{ Form::number('user_limit', old('user_limit'), ['class' => 'form-control', 'placeholder' => 'Number of participants','min'=>1]) }}
							@if ($errors->has('type'))
								<span class="help-block">
									{{ $errors->first('type') }}
								</span>
							@endif
						</div>
						<div class="col-md-6">
							{{-- <label for="price">Price: <small>(Provide <code>0</code> for FREE Event)</small></label> --}}
							{{ Form::number('price', old('price'), ['class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off','placeholder' => 'Price' ,'max'=>10000]) }}
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
							{{-- {{ Form::label('start_date', trans('admin.start_date'), ['class' => 'control-label']) }} --}}

							<div class="input-group date form_datetime">												

								<input type="text" class="datepick2 form-control" name="start_date" placeholder="Start Time" value="{{ old('start_date') }}">

								<span class="input-group-addon addon-right calendar-addon">
									<span class="fa fa-calendar"></span>
								</span>
							</div>
							@if ($errors->has('start_date'))
							<span class="help-block">
								{{ $errors->first('start_date') }}
							</span>
							@endif
						</div>
						<div class="col-md-6 hidden">
							{{ Form::number('duration', old('duration'), ['class' => 'form-control', 'id' => 'duration', 'autocomplete' => 'off','placeholder' => 'duration' ,'max'=>2]) }}
							@if ($errors->has('duration'))
								<span class="help-block">
									{{ $errors->first('duration') }}
								</span>
							@endif
						</div>
						<div class="col-md-6">
							{{ Form::number('duration', old('duration'), ['class' => 'form-control', 'id' => 'duration', 'autocomplete' => 'off','placeholder' => 'duration' ,'max'=>2]) }}
						</div>
					</div>
				</fieldset>

				<fieldset class="form-group">
					{{-- {{ Form::label('about', trans('common.about'), ['class' => 'control-label']) }}							 --}}
					{{ Form::textarea('about', old('about'), ['class' => 'form-control','placeholder' => trans('common.description'), 'maxlength'=>500]) }}									
				</fieldset>

				{{-- <fieldset class="form-group">
					{{ Form::label('focus', 'Focus: ', ['class' => 'control-label']) }}
					{{ Form::radio('focus', 'training', true) }} Training
                    {{ Form::radio('focus', 'motivation') }} Motivation
                    {{ Form::radio('focus', 'learning') }} Learning (mix)
				</fieldset> --}}
				<input type="hidden" name="focus" value="training">

				{!! Form::hidden('group_id', $group_id) !!}		

				<div class="pull-right">
					{{--@if($group_id != null)
					<a href="{!! url($username) !!}" class="btn btn-default">Cancel</a>								    
					@else
					<a href="{!! url($username.'/events') !!}" class="btn btn-default">Cancel</a>								    
					@endif--}}
					{{ Form::submit(trans('common.create_event'), ['class' => 'btn ft-btn-primary']) }}
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
</script>