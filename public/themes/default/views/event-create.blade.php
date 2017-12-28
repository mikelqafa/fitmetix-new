<!-- main-section -->
<link href="{{ asset('css/theme1.css') }}" rel="stylesheet">
<div class="container">
	<div class="row">              
		<div class="col-md-12 col-lg-7">
			{!! Theme::partial('create-event',compact('username','group_id','timeline_name')) !!}
		</div>

		<div class="hidden-xs col-md-12 col-lg-5">
			<div id="caleandar">
			</div>
		</div>
	</div>
</div>	
<!-- /main-section -->