<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="Web App">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}" />

		<title>Login - Web App</title>

		<!-- Roboto font -->
		<link href="http://fonts.googleapis.com/css?family=Roboto:700,500,400,300,100&ampamp;subset=latin,latin-ext,cyrillic,cyrillic-ext%22" rel="stylesheet">

		<link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">

		<link rel="stylesheet" type="text/css" href="/css/animate.css">
		<link rel="stylesheet" type="text/css" href="/css/style.css">
		<link rel="stylesheet" type="text/css" href="/css/web_app/web_app.css">
		<style type="text/css">
			.centrify {
				position: absolute;
				left: 50%;
				top: 50%;
				margin-left: -15px;
				margin-top: -15px;
			}
		</style>
	</head>
	<body class="gray-bg">
		<div class="text-center">
			<h1 class="logo-name">APP</h1>
		</div>
		<div class="middle-box text-center loginscreen animated fadeInDown" style="padding-top: 0px;">
			<div class="ibox-content">
				<h3>Welcome to Web App</h3>
				<form action="/login" method="POST" name="login" id="login" class="m-t" role="form">
					@if (Session::has('msg'))
						@if (Session::has('success') && Session::get('success') == "true")
							<div class="block">
								<div class="alert navy-bg alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<strong>
										<i class="fa fa-check fa-lg"></i> {{ Session::get('msg') }}
									</strong>
								</div>
							</div>
						@else
							<div class="block">
								<div class="alert red-bg alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<strong>
										<i class="fa fa-exclamation-triangle fa-lg"></i> {{ Session::get('msg') }}
									</strong>
								</div>
							</div>
						@endif
					@endif
					{!! csrf_field() !!}
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-envelope"></i>
							</span>
							<input type="text" name="login_id" id="login_id" class="form-control" placeholder="Login ID">
						</div>
						<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Login ID</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-key"></i>
							</span>
							<input type="password" name="password" id="password" class="form-control" placeholder="Password">
						</div>
						<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Password</div>
					</div>
					<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
					<a href="/password/email"><small>Forgot password?</small></a>
				</form>
			</div>
		</div>
		<!-- Mainly scripts -->
		<script type="text/javascript" src="/js/jquery.js"></script>
		<script type="text/javascript" src="/js/bootstrap.js"></script>
		<script type="text/javascript" src="/js/web_app/login.js"></script>
	</body>
</html>