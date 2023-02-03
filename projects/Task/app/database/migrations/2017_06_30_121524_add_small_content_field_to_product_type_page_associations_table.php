<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSmallContentFieldToProductTypePageAssociationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_type_page_associations', function(Blueprint $table)
		{
			$table->text('small_content')->default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_type_page_associations', function(Blueprint $table)
		{
			$table->dropColumn('small_content');
		});
	}

}
