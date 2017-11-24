<link rel="stylesheet" type="text/css" href="{{ asset('css/drawer.css') }}">
<style type="text/css">
   .ft-card{
       min-height: 300px;
       width: 100%;
       background-color: #fff;
       border-radius: 2px;
       display: flex;
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
		flex-wrap: wrap;
		margin-bottom: 4px;
	}

   .main-content > .container > .row > .col-md-7.col-lg-6 {
   	 width: 100% !important;
   }
   .md-drawer--permanent {
	   width: auto;
	   padding-right: 0px;
	   padding-left: 0;
	   padding-top: 64px;
	   z-index: 1;
   }
   @media screen and (min-width: 960px) {
	   .md-drawer {
		   left: auto;
		   right:0;
		   width: 270px;
		   -webkit-transform: translateX(0px);
		   transform: translateX(0px);
	   }
	   .md-drawer--permanent {
		   -webkit-transform: translateX(0px);
		   transform: translateX(0px);
	   }
	   .md-drawer--permanent .md-drawer__surface {
		   -webkit-transform:translateX(290px);
		   transform:translateX(290px)
	   }
	   .md-drawer--animating .md-drawer__surface {
		   -webkit-transform:translateX(290px);
		   transform:translateX(290px)
	   }

	   body.has-permanent-drawer.is-drawer-open {
		   padding-right: 280px;
		   padding-left: 0 !important;
	   }
   }
	.

</style>


<div class="main-content">
			<!-- List of user events-->
				<div class="post-filters pages-groups">
					
					<div class="pane">
					@include('flash::message')
						<div class="panel-heading no-bg panel-settings">						
							<div class="side-right">
								<a href="{{ url(Auth::user()->username.'/create-event') }}" class="btn btn-success">{{ trans('common.create_event') }}</a>
							</div>
							<h3 class="panel-title">
								{{ trans('messages.events-manage') }}
							</h3>
						</div>

						<div class="pan">
							@if(count($user_events))
							   <div class="row">
							   @foreach($user_events as $user_event)
										<div class="col-md-4 col-sm-6">
											<div class="ft-card">
												<div class="ft-card__img-wrapper" data-toggle="drawer" data-target="#drawer-1">
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
														<div class="icon"></div>
														<div class="card-desc">
															{{ $user_event->location }}
														</div>
													</div>
													<div class="ft-card__list">
														<div class="icon"></div>
														<div class="card-desc">
															{{ $user_event->gender }}
														</div>
													</div>
													<div class="ft-card__list">
														<div class="icon"></div>
														<div class="card-desc">
															{{ $user_event->start_date }} to {{ $user_event->end_date }}
														</div>
													</div>
													<div class="ft-card__list">
														<div class="icon"></div>
														<div class="card-desc">
															{{ $user_event->price }}
														</div>
													</div>
													</div>
												</div>
											</div>
										</div>
										@endforeach
									</div>

									<aside class="md-drawer" id="drawer-1" style="padding-top: 0" data-permanent="true" data-show="true" data-toggle="drawer">
									    <div class="md-drawer__shadow"></div>
									    <div class="md-drawer__surface">
											<div style="">
												<a class="btn" href="javascript:;" onclick="$('#drawer-1 .md-drawer__shadow').click()">
													&times;
												</a>
											</div>
									@foreach($user_events as $user_event)
										<div class="ft-card">
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
															<div class="icon"></div>
															<div class="card-desc">
																{{ $user_event->location }}
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon"></div>
															<div class="card-desc">
																{{ $user_event->gender }}
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon"></div>
															<div class="card-desc">
																{{ $user_event->start_date }} to {{ $user_event->end_date }}
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon"></div>
															<div class="card-desc">
																{{ $user_event->price }}
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon"></div>
															<div class="card-desc">
																{{ $user_event->users()->count() }}
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon"></div>
															<div class="card-desc">
																{{ $user_event->frequency }}
															</div>
														</div>
														<div class="ft-card__list">
															<div class="icon"></div>
															<div class="card-desc">
																{{ $user_event->timeline->about }}
															</div>
														</div>
													</div>
												</div>
											</div>
											</div>
									@endforeach
									</aside>

							{{-- <ul class="list-group page-likes">
								@foreach($user_events as $user_event)
								<li class="list-group-item deleteevent">
									<div class="connect-list">
										<div class="connect-link side-left">

											<a href="{{ url($user_event->timeline->username) }}">
												<img src=" @if(Auth::user()->timeline->avatar) {{ url('user/avatar/'.Auth::user()->timeline->avatar->source) }} @else {{ url('group/avatar/default-group-avatar.png') }} @endif" alt="{{ $user_event->timeline->name }}" title="{{ $user_event->timeline->name }}">{{ $user_event->timeline->name }}
											</a>
											<span class="label label-default">{{ $user_event->type }}</span>
										</div>
										<div class="side-right">
											<a href="" class="side-right delete-event delete_event" data-eventdelete-id="{{ $user_event->id }}"><i class="fa fa-close text-danger"></i></a>
										</div>
										<div class="clearfix"></div>
									</div>
								</li>
								@endforeach
							</ul> --}}
							@else
							<div class="alert alert-warning">
								{{ trans('messages.no_events') }}
							</div>
							@endif
						</div>
					</div>
				</div><!-- /panel -->
<!-- </div> -->

