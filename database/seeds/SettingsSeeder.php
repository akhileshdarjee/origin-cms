<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$settings = array(
			['field_name' => 'social_login', 'field_value' => 'Active', 'module' => 'Login', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
			['field_name' => 'facebook_login', 'field_value' => 'Active', 'module' => 'Social Login', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
			['field_name' => 'google_login', 'field_value' => 'Active', 'module' => 'Social Login', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
			['field_name' => 'email', 'field_value' => 'Active', 'module' => 'Email', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
			['field_name' => 'home_page', 'field_value' => 'modules', 'module' => 'Other', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
			['field_name' => 'list_view_records', 'field_value' => '15', 'module' => 'Other', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")]
		);

		DB::table('tabSettings')->insert($settings);
	}
}