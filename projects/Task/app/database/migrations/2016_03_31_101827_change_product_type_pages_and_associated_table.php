<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeProductTypePagesAndAssociatedTable extends Migration
{

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
                $table->dropColumn('in_home_page');
                $table->dropColumn('home_page_logo');
                $table->dropColumn('home_page_name');
            }
        );

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
            'product_type_pages',
            function (Blueprint $table) {
                $table->boolean('in_home_page')->default(false);
                $table->string('home_page_logo')->nullable();
                $table->string('home_page_name')->default('');
            }
        );

        Schema::table(
            'product_type_page_associations',
            function (Blueprint $table) {
                $table->text('short_content')->default('');
            }
        );
    }
}
