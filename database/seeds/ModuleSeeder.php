<?php

use App\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = array(
            [
                'name' => 'Module', 'display_name' => 'Module', 'table_name' => 'oc_modules', 'sequence_no' => 1, 
                'controller_name' => 'ModuleController', 'slug' => 'module', 'show' => 1, 'bg_color' => '#1ab394', 
                'list_view_columns' => 'display_name, table_name, controller_name, slug, is_active', 
                'icon' => 'fa fa-diamond', 'icon_color' => '#ffffff', 'form_title' => 'display_name', 
                'image_field' => null, 'owner' => 'admin', 'last_updated_by' => 'admin', 
                'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'User', 'display_name' => 'User', 'table_name' => 'oc_users', 'sequence_no' => 2, 
                'controller_name' => 'UserController', 'slug' => 'user', 'show' => 1, 'bg_color' => '#d35400', 
                'list_view_columns' => 'login_id, full_name, role, is_active', 
                'icon' => 'fa fa-user', 'icon_color' => '#ffffff', 'form_title' => 'login_id', 
                'image_field' => 'avatar', 'owner' => 'admin', 'last_updated_by' => 'admin', 
                'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ],
        );

        Module::insert($modules);
    }
}
