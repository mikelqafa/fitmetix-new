<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf_token" content="{!! csrf_token() !!}"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <meta name="keywords" content="{{ Setting::get('meta_keywords') }}">
        <meta name="description" content="{{ Setting::get('meta_description') }}">
        <link rel="icon" type="image/x-icon" href="{!! url('setting/'.Setting::get('favicon')) !!}">

        <title>{{ Theme::get('title') }}</title>

        <link href="{{ Theme::asset()->url('css/flag-icon.css') }}" rel="stylesheet">

        <link href="{{ asset('fitmetixfont/font.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ Theme::asset()->url('css/custom.css') }}" rel="stylesheet">
        {{--{!! Theme::asset()->styles() !!}--}}
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link href="{{ asset('css/prakash.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
        function SP_source() {
          return "{{ url('/') }}/";
        }

        var base_url = "{{ url('/') }}/";
        var theme_url = "{!! Theme::asset()->url('') !!}";
        var current_username = "{{ Auth::user()->username }}";

        </script>
        {!! Theme::asset()->scripts() !!}
        @if(Setting::get('google_analytics') != NULL)
            {!! Setting::get('google_analytics') !!}
        @endif
    </head>
    <body @if(Setting::get('enable_rtl') == 'on') class="direction-rtl" @endif>
        {!! Theme::partial('header') !!}

        <div class="page-wrapper">
            <div class="container">
                <div class="row">

                    <div class="col-md-3">
                        {!! Theme::partial('admin-leftbar') !!}
                    </div>

                    <div class="col-md-9">
                        {!! Theme::content() !!}
                    </div>

                </div><!-- /row -->
            </div>
        </div><!-- /amin-content -->
        
        {!! Theme::partial('footer') !!}
        <script>
            var pusherConfig = {
                token: "{{ csrf_token() }}",
                PUSHER_KEY: "{{ config('broadcasting.connections.pusher.key') }}"
            };
       </script>

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $('#scout_form').on('submit', function (ev) {
              alert("working")
              ev.preventDefault();
              var post_url = 'register';
              var formData = {
                'email' : $('input[name=email]').val(),
                'username' : $('input[name=username]').val(),
                'birthday' : $('input[name=birthday]').val(),
                'gender' : $('#gender').val(),
                'password' : $('input[name=password]').val(),
                '_token': $('input[name=_token]').val()
              };
              var submitBtn = $('#submit');
              submitBtn.prop('disabled',true);
              $.ajax({
                url : post_url,
                type: "post",
                data: formData
              }).done(function(e){ 
                
                if(e.status == 200) {
                  location.reload();
                } else {
                  console.log(e.err_result)
                  var c = 0;
                  $.each(e.err_result, function( index, value ) {
                    var config = {
                      messageText:  value,
                      alignCenter: false,
                      autoClose: true
                    }
                    setTimeout(function(){
                      window.materialSnackBar(config)
                    }, 2000*c)
                    c++;
                  });
                }
              }).always(function(e){
                submitBtn.prop('disabled',false)
              }).fail(function(e){
                var config = {
                  messageText:  'Authentication failed. Please try again!',
                  alignCenter: false,
                  autoClose: true
                }
                window.materialSnackBar(config)
              })
            })
        </script>

        {!! Theme::asset()->container('footer')->scripts() !!}
    </body>
</html>
