<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOnHomePageFieldToReviewsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'reviews',
            function (Blueprint $table) {
                $table->boolean('on_home_page')->default(0);
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
            'reviews',
            function (Blueprint $table) {
                $table->dropColumn('on_home_page');
            }
        );
    }

}
