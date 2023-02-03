<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegionsInPage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table(
            'nodes',
            function (Blueprint $table) {
                $table->boolean('hide_regions_in_page')->default(false);
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
            'nodes',
            function(Blueprint $table) {
                $table->dropColumn('hide_regions_in_page');
            }
        );
    }

}
