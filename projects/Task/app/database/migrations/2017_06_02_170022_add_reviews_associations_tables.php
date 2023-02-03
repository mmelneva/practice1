<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewsAssociationsTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(
                'reviews_product_type_pages_associations',
                function (Blueprint $table) {
                    $table->increments('id');

                    $table->unsignedInteger('reviews_id');
                    $table->foreign('reviews_id', 'reviews_product_type_pages_associations_fk_review')
                            ->on('reviews')->references('id');

                    $table->unsignedInteger('product_type_pages_id');
                    $table->foreign('product_type_pages_id', 'reviews_product_type_pages_associations_fk_page')
                            ->on('product_type_pages')->references('id');

                    $table->unique(['reviews_id', 'product_type_pages_id'], 'reviews_product_type_pages_associations_unique');
                }
        );

        Schema::create(
                'reviews_catalog_categories_associations',
                function (Blueprint $table) {
                    $table->increments('id');

                    $table->unsignedInteger('reviews_id');

                    $table->foreign('reviews_id', 'reviews_catalog_categories_associations_fk_review')
                            ->on('reviews')->references('id');

                    $table->unsignedInteger('catalog_categories_id');
                    $table->foreign('catalog_categories_id', 'reviews_product_type_pages_associations_fk_category')
                            ->on('catalog_categories')->references('id');

                    $table->unique(['reviews_id', 'catalog_categories_id'], 'reviews_catalog_categories_associations_unique');
                }
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
        Schema::drop('reviews_product_type_pages_associations');
        Schema::drop('reviews_catalog_categories_associations');
    }

}
