<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'order_items',
			function (Blueprint $table) {
				$table->increments('id');
				$table->timestamps();

				$table->string('name')->default('');
				$table->integer('count')->default(0);
				$table->decimal('price')->default(0);

				$table->unsignedInteger('order_id');
				$table->foreign('order_id')->references('id')->on('orders');

				$table->unsignedInteger('product_id')->nullable();
				$table->foreign('product_id')->references('id')->on('catalog_products');
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
		Schema::drop('order_items');
	}
}
