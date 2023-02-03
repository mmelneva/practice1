<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveShortContentFromProductTypePageAssociationsTable extends Migration
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
            'product_type_page_associations',
            function (Blueprint $table) {
                $table->text('short_content')->default('');
            }
        );
    }

}
