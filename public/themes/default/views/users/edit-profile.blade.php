<style>

</style>
<div class="container layout-m-t-2">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 post-filters--menu-fixed">
			<div class="list-group-navigation socialite-group">
				<header class="setting-header">
					<a class="ft-btn--icon" href="{{ url('/'.Auth::user()->username) }}">
						<img class="img-responsive" style="max-width: 24px" src="{{asset('images/left-arrow.png')}}">
					</a>
				</header>
				<div class="panel panel-default">

					<div class="panel-heading no-bg panel-settings">
						@include('flash::message')
						<h3 class="panel-title">
							{{ trans('common.edit_profile') }}
						</h3>
					</div>
					<div class="panel-body nopadding">
						<div class="socialite-form">
							<form method="POST" action="{{ url('/'.$username.'/settings/general/') }}">
								{{ csrf_field() }}
								<div class="row">
									<div class="col-md-6">

										<fieldset class="form-group required {{ $errors->has('username') ? ' has-error' : '' }}">
											{{ Form::label('username', trans('common.username')) }}
											{{ Form::text('new_username', Auth::user()->username, ['class' => 'form-control', 'placeholder' => trans('common.username')]) }}
											@if ($errors->has('username'))
												<span class="help-block">
											{{ $errors->first('username') }}
										</span>
											@endif
										</fieldset>

									</div>
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('name') ? ' has-error' : '' }}">
											{{ Form::label('name', trans('common.fullname')) }}
											{{ Form::text('name', Auth::user()->name, ['class' => 'form-control', 'placeholder' => trans('common.fullname')]) }}
											@if ($errors->has('name'))
												<span class="help-block">
											{{ $errors->first('name') }}
										</span>
											@endif
										</fieldset>
									</div>
								</div>
								<fieldset class="form-group">
									{{ Form::label('about', trans('common.about')) }}
									{{ Form::textarea('about', Auth::user()->about, ['class' => 'form-control', 'placeholder' => trans('messages.about_user_placeholder')]) }}
								</fieldset>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group required {{ $errors->has('email') ? ' has-error' : '' }}">
											{{ Form::label('email', trans('auth.email_address')) }}
											{{ Form::email('email', Auth::user()->email, ['class' => 'form-control', 'placeholder' => trans('auth.email_address')]) }}
											@if ($errors->has('email'))
												<span class="help-block">
											{{ $errors->first('email') }}
										</span>
											@endif
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<fieldset class="form-group">
											{{ Form::label('facebook_link', trans('admin.facebook_link')) }}
											<div class="input-group facebook-input-group">
												<div class="input-group-addon fb-btn"><i class="fa fa-facebook"></i></div>
												{{ Form::text('facebook_link', Auth::user()->facebook_link, array('class' => 'form-control account-form', 'placeholder' => trans('admin.facebook_link'))) }}
											</div>

										</fieldset>
									</div>
									<div class="col-md-6">
										<fieldset class="form-group">
											{{ Form::label('instagram_link', trans('admin.instagram_link')) }}
											<div class="input-group facebook-input-group instagram-input-group">
												<div class="input-group-addon instagram-btn"><i class="fa fa-instagram"></i></div>
												{{ Form::text('instagram_link', Auth::user()->instagram_link, array('class' => 'form-control', 'placeholder' => trans('admin.instagram_link'))) }}
											</div>

										</fieldset>
									</div>

								</div>

								<div class="pull-right">
									{{ Form::submit(trans('common.save_changes'), ['class' => 'btn btn-success']) }}
								</div>
								<div class="clearfix"></div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
