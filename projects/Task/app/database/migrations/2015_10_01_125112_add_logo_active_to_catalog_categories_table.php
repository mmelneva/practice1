<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogoActiveToCatalogCategoriesTable extends Migration
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
                $table->string('logo_active')->nullable();
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
                $table->dropColumn('logo_active');
            }
        );
    }

}
