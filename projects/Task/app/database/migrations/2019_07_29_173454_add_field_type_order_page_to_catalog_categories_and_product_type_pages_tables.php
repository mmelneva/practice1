<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldTypeOrderPageToCatalogCategoriesAndProductTypePagesTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('catalog_categories', function (Blueprint $table) {
                $table->char('type_order_button', 15)->default('');
        });

        Schema::table('product_type_pages', function (Blueprint $table) {
            $table->char('type_order_button', 15)->default('');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('catalog_categories', function (Blueprint $table) {
            $table->dropColumn('type_order_button');
        });

        Schema::table('product_type_pages', function (Blueprint $table) {
            $table->dropColumn('type_order_button');
        });
	}
}
