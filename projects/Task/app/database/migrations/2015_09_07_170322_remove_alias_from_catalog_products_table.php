<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAliasFromCatalogProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('catalog_products', function(Blueprint $table)
		{
			$table->dropUnique('catalog_products_unique_alias');
			$table->dropColumn('alias');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('catalog_products', function(Blueprint $table)
		{
			$table->string('alias')->nullable();
			$table->unique('alias', 'catalog_products_unique_alias');
		});
	}

}
