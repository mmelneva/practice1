<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProductCategoryAssociationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'product_category_associations',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('product_id');
                $table->unsignedInteger('category_id');

                $table->foreign('product_id', 'product_category_product_fk')
                    ->references('id')->on('catalog_products');
                $table->foreign('category_id', 'product_category_category_fk')
                    ->references('id')->on('catalog_categories');

                $table->unique(['category_id', 'product_id']);
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
        Schema::drop('product_category_associations');
    }

}
