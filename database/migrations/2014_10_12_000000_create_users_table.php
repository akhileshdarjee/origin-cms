<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oc_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('avatar')->nullable();
            $table->string('login_id')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('role');
            $table->boolean('is_active')->default('0');
            $table->string('language', 10)->default('en');
            $table->boolean('email_confirmed')->default('0');
            $table->string('email_confirmation_code')->nullable();
            $table->boolean('first_login')->default('0')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('oc_users');
    }
}
