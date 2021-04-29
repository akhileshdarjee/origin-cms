<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Language;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use CommonController;

    public function show()
    {
        $form_data['oc_settings'] = $this->getAppSetting();

        $language = Language::select('name', 'locale')
            ->where('locale', app()->getLocale())
            ->first();

        if ($language) {
            $form_data['oc_settings']['language'] = $language->name;
            $form_data['oc_settings']['locale'] = $language->locale;
        }

        $form_data['oc_settings']['time_zone'] = auth()->user()->time_zone;

        $settings_data = [
            'form_data' => isset($form_data) ? $form_data : [],
            'form_title' => 'Settings',
            'title' => 'Settings',
            'icon' => 'fas fa-cogs',
            'file' => 'admin.layouts.origin.settings',
            'module' => 'Settings',
            'slug' => 'settings',
            'module_type' => 'Single',
            'table_name' => 'oc_settings',
            'permissions' => ['update' => true]
        ];

        return view('admin.templates.form_view', $settings_data);
    }

    public function save(Request $request)
    {
        $settings_data = $request->all();
        unset($settings_data["_token"]);

        if (isset($settings_data['language'])) {
            unset($settings_data["language"]);
        }

        $locale = app()->getLocale();
        $locale_updated = false;

        $time_zone = auth()->user()->time_zone;
        $time_zone_updated = false;

        if (isset($settings_data['locale']) && trim($settings_data['locale'])) {
            if (trim($settings_data['locale']) != $locale) {
                $locale = trim($settings_data['locale']);
                $locale_updated = true;
            }

            unset($settings_data['locale']);
        }

        if (isset($settings_data['time_zone']) && trim($settings_data['time_zone'])) {
            if (trim($settings_data['time_zone']) != $time_zone) {
                $time_zone = trim($settings_data['time_zone']);
                $time_zone_updated = true;
            }

            unset($settings_data['time_zone']);
        }

        foreach ($settings_data as $setting => $value) {
            $result = DB::table('oc_settings')
                ->where('field_name', $setting)
                ->where('owner', auth()->user()->username)
                ->update([
                    'field_value' => $value, 
                    'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 
                    'last_updated_by' => auth()->user()->username
                ]);

            if ($result) {
                session()->flash('success', true);
            }
        }

        $this->putAppSettingsInSession();

        if ($locale_updated) {
            $this->updateLocale($locale);
        }

        if ($time_zone_updated) {
            $this->updateTimeZone($time_zone);
        }

        $data = [
            'success' => true,
            'msg' => __('App settings successfully saved')
        ];

        if ($request->ajax()) {
            return response()->json($data, 200);
        } else {
            return redirect()->route('show.app.settings')->with($data);
        }
    }

    public function changeTheme(Request $request)
    {
        $data = [
            'success' => false,
            'msg' => __('Please provide valid Theme i.e. "light" or "dark"')
        ];

        if ($request->filled('theme')) {
            $theme = trim($request->get('theme'));

            if (in_array($theme, ['light', 'dark'])) {
                $updated = DB::table('oc_settings')
                    ->where('field_name', 'theme')
                    ->where('owner', auth()->user()->username)
                    ->update([
                        'field_value' => $theme, 
                        'updated_at' => Carbon::now('UTC')->format('Y-m-d H:i:s'), 
                        'last_updated_by' => auth()->user()->username
                    ]);

                if ($updated) {
                    $this->putAppSettingsInSession();

                    $data = [
                        'success' => true,
                        'msg' => __('Theme changed successfully')
                    ];
                } else {
                    $data['msg'] = __('Some error occured. Please try again');
                }
            }
        }

        return response()->json($data, 200);
    }
}
