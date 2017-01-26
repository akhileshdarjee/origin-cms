<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login - Origin CMS</title>
		@include('templates.headers')
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<a href="/"><b>Origin</b>CMS</a>
			</div>
			<!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg">Sign In</p>
				<form action="/login" method="POST" name="login" id="login">
					@if (Session::has('msg'))
						@if (Session::has('success') && Session::get('success') == "true")
							<div class="block">
								<div class="alert alert-success alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<strong>
										<i class="fa fa-check fa-lg"></i> {{ Session::get('msg') }}
									</strong>
								</div>
							</div>
						@else
							<div class="block">
								<div class="alert alert-danger alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<strong>
										<i class="fa fa-exclamation-triangle fa-lg"></i> {{ Session::get('msg') }}
									</strong>
								</div>
							</div>
						@endif
					@endif
					{!! csrf_field() !!}
					<div class="form-group has-feedback">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-envelope"></i>
							</span>
							<input type="text" name="login_id" id="login_id" class="form-control" placeholder="Login ID">
						</div>
						<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Login ID</div>
					</div>
					<div class="form-group has-feedback">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-lock"></i>
							</span>
							<input type="password" name="password" id="password" class="form-control" placeholder="Password">
						</div>
						<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Password</div>
					</div>
					<div class="row">
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
						</div>
					</div>
				</form>
				<a href="/password/email">Forgot Password?</a><br>
				<!-- <a href="/" class="text-center">Register a new membership</a> -->
			</div>
		</div>
		<script type="text/javascript" src="/js/jquery.js"></script>
		<script type="text/javascript" src="/js/web_app/login.js"></script>
	</body>
</html>