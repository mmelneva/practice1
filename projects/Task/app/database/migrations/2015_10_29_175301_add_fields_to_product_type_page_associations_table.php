<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductTypePageAssociationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'product_type_page_associations',
            function (Blueprint $table) {
                $table->string('position')->default(0);
                $table->text('short_content')->default('');
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
            'product_type_page_associations',
            function (Blueprint $table) {
                $table->dropColumn('position');
                $table->dropColumn('short_content');
            }
        );
    }

}
