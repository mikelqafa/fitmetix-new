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
			@endif
			@foreach(App\StaticPage::active() as $staticpage)
				<a href="{{ url('page/'.$staticpage->slug) }}">{{ $staticpage->title }}</a>
			@endforeach
		</div>
		<div class="ft-copyright text-center">
			{{ trans('common.copyright') }} &copy; {{ date('Y') }} {{ Setting::get('site_name') }}. {{ trans('common.all_rights_reserved') }}
		</div>
		<div class="multi-lang">
			<a href="#" class="multi-lang__item cover-bg"  style="background-image: url({{ asset('images/sp.png') }})"></a>
			<a href="#" class="multi-lang__item cover-bg"  style="background-image: url({{ asset('images/en.png') }})"></a>
			<a href="#" class="multi-lang__option cover-bg" style="background-image: url({{ asset('images/en.png') }})"></a>
		</div>
	</div>
</div>

{{--
{!! Theme::asset()->container('footer')->usePath()->add('app', 'js/app.js') !!}--}}
