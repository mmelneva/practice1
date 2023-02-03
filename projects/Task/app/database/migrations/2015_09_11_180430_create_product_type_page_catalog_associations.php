<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTypePageCatalogAssociations extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'product_type_page_catalog_associations',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->unsignedInteger('product_type_page_id');
                $table->unsignedInteger('category_id');

                $table->foreign('product_type_page_id', 'product_type_page_catalog_associations_fk_page')
                    ->on('product_type_pages')->references('id');
                $table->foreign('category_id', 'product_type_page_catalog_associations_fk_cat')
                    ->on('catalog_categories')->references('id');

                $table->unique(['product_type_page_id', 'category_id'], 'product_type_page_catalog_associations_unique');

                $table->string('name')->default('');
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
        Schema::drop('product_type_page_catalog_associations');
    }

}
