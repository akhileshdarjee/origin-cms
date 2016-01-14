<!DOCTYPE html>
<html>
	<head>
		<title>Web App | 404 Error | Page Not Found</title>
		@include('website.templates.headers')
	</head>
	<body>
		@include('website.templates.navbar')
		<div class="middle-box text-center animated fadeInDown">
			<h1 class="under-text">404</h1>
			<h3 class="ttl-line">Page Not Found</h3>
			<div class="event-descri">
				Sorry, but the page you are looking for has not been found. 
				Try checking the URL for error, then hit the refresh button on your browser or try found something else in our website.
				<br /><br />
				<div class="col-md-2 col-md-offset-5 text-center">
					<a href="/">
						<img src="/img/home_icon.png" class="text-center" alt="...">
					</a>
				</div>
			</div>
		</div>
	</body>
</html>