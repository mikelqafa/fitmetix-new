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
							   <div class="ft-grid">
								   <?php $i = 0; ?>
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
										   <?php $i++ ?>
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
											<?php $i = 0; ?>
									@foreach($user_events as $user_event)
											<a href="javascript:;" class="ft-card hidden" data-index="{{$i}}">
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
											</a>
										<?php $i++ ?>
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
<!-- </div> -->
