<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentSubmenuAndSidebarInCatalogCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table(
            'catalog_categories',
            function (Blueprint $table) {
                $table->text('content_for_submenu')->default('');
                $table->text('content_for_sidebar')->default('');
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
            'catalog_categories',
            function(Blueprint $table) {
                $table->dropColumn('content_for_submenu');
                $table->dropColumn('content_for_sidebar');
            }
        );
	}

}
