<?php

namespace App\Http\Controllers\Reports;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserReport extends Controller
{
    // get all rows & colummns for report
    public function getData($request, $per_page, $download)
    {
        $user_table_name = cache('app_modules')['User']['table_name'];

        $query = DB::table($user_table_name)
            ->select(
                'id', 'full_name', 'username', 'email', 'role', 
                DB::raw("if(active, 'Yes', 'No') as active")
            );

        if ($request->filled('filters')) {
            $filters = $request->get('filters');

            if (isset($filters['full_name']) && $filters['full_name']) {
                $query = $query->where('full_name', $filters['full_name']);
            }
            if (isset($filters['username']) && $filters['username']) {
                $query = $query->where('username', $filters['username']);
            }
            if (isset($filters['email']) && $filters['email']) {
                $query = $query->where('email', $filters['email']);
            }
            if (isset($filters['role']) && $filters['role']) {
                $query = $query->where('role', $filters['role']);
            }
            if (isset($filters['active'])) {
                $query = $query->where('active', intval($filters['active']));
            }
            if (isset($filters['from_date']) && isset($filters['to_date']) && $filters['from_date'] && $filters['to_date']) {
                $query = $query->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($filters['from_date'])))
                    ->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($filters['to_date'])));
            }
        }

        if (!in_array(auth()->user()->role, ["System Administrator", "Administrator"])) {
            $query = $query->where('username', auth()->user()->username);
        }

        if ($download) {
            $rows = $query->orderBy('id', 'desc')->get();
        } else {
            $rows = $query->orderBy('id', 'desc')->paginate($per_page);
        }

        return array(
            'rows' => $rows,
            'columns' => array('full_name', 'username', 'email', 'role', 'active'),
            'module' => 'User',
            'link_field' => 'id',
            'form_title' => 'username'
        );
    }
}
