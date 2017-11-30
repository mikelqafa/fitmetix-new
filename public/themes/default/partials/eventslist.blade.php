<link rel="stylesheet" type="text/css" href="{{ asset('css/drawer.css') }}">
<style type="text/css">
   .ft-card{
       min-height: 300px;
       width: 100%;
       background-color: #fff;
       border-radius: 2px;
       display: flex;
	   min-width: 300px;
       flex-direction: column;
       margin-bottom: 16px;
   }

   .ft-card__img-wrapper {
        min-height: 200px;
		background-color: #fafafa;
		display: flex;
		justify-content: center;
   }

   .ft-card__img {
   	   max-width: 100%;
   	   margin: 0 auto;
	   display: block;
	   max-height: 204px;
   }
  
   .ft-card__primary {
   	    padding: 16px;
   	    font-size: 16px;
   }

   .ft-card__title {
   	    font-size: 16px;
   	    line-height: 24px;
   	    color: inherit;
   }

   .card-desc {
        color: rgba(0,0,0,.54);
        font-size: 14px;
        line-height: 20px;
   }
	.ft-card__list-wrapper {
		display: flex;
		flex-direction: column;
		font-size: 13px;
	}
	.ft-card__list {
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		margin-bottom: 15px;
	}
	.ft-card__list .icon {
		margin-right: 10px;
		height: 24px;
		width: 24px;
		line-height: 24px;
		text-align: center;
	}

    .main-content > .container > .row > .col-md-7.col-lg-6 {
   	    width: 100% !important;
    }
    .md-drawer--permanent {
		width: auto;
		padding-right: 0px;
		padding-left: 0;
		padding-top: 64px;
		z-index: -1;
    }
    .md-drawer--permanent.md-drawer--visible {
        z-index: 1;
    }
   @media screen and (min-width: 960px) {
	   .md-drawer {
		   left: auto;
		   right:0;
		   width: 360px;
		   -webkit-transform: translateX(0px);
		   transform: translateX(0px);
	   }
	   .md-drawer--permanent {
		   -webkit-transform: translateX(0px);
		   transform: translateX(0px);
		   max-width:100%;
		   width: 360px;
	   }
	   .md-drawer--permanent .md-drawer__surface {
		   -webkit-transform:translateX(360px);
		   transform:translateX(360px);
		   width:360px;
		   max-width: none;
	   }
	   .md-drawer--animating .md-drawer__surface {
		   -webkit-transform:translateX(360px);
		   transform:translateX(360px)
	   }

	   body.has-permanent-drawer.is-drawer-open {
		   padding-right: 360px;
		   padding-left: 0 !important;
	   }
   }
	.ft-grid {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		padding-top: 16px;
		margin: 0 -16px;
	}
	.ft-grid__item {
		padding: 16px;
		width: 33.33333%;
		transition-duration: .3s;
		transition-timing-function: cubic-bezier(.4,0,.2,1);
		transition-property: all;
	}
   	body.is-drawer-open .ft-grid__item {
		width: 50%;
	}

   @media (min-width: 1200px) {
	   .main-content .container {
		   max-width: 1170px;
		   width: 100%;
	   }
   }
	.ft-filter {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
	}
	.ft-filter .form-group {
		margin: 0 15px;
		margin-bottom: 30px;
	}
</style>


<div class="main-content">
			<!-- List of user events-->
	
	<div class="post-filters pages-groups">
					<div class="pane">
					@include('flash::message')
						<div class="panel-heading no-bg">
							<div class="side-right">
								<a href="{{ url(Auth::user()->username.'/create-event') }}" class="btn btn-success">{{ trans('common.create_event') }}</a>
							</div>
							<center>
								<h3 class="panel-title" style="font-weight: 700;">
									{{ trans('messages.events-manage') }}
								</h3>
							</center>
						</div>
						<br/>
						<div class="ft-filter">
							<fieldset class="form-group required " style="margin-left: 0">
								<form action="{{ url('filter-events-by-location') }}" method="POST">
									{{ csrf_field() }}
								<input class="form-control" id="filter-location-input" autocomplete="off" placeholder="By Location" name="location" type="text" style="position: relative; overflow: hidden;">
								<input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
								</form>
							</fieldset>
							<fieldset class="form-group required ">
								<form action="{{ url('filter-events-by-date') }}" method="POST">
									{{ csrf_field() }}
								<input class="form-control" name="date" id="filter-date" autocomplete="off" placeholder="By Date" type="text" style="position: relative; overflow: hidden;">
								<input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
								</form>
							</fieldset>
							<fieldset class="form-group required ">
								<form action="{{ url('filter-events-by-tags') }}" method="POST">
									{{ csrf_field() }}
								<input class="form-control" id="filter-tag" name="tags" autocomplete="off" placeholder="By Tag" type="text" style="position: relative; overflow: hidden;">
								<input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
								</form>
							</fieldset>
							<fieldset class="form-group required " style="margin-right: 0">
								<form action="{{ url('filter-events-by-title') }}" method="POST">
									{{ csrf_field() }}
								<input class="form-control" id="filter-title" name="title" autocomplete="off" placeholder="By Title" type="text" style="position: relative; overflow: hidden;">
								<input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
								</form>
							</fieldset>
						</div>

						<div class="pan">
							@if(count($user_events))
							   <div class="ft-grid">
								   @php $i = 0; @endphp
							   @foreach($user_events as $user_event)
									<div class="ft-grid__item">
										<div class="ft-card">
											<a href="javascript:;" class="ft-card__img-wrapper ft-card_drawer-trigger" data-index="{{$i}}">
												@if($user_event->timeline->cover)
													<img class="ft-card__img" src="{{ env('STORAGE_URL').'uploads/events/covers/'.$user_event->timeline->cover['source'] }}" alt="Event Cover">
												@else
													<img class="ft-card__img" src="{{ env('STORAGE_URL').'uploads/events/covers/default-cover-event.png' }}" alt="Event Cover">
												@endif
											</a>
											<div class="ft-card__primary">
												<div class="ft-card__title">
													<h5 class="ft-event-card__title">{{ $user_event->timeline->name }}</h5>
												</div>
												<div class="ft-card__list-wrapper">
													<div class="ft-card__list">
														<div class="icon icon-location-o"></div>
														<div class="card-desc">
														  <a href="{{ url('locate-on-map/'.$user_event->location.'') }}" target="_blank">{{ $user_event->location }}</a>
														</div>
													</div>
													<div class="ft-card__list">
														<div class="icon icon-participant"></div>
														<div class="card-desc">
															{{ $user_event->gender }}
														</div>
													</div>
													<div class="ft-card__list">
														<div class="icon icon-time-o"></div>
														<div class="card-desc">
															{{ $user_event->start_date }} to {{ $user_event->end_date }}
														</div>
													</div>
													<div class="ft-card__list">
														<div class="icon icon-label-o"></div>
														<div class="card-desc">
															@if(!$user_event->price)
																{{ "FREE" }}
															@else
															    {{ $user_event->price }}
															@endif
														</div>
													</div>
													@if($user_event->id == $event_tags['event_id'])
														<div class="ft-card__list">
															<div class="card-desc" style="color: #1E7C82">	
															    {{ $event_tags['tags'][0] }}
															</div>
														</div>
													@endif
												</div>
											</div>
										</div>
									</div>
										   @php $i++ @endphp
								@endforeach
								</div>

								<aside class="md-drawer md-drawer--permanent" id="drawer-1" data-permanent="true">
									    <div class="md-drawer__shadow"></div>
									    <div class="md-drawer__surface">
											<div style="">
												<a class="btn" href="javascript:;" onclick="$('#drawer-1').MaterialDrawer('toggle')">
													&times;
												</a>
											</div>
											@php $i = 0; @endphp
									@foreach($user_events as $user_event)
											<div class="ft-card hidden" data-index="{{$i}}">
												    <div class="dropdown">
													    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
													        <div class="icon icon-options"></div>
													    </a>
													    <ul class="dropdown-menu">
													        @if($user_event->user_id != Auth::user()->id)
													            <li><a href="javascript:;">Report</a></li>
													            <li><a href="javascript:;">Save</a></li>
													            <li><a href="javascript:;">Share on facebook</a></li>
													        @else
													            <li><a href="javascript:;" class="">Edit</a></li>
													            <li><a href="javascript:;" class="delete-own-event">Delete</a></li>
													            <li><a href="javascript:;">Share on facebook</a></li>
													        @endif
													    </ul>
													</div>
												<div class="ft-card__img-wrapper">
													@if($user_event->timeline->cover)
														<img class="ft-card__img" src="{{ env('STORAGE_URL').'uploads/events/covers/'.$user_event->timeline->cover['source'] }}" alt="Event Cover">
													@else
													    <img class="ft-card__img" src="{{ env('STORAGE_URL').'uploads/events/covers/default-cover-event.png' }}" alt="Event Cover">
													@endif
												</div>
												<div class="ft-card__primary">
													<div class="ft-card__title">
														<h5 class="ft-event-card__title">{{ $user_event->timeline->name }}</h5>
													</div>
													<div class="ft-card__list-wrapper">
														<div class="ft-card__list">
															<div class="icon icon-location-o"></div>
															<div class="card-desc">
																{{ $user_event->location }}
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon icon-participant"></div>
															<div class="card-desc">
																{{ $user_event->gender }}
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon icon-time-o"></div>
															<div class="card-desc">
																{{ $user_event->start_date }} to {{ $user_event->end_date }}
															</div>
														</div>

														<div class="ft-card__list">
															<div class="icon icon-label-o"></div>
															<div class="card-desc">
																@if(!$user_event->price)
																	{{ "FREE" }}
																@else
																    {{ $user_event->price }}
																@endif
															    @if(Auth::user()->id != $user_event->user_id)
																    @if($user_event->expired == true)
																	    <button class="btn" disabled>Register</button>
																	@else
																		@if(($user_event->gender == 'all') || ($user_event->gender == Auth::user()->gender))
																			
																			@if($user_event->registered == true) 
																			    <button class="btn btn-primary">Registered</button>
																			
																			@elseif($user_event->users()->count() >= $user_event->user_limit)
																			
																			    <button disabled class="btn" data-timeline = "{{ $user_event->timeline->id }}">Register</button>

																			@else
																			    <button class="btn btn-primary join-event-btn" data-timeline = "{{ $user_event->timeline->id }}">Register</button>
																			@endif	
																		@else
																		    <button disabled class="btn" data-timeline = "{{ $user_event->timeline->id }}">Register</button>
																		@endif
																	@endif
																@endif
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon icon-participant"></div>
															<div class="card-desc">
																<p class="show_participants" data-eventID = "{{ $user_event->id }}">{{ $user_event->users()->count() }}</p>
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon"></div>
															<div class="card-desc">
																{{ $user_event->frequency }}
															</div>
														</div>
													</div>
													<div class="ft-card__desc">
														{{ $user_event->timeline->about }}
													</div>
												</div>
											</div>
										@php $i++ @endphp
									@endforeach
										</div>
									</aside>
							@else
							<div class="alert alert-warning">
								{{ trans('messages.no_events') }}
							</div>
							@endif
						</div>
					</div>
				</div><!-- /panel -->
</div>
