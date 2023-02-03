<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShortContentToTextPagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'text_pages',
            function (Blueprint $table) {
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
            'text_pages',
            function (Blueprint $table) {
                $table->dropColumn('short_content');
            }
        );
    }

}
