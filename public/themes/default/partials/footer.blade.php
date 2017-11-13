<!-- Modal starts here-->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel">
    <div class="modal-dialog modal-likes" role="document">
        <div class="modal-content">
        	<i class="fa fa-spinner fa-spin"></i>
        </div>
    </div>
</div>
<div class="footer-description">
	<div class="footer__wrapper">
		<div class="socialite-terms text-center">
			@if(Auth::check())
				{{--<a href="{{ url(Auth::user()->username.'/create-page') }}">{{ trans('common.create_page') }}</a> -
                <a href="{{ url(Auth::user()->username.'/create-group') }}">{{ trans('common.create_group') }}</a>--}}
			@else
				<a href="{{ url('login') }}">{{ trans('auth.login') }}</a>
				<a href="{{ url('register') }}">{{ trans('auth.register') }}</a>
				<a href="{{ url('terms') }}">{{ trans('Terms') }}</a>
			{{--TODO transalate--}}
				<a href="{{ url('help') }}">{{ trans('Help') }}</a>
			@endif
		</div>
		<div class="ft-copyright text-center">
			{{ trans('common.copyright') }} &copy; {{ date('Y') }} {{ Setting::get('site_name') }}. {{ trans('common.all_rights_reserved') }}
		</div>
		<div class="multi-lang">
			{{csrf_field()}}
			<a href="javascript:;" class="multi-lang__item cover-bg switch-language" data-language="en"   style="background-image: url({{ asset('images/sp.png') }})"></a>
			<a href="javascript:;" class="multi-lang__item cover-bg switch-language" data-language="en"  style="background-image: url({{ asset('images/en.png') }})"></a>
			<a href="javascript:;" class="multi-lang__option cover-bg switch-language" data-language="es" style="background-image: url({{ asset('images/en.png') }})"></a>
		</div>
	</div>
</div>

{{--
{!! Theme::asset()->container('footer')->usePath()->add('app', 'js/app.js') !!}--}}
