<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Web App</title>
		@include('templates.headers')
	</head>
	<body data-url="/app" class="fixed-sidebar">
		<div id="wrapper">
			@include('templates.vertical_nav')
			<div id="page-wrapper" class="gray-bg">
				@include('templates.navbar', ['title' => ucwords(last(explode(".", $file)))])
				<div class="row dashboard-header">
					@include($file, ['data' => $data])
				</div>
				<div class="footer">
					<div>
						<span class="pull-left">
							<strong>Copyright</strong> Achieveee &copy; 2011-{{ date('Y') }}
						</span>
						<span class="pull-right">
							Made with <i class="fa fa-heart fa-lg" style="color: #d90429;"></i> by 
							<strong>
								<a href="http://www.achieveee.com/" target="_blank" style="color: #676a6c;">Achieveee</a>
							</strong>
						</span>
					</div>
				</div>
			</div>
		</div>
		@include('templates.msgbox')
		@if (Session::has('msg'))
			<script type="text/javascript">
				msgbox("{{ Session::get('msg') }}");
			</script>
		@endif
	</body>
</html>