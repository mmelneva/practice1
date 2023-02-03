<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'catalog_categories',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->unsignedInteger('parent_id')->nullable();
                $table->foreign('parent_id', 'catalog_category_parent_fk')
                    ->references('id')->on('catalog_categories');

                $table->string('name')->default('');
                $table->boolean('publish')->default(false);
                $table->integer('position')->default(0);

                $table->string('alias')->nullable();
                $table->unique('alias', 'catalog_category_unique_alias');

                $table->string('header')->default('');
                $table->text('content')->default('');

                $table->string('meta_title')->default('');
                $table->string('meta_keywords')->default('');
                $table->string('meta_description')->default('');
            }
        );

        Schema::create(
            'catalog_products',
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();

                $table->unsignedInteger('category_id');
                $table->foreign('category_id', 'catalog_product_category_fk')
                    ->references('id')->on('catalog_categories');

                $table->string('name')->default('');
                $table->boolean('publish')->default(false);
                $table->integer('position')->default(0);

                $table->string('alias')->nullable();
                $table->unique('alias', 'catalog_products_unique_alias');

                $table->string('image')->nullable();
                $table->decimal('price', 10, 2)->nullable();

                $table->string('header')->default('');
                $table->text('short_content')->default('');
                $table->text('content')->default('');

                $table->string('meta_title')->default('');
                $table->string('meta_keywords')->default('');
                $table->string('meta_description')->default('');
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
        Schema::drop('catalog_products');
        Schema::drop('catalog_categories');
    }
}
