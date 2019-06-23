<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;

class AppController extends Controller
{
    use CommonController;

    // show home page based on app settings
    public function showHome()
    {
        $app_page = $this->getAppSetting('home_page');
        $app_page = $app_page ? $app_page : 'modules';
        $app_page = 'show.app.' . $app_page;

        if (session()->has('msg')) {
            return redirect()->route($app_page)->with('msg', session('msg'));
        } else {
            return redirect()->route($app_page);
        }
    }
}
