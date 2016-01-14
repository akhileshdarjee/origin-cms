<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login - Web App</title>
		@include('templates.headers')
	</head>
	<body bgcolor="white">
		<header id="header" class="navbar bg bg-black">
			<a class="navbar-brand" href="/">Web App</a>
		</header>
		<section id="content">
			<div class="main padder">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4 m-t-large">
						<section class="panel">
							<header class="panel-heading">
								<h4><i class="fa fa-user"></i> <strong>Sign In</strong></h4>
							</header>
							<form action="/login" method="POST" name="login" id="login" class="panel-body">
								@if (Session::has('msg'))
									<div class="block">
										<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="fa fa-times"></i>
											</button>
											<strong>
												<i class="fa fa-exclamation-triangle fa-lg"></i> {{ Session::get('msg') }}
											</strong>
										</div>
									</div>
								@endif
								{!! csrf_field() !!}
								<div class="block">
									<label class="control-label">Login ID</label>
									<input type="text" name="login_id" id="login_id" placeholder="you@example.com" class="form-control" data-mandatory="yes">
								</div>
								<div class="block">
									<label class="control-label">Password</label>
									<input type="password" name="password" id="password" placeholder="Password" class="form-control" data-mandatory="yes">
								</div>
								<a href="/password/email" class="pull-right m-t-small">Forgot Password?</a>
								<button type="submit" class="btn btn-info" data-loading-text="Signing In...">Sign In</button>
							</form>
						</section>
					</div>
				</div>
			</div>
		</section>
		@include('templates.msgbox')
	</body>
</html>