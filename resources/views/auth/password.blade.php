<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Password Reset - Origin CMS</title>
		@include('templates.headers')
	</head>
	<body class="login">
		<div>
			<div class="login_wrapper">
				<div class="animate form login_form">
					<section class="login_content">
						<form action="/password/email" method="POST" name="password_email" id="password_email" class="m-t" role="form">
							<h1>Password Reset</h1>
							@if (Session::has('status'))
								<div class="block">
									<div class="alert navy-bg">
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
										<div class="alert red-bg">
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
							<div>
								<input type="text" class="form-control" name="email" id="email" placeholder="Email Address" />
							</div>
							<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Email Address</div>
							<div>
								<button type="submit" class="btn btn-block btn-default submit">Submit</button>
							</div>
							<div class="clearfix"></div>
							<div class="separator">
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
	</body>
</html>