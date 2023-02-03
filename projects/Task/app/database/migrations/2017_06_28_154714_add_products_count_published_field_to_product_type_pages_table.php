<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductsCountPublishedFieldToProductTypePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_type_pages', function(Blueprint $table)
		{
			$table->string('products_count_published')->default('0')->nullable();
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
			$table->dropColumn('products_count_published');
		});
	}

}
