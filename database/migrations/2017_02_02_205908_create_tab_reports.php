<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabReports extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tabReports', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('status')->default('Active');
			$table->string('type')->default('Standard');
			$table->string('module');
			$table->integer('sequence_no')->nullable();
			$table->text('columns')->nullable();
			$table->text('filters')->nullable();
			$table->string('icon')->nullable();
			$table->string('bg_color')->nullable();
			$table->string('icon_color')->nullable();
			$table->text('query')->nullable();
			$table->text('allowed_roles')->nullable();
			$table->string('order_by')->nullable();
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
		Schema::drop('tabReports');
	}
}