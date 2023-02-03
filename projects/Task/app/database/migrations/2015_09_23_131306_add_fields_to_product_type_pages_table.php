
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductTypePagesTable extends Migration
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
                $table->boolean('in_popular')->default(false);
                $table->boolean('in_home_page')->default(false);
                $table->string('home_page_logo')->nullable();

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
                $table->dropColumn('in_popular');
                $table->dropColumn('in_home_page');
                $table->dropColumn('home_page_logo');
            }
        );
    }

}
