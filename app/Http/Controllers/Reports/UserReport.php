<?php

namespace App\Http\Controllers\Reports;

use DB;
use App\Http\Controllers\Controller;

class UserReport extends Controller
{
    // get all rows & colummns for report
    public function getData($filters, $sort, $per_page, $download)
    {
        $table_name = cache('app_modules')['User']['table_name'];

        $rows = DB::table($table_name)
            ->select(
                'id', 'username', 'title', 'first_name', 'last_name', 'full_name', 
                'email', 'email_verified_at', 'role', 'locale', 'time_zone',
                DB::raw("if(active, 'Yes', 'No') as active"), 
                'created_at', 'updated_at'
            );

        if ($filters && count($filters) && isset($filters['columns']) && $filters['columns']) {
            foreach ($filters['columns'] as $column => $value) {
                if ($value || $value == '0') {
                    $rows = $rows->where($table_name . '.' . trim($column), 'like', '%' . trim($value) . '%');
                }
            }
        }

        if (!in_array(auth()->user()->role, ["System Administrator", "Administrator"])) {
            $rows = $rows->where('username', auth()->user()->username);
        }

        if ($download) {
            $rows = $rows->orderBy($table_name . '.' . key($sort), reset($sort))->get();
        } else {
            $rows = $rows->orderBy($table_name . '.' . key($sort), reset($sort))->paginate($per_page);
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
