<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGridContentFieldToHomePageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('home_pages', function (Blueprint $table) {
            $table->text('content_for_grid')->default('')->after('content');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn('content_for_grid');
		});
	}

}
