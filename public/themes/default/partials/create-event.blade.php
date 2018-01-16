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
    .event_images_upload--label{
        height: 105px;
    }
    .has-item .event_images_upload--label{
        display: none;
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
        z-index: 2;
    }

    .bdp-input {
        border-radius: 2px;
        padding: 4px 2px;
        border: 1px solid rgba(34, 36, 38, .15);
        cursor: pointer;
        display: flex;
        background-color: #fff;
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
        cursor: pointer;
    }
    .padding-bottom{
        padding-bottom: 16px;
    }
</style>
<div class="layout-m-t-1">
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
                <form class="margin-right padding-bottom create-event-form" method="POST" action="{{ url('/'.$username.'/create-event/') }}"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div id="app-create-post">
                        <app-make-event></app-make-event>
                    </div>
					<br/>
					<div class="form-helper-wrapper layout-m-b-1">
                        @if ($errors->has('name'))
                            <span class="help-block">
							{{ $errors->first('name') }}
						</span>
                        @endif
                        {{ Form::text('name', old('name'), ['id'=>'title','required'=>'required', 'class' => 'form-control', 'placeholder' => trans('common.name_of_your_event'),'maxlength'=>30]) }}
                        <div class="form-helper">
                            <div class="helper-inner arrow_box arrow_box--bottom-xs">
                                Give a great title for your event.
                            </div>
                        </div>
                    </div>
					<div class="row">
						<div class="col-md-6 form-helper-wrapper">
							{{ Form::select('type', array('' => trans('common.privacy'), 'private' => trans('common.private'), 'public' => trans('common.public')), null ,array('id'=> 'privacy', 'class' => 'form-control selectize')) }}
							@if ($errors->has('type'))
								<span class="help-block">
									{{ $errors->first('type') }}
								</span>
							@endif
                            <div class="form-helper">
                                <div class="helper-inner arrow_box arrow_box--bottom-xs">
                                    Private events are only joined by user you follow. Anyone can join public event.
                                </div>
                            </div>
						</div>
						<div class="col-md-6 form-helper-wrapper">
							<fieldset class="form-group">
								{{-- {{ Form::label('frequency', 'Frequency: ', ['class' => 'control-label']) }} --}}
								{{ Form::hidden('frequency', 'once', array('class' => 'form-control','required'=>'required')) }}
                                {{ Form::select('gender', array('' => trans('common.gender'), 'male' => 'Males Only', 'female' => 'Females Only', 'all' => 'Everyone'), null ,array('id' => 'gender','class' => 'form-control selectize')) }}
							</fieldset>
                            <div class="form-helper">
                                <div class="helper-inner arrow_box arrow_box--bottom-xs">
                                    If your event only for a specific gender, select gender here. Use everyone for all gender.
                                </div>
                            </div>
						</div>
					</div>
				</fieldset>

                    <fieldset class="form-helper-wrapper form-group required {{ $errors->has('type') ? ' has-error' : '' }}">
                        {{-- {{ Form::label('location', trans('common.location')) }} --}}
                        {{ Form::text('location', old('location'), ['required'=>'required', 'class' => 'form-control', 'id' => 'location-input', 'autocomplete' => 'off','placeholder' => trans('common.enter_location'), 'onKeyPress' => "return initMap(event)" ]) }}
                        @if ($errors->has('location'))
                            <span class="help-block">
							{{ $errors->first('location') }}
						</span>
					@endif
                        <div class="form-helper">
                            <div class="helper-inner arrow_box arrow_box--bottom-xs">
                                Provide location for your event, so that user can reach over there easily.
                            </div>
                        </div>
				</fieldset>

                    <fieldset
                            class="form-group required {{ $errors->has('location') || $errors->has('price') ? ' has-error' : '' }}">
                        <div class="row">
                            <div class="col-md-5 form-helper-wrapper">
                                {{ Form::number('user_limit', old('user_limit'), ['required'=>'required','class' => 'form-control', 'placeholder' => 'Number of participants','min'=>1]) }}
                                @if ($errors->has('type'))
                                    <span class="help-block">
									{{ $errors->first('type') }}
								</span>
                                @endif
                                <div class="form-helper">
                                    <div class="helper-inner arrow_box arrow_box--bottom-xs">
                                        Enter how many user can participate.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 form-helper-wrapper">
                                {{ Form::number('price', old('price'), ['class' => 'form-control', 'id' => 'price', 'autocomplete' => 'off','placeholder' => 'Price' ,'min'=>0,'max'=>10000]) }}
                                @if ($errors->has('price'))
                                    <span class="help-block">
									{{ $errors->first('price') }}
								</span>
                                @endif
                                <div class="form-helper">
                                    <div class="helper-inner arrow_box arrow_box--bottom-xs">
                                        Provide event Price. If free, enter 0.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 md-align md-align--center-center form-helper-wrapper">
                                <div class="md-layout md-layout-spacer form-control md-layout--row">
                                    <div class="md-layout md-layout--row">
                                        <input type="radio" name="currency" id="currency-euro" value="EURO"> <label class="bdp-label" for="currency-euro">EURO</label>
                                    </div>
                                    <div class="md-layout md-layout--row layout-m-l-1">
                                        <input type="radio" name="currency" id="currency-usd" value="USD" checked><label class="bdp-label" for="currency-usd">USD</label>
                                    </div>
                                </div>
                                <div class="form-helper">
                                    <div class="helper-inner arrow_box arrow_box--bottom-xs">
                                        Select currency type for paid event.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset
                            class="form-group required {{ $errors->has('start_date') || $errors->has('end_date') ? ' has-error' : '' }}">
                        <div class="row">
                            <div class="col-md-6 form-helper-wrapper">
                                <div class="input-group date form_datetime">
                                    <input type="text" required  class="datepick2--event form-control" name="start_date"
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
                                <div class="form-helper">
                                    <div class="helper-inner arrow_box arrow_box--bottom-xs">
                                        Provide date and time when event start.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 form-helper-wrapper">
                                {{ Form::text('duration', old('duration'), ['required'=>'required','class' => 'form-control', 'id' => 'duration-event', 'autocomplete' => 'off','placeholder' => 'duration']) }}
                                @if ($errors->has('duration'))
                                    <span class="help-block">
									{{ $errors->first('duration') }}
								</span>
                                @endif
                                <div class="form-helper">
                                    <div class="helper-inner arrow_box arrow_box--bottom-xs">
                                        How long your event will run. Maximum of 48 hours allowed.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-group form-helper-wrapper">
                        {{ Form::textarea('about', old('about'), ['id'=>'description','class' => 'form-control','placeholder' => trans('common.description'), 'maxlength'=>500]) }}
                        <div class="form-helper" style="top: -56px;">
                            <div class="helper-inner arrow_box arrow_box--bottom-xs arrow_box--bottom">
                                Enter details about event and things which need to carry to join event.
                            </div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="focus" value="training">

                    {!! Form::hidden('group_id', $group_id) !!}

                    <div class="pull-right hidden-xs">
                        {{ Form::submit(trans('common.create_event'), ['class' => 'btn ft-btn-primary']) }}
                    </div>
                    <div class="visible-xs hidden">
                        {{ Form::submit(trans('common.create_event'), ['class' => 'btn btn-block ft-btn-primary']) }}
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