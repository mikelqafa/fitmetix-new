<style>
    .ft-login__header{
        top:0;
    }
    .pos-rel {
        position: relative;
    }
    #datepicker-hidden{
        position: absolute;
        top:0;
        left: 0;
        width: 100%;
        height: 100%;
        -webkit-appearance: none;
        opacity: 0;
        z-index: 2;
    }
    #datepicker1{
        background-color: #FFF;
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
            <img src="{!! url('images/logo.png') !!}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}" />
        </a>
    </div>

    <div class="ft-login__header ft-login__header--register">
        <div class="container">
            <div class="md-layout md-layout--row md-align mobile-align-center md-align--start-start">
                <a class="ft-header__logo" href="{{ url('/') }}">
                    <img src="{!! url('images/logo.png') !!}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}" />
                </a>
                <div class="md-layout-spacer md-layout-spacer--mobile"></div>
                <form method="POST" id="signup-form" class="signup-form mobile-align-center md-layout md-layout--column ft-login__wrapper md-align md-align--center-start" action="{{ url('/register') }}">

                    <div class="md-layout layout-m-b-1 layout-m-b-1--register md-layout-spacer mobile-layout-column__register mobile-layout-column md-layout--row md-align md-align-start-center">
                        <div class="mail-form  form-group form-group__adjust pos-rel">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <span class="hidden-lg hidden-md" style="position: absolute; top: 0; left: 0;;"></span>
                            <input class="form-control" id="username" required placeholder="{{ trans('common.username') }}" name="username" type="text">
                        </div>
                        <div class="form-group form-group__adjust form-group__adjust--no-margin">
                            <input class="form-control" id="email" required placeholder="{{ trans('auth.email_address') }}" name="email" type="email" value="@if(isset($data['email'])) {{ $data['email'] }} @endif">
                        </div>
                    </div>

                    <div class="md-layout layout-m-b-1 layout-m-b-1--register md-layout-spacer mobile-layout-column__register mobile-layout-column md-layout--row md-align md-align-start-center" style="width: 100%">
                        <div class="mail-form  form-group form-group__adjust pos-rel">
                            <input class="form-control" id="password" required placeholder="{{ trans('auth.password') }}" name="password" type="password">
                        </div>
                        <div class="form-group form-group__adjust form-group__adjust--no-margin">
                            {{-- <input class="form-control" id="referral_code" placeholder="Referrer code" name="affiliate" type="text" value=""> --}}

                            <select class="form-control" id="gender" required name="gender">
                                <option value="">{{ trans('common.gender') }}</option>
                                <option value="female" @if(isset($data['gender']) && $data['gender'] == 'female') selected="selected" @endif>{{ trans('common.female') }}</option>
                                <option value="male" @if(isset($data['gender']) && $data['gender'] == 'male') selected="selected" @endif>{{ trans('common.male') }}</option>
                            </select>
                        </div>
                    </div>

                     <div class="md-layout layout-m-b-1 layout-m-b-1--register md-layout-spacer mobile-layout-column__register mobile-layout-column md-layout--row md-align md-align-start-center" style="width: 100%;">
                        <div class="mail-form  form-group form-group__adjust">
                            <a class="" href="{!! url('login') !!}" class="forgot-password">
                                <i class="icon icon-user"></i> {{ trans('auth.already_have_an_account') }}
                            </a>
                        </div>
                        <div class="form-group form-group__adjust">
                            <div class="md-layout layout-m-t-0 layout-p-r-0" style="width: 100%">
                                <div class="md-layout-spacer"></div>
                                <div class="form-group mobile-full-width md-layout layout-m-l-1 layout-m-l-0--sm">
                                    <button type="submit" id="submit" class="btn btn-primary btn-submit">{{ trans('auth.register') }}</button>
                                    <a href="{!! url('social/login/redirect/facebook') !!}" class="btn btn--icon btn-primary layout-m-l-0">
                                    <object type="image/svg+xml" data="{{asset('fonts/facebook.svg')}}" class="splash">
                                    </object>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- <div class="md-layout md-layout-spacer mobile-layout-column__register-group mobile-layout-column md-layout--row md-align md-align-start-center"> --}}
                        {{-- <div class="mail-form  form-group form-group__adjust pos-rel">
                            <input class="form-control" id="datepicker1" placeholder="Birthday" name="birthday" type="text" required readonly="true">
                        </div> --}}
                        {{-- <div class="mail-form  form-group form-group__adjust">
                            <select class="form-control" id="gender" required name="gender">
                                <option value="">Gender</option>
                                <option value="female" @if(isset($data['gender']) && $data['gender'] == 'female') selected="selected" @endif>Female</option>
                                <option value="male" @if(isset($data['gender']) && $data['gender'] == 'male') selected="selected" @endif>Male</option>
                            </select>
                        </div>
                    </div> --}}
                    @if(isset($data['social']) && $data['social'])
                        <input type="hidden" value="{{ $data['avatar'] }}" name="avatar">
                        <input type="hidden" value="1" name="social">
                    @endif
                    {{-- <div class="layout-m-t-0 layout-m-t-0--register md-layout md-layout-flex layout-p-r-0 adjust-mobile-center" style="width: 100%">
                        <div class="md-layout-spacer"></div>
                        <div class="list-inline layout-p-l-1--sm  list-inline__login  layout-p-r-1--sm">
                            <a class="" href="{!! url('login') !!}" class="forgot-password">
                                <i class="icon icon-user"></i> {{ trans('auth.already_have_an_account') }}
                            </a>
                        </div>
                    </div> --}}

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
<script>
    function  detectmob(){
        if (
                navigator.userAgent.match(/Android/i) ||
                navigator.userAgent.match(/webOS/i) ||
                navigator.userAgent.match(/iPhone/i) ||
                navigator.userAgent.match(/iPad/i) ||
                navigator.userAgent.match(/iPod/i) ||
                navigator.userAgent.match(/BlackBerry/i) ||
                navigator.userAgent.match(/Windows Phone/i)
        ) {
            return true
        } else {
            return false
        }
    }
    /*if(detectmob()) {
        document.getElementById('datepicker1').setAttribute('type', 'date')
    } else {
        document.getElementById('datepicker1').setAttribute('type', 'text')
    }*/
</script>