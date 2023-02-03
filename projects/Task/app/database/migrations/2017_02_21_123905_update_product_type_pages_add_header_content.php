<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductTypePagesAddHeaderContent extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_type_pages', function(Blueprint $table)
		{
            $table->boolean('content_header_show')->default(false);
            $table->text('content_header')->default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_type_pages', function(Blueprint $table)
		{
			$table->dropColumn('content_header_show');
            $table->dropColumn('content_header');
		});
	}

}
