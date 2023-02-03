<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSimilarProductsBlockNameToCatalogCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'catalog_categories',
            function (Blueprint $table) {
                $table->string('similar_products_block_name')->default('');
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
        Schema::table(
            'catalog_categories',
            function (Blueprint $table) {
                $table->dropColumn('similar_products_block_name');
            }
        );
    }

}
