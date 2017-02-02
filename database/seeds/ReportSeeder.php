<?php

use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$reports = array(
			[
				'name' => 'User Report', 'status' => 'Active', 'type' => 'Standard', 'module' => 'User', 
				'sequence_no' => 1, 'columns' => 'full_name, login_id, email, role, status', 
				'filters' => "<div class='row' id='report-filters'>\n\t <div class='col-md-3'>\n\t\t <div class='form-group'>\n\t\t\t <input type='text' name='email' id='email' class='form-control autocomplete' placeholder='Email' autocomplete='off' data-target-module='User' data-target-field='email'>\n\t\t </div>\n\t </div>\n\t <div class='col-md-3'>\n\t\t <div class='form-group'>\n\t\t\t <input type='text' name='role' id='role' class='form-control autocomplete' placeholder='Role' autocomplete='off' data-target-module='User' data-target-field='role'>\n\t\t </div>\n\t </div>\n\t <div class='col-md-3'>\n\t\t <div class='form-group'>\n\t\t\t <select name='status' id='status' class='form-control'>\n\t\t\t\t <option value='' default selected>Status</option>\n\t\t\t\t <option value='Active'>Active</option>\n\t\t\t\t <option value='Inactive'>Inactive</option>\n\t\t\t </select>\n\t\t </div>\n\t </div>\n </div>", 
				'icon' => 'fa fa-user', 'bg_color' => '#d35400', 'icon_color' => '#ffffff', 'query' => null, 
				'allowed_roles' => null, 'order_by' => null, 'description' => 'List of all User(s)', 
				'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
			]
		);

		DB::table('tabReports')->insert($reports);
	}
}