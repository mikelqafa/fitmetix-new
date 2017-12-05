<div class="panel panel-default">
@include('flash::message')
	<div class="panel-heading no-bg panel-settings">
		<h3 class="panel-title">
			{{ "Create Scout Account" }}
		</h3>
	</div>
	<div class="panel-body">		
         <form method="POST" id="scout_form" class="signup-form mobile-align-center md-layout md-layout--column ft-login__wrapper md-align md-align--center-start" action="{{ url('/register') }}">

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

                    <div class="md-layout layout-m-t-0 layout-p-r-0" style="width: 100%">
                        <div class="md-layout-spacer"></div>
                        <div class="form-group mobile-full-width md-layout layout-m-l-1 layout-m-l-0--sm">
                            <button type="submit" id="submit" class="btn btn-primary btn-submit">{{ trans('auth.register') }}</button>
                        </div>
                    </div>

                </form>
	</div>
</div><!-- /panel -->