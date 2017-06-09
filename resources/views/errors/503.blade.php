<!DOCTYPE html>
<html lang="en">
	<head>
		<title>503 Error | Service Temporarily Unavailable | {{ env('BRAND_NAME', 'Origin CMS') }}</title>
		@include('templates.headers')
	</head>
	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<!-- page content -->
				<div class="col-md-12">
					<div class="col-middle">
						<div class="text-center text-center">
							<h1 class="error-number">500</h1>
							<h2>Service Temporarily Unavailable</h2>
							<p>We track these errors automatically, but if the problem persists feel free to contact us. In the meantime, try refreshing.</p>
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