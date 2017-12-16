<link rel="stylesheet" type="text/css" href="{{ asset('css/drawer.css') }}">
<style>
	@media screen and (min-width: 960px) {
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
</style>
<div class="no-margin-sm" style="margin-top: 30px">
	<div id="app-timeline">
		<app-event-list>
			<div class="post-filters pages-groups">
				<div class="pane">
					<div class="pan">
						<div class="ft-grid">
							<div class="ft-grid__item lg-loading-skeleton">
								<div class="ft_card">
									<div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
									</div>
									<div class="ft-card__primary hidden-sm hidden-xs">
										<div class="ft-card__title lg-loadable">
											<h5 class="ft-event-card__title">&nbsp;</h5>
										</div>
										<div class="ft-card__list-wrapper">
											<div class="ft-card__list">
												<div class="icon lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
											<div class="ft-card__list">
												<div class="icon icon-participant lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
											<div class="ft-card__list">
												<div class="icon lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
											<div class="ft-card__list">
												<div class="icon lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="ft-grid__item lg-loading-skeleton">
								<div class="ft_card">
									<div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
									</div>
									<div class="ft-card__primary hidden-sm hidden-xs">
										<div class="ft-card__title lg-loadable">
											<h5 class="ft-event-card__title">&nbsp;</h5>
										</div>
										<div class="ft-card__list-wrapper">
											<div class="ft-card__list">
												<div class="icon lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
											<div class="ft-card__list">
												<div class="icon icon-participant lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
											<div class="ft-card__list">
												<div class="icon lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
											<div class="ft-card__list">
												<div class="icon lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="ft-grid__item lg-loading-skeleton">
								<div class="ft_card">
									<div class="lg-loadable ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" >
									</div>
									<div class="ft-card__primary hidden-sm hidden-xs">
										<div class="ft-card__title lg-loadable">
											<h5 class="ft-event-card__title">&nbsp;</h5>
										</div>
										<div class="ft-card__list-wrapper">
											<div class="ft-card__list">
												<div class="icon lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
											<div class="ft-card__list">
												<div class="icon icon-participant lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
											<div class="ft-card__list">
												<div class="icon lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
											<div class="ft-card__list">
												<div class="icon lg-loadable"></div>
												<div class="card-desc lg-loadable--text layout-m-b-0 lg-loadable">
													&nbsp;
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</app-event-list>
	</div>
	<div class="hidden post-filters pages-groups">
		<div class="pane">
			<div class="pan">
							@if(count($user_events))
							   <div class="ft-grid">
								   @php $i = 0; @endphp
							   @foreach($user_events as $user_event)
									<div class="ft-grid__item">
										<div class="ft-card">

											@if($user_event->timeline->cover)
												<a href="javascript:;" class="ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" style="background-image: url('{{ env('STORAGE_URL').'uploads/events/covers/'.$user_event->timeline->cover['source'] }}')" data-index="{{$i}}">
													<img class="ft-card__img" src="{{ env('STORAGE_URL').'uploads/events/covers/'.$user_event->timeline->cover['source'] }}" alt="Event Cover">
												</a>
											@else
												<a href="javascript:;" class="ft-card__img-wrapper ft-card_drawer-trigger ft-card__img-wrapper--background" style="background-image: url('{{ env('STORAGE_URL').'uploads/events/covers/default-cover-event.png' }}')" data-index="{{$i}}">
													<img class="ft-card__img" src="{{ env('STORAGE_URL').'uploads/events/covers/default-cover-event.png' }}" alt="Event Cover">
												</a>
											@endif
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
															@if(($user_event->price == NULL) || ($user_event->price < 1))
																{{ "FREE" }}
															@else
															    {{ "$ $user_event->price" }}
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

							@else
							<div class="alert alert-warning">
								{{ trans('messages.no_events') }}
							</div>
							@endif
						</div>
		</div>
	</div>
</div>
