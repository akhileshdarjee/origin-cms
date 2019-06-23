<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oc_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'display_name', 'table_name', 'controller_name', 'slug', 
        'sequence_no', 'show', 'list_view_columns', 'bg_color', 'icon', 'icon_color', 
        'form_title', 'image_field', 'is_child_table', 'sort_field', 'sort_order', 
        'description', 'owner', 'last_updated_by'
    ];
}
