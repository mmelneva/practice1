<?php

use Illuminate\Database\Migrations\Migration;

class RemoveProductTypePageCatalogAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->getCreateProductTypePageCatalogAssociationsMigration()->down();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->getCreateProductTypePageCatalogAssociationsMigration()->up();
    }

    private function getCreateProductTypePageCatalogAssociationsMigration()
    {
        return new CreateProductTypePageCatalogAssociations();
    }
}
