<!DOCTYPE html>
<html lang="en">
	<head>
		<title>User</title>
		<script type="text/javascript">
			var form_data = <?php echo isset($data) ? json_encode($data) : "false" ?>;
		</script>
		@include('templates.headers')
	</head>
	<body class="navbar-fixed">
		@include('templates.navbar')
		@include('templates.vertical_nav')
		<section id="content" class="content-sidebar bg-white">
			<section class="main">
				<div class="row">
					<div class="col-md-12">
						<section>
							<header class="panel-heading">
								<div class="row">
									<div class="col-md-6">
										<div class="h4">
											<span>
												<i class="fa fa-user"></i> {{ $data['tabUser']->full_name or 'New User' }}
											</span>
											@if (isset($data['tabUser']->login_id))
												<span class="text-mini m-l-large text-center" id="form-stats">
													<i class="fa fa-circle text-success"></i> <span class="m-l-mini h6" id="form-status"><b>Saved</b></span>
												</span>
											@endif
										</div>
									</div>
									<div class="col-md-6 col-md-push-4">
										<div style="line-height: 39px;">
											@if (isset($data['tabUser']->login_id))
												<a class="btn btn-danger btn-sm" id="delete" name="delete">
													<i class="fa fa-trash-o"></i> Delete
												</a>
											@endif
										</div>
									</div>
								</div>
							</header>
							@var $action = "/form/user"
							<form method="POST" action="{{ isset($data['tabUser']->id) ? $action."/".$data['tabUser']->login_id : $action }}" name="user" id="user" class="form-horizontal" enctype="multipart/form-data">
								{!! csrf_field() !!}
								<input type="hidden" name="id" id="id" class="form-control" data-mandatory="no" autocomplete="off" readonly>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-12" id="user-details">
											<h4>
												<strong><i class="fa fa-user"></i> User Details</strong>
											</h4>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-md-6 control-label">Avatar</label>
														<div class="col-md-4 media">
															<div class="bg-light pull-left text-center media-large thumb-large">
															@if (isset($data['tabUser']->avatar) && $data['tabUser']->avatar)
																<img src="{{ $data['tabUser']->avatar }}" alt="{{ $data['tabUser']->full_name }}">
															@else
																<i class="fa fa-user inline fa fa-light fa fa-3x m-t-large m-b-large"></i>
															@endif
															</div>
															<div class="media-body">
																<input type="file" title="Change" name="avatar" id="avatar" class="btn btn-sm btn-primary m-b-small">
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-md-3 control-label">Status</label>
														<div class="col-md-4">
															<select name="status" id="status" class="form-control" data-mandatory="yes">
																<option value="Active">Active</option>
																<option value="Inactive">Inactive</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-md-6 control-label">Full Name</label>
														<div class="col-md-6">
															<input type="text" name="full_name" id="full_name" class="bg-focus form-control" data-mandatory="yes" autocomplete="off">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-md-6 control-label">Login ID</label>
														<div class="col-md-6">
															<input type="text" name="login_id" id="login_id" class="form-control" data-mandatory="yes" autocomplete="off">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-md-6 control-label">Role</label>
														<div class="col-md-4">
															<select name="role" id="role" class="form-control" data-mandatory="yes">
																<option value="Administrator">Administrator</option>
																<option value="Guest">Guest</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<footer class="panel-footer">
									<div class="row">
										<div class="col-md-3">&nbsp;</div>
										<div class="col-md-8">
											<button type="reset" class="btn btn-white">Reset</button>
											<button type="submit" class="btn btn-primary disabled" id="save_form">
												<i class="fa fa-save"></i> Save changes
											</button>
										</div>
									</div>
								</footer>
							</form>
						</section>
					</div>
				</div>
			</section>
		</section>
		@include('templates.msgbox')
		@if (Session::has('msg'))
			<script type="text/javascript">
				msgbox("{{ Session::get('msg') }}");
			</script>
		@endif
	</body>
</html>