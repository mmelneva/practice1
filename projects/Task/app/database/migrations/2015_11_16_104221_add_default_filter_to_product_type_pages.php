<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultFilterToProductTypePages extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'product_type_pages',
            function (Blueprint $table) {
                $table->unsignedInteger('default_filter_category_id')->nullable();
                $table->foreign('default_filter_category_id', 'product_type_d_filter_fk')
                    ->references('id')->on('catalog_categories');
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
            'product_type_pages',
            function (Blueprint $table) {
                $table->dropForeign('product_type_d_filter_fk');
                $table->dropColumn('default_filter_category_id');
            }
        );
    }
}
