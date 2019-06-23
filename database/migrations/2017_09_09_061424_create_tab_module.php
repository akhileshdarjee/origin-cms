<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oc_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('is_active')->default('1');
            $table->string('display_name');
            $table->string('table_name');
            $table->string('controller_name');
            $table->string('slug');
            $table->boolean('create_migration')->default('1');
            $table->integer('sequence_no')->nullable();
            $table->boolean('show')->default('1');
            $table->string('list_view_columns')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('icon')->nullable();
            $table->string('icon_color')->nullable();
            $table->string('form_title')->nullable();
            $table->string('image_field')->nullable();
            $table->boolean('is_child_table')->default('0');
            $table->string('sort_field')->default('id');
            $table->string('sort_order')->default('desc');
            $table->text('description')->nullable();
            $table->string('owner');
            $table->string('last_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oc_modules');
    }
}
