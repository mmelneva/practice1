<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAdditionalServicesRelations extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'additional_services_catalog_products',
			function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('catalog_product_id');
				$table->unsignedInteger('additional_service_id');

				$table->foreign('catalog_product_id', 'add_serv_products_product_fk')->references('id')->on(
					'catalog_products'
				);
				$table->foreign('additional_service_id', 'add_serv_products_service_fk')->references('id')->on(
					'additional_services'
				);
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
		Schema::drop('additional_services_catalog_products');
	}
}
