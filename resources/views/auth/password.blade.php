<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Forgot Password - Origin CMS</title>
		@include('templates.headers')
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<a href="/"><b>Origin</b>CMS</a>
			</div>
			<!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg">Forgot Password Form</p>
				<form action="/password/email" method="POST" name="password_email" id="password_email">
					@if (Session::has('status'))
						<div class="block">
							<div class="alert alert-success alert-dismissible">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<strong>
									<i class="fa fa-check fa-lg"></i> {{ Session::get('status') }}
								</strong>
							</div>
						</div>
					@endif
					@if (count($errors) > 0)
						@foreach ($errors->all() as $error)
							<div class="block">
								<div class="alert alert-danger alert-dismissible">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									<strong>
										<i class="fa fa-exclamation-triangle fa-lg"></i> {{ $error }}
									</strong>
								</div>
							</div>
						@endforeach
					@endif
					{!! csrf_field() !!}
					<div class="form-group has-feedback">
						<input type="text" class="form-control" name="email" id="email" placeholder="Email Address" />
						<div class="text-danger" id="alert" style="text-align: left; display: none;">Please Enter Login ID</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<script type="text/javascript" src="/js/jquery.js"></script>
	</body>
</html>