<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductGalleryImagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'product_gallery_images',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('catalog_product_id');
                $table->foreign('catalog_product_id')->references('id')->on('catalog_products');

                $table->string('name')->default('');
                $table->boolean('publish')->default(false);
                $table->integer('position')->default(0);

                $table->string('image')->nullable();
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
        Schema::drop('product_gallery_images');
    }

}
