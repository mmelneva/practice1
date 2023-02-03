<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCatalogAndProductTables extends Migration
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
                $table->text('content_bottom')->default('');
                $table->string('logo')->nullable();
                $table->boolean('top_menu')->default(false);
            }
        );

        Schema::table(
            'catalog_products',
            function (Blueprint $table) {
                $table->decimal('old_price', 10, 2)->nullable();
                $table->string('article')->default('');
                $table->string('product_code')->default('');
                $table->boolean('new')->default(false);
                $table->boolean('sale')->default(false);

                $table->dropColumn('short_content');
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
                $table->dropColumn('content_bottom');
                $table->dropColumn('logo');
                $table->dropColumn('top_menu');
            }
        );

        Schema::table(
            'catalog_products',
            function (Blueprint $table) {
                $table->dropColumn('old_price');
                $table->dropColumn('article');
                $table->dropColumn('product_code');
                $table->dropColumn('new');
                $table->dropColumn('sale');

                $table->text('short_content')->default('');
            }
        );
    }

}
