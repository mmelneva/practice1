<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use \App\Models\ProductBuiltInConstants as ProductBuiltIn;

class ChangeFieldsInCatalogProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $builtIn = [
            ProductBuiltIn::UNDEFINED,
            ProductBuiltIn::BUILT_IN,
            ProductBuiltIn::NOT_BUILT_IN,
        ];

        Schema::table(
            'catalog_products',
            function (Blueprint $table) use ($builtIn) {
                $table->dropColumn('article');
                $table->dropColumn('old_price');
                $table->dropColumn('new');
                $table->dropColumn('sale');
                $table->dropColumn('hit');
                $table->dropColumn('product_code');

                $table->enum('built_in', $builtIn)->default($builtIn[0]);
            }
        );

        $this->getAddExistenceToCatalogProductsTableMigration()->down();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table(
            'catalog_products',
            function (Blueprint $table) {
                $table->string('article')->default('');
                $table->decimal('old_price', 10, 2)->nullable();
                $table->boolean('new')->default(false);
                $table->boolean('sale')->default(false);
                $table->boolean('hit')->default(false);
                $table->string('product_code')->default('');

                $table->dropColumn('built_in');
            }
        );

        $this->getAddExistenceToCatalogProductsTableMigration()->up();
    }

    private function getAddExistenceToCatalogProductsTableMigration()
    {
        return new AddExistenceToCatalogProductsTable();
    }
}
