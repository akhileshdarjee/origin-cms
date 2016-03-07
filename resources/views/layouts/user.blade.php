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
						<div class="col-md-6 media">
							<div class="pull-left text-center avatar-box">
							@if (isset($form_data['tabUser']['avatar']) && $form_data['tabUser']['avatar'])
								<img src="{{ $form_data['tabUser']['avatar'] }}" alt="{{ $form_data['tabUser']['full_name'] }}">
							@else
								<i class="fa fa-picture-o inline fa-2x avatar"></i>
							@endif
							</div>
							<div class="media-body">
								<label title="Upload image file" for="avatar" class="btn btn-primary btn-sm">
									<input type="file" accept="image/*" name="avatar" id="avatar" class="hide">
									Change
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-4 control-label">Status</label>
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
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-3 control-label">Email</label>
						<div class="col-md-6">
							<input type="text" name="email" id="email" class="form-control" data-mandatory="yes" autocomplete="off">
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-6 control-label">Role</label>
						<div class="col-md-6">
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