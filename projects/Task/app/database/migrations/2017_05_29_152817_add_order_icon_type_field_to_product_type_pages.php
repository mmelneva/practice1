<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderIconTypeFieldToProductTypePages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_type_pages', function(Blueprint $table)
		{
			$table->boolean('order_icon_type')->default(false);
		});

		Schema::table('catalog_categories', function(Blueprint $table)
		{
			$table->boolean('order_icon_type')->default(false);
		});

		Schema::table('home_pages', function(Blueprint $table)
		{
			$table->boolean('order_icon_type')->default(false);
		});


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_type_pages', function(Blueprint $table)
		{
			$table->dropColumn('order_icon_type');
		});

		Schema::table('catalog_categories', function(Blueprint $table)
		{
			$table->dropColumn('order_icon_type');
		});

		Schema::table('home_pages', function(Blueprint $table)
		{
			$table->dropColumn('order_icon_type');
		});
	}

}
