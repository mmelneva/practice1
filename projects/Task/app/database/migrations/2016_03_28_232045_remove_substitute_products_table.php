<?php

use Illuminate\Database\Migrations\Migration;

class RemoveSubstituteProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->getCreateSubstituteProductsTableMigration()->down();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->getCreateSubstituteProductsTableMigration()->up();
    }

    private function getCreateSubstituteProductsTableMigration()
    {
        return new CreateSubstituteProductsTable();
    }
}
