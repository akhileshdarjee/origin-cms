<div class="panel-body">
	<div class="row" id="report-filters">
		<div class="col-md-3">
			<div class="form-group">
				<div class="input-group date datetimepicker" id="fromdate">
					<input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" autocomplete="off">
					<span class="input-group-addon">
						<span class="fa fa-calendar"></span>
					</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<div class="input-group date datetimepicker" id="todate">
					<input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" autocomplete="off">
					<span class="input-group-addon">
						<span class="fa fa-calendar"></span>
					</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<input type="text" name="company" id="company" class="form-control autocomplete" 
				placeholder="Company" autocomplete="off" data-target-module="Client" data-target-field="full_name" 
				value="{{ Session::get('role') == 'Client' ? Session::get('user') : '' }}">
			</div>
		</div>
	</div>
</div>