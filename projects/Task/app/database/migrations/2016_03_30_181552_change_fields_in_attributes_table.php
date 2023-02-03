<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeFieldsInAttributesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'attributes',
            function (Blueprint $table) {
                $table->dropColumn('publish');
                $table->dropColumn('use_in_filter');

                $table->boolean('on_product_page')->default(false);
                $table->boolean('use_in_similar_products')->default(false);
                $table->string('similar_products_name')->default('');
            }
        );

        $this->getAddShowInListToAttributesTableMigration()->down();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'attributes',
            function (Blueprint $table) {
                $table->boolean('publish')->default(false);
                $table->boolean('use_in_filter')->default(false);

                $table->dropColumn('on_product_page');
                $table->dropColumn('use_in_similar_products');
                $table->dropColumn('similar_products_name');
            }
        );

        $this->getAddShowInListToAttributesTableMigration()->up();
    }

    private function getAddShowInListToAttributesTableMigration()
    {
        return new AddShowInListToAttributesTable();
    }
}
