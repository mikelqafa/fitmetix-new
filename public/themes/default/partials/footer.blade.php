<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel">
    <div class="modal-dialog modal-likes" role="document">
        <div class="modal-content">
        	<i class="fa fa-spinner fa-spin"></i>
        </div>
    </div>
</div>
<div class="footer-description">
	<div class="md-layout-spacer--mobile"></div>
	<div class="footer__wrapper">
		<div class="md-layout-spacer--mobile"></div>
		<div class="socialite-terms text-center">
			@if(Auth::check())
				{{-- <a href="{{ url(Auth::user()->username.'/create-page') }}">{{ trans('common.create_page') }}</a> -
                <a href="{{ url(Auth::user()->username.'/create-group') }}">{{ trans('common.create_group') }}</a> --}}
			@else
				<a href="{{ url('login') }}">{{ trans('auth.login') }}</a>
				<a href="{{ url('register') }}">{{ trans('auth.register') }}</a>
				<a href="{{ url('terms') }}">{{ trans('Terms') }}</a>
				<a href="{{ url('help') }}">{{ trans('Help') }}</a>
			@endif
		</div>
			 <div class="ft-copyright text-center">
			{{ trans('common.copyright') }} &copy; {{ date('Y') }} {{ Setting::get('site_name') }}. {{ trans('common.all_rights_reserved') }}
			</div>
	</div>

	@if(!Auth::check())
		<div class="multi-lang hidden-sm hidden-xs">
			<a href="javascript:;" title="English" data-language="en" class="switch-language multi-lang__item" style="background-image: url({{asset('images/en.png')}})">

			</a>
			<a href="javascript:;" title="German" data-language="de" class="switch-language multi-lang__item" style="background-image: url({{asset('images/sp.png')}})">
			</a>
		</div>
		<div class="md-layout-spacer--mobile"></div>
		<div class="multi-lang multi-lang--mobile">
			<a href="javascript:;" title="English" data-language="en" class="switch-language multi-lang__option" style="background-image: url({{asset('images/en.png')}})">
			</a>
			<a href="javascript:;" title="German" data-language="de" class="switch-language multi-lang__option" style="background-image: url({{asset('images/sp.png')}})">
			</a>
		</div>
	@endif

</div>
