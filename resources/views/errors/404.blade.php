<!DOCTYPE html>
<html>
	<head>
		<title>Web App | 404 Error | Page Not Found</title>
		@include('templates.headers')
	</head>
	<body>
		@include('templates.navbar')
		<div class="middle-box text-center animated fadeInDown">
			<h1>404</h1>
			<h3>Page Not Found</h3>
			<div>
				Sorry, but the page you are looking for has not been found. 
				Try checking the URL for error, then hit the refresh button on your browser or try found something else in our website.
				<br /><br />
				<div class="col-md-2 col-md-offset-5 text-center">
					<a href="/" class="btn btn-primary">
						<i class ="fa fa-home"> Back to Home</i>
					</a>
				</div>
			</div>
		</div>
	</body>
</html>