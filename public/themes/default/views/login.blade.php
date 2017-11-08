<style>
    .navbar-default {
        display: none;
    }
    .navbar--login{
        display: block;
        position: fixed;
        top:0;
        left: 0;
    }
    body {
        position: relative;
    }
    .navbar.socialite { background: transparent;border: 1px solid transparent;margin-top: 40px;box-shadow: none !important; }
    .overlay { position:absolute;height:100px;background:#FFF;opacity:0.45;width:100%; }
    .bose { height: 100vh; }
    @media (max-width: 499px) { .overlay{ height:600px; }.bose { height: 100vh;} }
    @media (min-width: 500px) and (max-width: 960px) { .overlay{ height: 950px; } }
    .navbar a { color: #fff !important; }
    @media (min-width: 970px) { .navbar-brand{ margin-right: 500px; }.bose-holder img { width: 100% !important; } }
    .footer-description .socialite-terms:nth-child(1) { border-top: 0px !important; }
</style>
<div class="bose">
    <div class="pos-rel">
        <nav class="navbar navbar--login  socialite navbar-default no-bg guest-nav">
            <div class="overlay"></div>
            <div class="container" style="position: relative !important;">
                <div class="hidden-xs navbar-header text-center">
                    <a class="navbar-brand socialite" href="{!! url('/') !!}">
                        <img class="socialite-logo" src="{!! url('setting/'.Setting::get('logo')) !!}"  alt="Fitmetix" title="Fitmetix" style="height:60px;">
                    </a>
                </div>
                <div class="navbar-header visible-xs text-center">
                    <a class="socialite" href="{!! url('/') !!}">
                        <img class="socialite-logo" src="{!! url('setting/'.Setting::get('logo')) !!}" alt="Fitmetix" title="Fitmetix">
                    </a>
                </div>
                <div>
                    <form method="POST" class="login-form navbar-form" action="/login">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <fieldset class="form-group mail-form ">
                            <input class="form-control" id="email" placeholder="Enter E-mail or username" name="email" type="text">
                        </fieldset>
                        <fieldset class="form-group">
                            <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="">
                            <ul class="list-inline">
                                <li>
                                    <a class="hidden-xs" href="{!! url('register') !!}" class="forgot-password"><i class="fa fa-user-plus"></i> New? Register here</a>
                                </li>
                                <li>
                                    <a href="{!! url('password/reset') !!}" class="forgot-password"><i class="fa fa-refresh"></i> Forgot your password?</a>
                                </li>
                            </ul>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-submit">Log In</button>
                            <a href="{!! url('account/facebook') !!}" class="btn btn-success"><i class="fa fa-facebook"></i> | Facebook <span class="hidden-sm">Login</span></a>
                        </div>
                        <hr class="visible-xs">
                        <a href="{!! url('register') !!}" class="btn btn-success visible-xs">Create Account</a>
                    </form>
                </div>
            </div><!-- /.container-fluid -->
        </nav>
        <div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel">
            <div class="modal-dialog modal-likes" role="document">
                <div class="modal-content">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{!! asset('js/slider.min.js') !!}"></script>
<script>
    $(".bose").bose({
        images : [ "{!! url('images/4.jpg') !!}", "{!! url('images/3.jpeg') !!}", "{!! url('images/2.jpeg') !!}"],
        startIndex   : 0,
        transition   : 'fade',
        autofit      : true
    });
</script>