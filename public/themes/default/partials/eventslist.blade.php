<link rel="stylesheet" type="text/css" href="{{ asset('css/drawer.css') }}">
<style>

</style>
<div class="no-margin-sm" style="margin-top: 30px">
			<!-- List of user events-->
	
	@if (Session::has('msg'))
        <li>{!! session('msg') !!}</li>
    @endif

	<div class="post-filters pages-groups">
					<div class="pane">
					@include('flash::message')
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
											<div class="ft-card__primary hidden-sm hidden-xs">
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
												    <div class="dropdown-wrapper">
														<div class="dropdown">
															<a href="javascript:;" class="btn btn-icon dropdown-toggle"  data-toggle="dropdown">
																<span class="icon icon-options"></span>
															</a>
															<ul class="dropdown-menu" style="left: auto; right:0;">
																@if($user_event->user_id != Auth::user()->id)
																	<li><a href="javascript:;" class="event-report">Report</a></li>
																	<li><a href="javascript:;">Save</a></li>
																	<li><a href="javascript:;">Share on facebook</a></li>
																@else
																	<li><a href="javascript:;" class="">Edit</a></li>
																	<li><a href="javascript:;" class="delete-own-event">Delete</a></li>
																	<li><a href="javascript:;">Share on facebook</a></li>
																@endif
															</ul>
														</div>
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
																			    @if(!$user_event->price)

																			    <button class="btn btn-primary join-event-btn" data-timeline = "{{ $user_event->timeline->id }}">Register</button>
																			    
																			    @else

																			    <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!! URL::route('addmoney.paypal') !!}" >
														                        {{ csrf_field() }}
														                        <input id="amount" type="hidden" class="form-control" name="amount" value="{{ $user_event->price }}">
														                        <input id="currency" type="hidden" class="form-control" name="currency" value="USD">
																			    <button type="submit" class="btn btn-primary" data-timeline = "{{ $user_event->timeline->id }}">Register</button>

																				</form>

																			    @endif
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
	</div>
</div>
