<div class="row">
	<div class="col-md-12" id="report-details">
		<h4>
			<strong><i class="fa fa-sitemap"></i> Report Details</strong>
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
					<label class="col-md-6 control-label">Module</label>
					<div class="col-md-6">
						<input type="text" name="module" id="module" class="form-control autocomplete" data-mandatory="yes" autocomplete="off" data-target-module="Module" data-target-field="name">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-4 control-label">Type</label>
					<div class="col-md-4">
						<select name="type" id="type" class="form-control" data-mandatory="yes">
							<option value="Standard">Standard</option>
							<option value="Query">Query</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-6 control-label">Sequence No.</label>
					<div class="col-md-3">
						<input type="text" name="sequence_no" id="sequence_no" class="form-control" data-mandatory="no" autocomplete="off">
					</div>
				</div>
			</div>
		</div>
		<div class="hr-line-dashed"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-12" id="report-data">
		<h4>
			<strong><i class="fa fa-database"></i> Report Data</strong>
		</h4>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label">Columns</label>
					<div class="col-md-8">
						<input type="text" name="columns" id="columns" class="form-control" data-mandatory="no" autocomplete="off">
						<small class="block">
							DB column name with comma(,) separated, don't prefix or suffix column names with inverted commas
						</small>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label">Filters</label>
					<div class="col-md-8">
						<textarea rows="5" name="filters" id="filters" class="input-xlarge form-control" data-mandatory="no" autocomplete="off"></textarea>
						<small class="block">
							Include filters HTML with parent element id="report-filters"
						</small>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="query_section">
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label">Query</label>
					<div class="col-md-8">
						<textarea rows="7" name="query" id="query" class="input-xlarge form-control" data-mandatory="no" autocomplete="off"></textarea>
						<small class="block">
							Save output of records in $rows variable
						</small>
					</div>
				</div>
			</div>
		</div>
		<div class="hr-line-dashed"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-12" id="customization-details">
		<h4>
			<strong><i class="fa fa-magic"></i> Customization Details</strong>
		</h4>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-6 control-label">Background Color</label>
					<div class="col-md-6">
						<input type="text" name="bg_color" id="bg_color" class="form-control" data-mandatory="no" autocomplete="off" data-target-module="Module" data-target-field="bg_color">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-4 control-label">Order By</label>
					<div class="col-md-6">
						<input type="text" name="order_by" id="order_by" class="form-control" data-mandatory="no" autocomplete="off">
						<small class="block">
							Eg. 'id', 'desc' (include with single inverted comma('))
						</small>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-6 control-label">Icon</label>
					<div class="col-md-6">
						<input type="text" name="icon" id="icon" class="form-control" data-mandatory="no" autocomplete="off" data-target-module="Module" data-target-field="icon">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-4 control-label">Icon Color</label>
					<div class="col-md-6">
						<input type="text" name="icon_color" id="icon_color" class="form-control" data-mandatory="no" autocomplete="off" data-target-module="Module" data-target-field="icon_color">
					</div>
				</div>
			</div>
		</div>
		<div class="hr-line-dashed"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-12" id="other-details">
		<h4>
			<strong><i class="fa fa-info-circle"></i> Other Details</strong>
		</h4>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="col-md-3 control-label">Allowed Roles</label>
					<div class="col-md-8">
						<input type="text" name="allowed_roles" id="allowed_roles" class="form-control" data-mandatory="no" autocomplete="off">
						<small class="block">
							Multiple Roles to be comma(,) separated
						</small>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-6 control-label">Description</label>
					<div class="col-md-6">
						<textarea rows="5" name="description" id="description" class="input-xlarge form-control" data-mandatory="no" autocomplete="off"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>