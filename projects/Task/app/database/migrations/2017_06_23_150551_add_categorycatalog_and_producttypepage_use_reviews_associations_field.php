<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategorycatalogAndProducttypepageUseReviewsAssociationsField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_type_pages', function(Blueprint $table)
		{
			$table->boolean('use_reviews_associations')->default(false);
		});

		Schema::table('catalog_categories', function(Blueprint $table)
		{
			$table->boolean('use_reviews_associations')->default(false);
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
			$table->dropColumn('use_reviews_associations');
		});

		Schema::table('catalog_categories', function(Blueprint $table)
		{
			$table->dropColumn('use_reviews_associations');
		});
	}

}
