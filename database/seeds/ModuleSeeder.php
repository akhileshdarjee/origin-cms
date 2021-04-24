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
                'list_view_columns' => 'display_name, table_name, slug, bg_color, icon, form_title, active', 
                'icon' => 'fas fa-gem', 'icon_color' => '#ffffff', 'form_title' => 'display_name', 
                'image_field' => null, 'owner' => 'admin', 'last_updated_by' => 'admin', 
                'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'User', 'display_name' => 'User', 'table_name' => 'oc_users', 'sequence_no' => 2, 
                'controller_name' => 'UserController', 'slug' => 'user', 'show' => 1, 'bg_color' => '#d35400', 
                'list_view_columns' => 'avatar, username, full_name, role, active', 
                'icon' => 'fas fa-user', 'icon_color' => '#ffffff', 'form_title' => 'username', 
                'image_field' => 'avatar', 'owner' => 'admin', 'last_updated_by' => 'admin', 
                'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Language', 'display_name' => 'Language', 'table_name' => 'oc_language', 'sequence_no' => 3, 
                'controller_name' => 'LanguageController', 'slug' => 'language', 'show' => 1, 'bg_color' => '#1a7bb9', 
                'list_view_columns' => 'name, locale, active', 'icon' => 'fas fa-flag', 'icon_color' => '#ffffff', 
                'form_title' => 'name', 'image_field' => null, 'owner' => 'admin', 'last_updated_by' => 'admin', 
                'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Translation', 'display_name' => 'Translation', 'table_name' => 'oc_translation', 'sequence_no' => 4, 
                'controller_name' => 'TranslationController', 'slug' => 'translation', 'show' => 1, 'bg_color' => '#4CAF50', 
                'list_view_columns' => 'from, to, locale', 'icon' => 'fas fa-language', 'icon_color' => '#ffffff', 
                'form_title' => 'from', 'image_field' => null, 'owner' => 'admin', 'last_updated_by' => 'admin', 
                'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ],
        );

        Module::insert($modules);
    }
}
