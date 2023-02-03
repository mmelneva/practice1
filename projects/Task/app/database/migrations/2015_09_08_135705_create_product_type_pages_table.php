<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTypePagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'product_type_pages',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->unsignedInteger('node_id');
                $table->foreign('node_id')->references('id')->on('nodes');

                $table->string('header')->default('');
                $table->string('meta_title')->default('');
                $table->string('meta_keywords')->default('');
                $table->string('meta_description')->default('');

                $table->text('content')->default('');
                $table->text('content_bottom')->default('');

                $table->text('filter_query')->default('');
                $table->string('product_list_way')->nullable();

                $table->unsignedInteger('manual_product_list_category_id')->nullable();
                $table->foreign('manual_product_list_category_id', 'product_type_pages_manual_category_fk')
                    ->references('id')->on('catalog_categories');
            }
        );

        Schema::create(
            'product_type_page_associations',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->unsignedInteger('product_type_page_id');
                $table->unsignedInteger('catalog_product_id');

                $table->foreign('product_type_page_id', 'product_type_page_associations_fk_page')
                    ->on('product_type_pages')->references('id');
                $table->foreign('catalog_product_id', 'product_type_page_associations_fk_prod')
                    ->on('catalog_products')->references('id');

                $table->unique(['product_type_page_id', 'catalog_product_id'], 'product_type_page_associations_unique');

                $table->string('name')->default('');
                $table->text('short_content')->default('');
                $table->boolean('manual')->default(false);
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
        Schema::drop('product_type_page_associations');
        Schema::drop('product_type_pages');
    }

}
