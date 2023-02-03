<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPositionFieldInProductTypePageAssociationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::update("ALTER TABLE product_type_page_associations CHANGE `position` `position` int(11) NOT NULL DEFAULT 0;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::update("ALTER TABLE product_type_page_associations CHANGE `position` `position` VARCHAR ( 255 ) NOT NULL DEFAULT 0;");
	}

}
