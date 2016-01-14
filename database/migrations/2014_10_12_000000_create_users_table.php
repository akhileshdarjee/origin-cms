<?php

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
		Schema::create('tabUser', function (Blueprint $table) {
			$table->increments('id');
			$table->string('full_name');
			$table->string('avatar')->nullable();
			$table->string('login_id')->unique();
			$table->string('password');
			$table->string('email')->nullable();
			$table->string('role');
			$table->string('status', 12)->default('Active');
			$table->string('owner');
			$table->string('last_updated_by');
			$table->rememberToken();
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
		Schema::drop('tabUser');
	}
}