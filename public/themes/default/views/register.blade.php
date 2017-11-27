<style>
    .ft-login__header{
        top:0;
    }
</style>
<script type="text/javascript">
    function redirect_source() {
        return "{{ url('/register') }}/";
    }
</script>
@if(Session::has('guest_locale'))
    {{ App::setLocale(Session::get('guest_locale')) }}
@endif
<div class="ft-login">

    <div class="ft-mobile-logo ft-mobile-logo--register md-layout md-align md-align--center-center">
        <a class="ft-header__logo" href="{{ url('/') }}">
            <img src="{!! url('setting/'.Setting::get('logo')) !!}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}" />
        </a>
    </div>

    <div class="ft-login__header ft-login__header--register">
        <div class="container">
            <div class="md-layout md-layout--row md-align mobile-align-center md-align--start-start">
                <a class="ft-header__logo" href="{{ url('/') }}">
                    <img src="{!! url('setting/'.Setting::get('logo')) !!}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}" />
                </a>
                <div class="md-layout-spacer md-layout-spacer--mobile"></div>
                <form method="POST" id="signup-form" class="signup-form mobile-align-center md-layout md-layout--column ft-login__wrapper md-align md-align--center-start" action="{{ url('/register') }}">

                    <div class="md-layout layout-m-b-1 layout-m-b-1--register md-layout-spacer mobile-layout-column__register mobile-layout-column md-layout--row md-align md-align-start-center">
                        <div class="mail-form  form-group form-group__adjust">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input class="form-control" id="username" required placeholder="Username" name="username" type="text">
                        </div>
                        <div class="form-group form-group__adjust">
                            <input class="form-control" id="email" required placeholder="{{ trans('auth.email_address') }}" name="email" type="email" value="">
                        </div>
                    </div>

                    <div class="md-layout layout-m-b-1 layout-m-b-1--register md-layout-spacer mobile-layout-column__register mobile-layout-column md-layout--row md-align md-align-start-center">
                        <div class="mail-form  form-group form-group__adjust">
                            <input class="form-control" id="password" required placeholder="{{ trans('auth.password') }}" name="password" type="password">
                        </div>
                        <div class="form-group form-group__adjust">
                            <input class="form-control" id="referral_code" placeholder="Referrer code" name="affiliate" type="text" value="{{ substr(request()->getQueryString(),2) }}">
                        </div>
                    </div>


                    <div class="md-layout md-layout-spacer mobile-layout-column__register-group mobile-layout-column md-layout--row md-align md-align-start-center" style="width: 100%">
                        <div class="mail-form  form-group form-group__adjust">
                            <input class="form-control" id="datepicker1" placeholder="Birthday" name="birthday" type="date">
                        </div>
                        <div class="form-group form-group__adjust">
                            <select class="form-control" id="gender" required name="gender">
                                <option value="">Gender</option>
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                            </select>
                        </div>
                    </div>

                    <div class="layout-m-t-0 layout-m-t-0--register md-layout md-layout-flex layout-p-r-0 adjust-mobile-center" style="width: 100%">
                        <div class="md-layout-spacer"></div>
                        <div class="list-inline layout-p-l-1--sm  list-inline__login  layout-p-r-1--sm">
                            <a class="" href="{!! url('login') !!}" class="forgot-password">
                                <i class="icon icon-user"></i> {{ trans('auth.already_have_an_account') }}
                            </a>
                        </div>
                    </div>

                    <div class="md-layout layout-m-t-0 layout-p-r-0" style="width: 100%">
                        <div class="md-layout-spacer"></div>
                        <div class="form-group mobile-full-width md-layout layout-m-l-1 layout-m-l-0--sm">
                            <button type="submit" id="submit" class="btn btn-primary btn-submit">{{ trans('auth.register') }}</button>
                            <a href="{!! url('login/facebook') !!}" class="btn btn--icon btn-primary layout-m-l-0">
                                <object type="image/svg+xml" data="{{asset('fonts/facebook.svg')}}" class="splash">
                                </object>
                            </a>
                        </div>
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
                                push yourself every time you hit the ground
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
                                When nothing goes right <br/>
                                go lift
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
                                Eat well, live well...
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
                                Fit is Not a Destination
                                <br/>
                                it is a way of life
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="{!! url('register') !!}" class="btn btn-success hidden">Create Account</a>
