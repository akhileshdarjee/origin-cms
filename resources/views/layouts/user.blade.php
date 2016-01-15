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
</form>