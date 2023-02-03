<?php

use Illuminate\Database\Migrations\Migration;

class RemoveStatisticsSearchesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->getCreateStatisticsSearchesTableMigration()->down();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->getCreateStatisticsSearchesTableMigration()->up();
    }

    private function getCreateStatisticsSearchesTableMigration()
    {
        return new CreateStatisticsSearchesTable();
    }

}
