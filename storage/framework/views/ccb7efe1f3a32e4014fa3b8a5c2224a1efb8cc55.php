<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="<?php echo csrf_token(); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <meta property="og:image" content="<?php echo url('setting/'.$setting['logo']); ?>" />
    <meta property="og:title" content="<?php echo $setting['site_title']; ?>" />
    <meta property="og:type" content="Social Network" />
    <meta name="keywords" content="<?php echo $setting['meta_keywords']; ?>">
    <meta name="description" content="<?php echo $setting['meta_description']; ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo url($setting['favicon']); ?>">
    <title>Login | <?php echo $setting['site_title']; ?></title>
    <link href="<?php echo url('themes/default/assets/css/custom.css'); ?>" rel="stylesheet">
    <link media="all" type="text/css" rel="stylesheet" href="<?php echo url('themes/default/assets/css/style.d843a54abab569eaede4ed3a69a9b943.css'); ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js does not work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        function SP_source() {
            return "<?php echo url('/'); ?>/";
        }
        var base_url = "<?php echo url('/'); ?>/";
        var theme_url = "<?php echo url('themes/default/assets/'); ?>";
    </script>
    <link href="http://fitmetix.com/themes/default/assets/css/flag-icon.css" rel="stylesheet">
    <script src="<?php echo url('themes/default/assets/js/main.7e099de1c2d4b4d95065cb1d66b3cb74.js'); ?>"></script>
    <script src="<?php echo url('js/slider.min.js'); ?>"></script>
    <style>
        .navbar.socialite { background: transparent;border: 1px solid transparent;margin-top: 40px;box-shadow: none !important; }
        .overlay { position:absolute;height:100px;background:#FFF;opacity:0.45;width:100%; }
        .bose { height: 100vh; }
        @media (max-width: 499px) { .overlay{ height:300px; }.bose { height: 100vh;} }
        @media (min-width: 500px) and (max-width: 960px) { .overlay{ height: 150px; } }
        .navbar a { color: #fff !important; }
        @media (min-width: 970px) { .navbar-brand{ margin-right: 100px; }.bose-holder img { width: 100% !important; } }
.footer-description .socialite-terms:nth-child(1) { border-top: 0px !important; }
    </style>
</head>
<body >
<div class="">
    <div class="bose">
        <nav class="navbar socialite navbar-default no-bg guest-nav">
            <div class="overlay"></div>
            <div class="container" style="position: relative !important;">
                <div class="hidden-xs navbar-header text-center">
                    <a class="navbar-brand socialite" href="<?php echo url('/'); ?>">
                        <img class="socialite-logo" src="<?php echo url('setting/'.$setting['logo']); ?>" alt="Fitmetix" title="Fitmetix" style="height:60px;">
                    </a>
                </div>
                <div class="navbar-header visible-xs text-center">
                    <a class="socialite" href="<?php echo url('/'); ?>">
                        <img class="socialite-logo" src="<?php echo url('setting/'.$setting['logo']); ?>" alt="Fitmetix" title="Fitmetix">
                    </a>
                </div>
                <div>
                    <form method="POST" class="login-form navbar-form" action="/login">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <fieldset class="form-group mail-form ">
                            <input class="form-control" id="email" placeholder="Enter E-mail or username" name="email" type="text">
                        </fieldset>
                        <fieldset class="form-group">
                            <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="">
                            <ul class="list-inline">
                                <li>
                                    <a class="hidden-xs" href="<?php echo url('register'); ?>" class="forgot-password"><i class="fa fa-user-plus"></i> New? Register here</a>
                                </li>
                                <li>
                                    <a href="<?php echo url('password/reset'); ?>" class="forgot-password"><i class="fa fa-refresh"></i> Forgot your password?</a>
                                </li>
                            </ul>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-submit">Log In</button>
                            <a href="<?php echo url('account/facebook'); ?>" class="btn btn-success"><i class="fa fa-facebook"></i> | Facebook <span class="hidden-sm">Login</span></a>
                        </div>
                        <hr class="visible-xs">
                        <a href="<?php echo url('register'); ?>" class="btn btn-success visible-xs">Create Account</a>
                    </form>
                </div>
            </div><!-- /.container-fluid -->
        </nav>
    </div>

    <div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel">
        <div class="modal-dialog modal-likes" role="document">
            <div class="modal-content">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="margin-top: -110px;">
        <div class="footer-description">
            <div class="socialite-terms text-center">
               <!-- <a href="<?php echo url('/'); ?>/login"> LOG IN </a> -->
                <a href="<?php echo url('/'); ?>/register"> REGISTER </a>
               <!-- <a href="<?php echo url('/'); ?>/page/about">about</a>-->
                <a href="<?php echo url('/'); ?>/page/privacy"> - PRIVACY </a>
               <!-- <a href="<?php echo url('/'); ?>/page/disclaimer">disclaimer</a>-->
                <a href="<?php echo url('/'); ?>/page/terms"> - TERMS</a>
                <a href="<?php echo url('/'); ?>/contact"> - HELP</a>
                <span class="dropup pull-right">
			<a href="#" class="dropdown-toggle no-padding" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
				<span class="user-name"><span class="flag-icon flag-icon-us"></span></span> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
					<ul class="dropdown-menu">
						<li class=""><a href="#" class="switch-language" data-language="en">
																					<span class="flag-icon flag-icon-us"></span>English</a></li>
						<li class=""><a href="#" class="switch-language" data-language="fr">
																					<span class="flag-icon flag-icon-fr"></span>French</a></li>
						<li class=""><a href="#" class="switch-language" data-language="es">
																					<span class="flag-icon flag-icon-es"></span>Spanish</a></li>
						<li class=""><a href="#" class="switch-language" data-language="it">
																					<span class="flag-icon flag-icon-it"></span>Italian</a></li>
						<li class=""><a href="#" class="switch-language" data-language="tr">
																					<span class="flag-icon flag-icon-tr"></span>Turkish</a></li>
						<li class=""><a href="#" class="switch-language" data-language="de">
																					<span class="flag-icon flag-icon-de"></span>German</a></li>
					</ul>
		</span>
            </div>

            <div class="socialite-terms text-center">
                Copyright &copy; 2017 <?php echo $setting['site_name']; ?>. All Rights Reserved
            </div>
        </div>
    </div>

    <script src="<?php echo url('themes/default/assets/js/app.js'); ?>"></script>
    <script>
        $(".bose").bose({
            images : [ "<?php echo url('images/4.jpg'); ?>", "<?php echo url('images/3.jpeg'); ?>", "<?php echo url('images/2.jpeg'); ?>"],
            startIndex   : 0,
            transition   : 'fade',
            autofit      : true
        });
    </script>
</body>
</html>