<style>
    /*.ft-login__header{
        top:0;
    }*/
</style>
<div class="ft-login">

    <div class="ft-mobile-logo md-layout md-align md-align--center-center">
        <a class="ft-header__logo" href="{{ url('/') }}">
            <img src="{!! url('setting/'.Setting::get('logo')) !!}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}" />
        </a>
    </div>

    <div class="ft-login__header">
        <div class="container">
            <div class="md-layout md-layout--row md-align mobile-align-center md-align--start-center">
                <a class="ft-header__logo" href="{{ url('/') }}">
                    <img src="{!! url('setting/'.Setting::get('logo')) !!}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}" />
                </a>
                <div class="md-layout-spacer md-layout-spacer--mobile"></div>
                <form method="POST" action="/login" class="mobile-align-center md-layout-spacer md-layout md-layout--column ft-login__wrapper md-align md-align--center-start">
                    <div class="md-layout md-layout-spacer mobile-layout-column md-layout--row md-align md-align-start-center">
                        <div class="mail-form  form-group form-group__adjust">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input class="form-control" id="email" placeholder="Email or username" name="email" type="text">
                        </div>
                        <div class="form-group form-group__adjust">
                            <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="">
                        </div>
                        <div class="form-group mobile-full-width md-layout layout-m-l-1 layout-m-l-0--sm">
                            <button type="submit" class="btn btn-primary btn-submit">Log In</button>
                            <a href="{!! url('account/facebook') !!}" class="btn btn--icon btn-primary layout-m-l-0">
                                <object type="image/svg+xml" data="{{asset('fonts/facebook.svg')}}" class="splash">
                                </object>
                            </a>
                        </div>
                    </div>
                    <div class="form-group__adjust layout-m-t-0">
                        <ul class="list-inline layout-p-l-1--sm layout-p-r-1--sm">
                            <li>
                                <a class="" href="{!! url('register') !!}" class="forgot-password">
                                    <i class="icon icon-user"></i> New user?</a>
                            </li>
                            <li>
                                <a href="{!! url('password/reset') !!}" class="layout-m-l-0">
                                    Forgot password?
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
                <div class="ft-carousel__cover" style="background-image: url({{asset('images/2.jpeg')}})">
                    <div class="container pos-rel">
                        <div class="appearContainer ft-carousel__text">
                            <span class="font-curly">
                                When nothing goes right <br/>
                                go lift
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ft-carousel__cover" style="background-image: url({{asset('images/3.jpeg')}})">
                    <div class="container pos-rel">
                        <div class="appearContainer ft-carousel__text">
                            <span class="font-curly">
                                WORK OUT. EAT WELL. BE PATIENT
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ft-carousel__cover" style="background-image: url({{asset('images/4.jpeg')}})">
                    <div class="container pos-rel">
                        <div class="appearContainer ft-carousel__text">
                            <span class="font-curly">
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
