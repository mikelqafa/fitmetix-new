<div class="list-group list-group-navigation socialite-group">
	<header class="setting-header">
		<a class="ft-btn--icon" href="{{ url('/'.Auth::user()->username) }}">
			<img class="img-responsive" style="max-width: 24px" src="{{asset('images/left-arrow.png')}}">
		</a>
	</header>
	<a href="" class="list-group-item">
		<div class="list-text md-layout md-layout--row md-align md-align--start-center">
			<div class="avatar-img layout-m-r-1" style="width: 48px">
				<img class="img-responsive" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" title="{{ Auth::user()->name }}">
			</div>
			<div class="">My Profile</div>
		</div>
	</a>
	<div class="group-list-box">
		<a href="{{ url('/'.Auth::user()->username.'/settings/general') }}" class="list-group-item list-group-item--box {{ Request::segment(3) == 'general' ? 'active' : '' }}">
			<div class="list-text">
				{{ trans('common.general_settings') }}
			</div>
			<div class="clearfix"></div>
		</a>
		<a href="{{ url('/'.Auth::user()->username.'/settings/privacy') }}" class="list-group-item list-group-item--box {{ Request::segment(3) == 'privacy' ? 'active' : '' }}">
			<div class="list-text">
				{{ trans('common.privacy_settings') }}
			</div>
			<div class="clearfix"></div>
		</a>
		<a href="{{ url('/'.Auth::user()->username.'/settings/notifications') }}" class="list-group-item list-group-item--box {{ Request::segment(3) == 'notifications' ? 'active' : '' }}">
			<div class="list-text">
				{{ trans('common.email_notifications') }}
			</div>
			<div class="clearfix"></div>
		</a>
		@if(Auth::user()->custom_option1 == 'scout')
			<a href="{{ url('/'.Auth::user()->username.'/settings/affliates') }}" class="list-group-item list-group-item--box {{ Request::segment(3) == 'affliates' ? 'active' : '' }}">
				<div class="list-text">
					{{ trans('common.my_affiliates') }}
				</div>
				<div class="clearfix"></div>
			</a>
		@endif
		<a href="{{ url('/'.Auth::user()->username.'/settings/deactivate') }}" class="list-group-item list-group-item--box {{ Request::segment(3) == 'deactive' ? 'active' : '' }}">
			<div class="list-text">
				{{ trans('common.deactivate_account') }}
			</div>
			<div class="clearfix"></div>
		</a>
	</div>
</div>