<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Models\ProductExistenceConstants as ProductExistence;

class AddExistenceToCatalogProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $existence = [
            ProductExistence::AVAILABLE,
            ProductExistence::NOT_AVAILABLE,
        ];

        Schema::table(
            'catalog_products',
            function (Blueprint $table) use ($existence) {
                $table->enum('existence', $existence)->default($existence[0]);
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
            'catalog_products',
            function (Blueprint $table) {
                $table->dropColumn('existence');
            }
        );
    }

}
