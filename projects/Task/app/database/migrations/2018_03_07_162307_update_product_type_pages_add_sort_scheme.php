<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductTypePagesAddSortScheme extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_type_pages', function(Blueprint $table)
		{
            $table->boolean('use_sort_scheme')->default(false);
            $table->text('sort_scheme')->default('');
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
            $table->dropColumn(['use_sort_scheme', 'sort_scheme']);
		});
	}

}
