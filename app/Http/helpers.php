<?php

namespace App\Http;

use DB;
use File;
use Session;
use Mail;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


// get controller name which has called this controller function
// pass Route instance to this function
function get_controller_name($route) {
	$route_action = $route->getAction();
	return explode("@", class_basename($route_action['controller']))[0];
}