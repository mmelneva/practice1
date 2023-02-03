<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddNoTemplateTextToCatalogProductsTablr extends Migration
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
                $table->boolean('no_template_text')->default(false);
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
                $table->dropColumn('no_template_text');
            }
        );
    }

}
