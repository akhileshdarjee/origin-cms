<!DOCTYPE html>
<html lang="en">
	<head>
		<title>404 Error | Page Not Found | {{ env('BRAND_NAME', 'Origin CMS') }}</title>
		@include('templates.headers')
	</head>
	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<!-- page content -->
				<div class="col-md-12">
					<div class="col-middle">
						<div class="text-center text-center">
							<h1 class="error-number">404</h1>
							<h2>Sorry but we couldn't find this page</h2>
							<p>This page you are looking for does not exist</p>
							<div class="mid_center">
								<a href="{{ url('/app') }}" class="btn btn-success">Back to Home</a>
							</div>
						</div>
					</div>
				</div>
				<!-- /page content -->
			</div>
		</div>
	</body>
</html>