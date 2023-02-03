<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalServices extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'additional_services',
			function (Blueprint $table) {
				$table->increments('id');
				$table->timestamps();

				$table->string('name')->default('');
				$table->boolean('publish')->default(false);
				$table->integer('position')->default(0);
				$table->string('icon')->nullable();
				$table->text('content')->default('');
				$table->string('url')->default('');
			}
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('additional_services');
	}
}
