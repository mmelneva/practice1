<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHitToCatalogProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'catalog_products',
            function (Blueprint $table) {
                $table->boolean('hit')->default(false);
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
            'catalog_products',
            function (Blueprint $table) {
                $table->dropColumn('hit');
            }
        );
    }

}
