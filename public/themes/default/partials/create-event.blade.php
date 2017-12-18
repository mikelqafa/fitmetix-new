<style type="text/css">
    ::-webkit-input-placeholder {
        text-align: center;
    }

    :-moz-placeholder { /* Firefox 18- */
        text-align: center;
    }

    ::-moz-placeholder { /* Firefox 19+ */
        text-align: center;
    }

    :-ms-input-placeholder {
        text-align: center;
    }

    .event_images_upload {
        visibility: hidden;
        width: 0;
        height: 0;
    }

    .event_images_upload--label {
        height: 240px;
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

    #event_images_upload--image img {
        max-width: 100%;
        display: block;
        max-height: 100%;
    }

    #event_images_upload--image .event-remove-thumb {
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .bdp-input {
        border-radius: 2px;
        padding: 4px 2px;
        border: 1px solid rgba(34, 36, 38, .15);
        cursor: pointer;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    .bdp-input > .black-label {
        color: #c3c6cb;
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
        font-size: 90%;
        margin-left: 4px;
    }
    .padding-bottom{
        padding-bottom: 16px;
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
                    <i class="fa fa-warning"></i> Please add Google maps API key in "Environment settings" available in
                    admin panel to create an event as it needs location to be added.
                    }
                </div>
            </div>

        @else
            <div class="container">
                @if(session()->has('message'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        {{ session()->get('message') }}
                    </div>
                @endif
                <form class="margin-right padding-bottom" method="POST" action="{{ url('/'.$username.'/create-event/') }}"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}

				    <fieldset class="form-group required">
					<label class="event_images_upload--label" for="event_images_upload" style="background-image: url({{url('images/no-image.png')}})">
						<input id="event_images_upload" required type="file" multiple="multiple"
							   accept="image/jpeg,image/png,image/gif"
							   name="event_images_upload[]" class="event_images_upload form-control" required>
						<i class="hidden icon icon-add"></i>
						<div id="event_images_upload--image"></div>
					</label>

					<br/>
					{{-- {{ Form::label('name', trans('common.name_of_your_event'), ['class' => 'control-label']) }} --}}
					@if ($errors->has('name'))
						<span class="help-block">
							{{ $errors->first('name') }}
						</span>
					@endif
					{{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('common.name_of_your_event'),'maxlength'=>30]) }}
					<br/>	
					<div class="row">
						<div class="col-md-6">
							{{-- {{ Form::label('type', trans('common.privacy'), ['class' => 'control-label']) }} --}}
							{{ Form::select('type', array('' => trans('common.privacy'), 'private' => trans('common.private'), 'public' => trans('common.public')), null ,array('class' => 'form-control selectize','required'=>'required')) }}
							@if ($errors->has('type'))
								<span class="help-block">
									{{ $errors->first('type') }}
								</span>
							@endif
						</div>
						<div class="col-md-6">
							<fieldset class="form-group">
								{{-- {{ Form::label('frequency', 'Frequency: ', ['class' => 'control-label']) }} --}}
								{{ Form::hidden('frequency', 'once', array('class' => 'form-control','required'=>'required')) }}
                                {{ Form::select('gender', array('' => trans('common.gender'), 'male' => 'Males Only', 'female' => 'Females Only', 'all' => 'Everyone'), null ,array('class' => 'form-control selectize','required'=>'required')) }}
							</fieldset>
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

                    <fieldset
                            class="form-group required {{ $errors->has('location') || $errors->has('price') ? ' has-error' : '' }}">
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
                                {{ Form::number('price', old('price'), ['class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off','placeholder' => 'Price (USD)' ,'min'=>0,'max'=>10000]) }}
                                @if ($errors->has('price'))
                                    <span class="help-block">
									{{ $errors->first('price') }}
								</span>
                                @endif
                            </div>
                        </div>
                    </fieldset>

                    <fieldset
                            class="form-group required {{ $errors->has('start_date') || $errors->has('end_date') ? ' has-error' : '' }}">
                        <div class="row">
                            <div class="col-md-6">
                                {{-- {{ Form::label('start_date', trans('admin.start_date'), ['class' => 'control-label']) }} --}}

                                <div class="input-group date form_datetime">

                                    <input type="text" class="datepick2--event form-control" name="start_date"
                                           placeholder="Start Time" value="{{ old('start_date') }}">

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
                            <div class="col-md-6">
                                {{ Form::text('duration', old('duration'), ['class' => 'form-control', 'id' => 'duration-event', 'autocomplete' => 'off','placeholder' => 'duration']) }}
                                @if ($errors->has('duration'))
                                    <span class="help-block">
									{{ $errors->first('duration') }}
								</span>
                                @endif
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-group">
                        {{-- {{ Form::label('about', trans('common.about'), ['class' => 'control-label']) }}							 --}}
                        {{ Form::textarea('about', old('about'), ['class' => 'form-control','placeholder' => trans('common.description'), 'maxlength'=>500]) }}
                    </fieldset>
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
    function initMap(event) {
        var key;
        var map = new google.maps.Map(document.getElementById('location-input'), {});

        var input = /** @type {!HTMLInputElement} */(
                document.getElementById('location-input'));

        if (window.event) {
            key = window.event.keyCode;

        }
        else {
            if (event)
                key = event.which;
        }

        if (key == 13) {
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