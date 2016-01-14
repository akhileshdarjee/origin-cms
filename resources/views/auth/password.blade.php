<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Email Password - Web App</title>
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
							<form action="/password/email" method="POST" name="password_email" id="password_email" class="panel-body" role="form">
								@if (Session::has('status'))
									<div class="block">
										<div class="alert alert-success">
											<button type="button" class="close" data-dismiss="alert">
												<i class="fa fa-times"></i>
											</button>
											<strong>
												<i class="fa fa-check fa-lg"></i> {{ Session::get('status') }}
											</strong>
										</div>
									</div>
								@endif
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
								<div class="block">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-envelope"></i>
										</span>
										<input type="text" name="email" id="email" class="form-control" placeholder="Email Address">
									</div>
									<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Email Address</div>
								</div><br />
								<button type="submit" class="btn btn-primary block full-width m-b">Submit</button>
							</form>
						</section>
					</div>
				</div>
			</div>
		</section>
		@include('templates.msgbox')
	</body>
</html>