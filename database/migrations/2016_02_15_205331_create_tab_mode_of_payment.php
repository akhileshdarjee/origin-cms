<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabModeOfPayment extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tabModeOfPayment', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('status')->default('Active');
			$table->string('slug')->nullable;
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
		Schema::drop('tabModeOfPayment');
	}
}