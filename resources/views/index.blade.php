<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Web App</title>
		@include('templates.headers')
	</head>
	<body class="navbar-fixed bg-white" data-url="/app">
		@include('templates.navbar')
		@include('templates.vertical_nav')
		<section id="content">
			<section class="main padder">
				@include($file, ['data' => $data])
			</section>
		</section>
		@include('templates.msgbox')
		@if (Session::has('msg'))
			<script type="text/javascript">
				msgbox("{{ Session::get('msg') }}");
			</script>
		@endif
	</body>
</html>