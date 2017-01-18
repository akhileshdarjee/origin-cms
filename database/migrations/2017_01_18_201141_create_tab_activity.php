<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabActivity extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tabActivity', function (Blueprint $table) {
			$table->increments('id');
			$table->string('user');
			$table->integer('user_id');
			$table->boolean('status');
			$table->string('module');
			$table->string('icon');
			$table->string('action')->nullable();
			$table->integer('form_id')->nullable();
			$table->text('description');
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
		Schema::drop('tabActivity');
	}
}