<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use File;
use Artisan;
use App\Module;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\PermController;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    use CommonController;
    use PermController;

    // Show all modules based on user role
    public function show()
    {
        $user_role = auth()->user()->role;
        $modules = $this->getAppModules();

        if ($user_role != 'System Administrator') {
            $role_modules = $this->roleWiseModules($user_role, "Show");

            if ($role_modules) {
                foreach ($modules as $module_name => $config) {
                    if (!in_array($module_name, $role_modules)) {
                        unset($modules[$module_name]);
                    }
                }
            }
        }

        return view('layouts.origin.modules')->with(['data' => $modules]);
    }

    // put all functions to be performed after save
    public function beforeSave($request)
    {
        if ($request->has('id') && $request->get('id')) {
            session()->flash('success', false);
            throw new Exception("Cannot update Module. Please delete and re-create");
        }
    }

    // put all functions to be performed after save
    public function afterSave($data)
    {
        $module_table_name = $data['table_name'];
        $form_data = $data['form_data'][$module_table_name];

        if (session()->has('newly_created')) {
            $module_name = $form_data['name'];
            $table_name = $form_data['table_name'];

            // create migration
            if (isset($form_data['create_migration']) && intval($form_data['create_migration'])) {
                Artisan::call('make:migration', ['name' => 'create_' . $table_name . '_table', '--create' => $table_name]);
            }

            // create controller if doesn't exists
            if (!File::exists(app_path('Http/Controllers/' . $form_data["controller_name"] . '.php'))) {
                if (!isset($form_data['is_child_table'])) {
                    Artisan::call('make:controller', ['name' => $form_data["controller_name"]]);
                } else {
                    if (!intval($form_data['is_child_table'])) {
                        Artisan::call('make:controller', ['name' => $form_data["controller_name"]]);
                    }
                }
            }

            // create model if doesn't exists
            if (!File::exists(app_path($module_name . '.php'))) {
                Artisan::call('make:model', ['name' => $module_name]);
            }

            // create view file if doesn't exists
            if (!File::exists(resource_path('views/layouts/modules/' . $form_data["slug"] . '.blade.php'))) {
                File::put(resource_path('views/layouts/modules/' . $form_data["slug"] . '.blade.php'), '');
            }

            // create migration file for seeding module data
            $this->createDataMigration($form_data);
        }

        cache()->forget('app_modules');
    }

    // put all functions to be performed after delete
    public function afterDelete($data)
    {
        $module_table = $this->getModuleTable('Module');
        $name = $data[$module_table]['name'];
        $id = $data[$module_table]['id'];

        $this->moduleDeleteMigration($name, $id);
        cache()->forget('app_modules');
    }

    // create seeder file with the module data
    public function createDataMigration($data)
    {
        $int_fields = ['is_active', 'create_migration', 'sequence_no', 'show', 'is_child_table'];
        $app_name = getAppName();
        $id_numbering = spell_numbers($data['id']);
        $counter = 0;

        $file_text = "<?php\r\r";
        $file_text .= "use " . $app_name . "\Module;\r";
        $file_text .= "use Illuminate\Database\Schema\Blueprint;\r";
        $file_text .= "use Illuminate\Database\Migrations\Migration;\r\r";
        $file_text .= "class Seed" . $data['name'] . $id_numbering . "Module extends Migration\r";
        $file_text .= "{\r";
        $file_text .= "\t" . 'public function up()'. "\r";
        $file_text .= "\t{\r";
        $file_text .= "\t\t" . '$data = array(' . "\r";
        $file_text .= "\t\t\t[";

        foreach ($data as $key => $value) {
            if ($key !== "id") {
                if (in_array($key, $int_fields)) {
                    $value = intval($value);
                } else {
                    $value = $value ? ("'" . $value . "'") : null;
                }

                if ($counter > 0) {
                    $file_text .= ", '" . $key . "' => " . $value;
                } else {
                    $file_text .= "'" . $key . "' => " . $value;
                }

                $counter++;
            }
        }

        $file_text .= "]\r";
        $file_text .= "\t\t" . ");\r\r";
        $file_text .= "\t\t" . "Module::insert(" . '$data' . ");\r";
        $file_text .= "\t}\r\r";
        $file_text .= "\t" . 'public function down()'. "\r";
        $file_text .= "\t{\r";
        $file_text .= "\t\t" . "Module::where('name', '" . $data['name'] . "')->delete();\r";
        $file_text .= "\t}\r";
        $file_text .= "}\r";

        $file_name = date('Y_m_d_His') . "_seed_" . strtolower($data['name']) . "_" . snake_case($id_numbering) . "_module";
        File::put(database_path('migrations/' . $file_name . '.php'), $file_text);

        $max_migration_batch = DB::table('migrations')->max('batch');
        DB::table('migrations')->insert(['migration' => $file_name, 'batch' => $max_migration_batch + 1]);

        system('composer dump-autoload');
    }

    // prepare migration file for deleting module
    public function moduleDeleteMigration($module_name, $id)
    {
        $app_name = getAppName();
        $id_numbering = spell_numbers($id);

        $file_text = "<?php\r\r";
        $file_text .= "use " . $app_name . "\Module;\r";
        $file_text .= "use Illuminate\Database\Schema\Blueprint;\r";
        $file_text .= "use Illuminate\Database\Migrations\Migration;\r\r";
        $file_text .= "class Delete" . $module_name . $id_numbering . "Module extends Migration\r";
        $file_text .= "{\r";
        $file_text .= "\t" . 'public function up()'. "\r";
        $file_text .= "\t{\r";
        $file_text .= "\t\t" . "Module::where('name', '" . $module_name . "')->delete();\r";
        $file_text .= "\t}\r\r";
        $file_text .= "\t" . 'public function down()'. "\r";
        $file_text .= "\t{\r";
        $file_text .= "\r";
        $file_text .= "\t}\r";
        $file_text .= "}\r";

        $file_name = date('Y_m_d_His') . "_delete_" . strtolower($module_name) . "_" . snake_case($id_numbering) . "_module";
        File::put(database_path('migrations/' . $file_name . '.php'), $file_text);

        $max_migration_batch = DB::table('migrations')->max('batch');
        DB::table('migrations')->insert(['migration' => $file_name, 'batch' => $max_migration_batch + 1]);

        system('composer dump-autoload');
    }

    // update module sequence no
    public function updateSequence(Request $request)
    {
        $data = [
            'success' => false,
            'msg' => 'Please provide module data'
        ];

        if (in_array(auth()->user()->role, ["System Administrator", "Administrator"])) {
            if ($request->has('modules') && $request->get('modules')) {
                $modules = $request->get('modules');

                if (count($modules)) {
                    $updated = false;

                    foreach ($modules as $module) {
                        if ($module['name'] && intval($module['sequence_no'])) {
                            $updated = Module::where('name', $module['name'])
                                ->update(['sequence_no' => intval($module['sequence_no'])]);
                        }
                    }

                    if ($updated) {
                        $data['success'] = true;
                        $data['msg'] = 'Module sequence updated successfully';
                        cache()->forget('app_modules');
                    }
                }
            }
        } else {
            $data['msg'] = "You are not authorized to change Module sequence";
        }

        return response()->json($data, 200);
    }
}
