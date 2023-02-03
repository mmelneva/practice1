<?php

use Illuminate\Database\Migrations\Migration;

class RemoveShortContentFromCatalogProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->getAddFieldsToCatalogProductsTableMigration()->down();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->getAddFieldsToCatalogProductsTableMigration()->up();
    }

    private function getAddFieldsToCatalogProductsTableMigration()
    {
        return new AddFieldsToCatalogProductsTable();
    }
}
