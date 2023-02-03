<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaPagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'meta_pages',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('node_id');
                $table->foreign('node_id')->references('id')->on('nodes');

                $table->string('header')->default('');
                $table->string('meta_title')->default('');
                $table->string('meta_keywords')->default('');
                $table->string('meta_description')->default('');
                $table->text('content')->default('');

                $table->timestamps();
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
        Schema::drop('meta_pages');
    }

}
