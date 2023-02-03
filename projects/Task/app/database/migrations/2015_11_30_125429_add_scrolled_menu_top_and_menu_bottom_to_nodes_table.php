<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScrolledMenuTopAndMenuBottomToNodesTable extends Migration
{
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
                $table->boolean('scrolled_menu_top')->default(0);
                $table->boolean('menu_bottom')->default(0);
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
            function (Blueprint $table) {
                $table->dropColumn('scrolled_menu_top');
                $table->dropColumn('menu_bottom');
            }
        );
    }
}
