<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'banners',
            function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('home_page_id');
                $table->foreign('home_page_id', 'home_page_banners_fk')
                    ->references('id')->on('home_pages');
                $table->string('name')->default('');
                $table->boolean('publish')->default(false);
                $table->integer('position')->default(0);
                $table->string('link')->default('');
                $table->string('image')->nullable();
                $table->text('description')->default('');
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
        Schema::drop('banners');
    }

}
