<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPopularAndHomePageNameToProductTypePages extends Migration
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
                $table->string('home_page_name')->default('');
                $table->string('popular_name')->default('');
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
                $table->dropColumn('home_page_name');
                $table->dropColumn('popular_name');
            }
        );
    }

}
