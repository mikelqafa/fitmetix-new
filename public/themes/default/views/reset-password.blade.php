<div class="container">
    <div class="row tpadding-20">
        <div class="col-md-4 col-md-offset-4">
            <h2 class="register-heading">Reset Passowrd</h2>
            <div class="panel panel-default">
                <div class="panel-body nopadding">
                    <div class="login-bottom">
                        <ul class="signup-errors text-danger list-unstyled"></ul>
                        <form method="POST" class="signup-form" action="{{ url('/reset-password') }}">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset class="form-group required {{ $errors->has('email') ? ' has-error' : '' }}">
                                        {{ Form::label('email', trans('auth.email_address')) }}
                                        {{ Form::text('email', NULL, ['class' => 'form-control', 'id' => 'email', 'placeholder'=> trans('Email')]) }}
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                            {{ $errors->first('email') }}
                                            </span>
                                        @endif
                                    </fieldset>
                                </div>
                            </div>
                            {{ Form::button(trans('Rest Password'), ['type' => 'submit','class' => 'btn btn-success btn-submit']) }}
                        </form>
                    </div>
                </div>
            </div><!-- /panel -->
        </div>
    </div><!-- /row -->
</div><!-- /container -->
{!! Theme::asset()->container('footer')->usePath()->add('app', 'js/app.js') !!}