<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Password Reset - {{ env('BRAND_NAME', 'Origin CMS') }}</title>
		@include('templates.headers')
	</head>
	<body class="login">
		<div>
			<div class="login_wrapper">
				<div class="animate form login_form">
					<section class="login_content">
						<form action="{{ url('/password/reset') }}" method="POST" name="password_reset" id="password_reset" class="m-t" role="form">
							<h1>Password Reset</h1>
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
							<input type="hidden" name="token" value="{{ $token }}">
							<div>
								<input type="text" name="email" id="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}">
							</div>
							<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Email Address</div>
							<div>
								<input type="password" name="password" id="password" class="form-control" placeholder="Password">
							</div>
							<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Password</div>
							<div>
								<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password">
							</div>
							<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Confirm Password</div>
							<div>
								<button type="submit" class="btn btn-block btn-default submit">Submit</button>
							</div>
							<div class="clearfix"></div>
							<div class="separator">
								<div>
									<h1><i class="fa fa-eye"></i> {{ env('BRAND_NAME', 'Origin CMS') }}</h1>
								</div>
							</div>
						</form>
					</section>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="{{ url('/js/jquery.js') }}"></script>
	</body>
</html>