<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IdParentsCategoryInNode extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table(
            'product_type_pages',
            function (Blueprint $table) {
                $table->unsignedInteger('parent_category_id')->default(null);
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
            'product_type_pages',
            function (Blueprint $table) {
                $table->dropColumn('parent_category_id');
            }
        );
    }

}
