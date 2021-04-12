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
            ['field_name' => 'home_page', 'field_value' => 'modules', 'module' => 'Other', 'owner' => 'sysadmin', 'last_updated_by' => 'sysadmin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ['field_name' => 'list_view_records', 'field_value' => '15', 'module' => 'Other', 'owner' => 'sysadmin', 'last_updated_by' => 'sysadmin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ['field_name' => 'theme', 'field_value' => 'light', 'module' => 'Other', 'owner' => 'sysadmin', 'last_updated_by' => 'sysadmin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ['field_name' => 'home_page', 'field_value' => 'modules', 'module' => 'Other', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ['field_name' => 'list_view_records', 'field_value' => '15', 'module' => 'Other', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ['field_name' => 'theme', 'field_value' => 'light', 'module' => 'Other', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")],
            ['field_name' => 'enable_backups', 'field_value' => '1', 'module' => 'Other', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")]
        );

        DB::table('oc_settings')->insert($settings);
    }
}
