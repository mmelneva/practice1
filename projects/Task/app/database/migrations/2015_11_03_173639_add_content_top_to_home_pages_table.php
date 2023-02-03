<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentTopToHomePagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'home_pages',
            function (Blueprint $table) {
                $table->text('content_top')->default('');
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
            'home_pages',
            function (Blueprint $table) {
                $table->dropColumn('content_top');
            }
        );
    }

}
