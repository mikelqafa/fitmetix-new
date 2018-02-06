<style>
    .ft-login__header{
        top:0;
    }
</style>
@if(Session::has('guest_locale'))
    {{ App::setLocale(Session::get('guest_locale')) }}
@endif
<div class="ft-login">

    <div class="ft-mobile-logo md-layout md-align md-align--center-center">
        <a class="ft-header__logo" href="{{ url('/') }}">
            <img src="{!! url('images/logo.png') !!}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}" />
        </a>
    </div>

    <div class="ft-login__header">
        <div class="container">
            <div class="md-layout md-layout--row md-align mobile-align-center md-align--start-center">
                <a class="ft-header__logo" href="{{ url('/') }}">
                    <img src="{!! url('images/logo.png') !!}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}" />
                </a>
                <div class="md-layout-spacer md-layout-spacer--mobile"></div>
                <form method="POST" action="{{ url('/login') }}" id="login-form" class="login-form mobile-align-center md-layout md-layout--column ft-login__wrapper md-align md-align--center-start">
                    <div class="md-layout md-layout-spacer mobile-layout-column md-layout--row md-align md-align-start-center">
                        <div class="mail-form  form-group form-group__adjust">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input class="form-control" id="email" required placeholder="{{ trans('auth.enter_email_or_username') }}" name="email" type="text">
                        </div>
                        <div class="form-group form-group__adjust">
                            <input class="form-control" id="password" required placeholder="{{ trans('auth.password') }}" name="password" type="password" value="">
                        </div>
                        <div class="form-group mobile-full-width md-layout layout-m-l-1 layout-m-l-0--sm">
                            <button type="submit" id="submit" class="btn btn-primary btn-submit">{{ trans('auth.login') }}</button>
                            <a href="{!! url('social/login/redirect/facebook') !!}" class="btn btn--icon btn-primary layout-m-l-0">
                                <object type="image/svg+xml" data="{{asset('fonts/facebook.svg')}}" class="splash">
                                </object>
                            </a>
                        </div>
                    </div>
                    <div class="form-group__adjust layout-m-t-0 text-center--mobile">
                        <ul class="list-inline  layout-p-l-1--sm  list-inline__login  layout-p-r-1--sm mobile-layout-column">
                            <li>
                                <a href="{!! url('register') !!}" class="forgot-password">
                                    <i class="icon icon-user"></i> {{ trans('auth.register') }}</a>
                            </li>
                            <li>
                                <a href="{!! url('password/reset') !!}" class="layout-m-l-0 text-capitalize">
                                    <img src="{{asset('images/icons/ic_lock_white_24px.svg')}}" class="icon-img">  {{ trans('auth.forgot_password') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="ft-login__slider">
        <div id="slider-owl"  class="owl-carousel ft-carousel owl-theme">
            <div class="item">
                <div class="ft-carousel__cover" style="background-image: url({{asset('images/back1-min.jpg')}})">
                    <div class="container pos-rel">
                        <div class="appearContainer ft-carousel__text ft-carousel__text--register">
                            <span class="font-curl">
                                {!! trans('slideshow.READY_FOR') !!}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ft-carousel__cover" style="background-image: url({{asset('images/back2-min.jpg')}})">
                    <div class="container pos-rel">
                        <div class="appearContainer ft-carousel__text ft-carousel__text--register">
                            <span class="font-curl">
                                {!! trans('slideshow.IGNITE') !!}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ft-carousel__cover" style="background-image: url({{asset('images/back3-min.jpg')}})">
                    <div class="container pos-rel">
                        <div class="appearContainer ft-carousel__text ft-carousel__text--register">
                            <span class="font-curl">
                                {!! trans('slideshow.RECIPE') !!}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ft-carousel__cover" style="background-image: url({{asset('images/back4-min.jpg')}})">
                    <div class="container pos-rel">
                        <div class="appearContainer ft-carousel__text ft-carousel__text--register">
                            <span class="font-curl">
                                {!! trans('slideshow.JOURNEY') !!}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="{!! url('register') !!}" class="btn btn-success hidden">Create Account</a>
