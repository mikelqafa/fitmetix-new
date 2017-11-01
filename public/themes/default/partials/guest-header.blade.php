<nav class="navbar socialite navbar-default no-bg guest-nav">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-4" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand socialite" href="{{ url('/') }}">
				<img class="socialite-logo" src="{!! url('setting/'.Setting::get('logo')) !!}" alt="{{ Setting::get('site_name') }}" title="{{ Setting::get('site_name') }}">
			</a>
		</div>
		@if (Auth::guest())
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">			
			<form method="POST" class="login-form navbar-form navbar-right" action="{{ url('/login') }}">
				{{ csrf_field() }}
				<fieldset class="form-group mail-form {{ $errors->has('email') ? ' has-error' : '' }}">
					{{ Form::text('email', NULL, ['class' => 'form-control', 'id' => 'email', 'placeholder'=> trans('auth.enter_email_or_username')]) }}
				</fieldset>
				<fieldset class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
					{{ Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder'=> trans('auth.password')]) }}
					<ul class="list-inline">
						<li>
							<a href="{{ url('/register') }}" class="forgot-password"><i class="fa fa-user-plus"></i> Create new Account</a>
						</li>
						<li>
							<a href="{{ url('/password/reset') }}" class="forgot-password"><i class="fa fa-refresh"></i> Forgot your password</a>
						</li>
					</ul>
				</fieldset>
				{{ Form::button( trans('common.signin') , ['type' => 'submit','class' => 'btn btn-success btn-submit']) }}
			</form>
		</div>
		@endif
		
	</div><!-- /.container-fluid -->
</nav>	
