<?php

namespace App\Http\Controllers\Reports;

use DB;
use App\User;
use App\Http\Controllers\Controller;

class UserReport extends Controller
{
    // get all rows & colummns for report
    public function getData($request, $per_page, $download)
    {
        $rows = User::select(
                'id', 'username', 'title', 'first_name', 'last_name', 'full_name', 
                'email', 'email_verified_at', 'role', 'locale', 'time_zone',
                DB::raw("if(active, 'Yes', 'No') as active"), 
                'created_at', 'updated_at'
            );

        if ($request->filled('filters')) {
            $filters = $request->get('filters');

            if (isset($filters['full_name']) && $filters['full_name']) {
                $rows = $rows->where('full_name', $filters['full_name']);
            }
            if (isset($filters['username']) && $filters['username']) {
                $rows = $rows->where('username', $filters['username']);
            }
            if (isset($filters['email']) && $filters['email']) {
                $rows = $rows->where('email', $filters['email']);
            }
            if (isset($filters['role']) && $filters['role']) {
                $rows = $rows->where('role', $filters['role']);
            }
            if (isset($filters['active'])) {
                $rows = $rows->where('active', intval($filters['active']));
            }
        }

        if (!in_array(auth()->user()->role, ["System Administrator", "Administrator"])) {
            $rows = $rows->where('username', auth()->user()->username);
        }

        if ($download) {
            $rows = $rows->orderBy('id', 'desc')->get();
        } else {
            $rows = $rows->orderBy('id', 'desc')->paginate($per_page);
        }

        return array(
            'rows' => $rows,
            'columns' => array(
                'username', 'title', 'first_name', 'last_name', 'full_name', 
                'email', 'email_verified_at', 'role', 'locale', 'time_zone', 
                'active', 'created_at', 'updated_at'
            ),
            'module' => 'User',
            'link_field' => 'id',
            'form_title' => 'username'
        );
    }
}
