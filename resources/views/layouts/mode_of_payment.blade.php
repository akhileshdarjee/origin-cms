@var $action = "/form/mode_of_payment"
<form method="POST" action="{{ isset($form_data['tabModeOfPayment']['id']) ? $action."/".$form_data['tabModeOfPayment'][$link_field] : $action }}" name="mode_of_payment" id="mode_of_payment" class="form-horizontal" enctype="multipart/form-data">
	{!! csrf_field() !!}
	<input type="hidden" name="id" id="id" class="form-control" data-mandatory="no" autocomplete="off" readonly>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12" id="mode-of-payment-details">
				<h4>
					<strong><i class="fa fa-money"></i> Mode Of Payment Details</strong>
				</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-6 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" name="name" id="name" class="bg-focus form-control" data-mandatory="yes" autocomplete="off">
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
			</div>
		</div>
	</div>
</form>