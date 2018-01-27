<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>FITMETIX</title>
    <meta content="{{ $url }}" property="og:url"/>
    <meta content="{{ $post_image }}" property="og:image"/>
    <meta content="{{ $description }}" property="og:description"/>
    <meta content="{{ $title }}" property="og:title"/>
    <meta content="website" property="og:type"/>
    <meta content="Fitmetix" property="og:site_name"/>
</head>
<body>
	
	@if(Auth::check())
	    @php $url = url('/'.$post_id); @endphp
	@else
	    @php $url = url('/'); @endphp
	@endif
	<script type="text/javascript">
		window.location.href="{{ $url }}";
	</script>
	
</body>
</html>