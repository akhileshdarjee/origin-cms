<?php

namespace App\Http\Controllers\Reports;

use DB;
use Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserReport extends Controller
{
	// get all rows & colummns for report
	public function get_data($request) {
		$query = DB::table('tabUser')
			->select(
				'full_name', 'login_id', 'email', 'role', 'status'
			);

		if ($request->has('filters') && $request->get('filters')) {
			$filters = $request->get('filters');
			if (isset($filters['email']) && $filters['email']) {
				$query = $query->where('email', $filters['email']);
			}
			if (isset($filters['role']) && $filters['role']) {
				$query = $query->where('role', $filters['role']);
			}
			if (isset($filters['status']) && $filters['status']) {
				$query = $query->where('status', $filters['status']);
			}
			if (isset($filters['from_date']) && isset($filters['to_date']) && $filters['from_date'] && $filters['to_date']) {
				$user_list = [];
				$user_records = DB::table('tabUser')->get();

				if ($user_records) {
					foreach ($user_records as $user) {
						if ((strtotime($user->created_at) >= strtotime($filters['from_date']) && 
							strtotime($user->created_at) <= strtotime($filters['to_date']))) {
								if (!in_array($user->email, $user_list)){
									array_push($user_list, $user->email);
								}
						}
					}
				}

				$query = $query->whereIn('email', $user_list);
			}
		}

		$rows = $query->orderBy('id', 'desc')->get();

		return array(
			'rows' => $rows,
			'columns' => array('full_name', 'login_id', 'email', 'role', 'status'),
			'module' => 'User',
			'link_field' => 'login_id',
			'record_identifier' => 'login_id'
		);
	}
}