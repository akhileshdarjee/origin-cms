<form method="POST" action="/app/settings" name="settings" id="settings" class="form-horizontal" enctype="multipart/form-data">
	{!! csrf_field() !!}
	@if (Session::get('role') == "Administrator")
		<div class="row">
			<div class="col-md-12" id="email-settings">
				<h4>
					<strong><i class="fa fa-at"></i> Email</strong>
				</h4>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="col-md-2 control-label">Email</label>
							<div class="col-md-2">
								<select name="email" id="email" class="form-control" data-mandatory="yes">
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="hr-line-dashed"></div>
		<div class="row">
			<div class="col-md-12" id="sms-settings">
				<h4>
					<strong><i class="fa fa-envelope"></i> SMS</strong>
				</h4>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="col-md-2 control-label">SMS</label>
							<div class="col-md-2">
								<select name="sms" id="sms" class="form-control" data-mandatory="yes">
									<option value="Active">Active</option>
									<option value="Inactive">Inactive</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="hr-line-dashed"></div>
	@endif
	<div class="row">
		<div class="col-md-12" id="home-page-settings">
			<h4>
				<strong><i class="fa fa-home"></i> Home</strong>
			</h4>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="col-md-2 control-label">Home Page</label>
						<div class="col-md-2">
							<select name="home_page" id="home_page" class="form-control" data-mandatory="yes">
								<option value="modules">Modules</option>
								<option value="reports">Reports</option>
								<option value="settings">Settings</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="hr-line-dashed"></div>
	<div class="row">
		<div class="col-md-12" id="list-view-settings">
			<h4>
				<strong><i class="fa fa-list"></i> List View</strong>
			</h4>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="col-md-2 control-label">Record(s) per page</label>
						<div class="col-md-1">
							<input type="text" name="list_view_records" id="list_view_records" class="form-control" data-mandatory="yes" autocomplete="off">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>