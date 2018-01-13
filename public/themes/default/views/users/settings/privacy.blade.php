<!-- main-section -->
<!-- <div class="main-content"> -->
	<div class="container">
		<div class="row">
			{{--<div class="col-md-4">
				<div class="post-filters">
					{!! Theme::partial('usermenu-settings') !!}
				</div>
			</div>--}}
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading no-bg panel-settings">
						<h3 class="panel-title">
							{{ trans('common.privacy_settings') }}
						</h3>
					</div>
					<div class="panel-body">
						@include('flash::message')

						{{ Form::open(array('class' => 'form-inline','url' => Auth::user()->username.'/settings/privacy', 'method' => 'post')) }}

						{{ csrf_field() }}
						@php
						   $prev_type = 'private';
						   if($settings['privacy']->confirm_follow == 'yes') {
						   	    $prev_type = 'public';
						   }
						@endphp
						<div class="container">
							<div class="row">
								<div class="col-md-6 layout-m-b-1--sm">
								    {{ Form::label('privacy', trans('common.privacy_type')) }}
								</div>
								<div class="col-md-2 layout-m-b-1--sm">
									{{ Form::select('privacy_type', array('public' => trans('common.public'), 'private' => trans('common.private')), $prev_type, array('class' => 'form-control')) }}
								</div>
								<div class="col-md-4">
									{{ Form::submit(trans('common.save_changes'), ['class' => 'btn btn-success']) }}
								</div>
							</div>
						</div>
						{{ Form::close() }}
					</div>
				</div><!-- /panel -->
				<div class="panel panel-default">
					<div class="panel-heading no-bg panel-settings">
						<h3 class="panel-title">
							Blocked Users
						</h3>
					</div>
					<div class="panel-body">
						<table class="table">
							<thead>
							<tr>
								<th>Username</th>
								<th>Blocked On</th>
								<th>Unblock</th>
							</tr>
							</thead>
							@if(!empty($settings['blocklist']))
								@foreach($settings['blocklist'] as $key => $value)
									<tr>
										<td>{{$value->blocked_username}}</td>
										<td>{{$value->created_at}}</td>
										<td><a target="_blank" href="{{ URL::to('ajax/unblock-user/'.$value->id) }}" >Unblock</a></td>
									</tr>
									@endforeach
							@endif
						</table>
					</div>
				</div>
			</div>
		</div><!-- /row -->
	</div>
<!-- </div> --><!-- /main-content -->
