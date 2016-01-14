<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Password Reset - Web App</title>
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
								<h4><i class="fa fa-user"></i> <strong>Password Reset</strong></h4>
							</header>
							<form action="/password/reset" method="POST" name="password_reset" id="password_reset" class="panel-body" role="form">
								@if (count($errors) > 0)
									@foreach ($errors->all() as $error)
										<div class="block">
											<div class="alert alert-danger">
												<button type="button" class="close" data-dismiss="alert">
													<i class="fa fa-times"></i>
												</button>
												<strong>
													<i class="fa fa-exclamation-triangle fa-lg"></i> {{ $error }}
												</strong>
											</div>
										</div>
									@endforeach
								@endif
								{!! csrf_field() !!}
								<input type="hidden" name="token" value="{{ $token }}">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-envelope"></i>
										</span>
										<input type="text" name="email" id="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}">
									</div>
									<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Email Address</div>
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
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-key"></i>
										</span>
										<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password">
									</div>
									<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Confirm Password</div>
								</div>
								<button type="submit" class="btn btn-primary block full-width m-b">Reset Password</button>
							</form>
						</section>
					</div>
				</div>
			</div>
		</section>
		@include('templates.msgbox')
	</body>
</html>