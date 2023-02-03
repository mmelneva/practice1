<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductHomepageAssociations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create(
            'product_home_page_associations',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->unsignedInteger('home_page_id');
                $table->unsignedInteger('catalog_product_id');

                $table->foreign('home_page_id')
                    ->on('home_pages')->references('id');
                $table->foreign('catalog_product_id')
                    ->on('catalog_products')->references('id');

                $table->unique(['home_page_id', 'catalog_product_id'], 'home_page_associations_unique');
                $table->integer('position')->default(0);

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
        Schema::dropIfExists('product_home_page_associations');
	}

}
