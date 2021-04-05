<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use CommonController;

    // Show app settings page
    public function show()
    {
        $data['oc_settings'] = $this->getAppSetting();

        $settings_data = [
            'form_data' => isset($data) ? $data : [],
            'form_title' => 'Settings',
            'title' => 'Settings',
            'icon' => 'fas fa-cogs',
            'file' => 'layouts.origin.settings',
            'module' => 'Settings',
            'slug' => 'settings',
            'module_type' => 'Single',
            'table_name' => 'oc_settings',
            'permissions' => ['update' => true]
        ];

        return view('templates.form_view', $settings_data);
    }

    // Save app settings
    public function save(Request $request)
    {
        $settings_data = $request->all();
        unset($settings_data["_token"]);

        foreach ($settings_data as $setting => $value) {
            $result = DB::table('oc_settings')
                ->where('field_name', $setting)
                ->where('owner', auth()->user()->username)
                ->update([
                    'field_value' => $value, 
                    'updated_at' => date('Y-m-d H:i:s'), 
                    'last_updated_by' => auth()->user()->username
                ]);

            if ($result) {
                session()->flash('success', true);
            }
        }

        $this->putAppSettingsInSession();

        return redirect()->route('show.app.settings')
            ->with(['msg' => __('App settings successfully saved')]);
    }
}
