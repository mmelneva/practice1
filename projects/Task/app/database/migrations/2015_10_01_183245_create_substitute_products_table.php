<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubstituteProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'substitute_products',
            function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('publish')->default(false);

                $table->unsignedInteger('product_id');
                $table->foreign('product_id')->references('id')->on('catalog_products');

                $table->unsignedInteger('parent_id');
                $table->foreign('parent_id')->references('id')->on('catalog_products');

                $table->unique(['product_id', 'parent_id']);
                $table->timestamps();
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
        Schema::drop('substitute_products');
    }

}
