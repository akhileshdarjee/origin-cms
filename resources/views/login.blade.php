<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login - Web App</title>
		@include('templates.headers')
	</head>
	<body class="login">
		<div>
			<a class="hiddenanchor" id="signup"></a>
			<a class="hiddenanchor" id="signin"></a>
			<div class="login_wrapper">
				<div class="animate form login_form">
					<section class="login_content">
						<form action="/login" method="POST" name="login" id="login" class="m-t" role="form">
							<h1>Login</h1>
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
							<div>
								<input type="text" class="form-control" name="login_id" id="login_id" placeholder="Login ID" />
							</div>
							<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Login ID</div>
							<div>
								<input type="password" name="password" id="password" class="form-control" placeholder="Password" />
							</div>
							<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Password</div>
							<div>
								<button type="submit" class="btn btn-default submit">Login</button>
								<a class="reset_pass" href="/password/email">Forgot password?</a>
							</div>
							<div class="clearfix"></div>
							<div class="separator">
								<p class="change_link">New to site?
									<a href="#signup" class="to_register"> Create Account </a>
								</p>
								<div class="clearfix"></div>
								<br />
								<div>
									<h1><i class="fa fa-eye"></i> Origin CMS</h1>
								</div>
							</div>
						</form>
					</section>
				</div>
				<div id="register" class="animate form registration_form">
					<section class="login_content">
						<form>
							<h1>Create Account</h1>
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
							<div>
								<input type="text" class="form-control" placeholder="Login ID" />
							</div>
							<div>
								<input type="email" class="form-control" placeholder="Email" />
							</div>
							<div>
								<input type="password" class="form-control" placeholder="Password" />
							</div>
							<div>
								<a class="btn btn-default submit" href="index.html">Submit</a>
							</div>
							<div class="clearfix"></div>
							<div class="separator">
								<p class="change_link">Already a member ?
									<a href="#signin" class="to_register"> Log in </a>
								</p>
								<div class="clearfix"></div>
								<br />
								<div>
									<h1><i class="fa fa-eye"></i> Origin CMS</h1>
								</div>
							</div>
						</form>
					</section>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="/js/jquery.js"></script>
		<script type="text/javascript" src="/js/web_app/login.js"></script>
	</body>
</html>