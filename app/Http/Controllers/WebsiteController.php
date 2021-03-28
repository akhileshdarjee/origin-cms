<?php

namespace App\Http\Controllers;

class WebsiteController extends Controller
{
    public function showIndex()
    {
        return redirect()->route('home');
    }
}
