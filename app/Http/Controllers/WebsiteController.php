<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function showIndex()
    {
        return redirect()->route('home');
    }
}
