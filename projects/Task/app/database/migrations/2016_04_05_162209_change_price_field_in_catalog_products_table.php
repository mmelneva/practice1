<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangePriceFieldInCatalogProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update("ALTER TABLE catalog_products CHANGE price price VARCHAR ( 255 ) DEFAULT NULL ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update("ALTER TABLE catalog_products CHANGE price price decimal(10,2) DEFAULT NULL ;");
    }

}
