<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PermController extends Controller
{
	// define permission manager variables

	// table name in database(not included prefix 'tab')
	private static $modules = [
		'Band', 'Carousel', 'Voter', 'User'
	];

	// restrict data to this roles
	private static $roles = array('Website User');

	// set module actions
	private static $permissions = array('Read', 'Create', 'Update', 'Delete');

	// define which role can access which modules
	private static $role_modules_based_on_perm;

	// define modules wise permissions to roles
	private static $module_permissions_based_on_role;


	// list all the modules of the roles based on permissions
	public static function role_wise_modules($role = null, $action = null, $module = null) {
		self::$role_modules_based_on_perm = (object) array(
			'Website User' => (object) array(
				'Create' => array('Band', 'Voter')
			),
		);

		if ($role && in_array($role, self::$roles)) {
			if (isset(self::$role_modules_based_on_perm->$role->$action)) {
				$role_modules = self::$role_modules_based_on_perm->$role->$action;
			}

			if ($module && isset($role_modules) && $role_modules) {
				if (in_array($module, $role_modules)) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				if (isset($role_modules) && $role_modules) {
					return $role_modules;
				}
				else {
					return false;
				}
			}
		}
	}


	// gets the data related to the role
	public static function module_wise_permissions($role = null, $action = null, $module_name = null) {
		$user_name = Session::get('user');
		$user_login_id = Session::get('login_id');

		self::$module_permissions_based_on_role = array();

		if ($role && in_array($role, self::$roles)) {
			if (isset(self::$module_permissions_based_on_role[$role]->$action->$module_name)) {
				return (object) self::$module_permissions_based_on_role[$role]->$action->$module_name;
			}
			else {
				return false;
			}
		}
	}


	// gives modules and it's config list based on roles
	public static function modules_config_based_on_roles($role, $action) {
		if ($role == 'Administrator') {
			return ModuleController::modules_config();
		}
		else {
			return ModuleController::modules_config(self::role_wise_modules($role, $action));
		}
	}
}