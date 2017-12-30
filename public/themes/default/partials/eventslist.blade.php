<style>
	@media  screen and (min-width: 960px) {
		.main-content > .container > .row > .col-md-7.col-lg-6{
			width: 100%;
		}
		.main-content .hide-on-other{
			display: none !important;
		}
		.md-drawer--permanent {
			width: auto;
			padding-right: 0px;
			padding-left: 0;
			padding-top: 60px;
			z-index: -1;
		}
		.md-drawer--permanent.md-drawer--visible {
			z-index: 1 !important;
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
		<app-post-option></app-post-option>
		<app-comment-option></app-comment-option>
		<app-event-list>
			<div class="post-filters pages-groups post-filters--auto-width">
				<div class="pane">
					<div class="pan">
						<div class="ft-grid ft-grid--12-xs">
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
			</div>
		</app-event-list>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_vuWi_hzMDDeenNYwaNAj0PHzzS2GAx8&libraries=places&callback=init"
		async defer></script>
<script>
	var mapReady = false;
	var initMap = false
	function init() {
		mapReady = initMap = true
		if(document.getElementById('filter-location-input') !== undefined) {
			initMapDesk()
			initMapMob()
		}
	}
	function initMapDesk() {
		var input = document.getElementById('filter-location-input');
		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.addListener('place_changed', function(e) {
			window.timeLine.$options.components["app-event-list"].methods.acMapEventDesk('filter-location-input')
		})
	}
	function initMapMob() {
		var input = document.getElementById('filter-location-input-mob');
		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.addListener('place_changed', function(e) {
			window.timeLine.$options.components["app-event-list"].methods.acMapEventMobo('filter-location-input-mob')
		})
	}
</script>
