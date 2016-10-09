<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ awesome_case(last(explode(".", $file))) }} - Web App</title>
		@include('templates.headers')
		<script type="text/javascript">
			window.doc = {
				data: <?php echo isset($data) ? json_encode($data) : "false" ?>,
			};
		</script>
	</head>
	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				@include('templates.vertical_nav')
				@include('templates.navbar')
				<!-- page content -->
				<div class="right_col" role="main">
					@include($file, ['data' => $data])
				</div>
				<!-- /page content -->
				@include('templates.footer')
			</div>
		</div>
	</body>
</html>